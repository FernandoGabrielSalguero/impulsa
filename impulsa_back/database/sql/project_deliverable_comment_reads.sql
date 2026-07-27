-- Ejecutar en phpMyAdmin (producción no usa migrate para schema de negocio).

CREATE TABLE IF NOT EXISTS project_deliverable_comment_reads (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_auth_id INT(10) UNSIGNED NOT NULL,
  deliverable_id BIGINT UNSIGNED NOT NULL,
  last_read_comment_id BIGINT UNSIGNED NULL,
  last_read_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  UNIQUE KEY project_deliverable_comment_reads_user_deliverable_unique (user_auth_id, deliverable_id),
  KEY project_deliverable_comment_reads_deliverable_index (deliverable_id),
  CONSTRAINT project_deliverable_comment_reads_user_auth_id_foreign
    FOREIGN KEY (user_auth_id) REFERENCES user_auth(id) ON DELETE CASCADE
);
