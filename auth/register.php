<?php

require_once __DIR__ . '/auth_helpers.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = authSanitizarCorreo($_POST['correo'] ?? '');
    $password = (string) ($_POST['password'] ?? '');
    $passwordConfirmacion = (string) ($_POST['password_confirmacion'] ?? '');

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = 'Ingresá un correo válido.';
    } elseif (strlen($password) < 8) {
        $error = 'La contraseña debe tener al menos 8 caracteres.';
    } elseif ($password !== $passwordConfirmacion) {
        $error = 'La verificación de contraseña no coincide.';
    } else {
        try {
            $stmt = $pdo->prepare('SELECT id FROM user_auth WHERE correo = :correo LIMIT 1');
            $stmt->execute(['correo' => $correo]);

            if ($stmt->fetch()) {
                $error = 'Ya existe un usuario registrado con ese correo.';
            } else {
                $pdo->beginTransaction();

                $stmt = $pdo->prepare(
                    'INSERT INTO user_auth (correo, password, rol, created_at, updated_at)
                     VALUES (:correo, :password, :rol, NOW(), NOW())'
                );
                $stmt->execute([
                    'correo' => $correo,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'rol' => 'impulsa_emprendedor',
                ]);

                $userId = (int) $pdo->lastInsertId();
                $stmt = $pdo->prepare(
                    'INSERT INTO user_contacto (user_auth_id, correo, check_correo, permison_correo, created_at, updated_at)
                     VALUES (:user_auth_id, :correo, 0, 1, NOW(), NOW())'
                );
                $stmt->execute([
                    'user_auth_id' => $userId,
                    'correo' => $correo,
                ]);

                $pdo->commit();
                authRedirect('/auth/login.php?estado=registrado');
            }
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $error = 'No pudimos crear el registro en este momento.';
        }
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registro | Impulsa Emprende</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,400,0,0&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/impulsa_material/css/material.css">
</head>
<body>
  <main class="im-contenido">
    <?php if ($error !== ''): ?>
      <div class="im-alerta im-alerta--info"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <form class="im-formulario" action="/auth/register.php" method="post" target="_top">
      <label class="im-campo im-campo-material" data-im-campo="email">
        <span>Correo</span>
        <input type="email" name="correo" autocomplete="email" required>
        <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">mail</i>
        <small data-im-error>Correo requerido.</small>
      </label>
      <label class="im-campo im-campo-material" data-im-campo="password">
        <span>Contraseña</span>
        <input type="password" name="password" autocomplete="new-password" required>
        <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">lock</i>
        <small data-im-error>Contraseña requerida.</small>
      </label>
      <label class="im-campo im-campo-material" data-im-campo="confirmarPassword" data-im-confirmar="password">
        <span>Verificar contraseña</span>
        <input type="password" name="password_confirmacion" autocomplete="new-password" required>
        <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">lock_reset</i>
        <small data-im-error>La verificación es requerida.</small>
      </label>
      <button class="im-boton im-boton--principal" type="submit">Crear cuenta</button>
    </form>
  </main>
  <script src="../assets/impulsa_material/js/material-validaciones.js"></script>
</body>
</html>

