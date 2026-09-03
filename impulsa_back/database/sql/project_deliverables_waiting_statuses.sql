-- Ejecutar en phpMyAdmin (producción no usa migrate para schema de negocio).
-- Agrega estados de espera en objetivos: backend, frontend y confirmación del cliente.

ALTER TABLE project_deliverables
  MODIFY COLUMN status ENUM(
    'pending',
    'in_progress',
    'waiting_backend',
    'waiting_frontend',
    'ready_for_review',
    'waiting_client_confirmation',
    'delivered'
  ) NOT NULL DEFAULT 'pending';
