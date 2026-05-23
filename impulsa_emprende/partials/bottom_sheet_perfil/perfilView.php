<?php
$perfilDatos = $perfilDatos ?? [];
$perfilCompleto = $perfilCompleto ?? true;
$perfilSesionDebug = $perfilSesionDebug ?? [];
$perfilSnackbar = $perfilSnackbar ?? null;

if (!function_exists('perfilCampo')) {
    function perfilCampo(array $perfil, string $clave): string
    {
        return htmlspecialchars((string) ($perfil[$clave] ?? ''), ENT_QUOTES, 'UTF-8');
    }
}
?>
<div class="im-bottom-sheet-cortina" data-cerrar-perfil></div>
<section class="im-bottom-sheet im-bottom-sheet--perfil" id="bottom-sheet-perfil" role="dialog" aria-modal="true" aria-labelledby="perfil-titulo">
  <header class="im-bottom-sheet__cabecera">
    <div>
      <h3 id="perfil-titulo">Mi perfil</h3>
      <p>Completá tus datos para mantener tu cuenta actualizada.</p>
    </div>
    <button class="im-boton-icono" type="button" data-cerrar-perfil aria-label="Cerrar dialog"></button>
  </header>

  <form class="im-formulario" action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="perfil_accion" value="guardar_perfil">
    <input type="hidden" name="check_correo_actual" value="<?= (int) ($perfilDatos['check_correo'] ?? 0) ?>">
    <input type="hidden" name="check_whatsapp_actual" value="<?= (int) ($perfilDatos['check_whatsapp'] ?? 0) ?>">

    <div class="im-formulario__separador">Datos personales</div>
    <label class="im-campo im-campo-material">
      <span>Nombre</span>
      <input type="text" name="nombre" value="<?= perfilCampo($perfilDatos, 'nombre') ?>" data-im-placeholder>
    </label>
    <label class="im-campo im-campo-material">
      <span>Apellido</span>
      <input type="text" name="apellido" value="<?= perfilCampo($perfilDatos, 'apellido') ?>" data-im-placeholder>
    </label>
    <label class="im-campo im-campo-material">
      <span>Apodo</span>
      <input type="text" name="apodo" value="<?= perfilCampo($perfilDatos, 'apodo') ?>" data-im-placeholder>
    </label>
    <label class="im-campo im-campo-material">
      <span>Fecha de nacimiento</span>
      <input type="date" name="fecha_nacimiento" value="<?= perfilCampo($perfilDatos, 'fecha_nacimiento') ?>">
    </label>

    <div class="im-formulario__separador">Contacto</div>
    <label class="im-campo im-campo-material">
      <span>Correo electrónico</span>
      <input type="email" name="correo" value="<?= perfilCampo($perfilDatos, 'correo') ?>" data-im-placeholder>
      <?php if ((int) ($perfilDatos['check_correo'] ?? 0) === 1): ?>
        <i class="im-campo__icono material-symbols-rounded im-perfil-check" aria-hidden="true">check_circle</i>
      <?php endif; ?>
    </label>
    <label class="im-campo im-campo-material">
      <span>WhatsApp</span>
      <input type="tel" name="whatsapp" value="<?= perfilCampo($perfilDatos, 'whatsapp') ?>" data-im-placeholder>
      <?php if ((int) ($perfilDatos['check_whatsapp'] ?? 0) === 1): ?>
        <i class="im-campo__icono material-symbols-rounded im-perfil-check" aria-hidden="true">check_circle</i>
      <?php endif; ?>
    </label>

    <div class="im-formulario__separador">Notificaciones</div>
    <label class="im-slide-toggle im-campo--ancho">
      <input type="checkbox" name="permison_correo" value="1" <?= (int) ($perfilDatos['permison_correo'] ?? 1) === 1 ? 'checked' : '' ?>>
      <span></span>
      ¿Podemos enviarte correos?
    </label>
    <label class="im-slide-toggle im-campo--ancho">
      <input type="checkbox" name="permison_whatsapp" value="1" <?= (int) ($perfilDatos['permison_whatsapp'] ?? 1) === 1 ? 'checked' : '' ?>>
      <span></span>
      ¿Podemos enviarte WhatsApps?
    </label>

    <div class="im-formulario__separador">Avatar</div>
    <label class="im-campo im-campo-material im-campo--ancho">
      <span>Imagen de perfil</span>
      <input type="file" name="avatar" accept="image/png,image/jpeg,image/webp">
    </label>

    <div class="im-formulario__acciones">
      <button class="im-boton im-boton--tonal" type="button" data-cerrar-perfil>Cerrar</button>
      <button class="im-boton im-boton--principal" type="submit">Guardar perfil</button>
    </div>
  </form>
</section>

<script>
  window.__impulsaSesion = <?= json_encode($perfilSesionDebug, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
  window.__impulsaPerfilSnackbar = <?= json_encode($perfilSnackbar, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
  console.log('Sesion Impulsa', window.__impulsaSesion);

  (() => {
    const sheet = document.getElementById('bottom-sheet-perfil');
    const backdrop = document.querySelector('[data-cerrar-perfil].im-bottom-sheet-cortina');
    const abrir = document.querySelectorAll('[data-abrir-perfil]');
    const cerrar = document.querySelectorAll('[data-cerrar-perfil]');
    const perfilCompleto = <?= $perfilCompleto ? 'true' : 'false' ?>;

    const setOpen = (open) => {
      sheet?.classList.toggle('abierto', open);
      backdrop?.classList.toggle('abierto', open);
    };

    abrir.forEach((boton) => {
      boton.addEventListener('click', () => setOpen(true));
    });

    cerrar.forEach((boton) => {
      boton.addEventListener('click', () => setOpen(false));
    });

    if (!perfilCompleto && !window.__impulsaPerfilSnackbar) {
      window.addEventListener('load', () => setOpen(true));
    }

    if (window.__impulsaPerfilSnackbar?.mensaje) {
      window.addEventListener('load', () => {
        setTimeout(() => {
          const snackbar = document.querySelector('.im-snackbar');
          if (!snackbar) {
            return;
          }

          const texto = snackbar.querySelector('span');
          if (texto) {
            texto.textContent = window.__impulsaPerfilSnackbar.mensaje;
          }

          snackbar.classList.add('abierto');
          clearTimeout(window.__impulsaPerfilSnackbarTimer);
          window.__impulsaPerfilSnackbarTimer = setTimeout(() => snackbar.classList.remove('abierto'), 3600);
        }, 0);
      });
    }
  })();
</script>
