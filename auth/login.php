<?php

require_once __DIR__ . '/auth_helpers.php';

$error = '';
$estado = authMensajeEstado($_GET['estado'] ?? null);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = authSanitizarCorreo($_POST['correo'] ?? '');
    $password = (string) ($_POST['password'] ?? '');

    if ($correo === '' || $password === '') {
        $error = 'Credenciales incorrectas.';
    } else {
        $stmt = $pdo->prepare('SELECT id, correo, password, rol FROM user_auth WHERE correo = :correo LIMIT 1');
        $stmt->execute(['correo' => $correo]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario || !password_verify($password, $usuario['password'])) {
            $error = 'Credenciales incorrectas.';
        } else {
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = (int) $usuario['id'];
            $_SESSION['usuario'] = $usuario['correo'];
            $_SESSION['rol'] = $usuario['rol'];

            authRedirigirPorRol($usuario['rol']);
        }
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ingresar | Impulsa Emprende</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,400,0,0&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/impulsa_material/css/material.css">
</head>
<body>
  <main class="im-contenido">
    <?php if ($estado !== ''): ?>
      <div class="im-alerta im-alerta--info"><?= htmlspecialchars($estado, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <?php if ($error !== ''): ?>
      <div class="im-alerta im-alerta--info"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <form class="im-formulario" action="/auth/login.php" method="post" target="_top">
      <label class="im-campo im-campo-material im-campo--ancho">
        <span>Correo</span>
        <input type="text" name="correo" autocomplete="email">
        <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">mail</i>
      </label>
      <label class="im-campo im-campo-material im-campo--ancho">
        <span>Contraseña</span>
        <input type="password" name="password" autocomplete="current-password">
        <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">lock</i>
      </label>
      <button class="im-boton im-boton--principal im-campo--ancho" type="submit">Ingresar</button>
    </form>
  </main>
</body>
</html>
