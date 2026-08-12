-- Módulo Finanzas (Fase 1 + Fase 2)
-- Ejecutar en la base `impulsa` (MySQL/MariaDB).
-- Idempotente con IF NOT EXISTS donde aplica.

CREATE TABLE IF NOT EXISTS `finance_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_auth_id` int(10) unsigned NOT NULL,
  `currency` varchar(8) NOT NULL DEFAULT 'ARS',
  `opening_balance` decimal(14,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `finance_settings_user_auth_id_unique` (`user_auth_id`),
  CONSTRAINT `finance_settings_user_auth_id_foreign`
    FOREIGN KEY (`user_auth_id`) REFERENCES `user_auth` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `finance_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_auth_id` int(10) unsigned DEFAULT NULL,
  `type` enum('ingreso','egreso','inversion') NOT NULL,
  `name` varchar(120) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `finance_categories_user_auth_id_type_is_active_index` (`user_auth_id`,`type`,`is_active`),
  CONSTRAINT `finance_categories_user_auth_id_foreign`
    FOREIGN KEY (`user_auth_id`) REFERENCES `user_auth` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `finance_movements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_auth_id` int(10) unsigned NOT NULL,
  `type` enum('ingreso','egreso','inversion') NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `occurred_on` date NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `finance_movements_user_auth_id_occurred_on_index` (`user_auth_id`,`occurred_on`),
  KEY `finance_movements_user_auth_id_type_index` (`user_auth_id`,`type`),
  KEY `finance_movements_category_id_foreign` (`category_id`),
  KEY `finance_movements_product_id_foreign` (`product_id`),
  CONSTRAINT `finance_movements_user_auth_id_foreign`
    FOREIGN KEY (`user_auth_id`) REFERENCES `user_auth` (`id`) ON DELETE CASCADE,
  CONSTRAINT `finance_movements_category_id_foreign`
    FOREIGN KEY (`category_id`) REFERENCES `finance_categories` (`id`),
  CONSTRAINT `finance_movements_product_id_foreign`
    FOREIGN KEY (`product_id`) REFERENCES `api_products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `finance_fixed_costs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_auth_id` int(10) unsigned NOT NULL,
  `name` varchar(160) NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `frequency` enum('mensual','anual') NOT NULL DEFAULT 'mensual',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `finance_fixed_costs_user_auth_id_is_active_index` (`user_auth_id`,`is_active`),
  CONSTRAINT `finance_fixed_costs_user_auth_id_foreign`
    FOREIGN KEY (`user_auth_id`) REFERENCES `user_auth` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `finance_pricing_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_auth_id` int(10) unsigned NOT NULL,
  `name` varchar(160) NOT NULL,
  `variable_cost` decimal(14,2) NOT NULL DEFAULT 0.00,
  `extra_costs` decimal(14,2) NOT NULL DEFAULT 0.00,
  `mode` enum('markup','margen') NOT NULL DEFAULT 'margen',
  `target_percent` decimal(8,2) NOT NULL DEFAULT 30.00,
  `suggested_price` decimal(14,2) NOT NULL DEFAULT 0.00,
  `notes` varchar(500) DEFAULT NULL,
  `product_id` bigint(20) unsigned DEFAULT NULL,
  `competitors_json` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `finance_pricing_items_user_auth_id_index` (`user_auth_id`),
  KEY `finance_pricing_items_product_id_foreign` (`product_id`),
  CONSTRAINT `finance_pricing_items_user_auth_id_foreign`
    FOREIGN KEY (`user_auth_id`) REFERENCES `user_auth` (`id`) ON DELETE CASCADE,
  CONSTRAINT `finance_pricing_items_product_id_foreign`
    FOREIGN KEY (`product_id`) REFERENCES `api_products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `finance_projections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_auth_id` int(10) unsigned NOT NULL,
  `name` varchar(160) NOT NULL,
  `months` tinyint(3) unsigned NOT NULL DEFAULT 6,
  `assumptions_json` json NOT NULL,
  `series_json` json NOT NULL,
  `notes` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `finance_projections_user_auth_id_index` (`user_auth_id`),
  CONSTRAINT `finance_projections_user_auth_id_foreign`
    FOREIGN KEY (`user_auth_id`) REFERENCES `user_auth` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `finance_scenarios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_auth_id` int(10) unsigned NOT NULL,
  `name` varchar(160) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `is_baseline` tinyint(1) NOT NULL DEFAULT 0,
  `months` tinyint(3) unsigned NOT NULL DEFAULT 6,
  `assumptions_json` json NOT NULL,
  `result_json` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `finance_scenarios_user_auth_id_is_baseline_index` (`user_auth_id`,`is_baseline`),
  CONSTRAINT `finance_scenarios_user_auth_id_foreign`
    FOREIGN KEY (`user_auth_id`) REFERENCES `user_auth` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Categorías de sistema (solo si todavía no hay ninguna de sistema)
INSERT INTO `finance_categories` (`user_auth_id`, `type`, `name`, `is_active`, `created_at`, `updated_at`)
SELECT NULL, 'ingreso', 'Ventas', 1, NOW(), NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM `finance_categories` WHERE `user_auth_id` IS NULL AND `type` = 'ingreso' AND `name` = 'Ventas'
);

