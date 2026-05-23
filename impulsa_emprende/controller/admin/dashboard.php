<?php

require_once __DIR__ . '/../../../auth/auth_helpers.php';

$usuario = authRequiereRol('impulsa_administrador');

require __DIR__ . '/../../view/admin/dashboard.php';

