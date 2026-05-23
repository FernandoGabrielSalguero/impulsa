<?php

require_once __DIR__ . '/auth_helpers.php';

header('Content-Type: application/json; charset=UTF-8');

$correo = authSanitizarCorreo($_POST['valor'] ?? $_GET['valor'] ?? '');

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'valido' => false,
        'mensaje' => 'Ingresá un correo válido.',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT id FROM user_auth WHERE correo = :correo LIMIT 1');
    $stmt->execute(['correo' => $correo]);

    if ($stmt->fetch()) {
        echo json_encode([
            'valido' => false,
            'mensaje' => 'Ya existe un usuario registrado con ese correo.',
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    echo json_encode([
        'valido' => true,
        'mensaje' => 'Correo disponible.',
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'valido' => false,
        'mensaje' => 'No pudimos validar el correo en este momento.',
    ], JSON_UNESCAPED_UNICODE);
}

