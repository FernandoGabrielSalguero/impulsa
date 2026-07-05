-- =============================================================================
-- Impulsa — Suscripciones web + catálogo Mercado Pago
-- Base de datos: impulsa
-- Requisito: la tabla `api_integrations` debe existir previamente.
--
-- CÓMO USAR
-- ---------
-- A) Instalación nueva (no tenés ninguna de estas tablas):
--    Ejecutá todo el bloque "A) CREAR TABLAS".
--
-- B) Ya tenías website_subscriptions SIN la columna del plan MP:
--    Ejecutá solo el bloque "B) ALTER (actualización)".
-- =============================================================================

USE `impulsa`;

-- =============================================================================
-- A) CREAR TABLAS (instalación nueva)
-- =============================================================================

CREATE TABLE IF NOT EXISTS `mercadopago_subscription_plans` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL COMMENT 'Nombre visible en admin, ej. Web básica',
  `amount` DECIMAL(12, 2) NOT NULL DEFAULT 0.00 COMMENT 'Monto informativo (debe coincidir con MP)',
  `payment_url` VARCHAR(500) NOT NULL COMMENT 'Link de suscripción copiado de Mercado Pago',
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mercadopago_subscription_plans_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Catálogo de planes/links de cobro Mercado Pago';

CREATE TABLE IF NOT EXISTS `website_subscriptions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `api_integration_id` BIGINT UNSIGNED NOT NULL COMMENT 'FK lógica → api_integrations.id',
  `mercadopago_subscription_plan_id` BIGINT UNSIGNED NULL COMMENT 'Plan MP asignado al sitio',
  `status` ENUM('active', 'paused', 'cancelled') NOT NULL DEFAULT 'active',
  `mercadopago_preapproval_id` VARCHAR(120) NULL COMMENT 'ID suscripción MP (webhook)',
  `grace_months_count` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `default_amount` DECIMAL(12, 2) NOT NULL DEFAULT 0.00 COMMENT 'Monto mensual informado',
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `website_subscriptions_api_integration_id_unique` (`api_integration_id`),
  KEY `website_subscriptions_status_index` (`status`),
  KEY `website_subscriptions_mercadopago_subscription_plan_id_index` (`mercadopago_subscription_plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Suscripción mensual por sitio web (1 por integración API)';

CREATE TABLE IF NOT EXISTS `website_subscription_periods` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `website_subscription_id` BIGINT UNSIGNED NOT NULL,
  `year` SMALLINT UNSIGNED NOT NULL,
  `month` TINYINT UNSIGNED NOT NULL COMMENT '1-12',
  `amount` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
  `status` ENUM('pending', 'paid', 'grace', 'waived', 'overdue') NOT NULL DEFAULT 'pending',
  `mercadopago_payment_id` VARCHAR(120) NULL,
  `paid_at` TIMESTAMP NULL DEFAULT NULL,
  `first_notice_sent_at` TIMESTAMP NULL DEFAULT NULL,
  `last_reminder_sent_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ws_period_unique` (`website_subscription_id`, `year`, `month`),
  KEY `website_subscription_periods_year_month_index` (`year`, `month`),
  KEY `website_subscription_periods_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Calendario mensual de cobro por suscripción';

-- =============================================================================
-- B) ALTER (actualización) — solo si faltan objetos en una base ya existente
--    Ejecutá línea por línea; si MySQL dice "Duplicate column" o "already exists",
--    saltá esa sentencia (ya está aplicada).
-- =============================================================================

-- B.1) Tabla de planes MP (si no existía)
-- CREATE TABLE IF NOT EXISTS ... (mismo bloque de arriba, o ejecutá solo A completo)

-- B.2) Columna plan en website_subscriptions (si la tabla ya existía sin ella)
ALTER TABLE `website_subscriptions`
  ADD COLUMN `mercadopago_subscription_plan_id` BIGINT UNSIGNED NULL
    COMMENT 'Plan MP asignado al sitio'
    AFTER `api_integration_id`;

ALTER TABLE `website_subscriptions`
  ADD KEY `website_subscriptions_mercadopago_subscription_plan_id_index` (`mercadopago_subscription_plan_id`);

-- =============================================================================
-- C) FKs opcionales (descomentar solo si querés integridad referencial estricta)
-- =============================================================================
/*
ALTER TABLE `website_subscriptions`
  ADD CONSTRAINT `ws_api_integration_fk`
    FOREIGN KEY (`api_integration_id`) REFERENCES `api_integrations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ws_mp_plan_fk`
    FOREIGN KEY (`mercadopago_subscription_plan_id`) REFERENCES `mercadopago_subscription_plans` (`id`) ON DELETE SET NULL;

ALTER TABLE `website_subscription_periods`
  ADD CONSTRAINT `wsp_subscription_fk`
    FOREIGN KEY (`website_subscription_id`) REFERENCES `website_subscriptions` (`id`) ON DELETE CASCADE;
*/

-- =============================================================================
-- D) Verificación rápida
-- =============================================================================
-- SHOW TABLES LIKE '%subscription%';
-- SHOW COLUMNS FROM `website_subscriptions`;
