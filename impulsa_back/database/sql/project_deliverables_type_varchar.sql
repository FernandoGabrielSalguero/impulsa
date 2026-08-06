-- Ejecutar en phpMyAdmin (producción no usa migrate para schema de negocio).
-- Corrige 500 al guardar deliverable_type = 'corrections' cuando la columna es ENUM
-- sin ese valor. Alinea producción al esquema de la app (VARCHAR(30)).

-- Opcional: confirmar tipo actual
-- SHOW COLUMNS FROM project_deliverables LIKE 'deliverable_type';

ALTER TABLE project_deliverables
  MODIFY COLUMN deliverable_type VARCHAR(30) NOT NULL DEFAULT 'other';
