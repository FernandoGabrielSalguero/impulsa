CREATE TABLE chatbots (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    api_integration_id BIGINT(20) UNSIGNED NOT NULL,
    name VARCHAR(180) NOT NULL,
    avatar_url VARCHAR(255) NULL,
    whatsapp VARCHAR(80) NOT NULL,
    initial_message TEXT NOT NULL,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'inactive',
    disabled_by_admin TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uniq_chatbots_api_integration (api_integration_id),
    KEY idx_chatbots_status (status),
    KEY idx_chatbots_disabled_by_admin (disabled_by_admin),
    CONSTRAINT fk_chatbots_api_integration
        FOREIGN KEY (api_integration_id) REFERENCES api_integrations(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE chatbot_nodes (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    chatbot_id BIGINT(20) UNSIGNED NOT NULL,
    title VARCHAR(180) NOT NULL,
    body TEXT NOT NULL,
    sort_order INT(10) UNSIGNED NOT NULL DEFAULT 1,
    is_start TINYINT(1) NOT NULL DEFAULT 0,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_chatbot_nodes_chatbot (chatbot_id),
    KEY idx_chatbot_nodes_sort (chatbot_id, sort_order),
    KEY idx_chatbot_nodes_start (chatbot_id, is_start),
    CONSTRAINT fk_chatbot_nodes_chatbot
        FOREIGN KEY (chatbot_id) REFERENCES chatbots(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE chatbot_node_options (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    node_id BIGINT(20) UNSIGNED NOT NULL,
    label VARCHAR(180) NOT NULL,
    action_type ENUM('go_to_node', 'whatsapp', 'restart', 'close') NOT NULL,
    target_node_id BIGINT(20) UNSIGNED NULL,
    sort_order INT(10) UNSIGNED NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_chatbot_node_options_node (node_id),
    KEY idx_chatbot_node_options_target (target_node_id),
    KEY idx_chatbot_node_options_sort (node_id, sort_order),
    CONSTRAINT fk_chatbot_node_options_node
        FOREIGN KEY (node_id) REFERENCES chatbot_nodes(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_chatbot_node_options_target
        FOREIGN KEY (target_node_id) REFERENCES chatbot_nodes(id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE chatbot_events (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    chatbot_id BIGINT(20) UNSIGNED NOT NULL,
    api_integration_id BIGINT(20) UNSIGNED NOT NULL,
    event_type ENUM('widget_loaded', 'bubble_opened', 'question_viewed', 'option_clicked', 'whatsapp_clicked', 'chat_closed') NOT NULL,
    node_id BIGINT(20) UNSIGNED NULL,
    option_id BIGINT(20) UNSIGNED NULL,
    page_url VARCHAR(500) NULL,
    metadata_json TEXT NULL,
    ip_hash CHAR(64) NULL,
    user_agent VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_chatbot_events_chatbot (chatbot_id),
    KEY idx_chatbot_events_integration (api_integration_id),
    KEY idx_chatbot_events_type_created (event_type, created_at),
    KEY idx_chatbot_events_created (created_at),
    CONSTRAINT fk_chatbot_events_chatbot
        FOREIGN KEY (chatbot_id) REFERENCES chatbots(id),
    CONSTRAINT fk_chatbot_events_api_integration
        FOREIGN KEY (api_integration_id) REFERENCES api_integrations(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
