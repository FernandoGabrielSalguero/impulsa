-- Vincula opciones de precio de planes marketing con planes Mercado Pago
-- Ejecutar si la columna aún no existe en marketing_plan_pricing_options

ALTER TABLE marketing_plan_pricing_options
    ADD COLUMN IF NOT EXISTS mercadopago_subscription_plan_id BIGINT UNSIGNED NULL AFTER currency,
    ADD CONSTRAINT mpo_mp_plan_fk
        FOREIGN KEY (mercadopago_subscription_plan_id)
        REFERENCES mercadopago_subscription_plans(id)
        ON DELETE SET NULL;
