ALTER TABLE api_integrations
  ADD COLUMN user_auth_id INT(10) UNSIGNED NULL AFTER status,
  ADD KEY idx_api_integrations_user_auth_id (user_auth_id),
  ADD CONSTRAINT fk_api_integrations_user_auth
    FOREIGN KEY (user_auth_id) REFERENCES user_auth(id)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

UPDATE api_integrations ai
LEFT JOIN (
  SELECT project_name, MIN(client_user_id) AS client_user_id
  FROM projects
  WHERE client_user_id IS NOT NULL
    AND TRIM(COALESCE(project_name, '')) <> ''
  GROUP BY project_name
) p ON p.project_name = ai.project_name
LEFT JOIN (
  SELECT nombre_emprendimiento, MIN(user_auth_id) AS user_auth_id
  FROM landing_page_request
  WHERE user_auth_id IS NOT NULL
    AND TRIM(COALESCE(nombre_emprendimiento, '')) <> ''
  GROUP BY nombre_emprendimiento
) lpr ON lpr.nombre_emprendimiento = ai.project_name
SET ai.user_auth_id = COALESCE(p.client_user_id, lpr.user_auth_id)
WHERE ai.user_auth_id IS NULL
  AND COALESCE(p.client_user_id, lpr.user_auth_id) IS NOT NULL;
