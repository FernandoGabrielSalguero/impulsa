<?php

namespace App\Services\Admin;

use App\Models\MarketingPlan;
use App\Models\MarketingPlanPricingOption;
use App\Models\MarketingPlanSubscription;
use Illuminate\Support\Facades\DB;

class MarketingDashboardAdminService
{
    /** @return array<string, mixed> */
    public function summary(): array
    {
        $activeSubscriptions = MarketingPlanSubscription::query()->where('status', 'active');

        return [
            'total_plans' => (int) MarketingPlan::query()->count(),
            'published_plans' => (int) MarketingPlan::query()->where('status', 'published')->count(),
            'visible_plans' => (int) MarketingPlan::query()
                ->where('status', 'published')
                ->where('is_visible_to_clients', true)
                ->count(),
            'active_subscriptions' => (int) (clone $activeSubscriptions)->count(),
            'pending_requests' => (int) MarketingPlanSubscription::query()->where('status', 'requested')->count(),
            'pending_payment' => (int) MarketingPlanSubscription::query()->where('status', 'pending_payment')->count(),
            'monthly_recurring_revenue' => (float) (clone $activeSubscriptions)->sum('monthly_price'),
            'pricing_without_mp' => (int) MarketingPlanPricingOption::query()
                ->whereNull('mercadopago_subscription_plan_id')
                ->whereHas('plan', static fn ($builder) => $builder->whereIn('status', ['published', 'draft']))
                ->count(),
            'subscriptions_by_plan' => DB::table('marketing_plan_subscriptions as mps')
                ->join('marketing_plans as mp', 'mp.id', '=', 'mps.plan_id')
                ->select([
                    'mps.plan_id',
                    'mp.name as plan_name',
                    'mp.status as plan_status',
                ])
                ->selectRaw("SUM(CASE WHEN mps.status = 'active' THEN 1 ELSE 0 END) as active_count")
                ->selectRaw('COUNT(*) as total_count')
                ->groupBy('mps.plan_id', 'mp.name', 'mp.status')
                ->orderByDesc('active_count')
                ->get()
                ->map(static fn ($row): array => [
                    'plan_id' => (int) $row->plan_id,
                    'plan_name' => (string) $row->plan_name,
                    'plan_status' => (string) $row->plan_status,
                    'active_count' => (int) $row->active_count,
                    'total_count' => (int) $row->total_count,
                ])
                ->values()
                ->all(),
            'subscriptions_by_status' => MarketingPlanSubscription::query()
                ->select('status')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('status')
                ->orderBy('status')
                ->get()
                ->map(static fn ($row): array => [
                    'status' => (string) $row->status,
                    'status_label' => \App\Support\MarketingLabels::subscriptionStatusLabel((string) $row->status),
                    'total' => (int) $row->total,
                ])
                ->values()
                ->all(),
            'recent_subscriptions' => DB::table('marketing_plan_subscriptions as mps')
                ->join('marketing_plans as mp', 'mp.id', '=', 'mps.plan_id')
                ->leftJoin('user_auth as ua', function ($join): void {
                    $join->on('ua.id', '=', 'mps.client_user_id')
                        ->orOn('ua.id', '=', 'mps.entrepreneur_user_id');
                })
                ->select([
                    'mps.id',
                    'mps.status',
                    'mps.created_at',
                    'mp.name as plan_name',
                    'ua.correo as user_correo',
                ])
                ->orderByDesc('mps.created_at')
                ->limit(5)
                ->get(),
        ];
    }
}
