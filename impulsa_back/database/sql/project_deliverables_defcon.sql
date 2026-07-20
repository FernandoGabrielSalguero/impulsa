-- Ejecutar en phpMyAdmin (producción no usa migrate para schema de negocio).
-- Defcon 1 = máxima prioridad; Defcon 5 = puede esperar.

ALTER TABLE project_deliverables
  ADD COLUMN defcon TINYINT UNSIGNED NOT NULL DEFAULT 5 AFTER assigned_user_id;
