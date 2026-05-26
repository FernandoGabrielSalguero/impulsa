<?php
$usuarioCorreo = $usuarioCorreo ?? '';
$usuarioInicial = $usuarioInicial ?? '?';
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Cliente';
$paginaWebEstadoDefinicion = $paginaWebEstadoDefinicion ?? ['mision' => false, 'vision' => false, 'buyer' => false];
$paginaWebDefinicionCompleta = $paginaWebDefinicionCompleta ?? false;
$paginaWebSolicitud = $paginaWebSolicitud ?? [];
$paginaWebSnackbar = $paginaWebSnackbar ?? null;

if (!function_exists('paginaWebH')) {
    function paginaWebH(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('paginaWebFecha')) {
    function paginaWebFecha(?string $fecha): string
    {
        $timestamp = strtotime((string) $fecha);

        return $timestamp ? date('d/m/Y', $timestamp) : 'Sin fecha';
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pagina web | Impulsa Emprende</title>
  <link rel="stylesheet" href="../../../assets/impulsa_material/css/material.css">
</head>
<body>
  <div class="im-aplicacion" data-menu-colapsado="false">
    <aside class="im-menu-lateral" id="menu-lateral" aria-label="Navegacion principal">
      <div class="im-marca">
        <span class="im-marca__isotipo" aria-hidden="true"><?= paginaWebH($usuarioInicial) ?></span>
        <div class="im-marca__texto">
          <strong><?= paginaWebH($usuarioMarcaNombre) ?></strong>
          <span>Cliente</span>
        </div>
      </div>
      <nav class="im-navegacion">
        <a class="im-nav-item" href="/impulsa_emprende/controller/user/UserDashboardController.php">
          <span class="material-symbols-rounded" aria-hidden="true">dashboard</span>
          <span class="im-nav-item__texto">Dashboard</span>
        </a>
        <a class="im-nav-item" href="/impulsa_emprende/controller/user/userDefinicionController.php">
          <span class="material-symbols-rounded" aria-hidden="true">psychology</span>
          <span class="im-nav-item__texto">Definicion</span>
        </a>
        <a class="im-nav-item activo" href="#pagina-web" data-seccion="pagina-web">
          <span class="material-symbols-rounded" aria-hidden="true">web</span>
          <span class="im-nav-item__texto">Pagina web</span>
        </a>
      </nav>
    </aside>

    <div class="im-cortina" data-cerrar-menu></div>

    <div class="im-contenedor">
      <header class="im-barra-superior">
        <div class="im-barra-superior__grupo">
          <button class="im-boton-icono" type="button" data-alternar-menu aria-label="Menu"></button>
          <div>
            <p class="im-sobrelinea">Impulsa Emprende</p>
            <h1>Pagina web</h1>
          </div>
        </div>
        <div class="im-barra-superior__acciones">
          <button class="im-boton-icono im-boton-icono--principal im-tooltip" type="button" data-abrir-config-tema aria-label="Configurar temas" data-tooltip="Configurar estilos"></button>
          <button class="im-boton-icono material-symbols-rounded im-tooltip" type="button" data-abrir-perfil aria-label="Mi perfil" data-tooltip="Mi perfil">account_circle</button>
          <a class="im-boton-icono material-symbols-rounded im-tooltip im-accion--eliminar" href="/auth/logout.php" aria-label="Salir" data-tooltip="Salir">logout</a>
        </div>
      </header>

      <main class="im-contenido">
        <section class="im-seccion-documento activa" id="pagina-web" data-panel="pagina-web">
          <div class="im-encabezado-seccion">
            <div>
              <p class="im-sobrelinea">Solicitud</p>
              <h2>Solicitud de pagina web</h2>
              <p>Este formulario queda disponible cuando tu mision, vision y buyer persona estan completos.</p>
            </div>
          </div>

          <?php if (!$paginaWebDefinicionCompleta): ?>
            <article class="im-tarjeta">
              <div class="im-tarjeta__cabecera">
                <div>
                  <h3>Completa primero tu definicion</h3>
                  <p>Para solicitar la pagina web necesitamos que termines mision, vision y buyer persona.</p>
                </div>
                <span class="im-chip im-chip--pendiente">Incompleto</span>
              </div>
              <div class="im-chip-lista">
                <span class="im-chip <?= $paginaWebEstadoDefinicion['mision'] ? 'im-chip--completado' : 'im-chip--pendiente' ?>">Mision</span>
                <span class="im-chip <?= $paginaWebEstadoDefinicion['vision'] ? 'im-chip--completado' : 'im-chip--pendiente' ?>">Vision</span>
                <span class="im-chip <?= $paginaWebEstadoDefinicion['buyer'] ? 'im-chip--completado' : 'im-chip--pendiente' ?>">Buyer persona</span>
              </div>
              <div class="im-formulario__acciones">
                <a class="im-boton im-boton--principal" href="/impulsa_emprende/controller/user/userDefinicionController.php">Completar definicion</a>
              </div>
            </article>
          <?php elseif ($paginaWebSolicitud): ?>
            <article class="im-tarjeta">
              <div class="im-tarjeta__cabecera">
                <div>
                  <h3>Solicitud enviada</h3>
                  <p>Ya registramos una solicitud de pagina web para tu usuario.</p>
                </div>
                <span class="im-chip <?= (int) ($paginaWebSolicitud['completado'] ?? 0) === 1 ? 'im-chip--completado' : 'im-chip--pendiente' ?>"><?= (int) ($paginaWebSolicitud['completado'] ?? 0) === 1 ? 'Completada' : 'Pendiente' ?></span>
              </div>
              <div class="im-tabla-contenedor">
                <table class="im-tabla">
                  <tbody>
                    <tr><th>Emprendimiento</th><td><?= paginaWebH($paginaWebSolicitud['nombre_emprendimiento'] ?? '') ?></td></tr>
                    <tr><th>Fundador</th><td><?= paginaWebH($paginaWebSolicitud['nombre_fundador'] ?? '') ?></td></tr>
                    <tr><th>Telefono</th><td><?= paginaWebH($paginaWebSolicitud['telefono_contacto'] ?? '') ?></td></tr>
                    <tr><th>Fecha de solicitud</th><td><?= paginaWebFecha($paginaWebSolicitud['created_at'] ?? '') ?></td></tr>
                  </tbody>
                </table>
              </div>
            </article>
          <?php else: ?>
            <article class="im-tarjeta">
              <?php require __DIR__ . '/../../partials/pagina_web/pagina_web_view.php'; ?>
            </article>
          <?php endif; ?>
        </section>
      </main>
    </div>
  </div>

  <?php require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilView.php'; ?>
  <script>
    window.__impulsaPaginaWebSnackbar = <?= json_encode($paginaWebSnackbar, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    (() => {
      if (!window.__impulsaPaginaWebSnackbar || !window.__impulsaPaginaWebSnackbar.mensaje) {
        return;
      }

      window.addEventListener('load', () => {
        setTimeout(() => {
          const snackbar = document.querySelector('.im-snackbar');
          const texto = snackbar ? snackbar.querySelector('span') : null;
          if (!snackbar || !texto) {
            return;
          }

          texto.textContent = window.__impulsaPaginaWebSnackbar.mensaje;
          snackbar.classList.add('abierto');
          clearTimeout(window.__impulsaPaginaWebSnackbarTimer);
          window.__impulsaPaginaWebSnackbarTimer = setTimeout(() => snackbar.classList.remove('abierto'), 3600);
        }, 0);
      });
    })();
  </script>
  <script src="../../../assets/impulsa_material/js/material.js"></script>
</body>
</html>
