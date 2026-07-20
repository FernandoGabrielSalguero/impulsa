-- Ejecutar en phpMyAdmin (producción no usa migrate para schema de negocio).

CREATE TABLE IF NOT EXISTS user_notifications (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_auth_id INT(10) UNSIGNED NOT NULL,
  type VARCHAR(80) NOT NULL,
  title VARCHAR(255) NOT NULL,
  body TEXT NULL,
  payload JSON NULL,
  read_at TIMESTAMP NULL,
  dismissed_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  KEY user_notifications_user_unread_created_index (user_auth_id, read_at, created_at),
  KEY user_notifications_user_dismissed_index (user_auth_id, dismissed_at),
  KEY user_notifications_type_index (type),
  CONSTRAINT user_notifications_user_auth_id_foreign
    FOREIGN KEY (user_auth_id) REFERENCES user_auth(id) ON DELETE CASCADE
);
