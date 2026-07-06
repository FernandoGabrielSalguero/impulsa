<?php

namespace App\Services\Marketing;

use Illuminate\Support\Facades\DB;

class MarketingResultsService
{
    /** @return list<array<string, mixed>> */
    public function results(?int $subscriptionId = null): array
    {
        $query = DB::table('marketing_plan_subscriptions as mps')
            ->join('marketing_plans as mp', 'mp.id', '=', 'mps.plan_id')
            ->leftJoin('marketing_campaigns as mc', 'mc.subscription_id', '=', 'mps.id')
            ->leftJoin('marketing_campaign_metrics as mcm', 'mcm.campaign_id', '=', 'mc.id')
            ->leftJoin('marketing_commercial_metrics as mcom', 'mcom.campaign_id', '=', 'mc.id')
            ->leftJoin('marketing_reports as mr', 'mr.subscription_id', '=', 'mps.id');

        if ($subscriptionId !== null && $subscriptionId > 0) {
            $query->where('mps.id', $subscriptionId);
        }

        return $query
            ->groupBy('mps.id', 'mc.id', 'mps.status', 'mp.name', 'mc.campaign_name', 'mc.status')
            ->orderByDesc(DB::raw('COALESCE(MAX(mcm.report_end_date), mps.updated_at)'))
            ->get([
                'mps.id as subscription_id',
                'mps.status as subscription_status',
                'mp.name as plan_name',
                'mc.id as campaign_id',
                'mc.campaign_name',
                'mc.status as campaign_status',
                DB::raw('MAX(mcm.report_end_date) as last_metric_date'),
                DB::raw('SUM(COALESCE(mcm.amount_spent_ars, 0)) as spent_total'),
                DB::raw('SUM(COALESCE(mcm.impressions, 0)) as impressions_total'),
                DB::raw('SUM(COALESCE(mcm.reach, 0)) as reach_total'),
                DB::raw('SUM(COALESCE(mcm.results, 0)) as results_total'),
                DB::raw('SUM(COALESCE(mcom.closed_clients, 0)) as closed_clients_total'),
                DB::raw('SUM(COALESCE(mcom.closed_revenue, 0)) as closed_revenue_total'),
                DB::raw('COUNT(DISTINCT mr.id) as reports_total'),
            ])
            ->map(static fn ($row): array => (array) $row)
            ->all();
    }

    /** @return list<array<string, mixed>> */
    public function reports(): array
    {
        return DB::table('marketing_reports as mr')
            ->join('marketing_plan_subscriptions as mps', 'mps.id', '=', 'mr.subscription_id')
            ->join('marketing_plans as mp', 'mp.id', '=', 'mps.plan_id')
            ->orderByDesc('mr.period_end')
            ->orderByDesc('mr.created_at')
            ->get([
                'mr.*',
                'mp.name as plan_name',
            ])
            ->map(static fn ($row): array => (array) $row)
            ->all();
    }
}