INSERT INTO `finance_categories` (`user_auth_id`, `type`, `name`, `is_active`, `created_at`, `updated_at`)
SELECT NULL, 'ingreso', 'Servicios', 1, NOW(), NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM `finance_categories` WHERE `user_auth_id` IS NULL AND `type` = 'ingreso' AND `name` = 'Servicios'
);

INSERT INTO `finance_categories` (`user_auth_id`, `type`, `name`, `is_active`, `created_at`, `updated_at`)
SELECT NULL, 'ingreso', 'Otros ingresos', 1, NOW(), NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM `finance_categories` WHERE `user_auth_id` IS NULL AND `type` = 'ingreso' AND `name` = 'Otros ingresos'
);

INSERT INTO `finance_categories` (`user_auth_id`, `type`, `name`, `is_active`, `created_at`, `updated_at`)
SELECT NULL, 'egreso', 'Alquiler', 1, NOW(), NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM `finance_categories` WHERE `user_auth_id` IS NULL AND `type` = 'egreso' AND `name` = 'Alquiler'
);

INSERT INTO `finance_categories` (`user_auth_id`, `type`, `name`, `is_active`, `created_at`, `updated_at`)
SELECT NULL, 'egreso', 'Insumos', 1, NOW(), NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM `finance_categories` WHERE `user_auth_id` IS NULL AND `type` = 'egreso' AND `name` = 'Insumos'
);

INSERT INTO `finance_categories` (`user_auth_id`, `type`, `name`, `is_active`, `created_at`, `updated_at`)
SELECT NULL, 'egreso', 'Marketing', 1, NOW(), NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM `finance_categories` WHERE `user_auth_id` IS NULL AND `type` = 'egreso' AND `name` = 'Marketing'
);

INSERT INTO `finance_categories` (`user_auth_id`, `type`, `name`, `is_active`, `created_at`, `updated_at`)
SELECT NULL, 'egreso', 'Sueldos', 1, NOW(), NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM `finance_categories` WHERE `user_auth_id` IS NULL AND `type` = 'egreso' AND `name` = 'Sueldos'
);

INSERT INTO `finance_categories` (`user_auth_id`, `type`, `name`, `is_active`, `created_at`, `updated_at`)
SELECT NULL, 'egreso', 'Impuestos', 1, NOW(), NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM `finance_categories` WHERE `user_auth_id` IS NULL AND `type` = 'egreso' AND `name` = 'Impuestos'
);

INSERT INTO `finance_categories` (`user_auth_id`, `type`, `name`, `is_active`, `created_at`, `updated_at`)
SELECT NULL, 'egreso', 'Servicios / suscripciones', 1, NOW(), NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM `finance_categories` WHERE `user_auth_id` IS NULL AND `type` = 'egreso' AND `name` = 'Servicios / suscripciones'
);

INSERT INTO `finance_categories` (`user_auth_id`, `type`, `name`, `is_active`, `created_at`, `updated_at`)
SELECT NULL, 'egreso', 'Otros egresos', 1, NOW(), NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM `finance_categories` WHERE `user_auth_id` IS NULL AND `type` = 'egreso' AND `name` = 'Otros egresos'
);

INSERT INTO `finance_categories` (`user_auth_id`, `type`, `name`, `is_active`, `created_at`, `updated_at`)
SELECT NULL, 'inversion', 'Equipo', 1, NOW(), NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM `finance_categories` WHERE `user_auth_id` IS NULL AND `type` = 'inversion' AND `name` = 'Equipo'
);

INSERT INTO `finance_categories` (`user_auth_id`, `type`, `name`, `is_active`, `created_at`, `updated_at`)
SELECT NULL, 'inversion', 'Desarrollo', 1, NOW(), NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM `finance_categories` WHERE `user_auth_id` IS NULL AND `type` = 'inversion' AND `name` = 'Desarrollo'
);

INSERT INTO `finance_categories` (`user_auth_id`, `type`, `name`, `is_active`, `created_at`, `updated_at`)
SELECT NULL, 'inversion', 'Otras inversiones', 1, NOW(), NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM `finance_categories` WHERE `user_auth_id` IS NULL AND `type` = 'inversion' AND `name` = 'Otras inversiones'
);
