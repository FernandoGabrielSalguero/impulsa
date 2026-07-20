-- Ejecutar en phpMyAdmin (producción no usa migrate para schema de negocio).
CREATE TABLE IF NOT EXISTS project_collaborators (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id BIGINT UNSIGNED NOT NULL,
  user_auth_id INT(10) UNSIGNED NOT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  UNIQUE KEY project_collaborators_project_user_unique (project_id, user_auth_id),
  KEY project_collaborators_user_auth_id_index (user_auth_id),
  CONSTRAINT project_collaborators_project_id_foreign
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
  CONSTRAINT project_collaborators_user_auth_id_foreign
    FOREIGN KEY (user_auth_id) REFERENCES user_auth(id) ON DELETE CASCADE
);
