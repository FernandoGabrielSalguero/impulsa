<?php

require_once __DIR__ . '/auth_helpers.php';

$error = '';
$correoError = '';
$correoValor = '';
$nombreValor = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim((string) ($_POST['nombre'] ?? ''));
    $correo = authSanitizarCorreo($_POST['correo'] ?? '');
    $password = (string) ($_POST['password'] ?? '');
    $passwordConfirmacion = (string) ($_POST['password_confirmacion'] ?? '');
    $nombreValor = $nombre;
    $correoValor = $correo;

    if ($nombre !== '' && mb_strlen($nombre) < 2) {
        $error = 'Ingresá un nombre válido.';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $correoError = 'Ingresá un correo válido.';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif ($passwordConfirmacion !== '' && $password !== $passwordConfirmacion) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        try {
            $stmt = $pdo->prepare('SELECT id FROM user_auth WHERE correo = :correo LIMIT 1');
            $stmt->execute(['correo' => $correo]);

            if ($stmt->fetch()) {
                $correoError = 'Ya existe un usuario registrado con ese correo.';
            } else {
                $pdo->beginTransaction();

                $stmt = $pdo->prepare(
                    'INSERT INTO user_auth (correo, password, rol, created_at, updated_at)
                     VALUES (:correo, :password, :rol, NOW(), NOW())'
                );
                $stmt->execute([
                    'correo' => $correo,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'rol' => 'impulsa_usuario',
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

                if ($nombre !== '') {
                    $stmt = $pdo->prepare(
                        'INSERT INTO user_info (user_auth_id, nombre, created_at, updated_at)
                         VALUES (:user_auth_id, :nombre, NOW(), NOW())'
                    );
                    $stmt->execute([
                        'user_auth_id' => $userId,
                        'nombre' => $nombre,
                    ]);
                }

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
      <label class="im-campo im-campo-material im-campo--ancho">
        <span>Nombre</span>
        <input type="text" name="nombre" autocomplete="name" minlength="2" value="<?= htmlspecialchars($nombreValor, ENT_QUOTES, 'UTF-8') ?>" required>
        <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">person</i>
      </label>
      <label class="im-campo im-campo-material im-campo--ancho<?= $correoError !== '' ? ' im-campo--error' : '' ?>" data-im-campo="email" data-im-validacion-url="/auth/check_email.php"<?= $correoError !== '' ? ' data-im-valido="false"' : '' ?>>
        <span>Correo</span>
        <input type="email" name="correo" autocomplete="email" value="<?= htmlspecialchars($correoValor, ENT_QUOTES, 'UTF-8') ?>" required>
        <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">mail</i>
        <small data-im-error><?= htmlspecialchars($correoError !== '' ? $correoError : 'Correo requerido.', ENT_QUOTES, 'UTF-8') ?></small>
      </label>
      <label class="im-campo im-campo-material im-campo--ancho">
        <span>Contraseña</span>
        <input type="password" name="password" autocomplete="new-password" minlength="6" required>
        <button class="im-campo__boton-icono material-symbols-rounded" type="button" data-im-toggle-password aria-label="Mostrar contraseña">visibility</button>
      </label>
      <label class="im-campo im-campo-material im-campo--ancho">
        <span>Confirmar contraseña</span>
        <input type="password" name="password_confirmacion" autocomplete="new-password" minlength="6" required>
        <button class="im-campo__boton-icono material-symbols-rounded" type="button" data-im-toggle-password aria-label="Mostrar contraseña">visibility</button>
      </label>
      <button class="im-boton im-boton--principal im-campo--ancho" type="submit">Crear cuenta</button>
    </form>
  </main>
  <script src="../assets/impulsa_material/js/material-validaciones.js"></script>
</body>
</html>
