<?php

require_once __DIR__ . '/auth_helpers.php';

$token = trim((string) ($_GET['token'] ?? ''));

if ($token === '') {
    authRedirect('/auth/login.php?estado=verificacion_invalida');
}

try {
    $stmt = $pdo->prepare(
        'SELECT id
         FROM user_auth
         WHERE verification_token = :token
         LIMIT 1'
    );
    $stmt->execute(['token' => $token]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        authRedirect('/auth/login.php?estado=verificacion_invalida');
    }

    $userId = (int) $usuario['id'];
    $pdo->beginTransaction();

    $stmt = $pdo->prepare(
        'UPDATE user_auth
         SET email_verified_at = NOW(), verification_token = NULL, updated_at = NOW()
         WHERE id = :user_id'
    );
    $stmt->execute(['user_id' => $userId]);

    $stmt = $pdo->prepare(
        'UPDATE user_contacto
         SET check_correo = 1, updated_at = NOW()
         WHERE user_auth_id = :user_id'
    );
    $stmt->execute(['user_id' => $userId]);

    $pdo->commit();
    authRedirect('/auth/login.php?estado=correo_verificado');
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    authRedirect('/auth/login.php?estado=verificacion_error');
}
