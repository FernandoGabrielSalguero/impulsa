<?php

namespace App\Services\Admin;

use App\Models\UserAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserDeletionService
{
    public function delete(UserAuth $user): void
    {
        DB::transaction(function () use ($user): void {
            $userId = $user->id;

            $projectIds = $this->fetchIds(
                'projects',
                '(client_user_id = ? OR manager_user_id = ?)',
                [$userId, $userId],
            );
            $subscriptionIds = $this->fetchIds(
                'marketing_plan_subscriptions',
                '(client_user_id = ? OR entrepreneur_user_id = ?)',
                [$userId, $userId],
            );

            $campaignQuery = DB::table('marketing_campaigns')
                ->select('id')
                ->where('client_user_id', $userId)
                ->orWhere('entrepreneur_user_id', $userId);

            if ($subscriptionIds !== []) {
                $campaignQuery->orWhereIn('subscription_id', $subscriptionIds);
            }

            $campaignIds = $this->tableExists('marketing_campaigns')
                ? $campaignQuery->pluck('id')->map(fn ($id) => (int) $id)->all()
                : [];

            $this->deleteProjects($projectIds);
            $this->deleteMarketing($userId, $subscriptionIds, $campaignIds);

            $this->deleteWhereAny('admin_tareas', ['responsable_user_id', 'created_by_user_id'], $userId);
            $this->deleteWhere('correos_log', 'user_auth_id', $userId);
            $this->deleteWhere('emprendedor_buyer_persona', 'user_auth_id', $userId);
            $this->deleteWhere('emprendedor_mision', 'user_auth_id', $userId);
            $this->deleteWhere('emprendedor_vision', 'user_auth_id', $userId);
            $this->deleteWhere('landing_page_request', 'user_auth_id', $userId);
            $this->deleteWhere('marketing_client_codes', 'user_auth_id', $userId);
            $this->updateToNull('project_contracts', 'signed_by_user_id', $userId);
            $this->updateToNull('project_contracts', 'created_by_user_id', $userId);
            $this->updateToNull('project_contracts', 'updated_by_user_id', $userId);
            $this->deleteWhere('project_updates', 'created_by', $userId);

            $this->deleteWhere('user_menu_view', 'user_auth_id', $userId);
            $this->deleteWhere('user_params', 'user_auth_id', $userId);
            $this->deleteWhere('user_contacto', 'user_auth_id', $userId);
            $this->deleteWhere('user_info', 'user_auth_id', $userId);
            $this->deleteWhere('user_auth', 'id', $userId);
        });
    }

    private function deleteMarketing(int $userId, array $subscriptionIds, array $campaignIds): void
    {
        $this->deleteByIds('marketing_campaign_metrics', 'campaign_id', $campaignIds);
        $this->deleteByIds('marketing_commercial_metrics', 'campaign_id', $campaignIds);
        $this->deleteByIds('marketing_commercial_metrics', 'subscription_id', $subscriptionIds);
        $this->deleteByIds('marketing_reports', 'subscription_id', $subscriptionIds);
        $this->deleteByIds('marketing_external_campaign_mappings', 'internal_campaign_id', $campaignIds);
        $this->deleteByIds('marketing_external_campaign_mappings', 'internal_subscription_id', $subscriptionIds);

        $this->updateToNull('marketing_campaigns', 'created_by_user_id', $userId);
        $this->updateToNull('marketing_commercial_metrics', 'created_by_user_id', $userId);
        $this->updateToNull('marketing_external_campaign_mappings', 'created_by_user_id', $userId);
        $this->updateToNull('marketing_external_campaign_mappings', 'internal_client_user_id', $userId);
        $this->updateToNull('marketing_external_campaign_mappings', 'internal_entrepreneur_user_id', $userId);
        $this->updateToNull('marketing_import_batches', 'uploaded_by_user_id', $userId);
        $this->updateToNull('marketing_import_rows', 'internal_client_user_id', $userId);
        $this->updateToNull('marketing_import_rows', 'internal_entrepreneur_user_id', $userId);
        $this->updateToNull('marketing_plan_subscriptions', 'assigned_marketing_user_id', $userId);
        $this->updateToNull('marketing_plans', 'created_by_user_id', $userId);
        $this->updateToNull('marketing_reports', 'created_by_user_id', $userId);

        $this->deleteByIds('marketing_campaigns', 'id', $campaignIds);
        $this->deleteByIds('marketing_plan_subscriptions', 'id', $subscriptionIds);
    }

    private function deleteProjects(array $projectIds): void
    {
        if ($projectIds === []) {
            return;
        }

        $phaseIds = $this->fetchIdsByColumn('project_phases', 'project_id', $projectIds);
        $deliverableIds = $this->fetchIdsByColumn('project_deliverables', 'project_id', $projectIds);

        $this->deleteByIds('project_deliverable_tasks', 'deliverable_id', $deliverableIds);
        $this->deleteByIds('project_updates', 'phase_id', $phaseIds);
        $this->deleteByIds('project_updates', 'project_id', $projectIds);
        $this->deleteByIds('project_contracts', 'project_id', $projectIds);
        $this->deleteByIds('project_deliverables', 'id', $deliverableIds);
        $this->deleteByIds('project_phases', 'id', $phaseIds);
        $this->deleteByIds('projects', 'id', $projectIds);
    }

    private function fetchIds(string $table, string $where, array $bindings): array
    {
        if (! $this->tableExists($table)) {
            return [];
        }

        return DB::table($table)
            ->select('id')
            ->whereRaw($where, $bindings)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    private function fetchIdsByColumn(string $table, string $column, array $ids): array
    {
        if ($ids === [] || ! $this->tableExists($table)) {
            return [];
        }

        return DB::table($table)
            ->select('id')
            ->whereIn($column, $ids)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    private function deleteByIds(string $table, string $column, array $ids): void
    {
        if ($ids === [] || ! $this->tableExists($table)) {
            return;
        }

        DB::table($table)->whereIn($column, $ids)->delete();
    }

    private function deleteWhere(string $table, string $column, int $userId): void
    {
        if (! $this->tableExists($table)) {
            return;
        }

        DB::table($table)->where($column, $userId)->delete();
    }

    private function deleteWhereAny(string $table, array $columns, int $userId): void
    {
        if (! $this->tableExists($table)) {
            return;
        }

        DB::table($table)->where(function ($query) use ($columns, $userId): void {
            foreach ($columns as $index => $column) {
                if ($index === 0) {
                    $query->where($column, $userId);
                    continue;
                }

                $query->orWhere($column, $userId);
            }
        })->delete();
    }

    private function updateToNull(string $table, string $column, int $userId): void
    {
        if (! $this->tableExists($table) || ! Schema::hasColumn($table, $column)) {
            return;
        }

        DB::table($table)->where($column, $userId)->update([$column => null]);
    }

    private function tableExists(string $table): bool
    {
        return Schema::hasTable($table);
    }
}
