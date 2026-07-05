<?php

namespace App\Services\Admin;

use App\Models\MarketingPlanSubscription;
use App\Support\MarketingLabels;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MarketingSubscriptionAdminService
{
    /** @return array{data: LengthAwarePaginator} */
    public function list(?string $q, ?string $status, ?int $planId, int $perPage = 20): array
    {
        $query = $this->baseListQuery()
            ->orderByDesc('mps.updated_at')
            ->orderByDesc('mps.id');

        $search = trim((string) $q);

        if (mb_strlen($search) >= 3) {
            $like = '%' . $search . '%';
            $query->where(function ($builder) use ($like): void {
                $builder
                    ->where('mp.name', 'like', $like)
                    ->orWhere('ua_client.correo', 'like', $like)
                    ->orWhere('ua_ent.correo', 'like', $like)
                    ->orWhereRaw('CAST(mps.id AS CHAR) LIKE ?', [$like]);
            });
        }

        $statusFilter = trim((string) $status);

        if ($statusFilter !== '' && $statusFilter !== '__all__') {
            $query->where('mps.status', $statusFilter);
        }

        if ($planId !== null && $planId > 0) {
            $query->where('mps.plan_id', $planId);
        }

        return [
            'data' => $query->paginate($perPage)->withQueryString(),
        ];
    }

    public function find(int $subscriptionId): object
    {
        $row = $this->baseListQuery()->where('mps.id', $subscriptionId)->first();

        if ($row === null) {
            throw ValidationException::withMessages([
                'subscription' => ['La suscripción no existe.'],
            ]);
        }

        return $row;
    }

    public function updateStatus(MarketingPlanSubscription $subscription, string $status, ?int $assignedUserId = null): MarketingPlanSubscription
    {
        if (! in_array($status, MarketingLabels::subscriptionStatuses(), true)) {
            throw ValidationException::withMessages([
                'status' => ['El estado de la suscripción no es válido.'],
            ]);
        }

        $subscription->load('pricingOption.mercadopagoPlan');

        if ($status === 'pending_payment') {
            $mpPlan = $subscription->pricingOption?->mercadopagoPlan;

            if ($mpPlan === null || trim((string) $mpPlan->payment_url) === '') {
                throw ValidationException::withMessages([
                    'status' => ['Asigná un plan Mercado Pago a la opción de precio antes de enviar a pago pendiente.'],
                ]);
            }

            $subscription->payment_required = true;
            $subscription->payment_status = 'pending';
            $subscription->payment_provider = 'mercadopago';
            $subscription->payment_reference = $mpPlan->payment_url;
        }

        if ($status === 'active') {
            $subscription->payment_status = $subscription->payment_required ? 'paid' : $subscription->payment_status;
            $subscription->activated_at = $subscription->activated_at ?? now();
        }

        if (in_array($status, ['cancelled', 'completed'], true) && $subscription->payment_status === 'pending') {
            $subscription->payment_status = 'cancelled';
        }

        $subscription->status = $status;

        if ($assignedUserId !== null && $assignedUserId > 0) {
            $subscription->assigned_marketing_user_id = $assignedUserId;
        }

        $subscription->save();

        return $subscription->fresh(['plan', 'pricingOption.mercadopagoPlan']);
    }

    public function markPaid(MarketingPlanSubscription $subscription): MarketingPlanSubscription
    {
        $subscription->payment_status = 'paid';
        $subscription->status = 'active';
        $subscription->payment_required = true;
        $subscription->activated_at = now();
        $subscription->save();

        return $subscription->fresh(['plan', 'pricingOption.mercadopagoPlan']);
    }

    private function baseListQuery()
    {
        return DB::table('marketing_plan_subscriptions as mps')
            ->join('marketing_plans as mp', 'mp.id', '=', 'mps.plan_id')
            ->join('marketing_plan_pricing_options as mpo', 'mpo.id', '=', 'mps.pricing_option_id')
            ->leftJoin('mercadopago_subscription_plans as msp', 'msp.id', '=', 'mpo.mercadopago_subscription_plan_id')
            ->leftJoin('user_auth as ua_client', 'ua_client.id', '=', 'mps.client_user_id')
            ->leftJoin('user_contacto as uc_client', 'uc_client.user_auth_id', '=', 'ua_client.id')
            ->leftJoin('user_info as ui_client', 'ui_client.user_auth_id', '=', 'ua_client.id')
            ->leftJoin('user_auth as ua_ent', 'ua_ent.id', '=', 'mps.entrepreneur_user_id')
            ->leftJoin('user_contacto as uc_ent', 'uc_ent.user_auth_id', '=', 'ua_ent.id')
            ->leftJoin('user_info as ui_ent', 'ui_ent.user_auth_id', '=', 'ua_ent.id')
            ->select([
                'mps.*',
                'mp.name as plan_name',
                'mp.slug as plan_slug',
                'mp.status as plan_status',
                'mpo.duration_months as pricing_duration_months',
                'mpo.currency as pricing_currency',
                'msp.id as mercadopago_plan_id',
                'msp.name as mercadopago_plan_name',
                'msp.amount as mercadopago_plan_amount',
                'msp.payment_url as mercadopago_plan_payment_url',
                'ua_client.correo as client_auth_correo',
                'uc_client.correo as client_contacto_correo',
                'ui_client.nombre as client_nombre',
                'ui_client.apellido as client_apellido',
                'ua_ent.correo as entrepreneur_auth_correo',
                'uc_ent.correo as entrepreneur_contacto_correo',
                'ui_ent.nombre as entrepreneur_nombre',
                'ui_ent.apellido as entrepreneur_apellido',
            ]);
    }
}
