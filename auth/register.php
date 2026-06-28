<?php

require_once __DIR__ . '/auth_helpers.php';
require_once __DIR__ . '/../impulsa_emprende/mail/Mail.php';

$error = '';
$correoError = '';
$correoValor = '';
$nombreValor = '';
$esEmbed = ($_GET['embed'] ?? '') === '1';

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
                $verificationToken = bin2hex(random_bytes(32));
                $pdo->beginTransaction();

                $stmt = $pdo->prepare(
                    'INSERT INTO user_auth (correo, password, rol, verification_token, created_at, updated_at)
                     VALUES (:correo, :password, :rol, :verification_token, NOW(), NOW())'
                );
                $stmt->execute([
                    'correo' => $correo,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'rol' => 'impulsa_emprendedor',
                    'verification_token' => $verificationToken,
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
                $appUrl = rtrim((string) (getenv('APP_URL') ?: ''), '/');
                if ($appUrl === '') {
                    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                    $appUrl = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
                }

                \SVE\Mail\Mailer::enviarVerificacionCorreo([
                    'correo' => $correo,
                    'link' => $appUrl . '/auth/verificar_correo.php?token=' . urlencode($verificationToken),
                    'user_auth_id' => $userId,
                ]);

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
  <link rel="icon" href="<?= htmlspecialchars(obtenerFaviconHref(), ENT_QUOTES, 'UTF-8'); ?>" type="image/x-icon">
  <?= renderImpulsaMaterialFonts() ?>
  <link rel="stylesheet" href="<?= htmlspecialchars(obtenerImpulsaMaterialCssHref(), ENT_QUOTES, 'UTF-8'); ?>">
  <style>
    body.auth-embed {
      min-height: auto;
      overflow: hidden;
      background: transparent;
    }

    body.auth-embed .im-contenido {
      padding: .65rem;
    }

    body.auth-embed .im-formulario {
      gap: .7rem;
    }

    body.auth-embed .im-campo-material input {
      min-height: 50px;
      padding-top: .9rem;
      padding-bottom: .6rem;
    }

    body.auth-embed .im-campo-material .im-campo__boton-icono {
      top: .4rem;
    }

    body.auth-embed .im-campo-material .im-campo__icono {
      top: .85rem;
    }

    body.auth-embed .im-boton {
      min-height: 44px;
    }
  </style>
</head>
<body<?= $esEmbed ? ' class="auth-embed"' : '' ?>>
  <main class="im-contenido">
    <?php if ($error !== ''): ?>
      <div class="im-alerta im-alerta--info"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <form class="im-formulario" action="/auth/register.php" method="post" target="_top" data-register-form>
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
      <label class="im-campo im-campo-material im-campo--ancho" data-register-password-field>
        <span>Contraseña</span>
        <input type="password" name="password" autocomplete="new-password" minlength="6" required>
        <button class="im-campo__boton-icono material-symbols-rounded" type="button" data-im-toggle-password aria-label="Mostrar contraseña">visibility</button>
      </label>
      <label class="im-campo im-campo-material im-campo--ancho" data-register-confirm-field>
        <span>Confirmar contraseña</span>
        <input type="password" name="password_confirmacion" autocomplete="new-password" minlength="6" required>
        <button class="im-campo__boton-icono material-symbols-rounded" type="button" data-im-toggle-password aria-label="Mostrar contraseña">visibility</button>
        <small data-register-password-error>Confirmá la contraseña.</small>
      </label>
      <button class="im-boton im-boton--principal im-campo--ancho" type="submit">Crear cuenta</button>
    </form>
  </main>
  <script src="<?= htmlspecialchars(obtenerImpulsaMaterialValidacionesJsSrc(), ENT_QUOTES, 'UTF-8'); ?>" defer></script>
  <script>
    (() => {
      const form = document.querySelector('[data-register-form]');
      if (!form) {
        return;
      }

      const password = form.querySelector('[name="password"]');
      const confirmacion = form.querySelector('[name="password_confirmacion"]');
      const correo = form.querySelector('[name="correo"]');
      const campoCorreo = correo?.closest('[data-im-campo]');
      const avisoCorreo = campoCorreo?.querySelector('[data-im-error], small');
      const campoConfirmacion = form.querySelector('[data-register-confirm-field]');
      const aviso = form.querySelector('[data-register-password-error]');
      const botonSubmit = form.querySelector('[type="submit"]');

      const limpiarAvisoPassword = () => {
        campoConfirmacion?.classList.remove('im-campo--error');
        if (aviso) {
          aviso.textContent = 'Confirmá la contraseña.';
        }
      };

      const limpiarAvisoCorreo = () => {
        campoCorreo?.classList.remove('im-campo--error');
        if (avisoCorreo) {
          avisoCorreo.textContent = 'Correo requerido.';
        }
      };

      const mostrarErrorCorreo = (mensaje) => {
        campoCorreo?.classList.add('im-campo--error');
        if (avisoCorreo) {
          avisoCorreo.textContent = mensaje;
        }
        correo?.focus();
      };

      const mostrarErrorPassword = () => {
        campoConfirmacion?.classList.add('im-campo--error');
        if (aviso) {
          aviso.textContent = 'Las contraseñas no coinciden.';
        }
        confirmacion.focus();
      };

      const validarCorreoDisponible = async () => {
        if (!correo || !correo.value.trim()) {
          return true;
        }

        try {
          const respuesta = await fetch('/auth/check_email.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
              'X-Requested-With': 'fetch',
            },
            body: new URLSearchParams({ valor: correo.value.trim(), campo: 'email' }),
          });
          const payload = await respuesta.json();

          if (!payload.valido) {
            mostrarErrorCorreo(payload.mensaje || 'Ya existe un usuario registrado con ese correo.');
            return false;
          }

          limpiarAvisoCorreo();
          return true;
        } catch (error) {
          mostrarErrorCorreo('No pudimos validar el correo en este momento.');
          return false;
        }
      };

      form.addEventListener('submit', async (evento) => {
        evento.preventDefault();

        if (password && confirmacion && password.value !== confirmacion.value) {
          mostrarErrorPassword();
          return;
        }

        if (botonSubmit) {
          botonSubmit.disabled = true;
        }

        const correoDisponible = await validarCorreoDisponible();
        if (!correoDisponible) {
          if (botonSubmit) {
            botonSubmit.disabled = false;
          }
          return;
        }

        form.submit();
      });

      correo?.addEventListener('input', limpiarAvisoCorreo);
      password?.addEventListener('input', limpiarAvisoPassword);
      confirmacion?.addEventListener('input', limpiarAvisoPassword);
    })();
  </script>
</body>
</html>
