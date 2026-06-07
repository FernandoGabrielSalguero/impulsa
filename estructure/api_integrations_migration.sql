CREATE TABLE IF NOT EXISTS api_integrations (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    project_name VARCHAR(180) NOT NULL,
    allowed_domain VARCHAR(190) NOT NULL,
    public_key VARCHAR(80) NOT NULL,
    secret_key_hash VARCHAR(255) DEFAULT NULL,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_used_at DATETIME DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_api_integrations_public_key (public_key),
    KEY idx_api_integrations_status (status),
    KEY idx_api_integrations_domain (allowed_domain)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE visit_user_page
    ADD COLUMN api_integration_id BIGINT(20) UNSIGNED NULL AFTER page,
    ADD KEY idx_visit_user_page_api_integration_id (api_integration_id),
    ADD CONSTRAINT fk_visit_user_page_api_integration
        FOREIGN KEY (api_integration_id) REFERENCES api_integrations(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL;

ALTER TABLE forms_clients_contact
    ADD COLUMN api_integration_id BIGINT(20) UNSIGNED NULL AFTER page,
    ADD KEY idx_forms_clients_contact_api_integration_id (api_integration_id),
    ADD CONSTRAINT fk_forms_clients_contact_api_integration
        FOREIGN KEY (api_integration_id) REFERENCES api_integrations(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL;
