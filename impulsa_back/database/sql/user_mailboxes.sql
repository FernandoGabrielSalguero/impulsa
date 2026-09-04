-- Ejecutar en phpMyAdmin (producción no usa migrate para schema de negocio).

CREATE TABLE IF NOT EXISTS user_mailboxes (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_auth_id INT(10) UNSIGNED NOT NULL,
  email VARCHAR(255) NOT NULL,
  password_encrypted TEXT NOT NULL,
  enabled TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  UNIQUE KEY user_mailboxes_user_auth_id_unique (user_auth_id),
  KEY user_mailboxes_enabled_index (enabled),
  CONSTRAINT user_mailboxes_user_auth_id_foreign
    FOREIGN KEY (user_auth_id) REFERENCES user_auth(id) ON DELETE CASCADE
);
