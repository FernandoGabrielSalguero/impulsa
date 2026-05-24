<?php
$usuarioCorreo = $usuarioCorreo ?? '';
$usuarioInicial = $usuarioInicial ?? '?';
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Cliente';
$definicionSnackbar = $definicionSnackbar ?? null;

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
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,400,0,0&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../../assets/impulsa_material/css/material.css">
</head>
<body>
  <div class="im-aplicacion" data-menu-colapsado="false">
    <aside class="im-menu-lateral" id="menu-lateral" aria-label="Navegacion principal">
      <div class="im-marca">
        <span class="im-marca__isotipo" aria-hidden="true"><?= definicionH($usuarioInicial) ?></span>
        <div class="im-marca__texto">
          <strong><?= definicionH($usuarioMarcaNombre) ?></strong>
          <span>Cliente</span>
        </div>
      </div>
      <nav class="im-navegacion">
        <a class="im-nav-item" href="/impulsa_emprende/controller/user/UserDashboardController.php">
          <span class="material-symbols-rounded" aria-hidden="true">dashboard</span>
          <span class="im-nav-item__texto">Dashboard</span>
        </a>
        <a class="im-nav-item activo" href="#definicion" data-seccion="definicion">
          <span class="material-symbols-rounded" aria-hidden="true">psychology</span>
          <span class="im-nav-item__texto">Definicion</span>
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
        <section class="im-seccion-documento activa" id="definicion" data-panel="definicion">
          <div class="im-encabezado-seccion">
            <div>
              <p class="im-sobrelinea">Estrategia</p>
              <h2>Definicion del emprendimiento</h2>
              <p>Guarda avances parciales de mision, vision y buyer persona. Cada modulo queda completado cuando todos sus campos tienen informacion.</p>
            </div>
          </div>

          <div class="im-acordeon">
            <details class="im-expansion" id="mision" open>
              <summary>Mision</summary>
              <?php require __DIR__ . '/../../partials/mision/misionView.php'; ?>
            </details>
            <details class="im-expansion" id="vision">
              <summary>Vision</summary>
              <?php require __DIR__ . '/../../partials/vision/visionView.php'; ?>
            </details>
            <details class="im-expansion" id="buyer-persona">
              <summary>Buyer Persona</summary>
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
      if (!window.__impulsaDefinicionSnackbar?.mensaje) {
        return;
      }

      window.addEventListener('load', () => {
        setTimeout(() => {
          const snackbar = document.querySelector('.im-snackbar');
          const texto = snackbar?.querySelector('span');
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
  <script src="../../../assets/impulsa_material/js/material.js"></script>
</body>
</html>
