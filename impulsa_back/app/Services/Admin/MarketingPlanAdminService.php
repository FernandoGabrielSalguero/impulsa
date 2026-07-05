<?php

namespace App\Services\Admin;

use App\Models\MarketingPlan;
use App\Models\MarketingPlanFeature;
use App\Models\MarketingPlanPricingOption;
use App\Support\MarketingLabels;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MarketingPlanAdminService
{
    public function __construct(
        private readonly MercadoPagoSubscriptionPlanAdminService $mpPlanService,
    ) {}

    /** @return array{data: LengthAwarePaginator} */
    public function list(?string $q, ?string $status, int $perPage = 20): array
    {
        $query = MarketingPlan::query()
            ->withCount(['features', 'pricingOptions', 'subscriptions'])
            ->withCount([
                'subscriptions as active_subscriptions_count' => static fn ($builder) => $builder->where('status', 'active'),
            ])
            ->orderByRaw("FIELD(status, 'published', 'draft', 'paused', 'archived')")
            ->orderByDesc('updated_at');

        $search = trim((string) $q);

        if (mb_strlen($search) >= 3) {
            $like = '%' . $search . '%';
            $query->where(function ($builder) use ($like): void {
                $builder
                    ->where('name', 'like', $like)
                    ->orWhere('slug', 'like', $like)
                    ->orWhere('objective', 'like', $like)
                    ->orWhereRaw('CAST(id AS CHAR) LIKE ?', [$like]);
            });
        }

        $statusFilter = trim((string) $status);

        if ($statusFilter !== '' && $statusFilter !== '__all__') {
            $query->where('status', $statusFilter);
        }

        return [
            'data' => $query->paginate($perPage)->withQueryString(),
        ];
    }

    public function find(int $planId): MarketingPlan
    {
        $plan = MarketingPlan::query()
            ->with([
                'features',
                'pricingOptions.mercadopagoPlan',
            ])
            ->withCount([
                'subscriptions',
                'subscriptions as active_subscriptions_count' => static fn ($builder) => $builder->where('status', 'active'),
            ])
            ->find($planId);

        if ($plan === null) {
            throw ValidationException::withMessages([
                'plan' => ['El plan de marketing no existe.'],
            ]);
        }

        return $plan;
    }

    public function preview(int $planId): MarketingPlan
    {
        return $this->find($planId);
    }

    /** @param array<string, mixed> $data */
    public function create(array $data, int $userId): MarketingPlan
    {
        return DB::transaction(function () use ($data, $userId): MarketingPlan {
            $plan = MarketingPlan::query()->create($this->planAttributes($data, $userId, isCreate: true));
            $this->syncFeatures($plan, $data['features'] ?? []);
            $this->syncPricingOptions($plan, $data['pricing_options'] ?? []);

            return $this->find((int) $plan->id);
        });
    }

    /** @param array<string, mixed> $data */
    public function update(MarketingPlan $plan, array $data): MarketingPlan
    {
        return DB::transaction(function () use ($plan, $data): MarketingPlan {
            $plan->fill($this->planAttributes($data, (int) ($plan->created_by_user_id ?? 0), isCreate: false, excludePlanId: (int) $plan->id));
            $plan->save();
            $this->syncFeatures($plan, $data['features'] ?? []);
            $this->syncPricingOptions($plan, $data['pricing_options'] ?? []);

            return $this->find((int) $plan->id);
        });
    }

    public function updateStatus(MarketingPlan $plan, string $status): MarketingPlan
    {
        if (! in_array($status, MarketingLabels::planStatuses(), true)) {
            throw ValidationException::withMessages([
                'status' => ['El estado del plan no es válido.'],
            ]);
        }

        $plan->status = $status;
        $plan->save();

        return $this->find((int) $plan->id);
    }

    /** @param array<string, mixed> $data */
    private function planAttributes(array $data, int $userId, bool $isCreate, ?int $excludePlanId = null): array
    {
        $name = trim((string) ($data['name'] ?? ''));

        if ($name === '') {
            throw ValidationException::withMessages([
                'name' => ['El nombre del plan es obligatorio.'],
            ]);
        }

        $slug = trim((string) ($data['slug'] ?? ''));

        if ($slug === '') {
            $slug = Str::slug($name);
        }

        $status = trim((string) ($data['status'] ?? 'draft'));

        if (! in_array($status, MarketingLabels::planStatuses(), true)) {
            $status = 'draft';
        }

        $attributes = [
            'name' => $name,
            'slug' => $this->uniqueSlug($slug, $isCreate ? null : $excludePlanId),
            'short_description' => $this->nullableString($data['short_description'] ?? null),
            'full_description' => $this->nullableString($data['full_description'] ?? null),
            'objective' => $this->nullableString($data['objective'] ?? null),
            'recommended_ad_budget_min' => $this->nullableDecimal($data['recommended_ad_budget_min'] ?? null),
            'recommended_ad_budget_max' => $this->nullableDecimal($data['recommended_ad_budget_max'] ?? null),
            'setup_fee' => $this->decimal($data['setup_fee'] ?? 0),
            'billing_period' => trim((string) ($data['billing_period'] ?? 'monthly')) ?: 'monthly',
            'report_frequency' => $this->nullableString($data['report_frequency'] ?? null),
            'support_level' => $this->nullableString($data['support_level'] ?? null),
            'is_visible_to_clients' => filter_var($data['is_visible_to_clients'] ?? false, FILTER_VALIDATE_BOOL),
            'status' => $status,
        ];

        if ($isCreate) {
            $attributes['created_by_user_id'] = $userId;
        }

        return $attributes;
    }

    /** @param list<array<string, mixed>> $features */
    private function syncFeatures(MarketingPlan $plan, array $features): void
    {
        $keptIds = [];

        foreach (array_values($features) as $index => $feature) {
            $name = trim((string) ($feature['feature_name'] ?? ''));

            if ($name === '') {
                continue;
            }

            $payload = [
                'feature_name' => $name,
                'feature_description' => $this->nullableString($feature['feature_description'] ?? null),
                'quantity' => $this->nullableDecimal($feature['quantity'] ?? null),
                'unit' => $this->nullableString($feature['unit'] ?? null),
                'feature_order' => (int) ($feature['feature_order'] ?? $index),
                'is_highlighted' => filter_var($feature['is_highlighted'] ?? false, FILTER_VALIDATE_BOOL),
            ];

            $featureId = isset($feature['id']) ? (int) $feature['id'] : 0;

            if ($featureId > 0) {
                $existing = MarketingPlanFeature::query()
                    ->where('plan_id', $plan->id)
                    ->where('id', $featureId)
                    ->first();

                if ($existing !== null) {
                    $existing->fill($payload);
                    $existing->save();
                    $keptIds[] = (int) $existing->id;

                    continue;
                }
            }

            $created = $plan->features()->create($payload);
            $keptIds[] = (int) $created->id;
        }

        $deleteQuery = MarketingPlanFeature::query()->where('plan_id', $plan->id);

        if ($keptIds !== []) {
            $deleteQuery->whereNotIn('id', $keptIds);
        }

        $deleteQuery->delete();
    }

    /** @param list<array<string, mixed>> $pricingOptions */
    private function syncPricingOptions(MarketingPlan $plan, array $pricingOptions): void
    {
        $keptIds = [];

        foreach (array_values($pricingOptions) as $index => $option) {
            $durationMonths = (int) ($option['duration_months'] ?? 0);
            $monthlyPrice = $this->decimal($option['monthly_price'] ?? 0);

            if ($durationMonths <= 0 || $monthlyPrice <= 0) {
                continue;
            }

            $rawMpPlanId = $option['mercadopago_subscription_plan_id'] ?? null;
            $mpPlanId = $rawMpPlanId !== null && (int) $rawMpPlanId > 0
                ? $this->mpPlanService->resolvePlanId((int) $rawMpPlanId)
                : null;

            $payload = [
                'duration_months' => $durationMonths,
                'monthly_price' => $monthlyPrice,
                'total_price' => round($monthlyPrice * $durationMonths, 2),
                'setup_fee' => $this->decimal($option['setup_fee'] ?? 0),
                'currency' => strtoupper(trim((string) ($option['currency'] ?? 'ARS')) ?: 'ARS'),
                'mercadopago_subscription_plan_id' => $mpPlanId,
                'is_featured' => filter_var($option['is_featured'] ?? false, FILTER_VALIDATE_BOOL),
                'is_default' => filter_var($option['is_default'] ?? false, FILTER_VALIDATE_BOOL),
                'display_order' => (int) ($option['display_order'] ?? $index),
            ];

            $optionId = isset($option['id']) ? (int) $option['id'] : 0;

            if ($optionId > 0) {
                $existing = MarketingPlanPricingOption::query()
                    ->where('plan_id', $plan->id)
                    ->where('id', $optionId)
                    ->first();

                if ($existing !== null) {
                    $existing->fill($payload);
                    $existing->save();
                    $keptIds[] = (int) $existing->id;

                    continue;
                }
            }

            $created = $plan->pricingOptions()->create($payload);
            $keptIds[] = (int) $created->id;
        }

        $orphanQuery = MarketingPlanPricingOption::query()->where('plan_id', $plan->id);

        if ($keptIds !== []) {
            $orphanQuery->whereNotIn('id', $keptIds);
        }

        foreach ($orphanQuery->get() as $orphan) {
            if ($orphan->subscriptions()->exists()) {
                throw ValidationException::withMessages([
                    'pricing_options' => ['No se puede quitar una opción de precio que ya tiene suscripciones.'],
                ]);
            }

            $orphan->delete();
        }
    }

    private function uniqueSlug(string $slug, ?int $excludeId): string
    {
        $base = Str::slug($slug) ?: Str::slug('plan-marketing');
        $candidate = $base;
        $suffix = 2;

        while (MarketingPlan::query()
            ->when($excludeId !== null, static fn ($builder) => $builder->where('id', '!=', $excludeId))
            ->where('slug', $candidate)
            ->exists()) {
            $candidate = $base . '-' . $suffix;
            $suffix++;
        }

        return $candidate;
    }

    private function nullableString(mixed $value): ?string
    {
        $text = trim((string) ($value ?? ''));

        return $text === '' ? null : $text;
    }

    private function nullableDecimal(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return number_format((float) $value, 2, '.', '');
    }

    private function decimal(mixed $value): string
    {
        return number_format((float) $value, 2, '.', '');
    }
}
