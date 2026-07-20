-- Ejecutar en phpMyAdmin (producción no usa migrate para schema de negocio).

ALTER TABLE project_phases
  ADD COLUMN assigned_user_id INT(10) UNSIGNED NULL AFTER completed_at,
  ADD KEY project_phases_assigned_user_id_index (assigned_user_id),
  ADD CONSTRAINT project_phases_assigned_user_id_foreign
    FOREIGN KEY (assigned_user_id) REFERENCES user_auth(id) ON DELETE SET NULL;

ALTER TABLE project_deliverables
  ADD COLUMN assigned_user_id INT(10) UNSIGNED NULL AFTER client_visible,
  ADD KEY project_deliverables_assigned_user_id_index (assigned_user_id),
  ADD CONSTRAINT project_deliverables_assigned_user_id_foreign
    FOREIGN KEY (assigned_user_id) REFERENCES user_auth(id) ON DELETE SET NULL;

CREATE TABLE IF NOT EXISTS project_deliverable_comments (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id BIGINT UNSIGNED NOT NULL,
  deliverable_id BIGINT UNSIGNED NOT NULL,
  user_auth_id INT(10) UNSIGNED NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  KEY project_deliverable_comments_deliverable_created_index (deliverable_id, created_at),
  KEY project_deliverable_comments_project_id_index (project_id),
  KEY project_deliverable_comments_user_auth_id_index (user_auth_id),
  CONSTRAINT project_deliverable_comments_project_id_foreign
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
  CONSTRAINT project_deliverable_comments_deliverable_id_foreign
    FOREIGN KEY (deliverable_id) REFERENCES project_deliverables(id) ON DELETE CASCADE,
  CONSTRAINT project_deliverable_comments_user_auth_id_foreign
    FOREIGN KEY (user_auth_id) REFERENCES user_auth(id) ON DELETE CASCADE
);
