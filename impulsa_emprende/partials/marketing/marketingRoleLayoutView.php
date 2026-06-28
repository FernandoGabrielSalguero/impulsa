<?php
$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Marketing';
$marketingPageTitle = $marketingPageTitle ?? 'Marketing';
$marketingContentView = $marketingContentView ?? null;
$marketingRolLabel = $marketingRolLabel ?? 'Marketing';
$marketingMensaje = $_SESSION['marketing_estado'] ?? null;
unset($_SESSION['marketing_estado']);
$h = static fn (mixed $valor): string => htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $h($marketingPageTitle) ?> | Marketing Impulsa</title>
  <link rel="icon" href="<?= $h(obtenerFaviconHref()) ?>" type="image/x-icon">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,400,0,0&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../../assets/impulsa_material/css/material.css">
  <link rel="stylesheet" href="../../../assets/css/marketing/marketingPlanes.css">
</head>
<body>
  <div class="im-aplicacion" data-menu-colapsado="false">
    <?php require __DIR__ . '/../../view/marketing/marketingMenu.php'; ?>
    <div class="im-cortina" data-cerrar-menu></div>
    <div class="im-contenedor">
      <header class="im-barra-superior">
        <div class="im-barra-superior__grupo">
          <button class="im-boton-icono" type="button" data-alternar-menu aria-label="Menu"></button>
          <div>
            <p class="im-sobrelinea">Impulsa</p>
            <h1><?= $h($marketingPageTitle) ?></h1>
          </div>
        </div>
        <div class="im-barra-superior__acciones">
          <button class="im-boton-icono im-boton-icono--principal im-tooltip" type="button" data-abrir-config-tema aria-label="Configurar temas" data-tooltip="Configurar estilos"></button>
          <button class="im-boton-icono material-symbols-rounded im-tooltip" type="button" data-abrir-perfil aria-label="Mi perfil" data-tooltip="Mi perfil">account_circle</button>
          <a class="im-boton-icono material-symbols-rounded im-tooltip im-accion-salir" href="/auth/logout.php" aria-label="Salir" data-tooltip="Salir">logout</a>
        </div>
      </header>

      <main class="im-contenido">
        <section class="im-seccion-documento activa">
          <?php if ($marketingContentView): ?>
            <?php require $marketingContentView; ?>
          <?php endif; ?>
        </section>
      </main>
    </div>
  </div>

  <?php require __DIR__ . '/../bottom_sheet_perfil/perfilView.php'; ?>
  <div class="im-modal-cortina im-drawer-cortina" data-marketing-dialog-backdrop></div>
  <aside class="im-drawer marketing-plan-detail-drawer" role="dialog" aria-modal="true" aria-labelledby="marketing-plan-detail-title" aria-hidden="true" data-marketing-plan-detail-modal>
    <header class="im-drawer__cabecera">
      <h3 id="marketing-plan-detail-title">Detalle del plan</h3>
      <button class="im-boton-icono material-symbols-rounded" type="button" data-marketing-close-plan-detail aria-label="Cerrar dialog">close</button>
    </header>
    <div class="im-drawer__contenido" data-marketing-plan-detail-content></div>
    <footer class="im-drawer__acciones">
      <button class="im-boton im-boton--tonal" type="button" data-marketing-close-plan-detail>Cerrar</button>
    </footer>
  </aside>
  <div
    class="im-snackbar"
    role="status"
    data-marketing-snackbar="<?= is_array($marketingMensaje) ? $h($marketingMensaje['mensaje'] ?? '') : '' ?>"
    data-estado="<?= is_array($marketingMensaje) ? $h($marketingMensaje['estado'] ?? 'ok') : 'ok' ?>"
  ><span></span><button type="button" data-cerrar-snackbar>Cerrar</button></div>
  <script src="../../../assets/impulsa_material/js/material.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/papaparse@5.4.1/papaparse.min.js"></script>
  <script src="../../../assets/js/marketing/marketingPlanes.js"></script>
</body>
</html>
