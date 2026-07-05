CREATE TABLE IF NOT EXISTS `api_content_views` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `api_integration_id` bigint(20) unsigned NOT NULL,
  `content_type` enum('blog_post','product') NOT NULL,
  `content_id` bigint(20) unsigned NOT NULL,
  `page_url` varchar(500) DEFAULT NULL,
  `ip_hash` char(64) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `api_content_views_api_integration_id_foreign` (`api_integration_id`),
  KEY `api_content_views_lookup` (`api_integration_id`,`content_type`,`content_id`),
  KEY `api_content_views_created_at_index` (`created_at`),
  CONSTRAINT `api_content_views_api_integration_id_foreign` FOREIGN KEY (`api_integration_id`) REFERENCES `api_integrations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
