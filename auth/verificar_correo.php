<?php

require_once __DIR__ . '/auth_helpers.php';

$token = trim((string) ($_GET['token'] ?? ''));
$estado = 'error';
$titulo = 'No pudimos verificar tu correo';
$mensaje = 'El enlace de verificacion no es valido o ya expiro. Podes solicitar un nuevo correo de verificacion desde la plataforma.';
$botonTexto = 'Ir al inicio de sesion';
$botonUrl = '/auth/login.php';
$icono = 'error';

if ($token === '' || preg_match('/\A[a-f0-9]{64}\z/i', $token) !== 1) {
    $estado = 'invalido';
} else {
    try {
        $stmt = $pdo->prepare(
            'SELECT id, email_verified_at
             FROM user_auth
             WHERE verification_token = :token
             LIMIT 1'
        );
        $stmt->execute(['token' => $token]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            $estado = 'invalido';
        } elseif (!empty($usuario['email_verified_at'])) {
            $estado = 'ya_verificado';
            $titulo = 'Tu correo ya estaba verificado';
            $mensaje = 'La direccion de email de esta cuenta ya fue confirmada anteriormente. Ya podes ingresar a la plataforma.';
            $botonTexto = 'Ingresar a la plataforma';
            $icono = 'mark_email_read';
        } else {
            $userId = (int) $usuario['id'];
            $pdo->beginTransaction();

            $stmt = $pdo->prepare(
                'UPDATE user_auth
                 SET email_verified_at = NOW(), updated_at = NOW()
                 WHERE id = :user_id
                   AND verification_token = :token
                   AND email_verified_at IS NULL'
            );
            $stmt->execute([
                'user_id' => $userId,
                'token' => $token,
            ]);

            if ($stmt->rowCount() < 1) {
                $pdo->rollBack();
                $estado = 'error';
            } else {
                $stmt = $pdo->prepare(
                    'UPDATE user_contacto
                     SET check_correo = 1, updated_at = NOW()
                     WHERE user_auth_id = :user_id'
                );
                $stmt->execute(['user_id' => $userId]);

                $pdo->commit();
                $estado = 'ok';
                $titulo = 'Que bueno! Verificamos tu correo electronico';
                $mensaje = 'Tu direccion de email fue confirmada correctamente. Usaremos este correo unicamente para enviarte informacion importante sobre tu cuenta, novedades de la plataforma y noticias relacionadas con la implementacion de nuevos servicios de Impulsa Group. Podes dejar de recibir estas comunicaciones cuando quieras, modificando tus preferencias desde el panel de perfil dentro de la plataforma.';
                $botonTexto = 'Ingresar a la plataforma';
                $icono = 'verified';
            }
        }
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        $estado = 'error';
        $titulo = 'No pudimos verificar tu correo';
        $mensaje = 'No pudimos completar la verificacion en este momento. Por favor, intenta nuevamente o ingresa a la plataforma para solicitar ayuda.';
    }
}

$esExito = in_array($estado, ['ok', 'ya_verificado'], true);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Verificacion de correo | Impulsa Emprende</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,400,0,0&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/impulsa_material/css/material.css">
  <style>
    body {
      min-height: 100vh;
      margin: 0;
      background: #f7f8fb;
    }

    .verificacion {
      width: min(100% - 32px, 720px);
      min-height: 100vh;
      margin: 0 auto;
      display: grid;
      place-items: center;
      padding: 48px 0;
    }

    .verificacion__panel {
      width: 100%;
      padding: 36px;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      background: #fff;
      box-shadow: 0 18px 44px rgba(15, 23, 42, .08);
    }

    .verificacion__icono {
      width: 56px;
      height: 56px;
      display: inline-grid;
      place-items: center;
      border-radius: 50%;
      margin-bottom: 20px;
      color: <?= $esExito ? '#047857' : '#b42318' ?>;
      background: <?= $esExito ? '#d1fae5' : '#fee4e2' ?>;
      font-size: 32px;
    }

    .verificacion__titulo {
      margin: 0 0 14px;
      color: #111827;
      font-size: clamp(28px, 4vw, 42px);
      line-height: 1.08;
      letter-spacing: 0;
    }

    .verificacion__texto {
      margin: 0 0 28px;
      color: #4b5563;
      font-size: 16px;
      line-height: 1.7;
    }

    .verificacion__acciones {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
    }
  </style>
</head>
<body>
  <main class="verificacion">
    <section class="verificacion__panel" aria-labelledby="verificacion-titulo">
      <span class="verificacion__icono material-symbols-rounded" aria-hidden="true"><?= htmlspecialchars($icono, ENT_QUOTES, 'UTF-8') ?></span>
      <h1 class="verificacion__titulo" id="verificacion-titulo"><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?></h1>
      <p class="verificacion__texto"><?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?></p>
      <div class="verificacion__acciones">
        <a class="im-boton im-boton--principal" href="<?= htmlspecialchars($botonUrl, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($botonTexto, ENT_QUOTES, 'UTF-8') ?></a>
      </div>
    </section>
  </main>
</body>
</html>
