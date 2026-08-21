<?php

namespace App\Services\Admin;

use App\Models\UserAuth;
use App\Support\ProjectLabels;
use App\Support\RoleLabels;
use App\Support\TaskLabels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminDashboardService
{
    /** @return array<string, mixed> */
    public function stats(): array
    {
        return [
            'total_users' => (int) UserAuth::query()->count(),
            'users_by_role' => $this->usersByRole(),
            'logins' => $this->loginStats(),
            'projects' => $this->projectStats(),
            'tasks' => $this->taskStats(),
            'consultas' => $this->consultaStats(),
            'web_requests' => $this->webRequestStats(),
            'marketing' => $this->marketingStats(),
            'website_subscriptions' => $this->websiteSubscriptionStats(),
            'goals' => $this->goalStats(),
            'emails' => $this->emailStats(),
            'ai_usage' => $this->aiUsageStats(),
            'content' => $this->contentStats(),
        ];
    }

    /** @return list<array{rol: string, label: string, count: int}> */
    private function usersByRole(): array
    {
        return UserAuth::query()
            ->select('rol', DB::raw('COUNT(*) as count'))
            ->groupBy('rol')
            ->orderByDesc('count')
            ->get()
            ->map(static fn ($row): array => [
                'rol' => (string) $row->rol,
                'label' => RoleLabels::labelFor((string) $row->rol),
                'count' => (int) $row->count,
            ])
            ->values()
            ->all();
    }

    /** @return array<string, mixed> */
    private function loginStats(): array
    {
        $series = [];
        $today = Carbon::today();

        for ($offset = 6; $offset >= 0; $offset--) {
            $date = $today->copy()->subDays($offset);
            $series[] = [
                'date' => $date->toDateString(),
                'label' => $date->format('d/m'),
                'count' => 0,
            ];
        }

        if (! Schema::hasTable('user_ingresos')) {
            return [
                'last_7_days' => 0,
                'last_30_days' => 0,
                'series_7_days' => $series,
            ];
        }

        $from7 = $today->copy()->subDays(6)->toDateString();
        $from30 = $today->copy()->subDays(29)->toDateString();

        $counts = DB::table('user_ingresos')
            ->whereDate('fecha_ingreso', '>=', $from7)
            ->select('fecha_ingreso', DB::raw('COUNT(*) as total'))
            ->groupBy('fecha_ingreso')
            ->pluck('total', 'fecha_ingreso');

        $byDate = [];
        foreach ($counts as $date => $total) {
            $byDate[substr((string) $date, 0, 10)] = (int) $total;
        }

        foreach ($series as &$point) {
            $point['count'] = $byDate[$point['date']] ?? 0;
        }
        unset($point);

        return [
            'last_7_days' => (int) DB::table('user_ingresos')->whereDate('fecha_ingreso', '>=', $from7)->count(),
            'last_30_days' => (int) DB::table('user_ingresos')->whereDate('fecha_ingreso', '>=', $from30)->count(),
            'series_7_days' => $series,
        ];
    }

    /** @return array<string, mixed> */
    private function projectStats(): array
    {
        $byStatus = collect(ProjectLabels::statuses())
            ->map(static fn (string $status): array => [
                'status' => $status,
                'label' => ProjectLabels::statusLabel($status),
                'count' => 0,
            ])
            ->keyBy('status');

        if (! Schema::hasTable('projects')) {
            return [
                'active' => 0,
                'total' => 0,
                'by_status' => $byStatus->values()->all(),
            ];
        }

        $rows = DB::table('projects')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        foreach ($rows as $status => $total) {
            if ($byStatus->has($status)) {
                $item = $byStatus->get($status);
                $item['count'] = (int) $total;
                $byStatus->put($status, $item);
            }
        }

        $activeStatuses = ['planned', 'in_progress', 'in_review'];

        return [
            'active' => (int) DB::table('projects')->whereIn('status', $activeStatuses)->count(),
            'total' => (int) DB::table('projects')->count(),
            'by_status' => $byStatus->values()->all(),
        ];
    }

    /** @return array<string, mixed> */
    private function taskStats(): array
    {
        $byStatus = collect(TaskLabels::statuses())
            ->map(static fn (string $status): array => [
                'status' => $status,
                'label' => TaskLabels::statusLabel($status),
                'count' => 0,
            ])
            ->keyBy('status');

        if (! Schema::hasTable('admin_tareas')) {
            return [
                'open' => 0,
                'overdue' => 0,
                'total' => 0,
                'by_status' => $byStatus->values()->all(),
            ];
        }

        $rows = DB::table('admin_tareas')
            ->select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado');

        foreach ($rows as $status => $total) {
            if ($byStatus->has($status)) {
                $item = $byStatus->get($status);
                $item['count'] = (int) $total;
                $byStatus->put($status, $item);
            }
        }

        return [
            'open' => (int) DB::table('admin_tareas')->whereIn('estado', ['pendiente', 'en_progreso'])->count(),
            'overdue' => (int) DB::table('admin_tareas')
                ->whereIn('estado', ['pendiente', 'en_progreso'])
                ->whereNotNull('fecha_entrega')
                ->whereDate('fecha_entrega', '<', Carbon::today()->toDateString())
                ->count(),
            'total' => (int) DB::table('admin_tareas')->count(),
            'by_status' => $byStatus->values()->all(),
        ];
    }

    /** @return array{pending: int, total: int} */
    private function consultaStats(): array
    {
        if (! Schema::hasTable('forms_clients_contact')) {
            return ['pending' => 0, 'total' => 0];
        }

        return [
            'pending' => (int) DB::table('forms_clients_contact')->where('state', 'recibido')->count(),
            'total' => (int) DB::table('forms_clients_contact')->count(),
        ];
    }

    /** @return array{pending: int, internal_pending: int, external_pending: int} */
    private function webRequestStats(): array
    {
        $internal = 0;
        $external = 0;

        if (Schema::hasTable('landing_page_request')) {
            $query = DB::table('landing_page_request as r');

            if (Schema::hasTable('projects')) {
                $query->leftJoin('projects as p', function ($join): void {
                    $join->on('p.source_id', '=', 'r.id')
                        ->where('p.source_type', '=', 'landing_page_request');
                })->whereNull('p.id');
            }

            $internal = (int) $query->count();
        }

        if (Schema::hasTable('landing_page_requests_external')) {
            $query = DB::table('landing_page_requests_external as r');

            if (Schema::hasTable('projects')) {
                $query->leftJoin('projects as p', function ($join): void {
                    $join->on('p.source_id', '=', 'r.id')
                        ->where('p.source_type', '=', 'landing_page_requests_external');
                })->whereNull('p.id');
            }

            $external = (int) $query->count();
        }

        return [
            'pending' => $internal + $external,
            'internal_pending' => $internal,
            'external_pending' => $external,
        ];
    }

    /** @return array{mrr: float, active_subscriptions: int} */
    private function marketingStats(): array
    {
        if (! Schema::hasTable('marketing_plan_subscriptions')) {
            return ['mrr' => 0.0, 'active_subscriptions' => 0];
        }

        $activeQuery = DB::table('marketing_plan_subscriptions')->where('status', 'active');

        return [
            'mrr' => (float) DB::table('marketing_plan_subscriptions')->where('status', 'active')->sum('monthly_price'),
            'active_subscriptions' => (int) $activeQuery->count(),
        ];
    }

    /** @return array{active: int, total: int} */
    private function websiteSubscriptionStats(): array
    {
        if (! Schema::hasTable('website_subscriptions')) {
            return ['active' => 0, 'total' => 0];
        }

        return [
            'active' => (int) DB::table('website_subscriptions')->where('status', 'active')->count(),
            'total' => (int) DB::table('website_subscriptions')->count(),
        ];
    }

    /** @return array{in_progress: int, completed: int, overdue: int, total: int} */
    private function goalStats(): array
    {
        if (! Schema::hasTable('user_goals')) {
            return [
                'in_progress' => 0,
                'completed' => 0,
                'overdue' => 0,
                'total' => 0,
            ];
        }

        $today = Carbon::today()->toDateString();
        $monitoredRoles = ['impulsa_emprendedor', 'impulsa_cliente'];

        $query = DB::table('user_goals as g')
            ->join('user_auth as ua', 'ua.id', '=', 'g.user_auth_id')
            ->whereIn('ua.rol', $monitoredRoles);

        return [
            'in_progress' => (int) (clone $query)->where('g.status', 'in_progress')->count(),
            'completed' => (int) (clone $query)->where('g.status', 'completed')->count(),
            'overdue' => (int) (clone $query)
                ->whereNotIn('g.status', ['completed', 'cancelled'])
                ->whereNotNull('g.due_date')
                ->whereDate('g.due_date', '<', $today)
                ->count(),
            'total' => (int) (clone $query)->count(),
        ];
    }

    /** @return array{sent: int, failed: int} */
    private function emailStats(): array
    {
        if (! Schema::hasTable('correos_log')) {
            return ['sent' => 0, 'failed' => 0];
        }

        $from = Carbon::today()->subDays(29)->startOfDay();

        $rows = DB::table('correos_log')
            ->where('created_at', '>=', $from)
            ->select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado');

        return [
            'sent' => (int) ($rows['enviado'] ?? 0),
            'failed' => (int) ($rows['fallido'] ?? 0),
        ];
    }

    /** @return array{success: int, failed: int} */
    private function aiUsageStats(): array
    {
        if (! Schema::hasTable('ai_usage_logs')) {
            return ['success' => 0, 'failed' => 0];
        }

        $from = Carbon::today()->subDays(29)->startOfDay();

        $rows = DB::table('ai_usage_logs')
            ->where('created_at', '>=', $from)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'success' => (int) ($rows['success'] ?? 0),
            'failed' => (int) ($rows['failed'] ?? 0),
        ];
    }

    /** @return array{blogs_active: int, academia_active: int, products_active: int} */
    private function contentStats(): array
    {
        return [
            'blogs_active' => Schema::hasTable('api_blog_posts')
                ? (int) DB::table('api_blog_posts')->where('status', 'active')->count()
                : 0,
            'academia_active' => Schema::hasTable('academia_videos')
                ? (int) DB::table('academia_videos')->where('status', 'active')->count()
                : 0,
            'products_active' => Schema::hasTable('api_products')
                ? (int) DB::table('api_products')->where('status', 'active')->count()
                : 0,
        ];
    }
}
