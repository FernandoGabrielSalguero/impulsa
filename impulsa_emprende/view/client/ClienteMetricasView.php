<?php
$usuarioCorreo = $usuarioCorreo ?? '';
$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Cliente';
$clienteActivePage = 'metricas';
$clienteMetricasData = $clienteMetricasData ?? [];
$clienteMetricasIntegraciones = $clienteMetricasData['integraciones'] ?? [];
$h = static fn (mixed $valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Metricas cliente | Impulsa</title>
  <link rel="icon" href="<?= htmlspecialchars(obtenerFaviconHref(), ENT_QUOTES, 'UTF-8'); ?>" type="image/x-icon">
  <?= renderImpulsaMaterialFonts() ?>
  <link rel="stylesheet" href="<?= htmlspecialchars(obtenerImpulsaMaterialCssHref(), ENT_QUOTES, 'UTF-8'); ?>">
  <style>
    .im-marca__isotipo img {
      width: 100%;
      height: 100%;
      border-radius: inherit;
      object-fit: cover;
    }

    .im-accion-salir {
      color: #ba1a1a;
    }

    .im-bottom-sheet--perfil {
      max-width: 860px;
      max-height: min(760px, calc(100vh - 2rem));
      overflow: auto;
    }

    .im-metricas-kpi-desactivado {
      opacity: .6;
      filter: grayscale(1);
    }

    .im-metricas-layout {
      display: grid;
      gap: 1rem;
    }
  </style>
</head>
<body>
  <div class="im-aplicacion" data-menu-colapsado="false">
    <?php require __DIR__ . '/clienteMenu.php'; ?>
    <div class="im-cortina" data-cerrar-menu></div>
    <div class="im-contenedor">
      <header class="im-barra-superior">
        <div class="im-barra-superior__grupo">
          <button class="im-boton-icono" type="button" data-alternar-menu aria-label="Menu"></button>
          <div>
            <p class="im-sobrelinea">Impulsa</p>
            <h1>Metricas cliente</h1>
          </div>
        </div>
        <div class="im-barra-superior__acciones">
          <button class="im-boton-icono im-boton-icono--principal im-tooltip" type="button" data-abrir-config-tema aria-label="Configurar temas" data-tooltip="Configurar estilos"></button>
          <button class="im-boton-icono material-symbols-rounded im-tooltip" type="button" data-abrir-perfil aria-label="Mi perfil" data-tooltip="Mi perfil">account_circle</button>
          <a class="im-boton-icono material-symbols-rounded im-tooltip im-accion-salir" href="/auth/logout.php" aria-label="Salir" data-tooltip="Salir">logout</a>
        </div>
      </header>

      <main class="im-contenido">
        <section class="im-seccion-documento activa" id="metricas" data-panel="metricas">
          <div class="im-encabezado-seccion">
            <div>
              <p class="im-sobrelinea">Analitica</p>
              <h2>Metricas de tu pagina</h2>
            </div>
            <span class="im-chip"><?= number_format(count($clienteMetricasIntegraciones), 0, ',', '.') ?> integraciones</span>
          </div>

          <div class="im-metricas-layout">
            <?php require __DIR__ . '/../../partials/components/metrics/visit_page/visit_page_controller.php'; ?>
            <?php require __DIR__ . '/../../partials/components/metrics/form_contact/form_contact_controller.php'; ?>
          </div>
        </section>
      </main>
    </div>
  </div>

  <?php require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilView.php'; ?>
  <script src="<?= htmlspecialchars(obtenerImpulsaMaterialJsSrc(), ENT_QUOTES, 'UTF-8'); ?>"></script>
</body>
</html>
