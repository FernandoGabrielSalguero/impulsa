<?php

class AdminContratoModel
{
    public function __construct(private PDO $pdo)
    {
    }

    public function obtenerContratosPorProyecto(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, project_id, contract_name, contract_html, contract_text, version_number,
                    is_signed, signed_at, signer_full_name, created_at, updated_at
             FROM project_contracts
             ORDER BY updated_at DESC'
        );
        $stmt->execute();
        $contratos = [];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $contrato) {
            $contratos[(int) $contrato['project_id']] = $contrato;
        }

        return $contratos;
    }

    public function existeProyecto(int $projectId): bool
    {
        $stmt = $this->pdo->prepare('SELECT id FROM projects WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $projectId]);

        return (bool) $stmt->fetchColumn();
    }

    public function guardarContrato(array $datos): void
    {
        $stmt = $this->pdo->prepare('SELECT id FROM project_contracts WHERE project_id = :project_id LIMIT 1');
        $stmt->execute(['project_id' => (int) $datos['project_id']]);
        $contratoId = (int) $stmt->fetchColumn();

        if ($contratoId > 0) {
            $stmt = $this->pdo->prepare(
                'UPDATE project_contracts
                 SET contract_name = :contract_name,
                     contract_html = :contract_html,
                     contract_text = :contract_text,
                     version_number = version_number + 1,
                     updated_by_user_id = :updated_by_user_id,
                     updated_at = NOW()
                 WHERE id = :id'
            );
            $stmt->execute([
                'contract_name' => $datos['contract_name'],
                'contract_html' => $datos['contract_html'],
                'contract_text' => $datos['contract_text'],
                'updated_by_user_id' => (int) $datos['admin_user_id'],
                'id' => $contratoId,
            ]);
            return;
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO project_contracts
                (project_id, contract_name, contract_html, contract_text, version_number, is_signed,
                 created_by_user_id, updated_by_user_id, created_at, updated_at)
             VALUES
                (:project_id, :contract_name, :contract_html, :contract_text, 1, 0,
                 :created_by_user_id, :updated_by_user_id, NOW(), NOW())'
        );
        $stmt->execute([
            'project_id' => (int) $datos['project_id'],
            'contract_name' => $datos['contract_name'],
            'contract_html' => $datos['contract_html'],
            'contract_text' => $datos['contract_text'],
            'created_by_user_id' => (int) $datos['admin_user_id'],
            'updated_by_user_id' => (int) $datos['admin_user_id'],
        ]);
    }
}
