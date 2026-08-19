-- Tablas del módulo Chatbot (emprendedor + widget público).
-- Ejecutar en producción si GET /api/v1/emprendedor/chatbot devuelve 500 por tablas faltantes.

CREATE TABLE IF NOT EXISTS `chatbots` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `api_integration_id` bigint(20) unsigned NOT NULL,
  `name` varchar(180) NOT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `icon_background_color` varchar(7) NOT NULL DEFAULT '#009EE3',
  `whatsapp` varchar(80) NOT NULL DEFAULT '',
  `initial_message` text NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  `disabled_by_admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `chatbots_api_integration_id_unique` (`api_integration_id`),
  KEY `chatbots_status_index` (`status`),
  KEY `chatbots_disabled_by_admin_index` (`disabled_by_admin`),
  CONSTRAINT `chatbots_api_integration_id_foreign` FOREIGN KEY (`api_integration_id`) REFERENCES `api_integrations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `chatbot_nodes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chatbot_id` bigint(20) unsigned NOT NULL,
  `title` varchar(180) NOT NULL,
  `body` text NOT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 1,
  `is_start` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `chatbot_nodes_chatbot_id_foreign` (`chatbot_id`),
  CONSTRAINT `chatbot_nodes_chatbot_id_foreign` FOREIGN KEY (`chatbot_id`) REFERENCES `chatbots` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `chatbot_node_options` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `node_id` bigint(20) unsigned NOT NULL,
  `label` varchar(180) NOT NULL,
  `action_type` enum('go_to_node','whatsapp','restart','close') NOT NULL,
  `target_node_id` bigint(20) unsigned DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `chatbot_node_options_node_id_foreign` (`node_id`),
  KEY `chatbot_node_options_target_node_id_foreign` (`target_node_id`),
  CONSTRAINT `chatbot_node_options_node_id_foreign` FOREIGN KEY (`node_id`) REFERENCES `chatbot_nodes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chatbot_node_options_target_node_id_foreign` FOREIGN KEY (`target_node_id`) REFERENCES `chatbot_nodes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `chatbot_events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chatbot_id` bigint(20) unsigned NOT NULL,
  `api_integration_id` bigint(20) unsigned NOT NULL,
  `event_type` enum('widget_loaded','bubble_opened','question_viewed','option_clicked','whatsapp_clicked','chat_closed') NOT NULL,
  `node_id` bigint(20) unsigned DEFAULT NULL,
  `option_id` bigint(20) unsigned DEFAULT NULL,
  `page_url` varchar(500) DEFAULT NULL,
  `metadata_json` text DEFAULT NULL,
  `ip_hash` char(64) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `chatbot_events_chatbot_id_foreign` (`chatbot_id`),
  KEY `chatbot_events_api_integration_id_foreign` (`api_integration_id`),
  KEY `chatbot_events_event_type_index` (`event_type`),
  KEY `chatbot_events_created_at_index` (`created_at`),
  CONSTRAINT `chatbot_events_chatbot_id_foreign` FOREIGN KEY (`chatbot_id`) REFERENCES `chatbots` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chatbot_events_api_integration_id_foreign` FOREIGN KEY (`api_integration_id`) REFERENCES `api_integrations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
