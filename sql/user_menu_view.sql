CREATE TABLE user_menu_view (
    id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    user_auth_id INT(10) UNSIGNED NOT NULL,
    menu_key VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uk_user_menu_view_user_menu (user_auth_id, menu_key),
    KEY idx_user_menu_view_user_auth_id (user_auth_id),
    CONSTRAINT fk_user_menu_view_user_auth
        FOREIGN KEY (user_auth_id) REFERENCES user_auth(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
