<?php

namespace App\Services\Emprendedor;

use App\Models\Chatbot;
use App\Models\UserAuth;
use App\Support\ChatbotEventLabels;
use App\Support\EmprendedorIntegrationAccess;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EmprendedorMetricsService
{
    private const PERIOD_DAYS = 30;

    public function __construct(
        private readonly EmprendedorIntegrationAccess $integrationAccess,
    ) {}

    /** @return array<string, mixed> */
    public function dashboard(UserAuth $user): array
    {
        $integration = $this->integrationAccess->integrationForUser($user);
        $integrationId = $integration !== null ? (int) $integration->id : null;
        $since = now()->subDays(self::PERIOD_DAYS - 1)->startOfDay();

        $marketing = $this->marketingSummary($user);

        return [
            'has_integration' => $integrationId !== null,
            'period_days' => self::PERIOD_DAYS,
            'summary' => [
                'visits_total' => $this->visitsTotal($integrationId, $since),
                'chatbot_events_total' => $this->chatbotEventsTotal($integrationId, $since),
                'whatsapp_clicks_total' => $this->chatbotEventTypeTotal($integrationId, $since, 'whatsapp_clicked'),
                'content_views_total' => $this->contentViewsTotal($integrationId, $since),
                'active_campaigns' => $marketing['active_campaigns'],
                'total_spent_ars' => $marketing['total_spent_ars'],
                'reports_count' => $marketing['reports_count'],
            ],
            'visits' => $this->visitsSeries($integrationId, $since),
            'chatbot' => $this->chatbotSeries($integrationId, $since),
            'chatbot_identity' => $this->chatbotIdentity($integrationId),
            'content' => $this->contentTop($integrationId, $since),
            'marketing' => $marketing,
        ];
    }

    /** @return array<string, mixed> */
    public function summary(UserAuth $user): array
    {
        return $this->dashboard($user);
    }

    /** @return array<string, mixed> */
    private function marketingSummary(UserAuth $user): array
    {
        $subscriptionColumn = $user->rol === 'impulsa_cliente' ? 'client_user_id' : 'entrepreneur_user_id';

        $subscriptionIds = DB::table('marketing_plan_subscriptions')
            ->where($subscriptionColumn, $user->id)
            ->pluck('id');

        if ($subscriptionIds->isEmpty()) {
            return [
                'active_campaigns' => 0,
                'total_spent_ars' => 0,
                'reports_count' => 0,
                'campaigns' => [],
                'reports' => [],
                'spending_by_campaign' => [],
                'totals' => [
                    'impressions' => 0,
                    'results' => 0,
                ],
            ];
        }

        $campaigns = DB::table('marketing_campaigns as mc')
            ->whereIn('mc.subscription_id', $subscriptionIds)
            ->orderByDesc('mc.updated_at')
            ->limit(20)
            ->get([
                'mc.id',
                'mc.campaign_name',
                'mc.channel',
                'mc.status',
                'mc.budget',
                'mc.start_date',
                'mc.end_date',
            ]);

        $reportsQuery = DB::table('marketing_reports as mr')
            ->join('marketing_plan_subscriptions as mps', 'mps.id', '=', 'mr.subscription_id')
            ->where("mps.{$subscriptionColumn}", $user->id);

        if ($user->rol === 'impulsa_cliente') {
            $reportsQuery->where('mr.visible_to_client', 1);
        }

        $reports = $reportsQuery
            ->orderByDesc('mr.period_end')
            ->limit(20)
            ->get([
                'mr.id',
                'mr.subscription_id',
                'mr.period_start',
                'mr.period_end',
                'mr.title',
                'mr.summary',
            ]);

        $metricsAggregate = DB::table('marketing_campaign_metrics as mcm')
            ->join('marketing_campaigns as mc', 'mc.id', '=', 'mcm.campaign_id')
            ->whereIn('mc.subscription_id', $subscriptionIds)
            ->selectRaw('COALESCE(SUM(mcm.amount_spent_ars), 0) as total_spent')
            ->selectRaw('COALESCE(SUM(mcm.impressions), 0) as total_impressions')
            ->selectRaw('COALESCE(SUM(mcm.results), 0) as total_results')
            ->first();

        $spendingByCampaign = DB::table('marketing_campaign_metrics as mcm')
            ->join('marketing_campaigns as mc', 'mc.id', '=', 'mcm.campaign_id')
            ->whereIn('mc.subscription_id', $subscriptionIds)
            ->groupBy('mc.id', 'mc.campaign_name')
            ->orderByDesc(DB::raw('SUM(mcm.amount_spent_ars)'))
            ->limit(8)
            ->get([
                'mc.id',
                'mc.campaign_name',
                DB::raw('COALESCE(SUM(mcm.amount_spent_ars), 0) as amount_spent_ars'),
                DB::raw('COALESCE(SUM(mcm.impressions), 0) as impressions'),
                DB::raw('COALESCE(SUM(mcm.results), 0) as results'),
            ])
            ->map(static fn ($row): array => [
                'id' => (int) $row->id,
                'campaign_name' => (string) $row->campaign_name,
                'amount_spent_ars' => (float) $row->amount_spent_ars,
                'impressions' => (int) $row->impressions,
                'results' => (float) $row->results,
            ])
            ->all();

        return [
            'active_campaigns' => DB::table('marketing_campaigns')
                ->whereIn('subscription_id', $subscriptionIds)
                ->where('status', 'active')
                ->count(),
            'total_spent_ars' => (float) ($metricsAggregate->total_spent ?? 0),
            'reports_count' => $reports->count(),
            'campaigns' => $campaigns,
            'reports' => $reports,
            'spending_by_campaign' => $spendingByCampaign,
            'totals' => [
                'impressions' => (int) ($metricsAggregate->total_impressions ?? 0),
                'results' => (float) ($metricsAggregate->total_results ?? 0),
            ],
        ];
    }

    /** @return array{labels: list<string>, values: list<int>, total: int} */
    private function visitsSeries(?int $integrationId, Carbon $since): array
    {
        if ($integrationId === null) {
            return $this->emptyDailySeries($since);
        }

        $rows = DB::table('visit_user_page')
            ->where('api_integration_id', $integrationId)
            ->where('visited_at', '>=', $since)
            ->selectRaw('DATE(visited_at) as visit_date, COUNT(*) as total')
            ->groupBy('visit_date')
            ->orderBy('visit_date')
            ->get()
            ->keyBy('visit_date');

        return $this->buildDailySeries($since, $rows, 'total');
    }

    /** @return array{labels: list<string>, datasets: list<array{event_type: string, label: string, value: int}>, total: int} */
    private function chatbotSeries(?int $integrationId, Carbon $since): array
    {
        if ($integrationId === null) {
            return [
                'labels' => [],
                'datasets' => [],
                'total' => 0,
            ];
        }

        $rows = DB::table('chatbot_events')
            ->where('api_integration_id', $integrationId)
            ->where('created_at', '>=', $since)
            ->selectRaw('event_type, COUNT(*) as total')
            ->groupBy('event_type')
            ->orderByDesc('total')
            ->get();

        $datasets = $rows->map(static fn ($row): array => [
            'event_type' => (string) $row->event_type,
            'label' => ChatbotEventLabels::label((string) $row->event_type),
            'value' => (int) $row->total,
        ])->values()->all();

        return [
            'labels' => collect($datasets)->pluck('label')->all(),
            'datasets' => $datasets,
            'total' => (int) collect($datasets)->sum('value'),
            'whatsapp_clicks' => $this->chatbotEventTypeTotal($integrationId, $since, 'whatsapp_clicked'),
            'top_questions' => $this->chatbotTopQuestions($integrationId, $since),
        ];
    }

    /** @return list<array{label: string, count: int}> */
    private function chatbotTopQuestions(?int $integrationId, Carbon $since): array
    {
        if ($integrationId === null) {
            return [];
        }

        $labelExpr = "COALESCE(NULLIF(TRIM(cno.label), ''), JSON_UNQUOTE(JSON_EXTRACT(ce.metadata_json, '$.option_label')), 'Pregunta eliminada')";

        $rows = DB::table('chatbot_events as ce')
            ->leftJoin('chatbot_node_options as cno', 'cno.id', '=', 'ce.option_id')
            ->where('ce.api_integration_id', $integrationId)
            ->where('ce.created_at', '>=', $since)
            ->where('ce.event_type', 'option_clicked')
            ->selectRaw("{$labelExpr} as question_label, COUNT(*) as total")
            ->groupBy(DB::raw($labelExpr))
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        return $rows->map(static fn ($row): array => [
            'label' => trim((string) $row->question_label) !== ''
                ? (string) $row->question_label
                : 'Pregunta eliminada',
            'count' => (int) $row->total,
        ])->values()->all();
    }

    /** @return array{has_avatar: bool, icon_background_color: string, updated_at: string|null} */
    private function chatbotIdentity(?int $integrationId): array
    {
        $fallback = [
            'has_avatar' => false,
            'icon_background_color' => Chatbot::DEFAULT_ICON_BACKGROUND_COLOR,
            'updated_at' => null,
        ];

        if ($integrationId === null) {
            return $fallback;
        }

        $chatbot = Chatbot::query()
            ->where('api_integration_id', $integrationId)
            ->first();

        if ($chatbot === null) {
            return $fallback;
        }

        $stored = trim((string) $chatbot->avatar_url);
        $color = strtoupper(trim((string) ($chatbot->icon_background_color ?? '')));

        return [
            'has_avatar' => $stored !== '' && str_starts_with($stored, 'chatbot-avatars/'),
            'icon_background_color' => preg_match('/^#[0-9A-F]{6}$/', $color) === 1
                ? $color
                : Chatbot::DEFAULT_ICON_BACKGROUND_COLOR,
            'updated_at' => $chatbot->updated_at?->toISOString(),
        ];
    }

    /** @return array{blog_posts: list<array<string, mixed>>, products: list<array<string, mixed>>, total: int} */
    private function contentTop(?int $integrationId, Carbon $since): array
    {
        if ($integrationId === null || ! Schema::hasTable('api_content_views')) {
            return [
                'blog_posts' => [],
                'products' => [],
                'total' => 0,
            ];
        }

        $blogPosts = DB::table('api_content_views as acv')
            ->join('api_blog_posts as abp', function ($join): void {
                $join->on('abp.id', '=', 'acv.content_id')
                    ->where('acv.content_type', '=', 'blog_post');
            })
            ->where('acv.api_integration_id', $integrationId)
            ->where('acv.created_at', '>=', $since)
            ->groupBy('acv.content_id', 'abp.title', 'abp.slug')
            ->orderByDesc(DB::raw('COUNT(*)'))
            ->limit(5)
            ->get([
                'acv.content_id',
                'abp.title',
                'abp.slug',
                DB::raw('COUNT(*) as views'),
            ])
            ->map(static fn ($row): array => [
                'id' => (int) $row->content_id,
                'title' => (string) $row->title,
                'slug' => (string) $row->slug,
                'views' => (int) $row->views,
            ])
            ->all();

        $products = DB::table('api_content_views as acv')
            ->join('api_products as ap', function ($join): void {
                $join->on('ap.id', '=', 'acv.content_id')
                    ->where('acv.content_type', '=', 'product');
            })
            ->where('acv.api_integration_id', $integrationId)
            ->where('acv.created_at', '>=', $since)
            ->groupBy('acv.content_id', 'ap.title', 'ap.slug')
            ->orderByDesc(DB::raw('COUNT(*)'))
            ->limit(5)
            ->get([
                'acv.content_id',
                'ap.title',
                'ap.slug',
                DB::raw('COUNT(*) as views'),
            ])
            ->map(static fn ($row): array => [
                'id' => (int) $row->content_id,
                'title' => (string) $row->title,
                'slug' => (string) $row->slug,
                'views' => (int) $row->views,
            ])
            ->all();

        $total = (int) DB::table('api_content_views')
            ->where('api_integration_id', $integrationId)
            ->where('created_at', '>=', $since)
            ->count();

        return [
            'blog_posts' => $blogPosts,
            'products' => $products,
            'total' => $total,
        ];
    }

    private function visitsTotal(?int $integrationId, Carbon $since): int
    {
        if ($integrationId === null) {
            return 0;
        }

        return (int) DB::table('visit_user_page')
            ->where('api_integration_id', $integrationId)
            ->where('visited_at', '>=', $since)
            ->count();
    }

    private function chatbotEventsTotal(?int $integrationId, Carbon $since): int
    {
        if ($integrationId === null) {
            return 0;
        }

        return (int) DB::table('chatbot_events')
            ->where('api_integration_id', $integrationId)
            ->where('created_at', '>=', $since)
            ->count();
    }

    private function chatbotEventTypeTotal(?int $integrationId, Carbon $since, string $eventType): int
    {
        if ($integrationId === null) {
            return 0;
        }

        return (int) DB::table('chatbot_events')
            ->where('api_integration_id', $integrationId)
            ->where('created_at', '>=', $since)
            ->where('event_type', $eventType)
            ->count();
    }

    private function contentViewsTotal(?int $integrationId, Carbon $since): int
    {
        if ($integrationId === null || ! Schema::hasTable('api_content_views')) {
            return 0;
        }

        return (int) DB::table('api_content_views')
            ->where('api_integration_id', $integrationId)
            ->where('created_at', '>=', $since)
            ->count();
    }

    /** @param Collection<int|string, object> $rows */
    private function buildDailySeries(Carbon $since, Collection $rows, string $valueColumn): array
    {
        $labels = [];
        $values = [];
        $cursor = $since->copy();

        for ($i = 0; $i < self::PERIOD_DAYS; $i++) {
            $key = $cursor->toDateString();
            $labels[] = $cursor->format('d/m');
            $values[] = (int) ($rows->get($key)?->$valueColumn ?? 0);
            $cursor->addDay();
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'total' => array_sum($values),
        ];
    }

    /** @return array{labels: list<string>, values: list<int>, total: int} */
    private function emptyDailySeries(Carbon $since): array
    {
        return $this->buildDailySeries($since, collect(), 'total');
    }
}
