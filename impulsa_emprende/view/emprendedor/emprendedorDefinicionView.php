<?php
$usuarioCorreo = $usuarioCorreo ?? '';
$usuarioInicial = $usuarioInicial ?? '?';
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Cliente';
$definicionSnackbar = $definicionSnackbar ?? null;
$misionCompleta = $misionCompleta ?? false;
$visionCompleta = $visionCompleta ?? false;
$buyerCompleto = $buyerCompleto ?? false;

if (!function_exists('definicionH')) {
    function definicionH(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Definicion | Impulsa Emprende</title>
  <link rel="icon" href="<?= htmlspecialchars(obtenerFaviconHref(), ENT_QUOTES, 'UTF-8'); ?>" type="image/x-icon">
  <link rel="stylesheet" href="/assets/impulsa_material/css/material.css?v=icons-local-1">
</head>
<body>
  <div class="im-aplicacion" data-menu-colapsado="false">
    <aside class="im-menu-lateral" id="menu-lateral" aria-label="Navegacion principal">
      <div class="im-marca">
        <span class="im-marca__isotipo" aria-hidden="true"><?= definicionH($usuarioInicial) ?></span>
        <div class="im-marca__texto">
          <strong><?= definicionH($usuarioMarcaNombre) ?></strong>
          <span>Emprendedor</span>
        </div>
      </div>
      <nav class="im-navegacion">
        <a class="im-nav-item" href="/impulsa_emprende/controller/emprendedor/EmprendedorDashboardController.php">
          <span class="material-symbols-rounded" aria-hidden="true">dashboard</span>
          <span class="im-nav-item__texto">Dashboard</span>
        </a>
        <a class="im-nav-item activo" href="/impulsa_emprende/controller/emprendedor/emprendedorDefinicionController.php">
          <span class="material-symbols-rounded" aria-hidden="true">psychology</span>
          <span class="im-nav-item__texto">Definicion</span>
        </a>
        <a class="im-nav-item" href="/impulsa_emprende/controller/emprendedor/EmprendedorPaginaWebController.php">
          <span class="material-symbols-rounded" aria-hidden="true">web</span>
          <span class="im-nav-item__texto">Pagina web</span>
        </a>
        <a class="im-nav-item" href="/impulsa_emprende/controller/emprendedor/EmprendedorMarketingController.php">
          <span class="material-symbols-rounded" aria-hidden="true">campaign</span>
          <span class="im-nav-item__texto">Marketing</span>
        </a>
        <a class="im-nav-item" href="/impulsa_emprende/controller/emprendedor/EmprendedorChatbotController.php">
          <span class="material-symbols-rounded" aria-hidden="true">forum</span>
          <span class="im-nav-item__texto">Chatbot</span>
        </a>
        <a class="im-nav-item" href="/impulsa_emprende/controller/emprendedor/EmprendedorBlogController.php">
          <span class="material-symbols-rounded" aria-hidden="true">article</span>
          <span class="im-nav-item__texto">Blog</span>
        </a>
        <a class="im-nav-item" href="/impulsa_emprende/controller/emprendedor/EmprendedorProductController.php">
          <span class="material-symbols-rounded" aria-hidden="true">inventory_2</span>
          <span class="im-nav-item__texto">Productos</span>
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
            <h1>Definicion</h1>
          </div>
        </div>
        <div class="im-barra-superior__acciones">
          <button class="im-boton-icono im-boton-icono--principal im-tooltip" type="button" data-abrir-config-tema aria-label="Configurar temas" data-tooltip="Configurar estilos"></button>
          <button class="im-boton-icono material-symbols-rounded im-tooltip" type="button" data-abrir-perfil aria-label="Mi perfil" data-tooltip="Mi perfil">account_circle</button>
          <a class="im-boton-icono material-symbols-rounded im-tooltip im-accion--eliminar" href="/auth/logout.php" aria-label="Salir" data-tooltip="Salir">logout</a>
        </div>
      </header>

      <main class="im-contenido">
        <section class="im-seccion-documento activa" id="dashboard" data-panel="dashboard">
          <div class="im-encabezado-seccion">
            <div>
              <p class="im-sobrelinea">Estrategia</p>
              <h2>Definicion del emprendimiento</h2>
              <p>Guarda avances parciales de mision, vision y buyer persona. Cada modulo queda completado cuando todos sus campos tienen informacion.</p>
            </div>
          </div>

          <div class="im-acordeon">
            <details class="im-expansion" id="mision">
              <summary>Mision <span class="im-chip <?= $misionCompleta ? 'im-chip--completado' : 'im-chip--pendiente' ?>"><?= $misionCompleta ? 'Completado' : 'Incompleto' ?></span></summary>
              <?php require __DIR__ . '/../../partials/mision/misionView.php'; ?>
            </details>
            <details class="im-expansion" id="vision">
              <summary>Vision <span class="im-chip <?= $visionCompleta ? 'im-chip--completado' : 'im-chip--pendiente' ?>"><?= $visionCompleta ? 'Completado' : 'Incompleto' ?></span></summary>
              <?php require __DIR__ . '/../../partials/vision/visionView.php'; ?>
            </details>
            <details class="im-expansion" id="buyer-persona">
              <summary>Buyer Persona <span class="im-chip <?= $buyerCompleto ? 'im-chip--completado' : 'im-chip--pendiente' ?>"><?= $buyerCompleto ? 'Completado' : 'Incompleto' ?></span></summary>
              <?php require __DIR__ . '/../../partials/buyerPerson/buyerView.php'; ?>
            </details>
          </div>
        </section>
      </main>
    </div>
  </div>

  <?php require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilView.php'; ?>
  <script>
    window.__impulsaDefinicionSnackbar = <?= json_encode($definicionSnackbar, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    (() => {
      if (!window.__impulsaDefinicionSnackbar || !window.__impulsaDefinicionSnackbar.mensaje) {
        return;
      }

      window.addEventListener('load', () => {
        setTimeout(() => {
          const snackbar = document.querySelector('.im-snackbar');
          const texto = snackbar ? snackbar.querySelector('span') : null;
          if (!snackbar || !texto) {
            return;
          }

          texto.textContent = window.__impulsaDefinicionSnackbar.mensaje;
          snackbar.classList.add('abierto');
          clearTimeout(window.__impulsaDefinicionSnackbarTimer);
          window.__impulsaDefinicionSnackbarTimer = setTimeout(() => snackbar.classList.remove('abierto'), 3600);
        }, 0);
      });
    })();
  </script>
  <script src="/assets/impulsa_material/js/material.js?v=panel-default-1"></script>
</body>
</html>
