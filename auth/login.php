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
  <style>
    body {
      min-height: 100vh;
      margin: 0;
      background:
        radial-gradient(circle at 12% 10%, rgba(0, 169, 157, .14), transparent 28rem),
        radial-gradient(circle at 88% 16%, rgba(18, 50, 91, .12), transparent 24rem),
        #f6f8fb;
      color: #111827;
      font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .auth-page {
      width: min(100% - 32px, 520px);
      min-height: 100vh;
      margin: 0 auto;
      display: grid;
      align-items: center;
      padding: 40px 0;
    }

    .auth-card {
      width: 100%;
      border: 1px solid rgba(17, 44, 78, .1);
      border-radius: 8px;
      background: rgba(255, 255, 255, .94);
      box-shadow: 0 22px 56px rgba(17, 24, 39, .1);
      overflow: hidden;
    }

    .auth-card__header {
      padding: 28px 28px 20px;
      border-bottom: 1px solid #e5e7eb;
      background: linear-gradient(180deg, #ffffff 0%, #f8fbfc 100%);
    }

    .auth-card__brand {
      width: min(220px, 72%);
      height: auto;
      display: block;
      margin-bottom: 22px;
    }

    .auth-card__header h1 {
      margin: 0 0 8px;
      font-size: clamp(1.8rem, 4vw, 2.35rem);
      line-height: 1.08;
      letter-spacing: 0;
    }

    .auth-card__header p {
      margin: 0;
      color: #5f6b7a;
      font-size: .98rem;
      line-height: 1.55;
    }

    .auth-card__body {
      padding: 24px 28px 28px;
    }

    .auth-card .im-alerta {
      margin-bottom: 16px;
      border-radius: 8px;
    }

    .auth-card .im-formulario {
      width: 100%;
    }

    .auth-card__footer {
      margin-top: 18px;
      display: flex;
      justify-content: center;
    }

    .auth-card__footer a {
      color: #12325b;
      font-weight: 700;
      text-decoration: none;
    }

    .auth-card__footer a:hover {
      text-decoration: underline;
    }

    @media (max-width: 640px) {
      .auth-page {
        width: min(100% - 24px, 520px);
        padding: 20px 0;
        align-items: start;
      }

      .auth-card__header,
      .auth-card__body {
        padding-left: 20px;
        padding-right: 20px;
      }
    }
  </style>
</head>
<body>
  <main class="auth-page">
    <section class="auth-card" aria-labelledby="auth-title">
      <header class="auth-card__header">
        <img class="auth-card__brand" src="../assets/brands/impulsa-emprende/Impulsa Emprende.png" alt="Impulsa Emprende">
        <h1 id="auth-title">Ingresar a la plataforma</h1>
        <p>Accede con tu correo y contrasena para continuar con tu panel de Impulsa Emprende.</p>
      </header>

      <div class="auth-card__body">
        <?php if ($estado !== ''): ?>
          <div class="im-alerta im-alerta--info"><?= htmlspecialchars($estado, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <?php if ($error !== ''): ?>
          <div class="im-alerta im-alerta--info"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <form class="im-formulario" action="/auth/login.php" method="post" target="_top">
          <label class="im-campo im-campo-material im-campo--ancho">
            <span>Correo</span>
            <input type="email" name="correo" autocomplete="email">
            <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">mail</i>
          </label>
          <label class="im-campo im-campo-material im-campo--ancho">
            <span>Contraseña</span>
            <input type="password" name="password" autocomplete="current-password">
            <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">lock</i>
          </label>
          <button class="im-boton im-boton--principal im-campo--ancho" type="submit">Ingresar</button>
        </form>

        <div class="auth-card__footer">
          <a href="/" target="_top">Volver a impulsagroup.com</a>
        </div>
      </div>
    </section>
  </main>
</body>
</html>
