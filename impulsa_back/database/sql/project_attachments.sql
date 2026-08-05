-- Adjuntos de fases y objetivos (máx. 3 por entidad).
CREATE TABLE IF NOT EXISTS project_attachments (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    project_id BIGINT UNSIGNED NOT NULL,
    phase_id BIGINT UNSIGNED NULL,
    deliverable_id BIGINT UNSIGNED NULL,
    file_path VARCHAR(500) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    mime_type VARCHAR(120) NOT NULL,
    size_bytes INT UNSIGNED NOT NULL DEFAULT 0,
    uploaded_by INT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    INDEX project_attachments_project_id_index (project_id),
    INDEX project_attachments_phase_id_index (phase_id),
    INDEX project_attachments_deliverable_id_index (deliverable_id),
    INDEX project_attachments_uploaded_by_index (uploaded_by)
);
