<?php

class AdminListUserModel
{
    public function __construct(private PDO $pdo)
    {
    }

    public function obtenerUsuarios(): array
    {
        $stmt = $this->pdo->prepare(
            $this->selectUsuariosSql() . '
             ORDER BY ua.created_at DESC, ua.id DESC'
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarUsuarios(string $busqueda): array
    {
        $busqueda = trim($busqueda);
        if (mb_strlen($busqueda) < 4) {
            return $this->obtenerUsuarios();
        }

        $stmt = $this->pdo->prepare(
            $this->selectUsuariosSql() . "
             WHERE ua.correo LIKE :busqueda
                OR uc.correo LIKE :busqueda
                OR ui.nombre LIKE :busqueda
                OR ui.apellido LIKE :busqueda
                OR ui.apodo LIKE :busqueda
                OR CONCAT_WS(' ', ui.nombre, ui.apellido) LIKE :busqueda
             ORDER BY ua.created_at DESC, ua.id DESC"
        );
        $stmt->execute([
            'busqueda' => '%' . $busqueda . '%',
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminarUsuarioCompleto(int $usuarioId): array
    {
        if ($usuarioId <= 0) {
            return [
                'ok' => false,
                'mensaje' => 'El ID de usuario no es valido.',
            ];
        }

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare('SELECT id FROM user_auth WHERE id = :id LIMIT 1 FOR UPDATE');
            $stmt->execute(['id' => $usuarioId]);
            if (!$stmt->fetchColumn()) {
                $this->pdo->rollBack();
                return [
                    'ok' => false,
                    'mensaje' => 'El usuario indicado no existe.',
                ];
            }

            $projectIds = $this->obtenerIds(
                'SELECT id FROM projects WHERE client_user_id = :id OR manager_user_id = :id',
                $usuarioId
            );
            $subscriptionIds = $this->obtenerIds(
                'SELECT id FROM marketing_plan_subscriptions WHERE client_user_id = :id OR entrepreneur_user_id = :id',
                $usuarioId
            );
            $campaignIds = $this->obtenerIds(
                'SELECT id
                 FROM marketing_campaigns
                 WHERE client_user_id = :id
                    OR entrepreneur_user_id = :id
                    OR (subscription_id IN (' . $this->placeholders($subscriptionIds) . '))',
                $usuarioId,
                $subscriptionIds
            );

            $this->eliminarProjects($projectIds);
            $this->eliminarMarketing($usuarioId, $subscriptionIds, $campaignIds);

            $this->ejecutar('DELETE FROM admin_tareas WHERE responsable_user_id = :id OR created_by_user_id = :id', $usuarioId);
            $this->ejecutar('DELETE FROM correos_log WHERE user_auth_id = :id', $usuarioId);
            $this->ejecutar('DELETE FROM emprendedor_buyer_persona WHERE user_auth_id = :id', $usuarioId);
            $this->ejecutar('DELETE FROM emprendedor_mision WHERE user_auth_id = :id', $usuarioId);
            $this->ejecutar('DELETE FROM emprendedor_vision WHERE user_auth_id = :id', $usuarioId);
            $this->ejecutar('DELETE FROM landing_page_request WHERE user_auth_id = :id', $usuarioId);
            $this->ejecutar('DELETE FROM marketing_client_codes WHERE user_auth_id = :id', $usuarioId);
            $this->ejecutar('UPDATE project_contracts SET signed_by_user_id = NULL WHERE signed_by_user_id = :id', $usuarioId);
            $this->ejecutar('UPDATE project_contracts SET created_by_user_id = NULL WHERE created_by_user_id = :id', $usuarioId);
            $this->ejecutar('UPDATE project_contracts SET updated_by_user_id = NULL WHERE updated_by_user_id = :id', $usuarioId);
            $this->ejecutar('DELETE FROM project_updates WHERE created_by = :id', $usuarioId);

            $this->ejecutar('DELETE FROM user_params WHERE user_auth_id = :id', $usuarioId);
            $this->ejecutar('DELETE FROM user_contacto WHERE user_auth_id = :id', $usuarioId);
            $this->ejecutar('DELETE FROM user_info WHERE user_auth_id = :id', $usuarioId);
            $this->ejecutar('DELETE FROM user_auth WHERE id = :id', $usuarioId);

            $this->pdo->commit();

            return [
                'ok' => true,
                'mensaje' => 'Usuario eliminado correctamente.',
            ];
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            return [
                'ok' => false,
                'mensaje' => 'No se pudo eliminar el usuario. ' . $e->getMessage(),
            ];
        }
    }

    private function selectUsuariosSql(): string
    {
        return 'SELECT ua.id,
                    ua.correo AS correo_login,
                    ua.rol,
                    ua.email_verified_at,
                    ua.created_at,
                    ua.updated_at,
                    ui.nombre,
                    ui.apellido,
                    ui.apodo,
                    ui.avatar_path,
                    uc.correo AS correo_contacto,
                    uc.whatsapp,
                    up.page AS pagina_inicio
             FROM user_auth ua
             LEFT JOIN user_info ui ON ui.user_auth_id = ua.id
             LEFT JOIN user_contacto uc ON uc.user_auth_id = ua.id
             LEFT JOIN user_params up ON up.user_auth_id = ua.id';
    }

    private function eliminarMarketing(int $usuarioId, array $subscriptionIds, array $campaignIds): void
    {
        $this->eliminarPorIds('marketing_campaign_metrics', 'campaign_id', $campaignIds);
        $this->eliminarPorIds('marketing_commercial_metrics', 'campaign_id', $campaignIds);
        $this->eliminarPorIds('marketing_commercial_metrics', 'subscription_id', $subscriptionIds);
        $this->eliminarPorIds('marketing_reports', 'subscription_id', $subscriptionIds);

        $this->eliminarPorIds('marketing_external_campaign_mappings', 'internal_campaign_id', $campaignIds);
        $this->eliminarPorIds('marketing_external_campaign_mappings', 'internal_subscription_id', $subscriptionIds);

        $this->ejecutar('UPDATE marketing_campaigns SET created_by_user_id = NULL WHERE created_by_user_id = :id', $usuarioId);
        $this->ejecutar('UPDATE marketing_commercial_metrics SET created_by_user_id = NULL WHERE created_by_user_id = :id', $usuarioId);
        $this->ejecutar('UPDATE marketing_external_campaign_mappings SET created_by_user_id = NULL WHERE created_by_user_id = :id', $usuarioId);
        $this->ejecutar('UPDATE marketing_external_campaign_mappings SET internal_client_user_id = NULL WHERE internal_client_user_id = :id', $usuarioId);
        $this->ejecutar('UPDATE marketing_external_campaign_mappings SET internal_entrepreneur_user_id = NULL WHERE internal_entrepreneur_user_id = :id', $usuarioId);
        $this->ejecutar('UPDATE marketing_import_batches SET uploaded_by_user_id = NULL WHERE uploaded_by_user_id = :id', $usuarioId);
        $this->ejecutar('UPDATE marketing_import_rows SET internal_client_user_id = NULL WHERE internal_client_user_id = :id', $usuarioId);
        $this->ejecutar('UPDATE marketing_import_rows SET internal_entrepreneur_user_id = NULL WHERE internal_entrepreneur_user_id = :id', $usuarioId);
        $this->ejecutar('UPDATE marketing_plan_subscriptions SET assigned_marketing_user_id = NULL WHERE assigned_marketing_user_id = :id', $usuarioId);
        $this->ejecutar('UPDATE marketing_plans SET created_by_user_id = NULL WHERE created_by_user_id = :id', $usuarioId);
        $this->ejecutar('UPDATE marketing_reports SET created_by_user_id = NULL WHERE created_by_user_id = :id', $usuarioId);

        $this->eliminarPorIds('marketing_campaigns', 'id', $campaignIds);
        $this->eliminarPorIds('marketing_plan_subscriptions', 'id', $subscriptionIds);
    }

    private function eliminarProjects(array $projectIds): void
    {
        if ($projectIds === []) {
            return;
        }

        $phaseIds = $this->obtenerIdsPorCampo('project_phases', 'project_id', $projectIds);
        $deliverableIds = $this->obtenerIdsPorCampo('project_deliverables', 'project_id', $projectIds);

        $this->eliminarPorIds('project_deliverable_tasks', 'deliverable_id', $deliverableIds);
        $this->eliminarPorIds('project_updates', 'phase_id', $phaseIds);
        $this->eliminarPorIds('project_updates', 'project_id', $projectIds);
        $this->eliminarPorIds('project_contracts', 'project_id', $projectIds);
        $this->eliminarPorIds('project_deliverables', 'id', $deliverableIds);
        $this->eliminarPorIds('project_phases', 'id', $phaseIds);
        $this->eliminarPorIds('projects', 'id', $projectIds);
    }

    private function obtenerIds(string $sql, int $usuarioId, array $ids = []): array
    {
        if ($ids === [] && str_contains($sql, 'IN ()')) {
            $sql = str_replace('IN ()', 'IN (NULL)', $sql);
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $usuarioId, PDO::PARAM_INT);
        foreach ($ids as $indice => $id) {
            $stmt->bindValue(':ids' . $indice, (int) $id, PDO::PARAM_INT);
        }
        $stmt->execute();

        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }

    private function obtenerIdsPorCampo(string $tabla, string $campo, array $ids): array
    {
        if ($ids === []) {
            return [];
        }

        $sql = sprintf(
            'SELECT id FROM %s WHERE %s IN (%s)',
            $tabla,
            $campo,
            $this->placeholders($ids)
        );
        $stmt = $this->pdo->prepare($sql);
        foreach ($ids as $indice => $id) {
            $stmt->bindValue(':ids' . $indice, (int) $id, PDO::PARAM_INT);
        }
        $stmt->execute();

        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }

    private function eliminarPorIds(string $tabla, string $campo, array $ids): void
    {
        if ($ids === []) {
            return;
        }

        $sql = sprintf(
            'DELETE FROM %s WHERE %s IN (%s)',
            $tabla,
            $campo,
            $this->placeholders($ids)
        );
        $stmt = $this->pdo->prepare($sql);
        foreach ($ids as $indice => $id) {
            $stmt->bindValue(':ids' . $indice, (int) $id, PDO::PARAM_INT);
        }
        $stmt->execute();
    }

    private function ejecutar(string $sql, int $usuarioId): void
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $usuarioId]);
    }

    private function placeholders(array $ids): string
    {
        if ($ids === []) {
            return 'NULL';
        }

        return implode(', ', array_map(static fn (int $indice): string => ':ids' . $indice, array_keys($ids)));
    }
}
