-- Lecturas de adjuntos por usuario (punto rojo / no vistos).
CREATE TABLE IF NOT EXISTS project_attachment_reads (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_auth_id INT UNSIGNED NOT NULL,
    project_id BIGINT UNSIGNED NOT NULL,
    phase_id BIGINT UNSIGNED NULL,
    deliverable_id BIGINT UNSIGNED NULL,
    last_read_attachment_id BIGINT UNSIGNED NULL,
    last_read_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    INDEX project_attachment_reads_user_auth_id_index (user_auth_id),
    INDEX project_attachment_reads_project_id_index (project_id),
    INDEX project_attachment_reads_phase_id_index (phase_id),
    INDEX project_attachment_reads_deliverable_id_index (deliverable_id)
);
