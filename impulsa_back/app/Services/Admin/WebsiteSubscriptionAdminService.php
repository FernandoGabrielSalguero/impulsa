<?php

namespace App\Services\Admin;

use App\Enums\WebsiteSubscriptionPeriodStatus;
use App\Models\ApiIntegration;
use App\Models\MercadoPagoSubscriptionPlan;
use App\Models\WebsiteSubscription;
use App\Models\WebsiteSubscriptionPeriod;
use App\Services\WebsiteSubscription\WebsiteSubscriptionAccessService;
use App\Services\WebsiteSubscription\WebsiteSubscriptionPaymentUrlService;
use App\Services\WebsiteSubscription\WebsiteSubscriptionPeriodService;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WebsiteSubscriptionAdminService
{
    public function __construct(
        private readonly WebsiteSubscriptionPeriodService $periodService,
        private readonly WebsiteSubscriptionAccessService $accessService,
        private readonly MercadoPagoSubscriptionPlanAdminService $planAdminService,
        private readonly WebsiteSubscriptionPaymentUrlService $paymentUrlService,
    ) {}

    /** @return array{data: LengthAwarePaginator} */
    public function list(?string $q, ?string $status, int $perPage = 20): array
    {
        $query = $this->baseListQuery()
            ->orderByDesc('ws.updated_at')
            ->orderByDesc('ws.id');

        $search = trim((string) $q);

        if (mb_strlen($search) >= 3) {
            $like = '%' . $search . '%';
            $query->where(function ($builder) use ($like): void {
                $builder
                    ->where('ai.project_name', 'like', $like)
                    ->orWhere('ai.allowed_domain', 'like', $like)
                    ->orWhere('ua.correo', 'like', $like)
                    ->orWhereRaw('CAST(ws.id AS CHAR) LIKE ?', [$like]);
            });
        }

        $statusFilter = trim((string) $status);

        if ($statusFilter !== '' && $statusFilter !== '__all__') {
            $query->where('ws.status', $statusFilter);
        }

        return [
            'data' => $query->paginate($perPage)->withQueryString(),
        ];
    }

    public function find(int $subscriptionId): WebsiteSubscription
    {
        $row = $this->baseListQuery()->where('ws.id', $subscriptionId)->first();

        if ($row === null) {
            throw ValidationException::withMessages([
                'subscription' => ['La suscripción no existe.'],
            ]);
        }

        $subscription = WebsiteSubscription::query()->findOrFail($subscriptionId);
        $subscription->setRelation('apiIntegration', ApiIntegration::query()->find($row->api_integration_id));
        $subscription->setAttribute('project_name', $row->project_name);
        $subscription->setAttribute('allowed_domain', $row->allowed_domain);
        $subscription->setAttribute('public_key', $row->public_key);
        $subscription->setAttribute('integration_status', $row->integration_status);
        $subscription->setAttribute('owner_email', $row->owner_email);
        $subscription->setAttribute('owner_name', $this->formatOwnerName($row));
        $subscription->setAttribute('current_period_status', $row->current_period_status);
        $subscription->setAttribute('current_period_amount', $row->current_period_amount);
        $subscription->setAttribute('access_allowed', (bool) $row->access_allowed);
        $subscription->setAttribute('mercadopago_plan_name', $row->mercadopago_plan_name);
        $subscription->setAttribute('mercadopago_plan_amount', $row->mercadopago_plan_amount);
        $subscription->setAttribute('mercadopago_plan_payment_url', $row->mercadopago_plan_payment_url);
        $subscription->load('mercadopagoPlan');

        return $subscription;
    }

    /** @return Collection<int, WebsiteSubscriptionPeriod> */
    public function listPeriods(WebsiteSubscription $subscription): Collection
    {
        $this->periodService->ensureRollingPeriods($subscription);

        return WebsiteSubscriptionPeriod::query()
            ->where('website_subscription_id', $subscription->id)
            ->orderBy('year')
            ->orderBy('month')
            ->get();
    }

    /** @param array<string, mixed> $data */
    public function create(array $data): WebsiteSubscription
    {
        $integrationId = (int) $data['api_integration_id'];

        if (WebsiteSubscription::query()->where('api_integration_id', $integrationId)->exists()) {
            throw ValidationException::withMessages([
                'api_integration_id' => ['Esta integración ya tiene una suscripción.'],
            ]);
        }

        $integration = ApiIntegration::query()->find($integrationId);

        if ($integration === null) {
            throw ValidationException::withMessages([
                'api_integration_id' => ['La integración no existe.'],
            ]);
        }

        $defaultAmount = (float) ($data['default_amount'] ?? 0);
        $graceMonths = max(0, min(24, (int) ($data['grace_months_count'] ?? 0)));
        $planId = $this->planAdminService->resolvePlanId(
            isset($data['mercadopago_subscription_plan_id'])
                ? (int) $data['mercadopago_subscription_plan_id']
                : null,
        );

        if ($planId !== null && ($defaultAmount <= 0 || ! isset($data['default_amount']))) {
            $plan = MercadoPagoSubscriptionPlan::query()->find($planId);
            $defaultAmount = $plan ? (float) $plan->amount : $defaultAmount;
        }

        $subscription = WebsiteSubscription::query()->create([
            'api_integration_id' => $integrationId,
            'mercadopago_subscription_plan_id' => $planId,
            'status' => $data['status'] ?? 'active',
            'grace_months_count' => $graceMonths,
            'default_amount' => $defaultAmount,
            'notes' => isset($data['notes']) ? trim((string) $data['notes']) : null,
        ]);

        $this->periodService->ensureRollingPeriods($subscription);
        $this->periodService->applyGraceMonths($subscription, $graceMonths);

        return $this->find((int) $subscription->id);
    }

    /** @param array<string, mixed> $data */
    public function update(WebsiteSubscription $subscription, array $data): WebsiteSubscription
    {
        $subscription->status = $data['status'] ?? $subscription->status;
        $subscription->default_amount = (float) ($data['default_amount'] ?? $subscription->default_amount);
        $subscription->notes = isset($data['notes']) ? trim((string) $data['notes']) : $subscription->notes;

        if (array_key_exists('mercadopago_subscription_plan_id', $data)) {
            $subscription->mercadopago_subscription_plan_id = $this->planAdminService->resolvePlanId(
                $data['mercadopago_subscription_plan_id'] !== null
                    ? (int) $data['mercadopago_subscription_plan_id']
                    : null,
            );
        }

        $newGrace = max(0, min(24, (int) ($data['grace_months_count'] ?? $subscription->grace_months_count)));

        if ($newGrace !== (int) $subscription->grace_months_count) {
            $subscription->grace_months_count = $newGrace;
            $this->periodService->applyGraceMonths($subscription, $newGrace);
        }

        $subscription->save();
        $this->periodService->ensureRollingPeriods($subscription);

        return $this->find((int) $subscription->id);
    }

    /** @param array<string, mixed> $data */
    public function updatePeriod(WebsiteSubscriptionPeriod $period, array $data): WebsiteSubscriptionPeriod
    {
        if (isset($data['amount'])) {
            $period->amount = (float) $data['amount'];
        }

        if (isset($data['status'])) {
            $period->status = $data['status'];

            if ($data['status'] === WebsiteSubscriptionPeriodStatus::Paid->value && $period->paid_at === null) {
                $period->paid_at = Carbon::now();
            }

            if ($data['status'] !== WebsiteSubscriptionPeriodStatus::Paid->value) {
                $period->paid_at = null;
                $period->mercadopago_payment_id = null;
            }
        }

        $period->save();

        return $period;
    }

    public function markPeriodPaid(WebsiteSubscriptionPeriod $period): WebsiteSubscriptionPeriod
    {
        return $this->periodService->markPeriodPaid($period);
    }

    /** @return Collection<int, object> */
    public function listAvailableIntegrations(): Collection
    {
        return DB::table('api_integrations as ai')
            ->leftJoin('website_subscriptions as ws', 'ws.api_integration_id', '=', 'ai.id')
            ->leftJoin('user_auth as ua', 'ua.id', '=', 'ai.user_auth_id')
            ->leftJoin('user_info as ui', 'ui.user_auth_id', '=', 'ua.id')
            ->whereNull('ws.id')
            ->where('ai.status', 'active')
            ->orderBy('ai.project_name')
            ->get([
                'ai.id',
                'ai.project_name',
                'ai.allowed_domain',
                'ua.correo as owner_email',
                'ui.nombre as owner_nombre',
                'ui.apellido as owner_apellido',
            ]);
    }

    /** @return array<string, mixed> */
    public function mercadoPagoConfig(): array
    {
        return [
            'subscription_plan_url' => config('mercadopago.subscription_plan_url'),
            'webhook_url' => rtrim((string) config('app.url'), '/') . '/api/v1/webhooks/mercadopago',
            'access_token_configured' => filled(config('mercadopago.access_token')),
        ];
    }

    private function baseListQuery()
    {
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;
        $blockDay = WebsiteSubscriptionAccessService::BLOCK_DAY;

        return DB::table('website_subscriptions as ws')
            ->join('api_integrations as ai', 'ai.id', '=', 'ws.api_integration_id')
            ->leftJoin('user_auth as ua', 'ua.id', '=', 'ai.user_auth_id')
            ->leftJoin('user_info as ui', 'ui.user_auth_id', '=', 'ua.id')
            ->leftJoin('mercadopago_subscription_plans as msp', 'msp.id', '=', 'ws.mercadopago_subscription_plan_id')
            ->leftJoin('website_subscription_periods as wsp', function ($join) use ($year, $month): void {
                $join->on('wsp.website_subscription_id', '=', 'ws.id')
                    ->where('wsp.year', '=', $year)
                    ->where('wsp.month', '=', $month);
            })
            ->select([
                'ws.*',
                'ai.project_name',
                'ai.allowed_domain',
                'ai.public_key',
                'ai.status as integration_status',
                'ua.correo as owner_email',
                'ui.nombre as owner_nombre',
                'ui.apellido as owner_apellido',
                'ui.apodo as owner_apodo',
                'wsp.status as current_period_status',
                'wsp.amount as current_period_amount',
                'msp.name as mercadopago_plan_name',
                'msp.amount as mercadopago_plan_amount',
                'msp.payment_url as mercadopago_plan_payment_url',
                DB::raw("CASE
                    WHEN ai.status <> 'active' THEN 0
                    WHEN ws.status IN ('cancelled', 'paused') THEN 0
                    WHEN wsp.status IN ('paid', 'grace', 'waived') THEN 1
                    WHEN DAY(CURDATE()) < {$blockDay} THEN 1
                    ELSE 0
                END as access_allowed"),
            ]);
    }

    private function formatOwnerName(object $row): ?string
    {
        $fullName = trim((string) ($row->owner_nombre ?? '') . ' ' . (string) ($row->owner_apellido ?? ''));

        if ($fullName !== '') {
            return $fullName;
        }

        $nickname = trim((string) ($row->owner_apodo ?? ''));

        return $nickname !== '' ? $nickname : null;
    }
}
