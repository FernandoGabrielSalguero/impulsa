<?php

namespace App\Services\Emprendedor;

use App\Models\MarketingPlan;
use App\Models\MarketingPlanPricingOption;
use App\Models\MarketingPlanSubscription;
use App\Models\UserAuth;
use App\Support\MarketingLabels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EmprendedorMarketingService
{
    /** @return Collection<int, MarketingPlan> */
    public function listPlans(): Collection
    {
        return MarketingPlan::query()
            ->with(['features', 'pricingOptions.mercadopagoPlan'])
            ->where('status', 'published')
            ->where('is_visible_to_clients', true)
            ->orderBy('name')
            ->get();
    }

    public function findPlan(int $planId): MarketingPlan
    {
        $plan = MarketingPlan::query()
            ->with(['features', 'pricingOptions.mercadopagoPlan'])
            ->where('status', 'published')
            ->where('is_visible_to_clients', true)
            ->find($planId);

        if ($plan === null) {
            throw ValidationException::withMessages([
                'plan' => ['El plan no está disponible.'],
            ]);
        }

        return $plan;
    }

    /** @return Collection<int, MarketingPlanSubscription> */
    public function listSubscriptions(UserAuth $user): Collection
    {
        return MarketingPlanSubscription::query()
            ->with(['plan', 'pricingOption.mercadopagoPlan'])
            ->where('entrepreneur_user_id', $user->id)
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->get();
    }

    /** @param array<string, mixed> $data */
    public function requestSubscription(UserAuth $user, array $data): MarketingPlanSubscription
    {
        $plan = $this->findPlan((int) $data['plan_id']);

        $pricingOption = MarketingPlanPricingOption::query()
            ->where('plan_id', $plan->id)
            ->where('id', (int) $data['pricing_option_id'])
            ->first();

        if ($pricingOption === null) {
            throw ValidationException::withMessages([
                'pricing_option_id' => ['La opción de precio no pertenece al plan seleccionado.'],
            ]);
        }

        $existingPending = MarketingPlanSubscription::query()
            ->where('entrepreneur_user_id', $user->id)
            ->where('plan_id', $plan->id)
            ->whereIn('status', ['requested', 'meeting_scheduled', 'approved_manually', 'pending_payment', 'active'])
            ->exists();

        if ($existingPending) {
            throw ValidationException::withMessages([
                'plan_id' => ['Ya tenés una solicitud o suscripción activa para este plan.'],
            ]);
        }

        $monthlyPrice = (float) $pricingOption->monthly_price;
        $durationMonths = (int) $pricingOption->duration_months;
        $totalValue = $monthlyPrice * $durationMonths + (float) $pricingOption->setup_fee;

        return DB::transaction(function () use ($user, $plan, $pricingOption, $data, $monthlyPrice, $durationMonths, $totalValue): MarketingPlanSubscription {
            return MarketingPlanSubscription::query()->create([
                'plan_id' => $plan->id,
                'pricing_option_id' => $pricingOption->id,
                'entrepreneur_user_id' => $user->id,
                'status' => 'requested',
                'payment_status' => 'not_required_yet',
                'payment_required' => false,
                'duration_months' => $durationMonths,
                'monthly_price' => $monthlyPrice,
                'total_contract_value' => $totalValue,
                'monthly_ad_budget' => isset($data['monthly_ad_budget']) && $data['monthly_ad_budget'] !== null
                    ? (float) $data['monthly_ad_budget']
                    : null,
                'notes' => trim((string) ($data['notes'] ?? '')) ?: null,
            ]);
        });
    }

    public function paymentUrl(MarketingPlanSubscription $subscription, UserAuth $user): ?string
    {
        $this->assertOwnership($subscription, $user);

        if ($subscription->status !== 'pending_payment') {
            throw ValidationException::withMessages([
                'subscription' => ['La suscripción no está pendiente de pago.'],
            ]);
        }

        $subscription->loadMissing('pricingOption.mercadopagoPlan');
        $url = trim((string) ($subscription->payment_reference ?: $subscription->pricingOption?->mercadopagoPlan?->payment_url));

        if ($url === '') {
            throw ValidationException::withMessages([
                'subscription' => ['Todavía no hay un link de pago disponible.'],
            ]);
        }

        return $url;
    }

    public function assertOwnership(MarketingPlanSubscription $subscription, UserAuth $user): void
    {
        if ((int) $subscription->entrepreneur_user_id !== (int) $user->id) {
            throw ValidationException::withMessages([
                'subscription' => ['No tenés permiso para acceder a esta suscripción.'],
            ]);
        }
    }
}
