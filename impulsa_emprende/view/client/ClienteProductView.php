<?php
$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Cliente';
$h = static fn (mixed $valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Productos cliente | Impulsa</title>
  <link rel="icon" href="<?= htmlspecialchars(obtenerFaviconHref(), ENT_QUOTES, 'UTF-8'); ?>" type="image/x-icon">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,400,0,0&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.bubble.css">
  <link rel="stylesheet" href="/assets/impulsa_material/css/material.css">
  <style>
    .im-marca__isotipo img { width: 100%; height: 100%; border-radius: inherit; object-fit: cover; }
    .im-accion-salir { color: #ba1a1a; }
    .im-bottom-sheet--perfil { max-width: 860px; max-height: min(760px, calc(100vh - 2rem)); overflow: auto; }
  </style>
</head>
<body>
  <div class="im-aplicacion" data-menu-colapsado="false">
    <aside class="im-menu-lateral" id="menu-lateral" aria-label="Navegacion principal">
      <div class="im-marca">
        <span class="im-marca__isotipo" aria-hidden="true">
          <?php if ($usuarioAvatarUrl): ?><img src="<?= $h($usuarioAvatarUrl) ?>" alt=""><?php else: ?><?= $h($usuarioInicial) ?><?php endif; ?>
        </span>
        <div class="im-marca__texto"><strong><?= $h($usuarioMarcaNombre) ?></strong><span>Cliente</span></div>
      </div>
      <nav class="im-navegacion">
        <a class="im-nav-item activo" href="/impulsa_emprende/controller/client/ClienteDashboardController.php">
          <span class="material-symbols-rounded" aria-hidden="true">dashboard</span>
          <span class="im-nav-item__texto">Dashboard</span>
        </a>
        <a class="im-nav-item" href="/impulsa_emprende/controller/client/ClienteMetricasController.php">
          <span class="material-symbols-rounded" aria-hidden="true">monitoring</span>
          <span class="im-nav-item__texto">Metricas</span>
        </a>
        <a class="im-nav-item" href="/impulsa_emprende/controller/client/ClienteMarketingController.php">
          <span class="material-symbols-rounded" aria-hidden="true">campaign</span>
          <span class="im-nav-item__texto">Marketing</span>
        </a>
        <a class="im-nav-item" href="/impulsa_emprende/controller/client/ClienteChatbotController.php">
          <span class="material-symbols-rounded" aria-hidden="true">forum</span>
          <span class="im-nav-item__texto">Chatbot</span>
        </a>
        <a class="im-nav-item" href="/impulsa_emprende/controller/client/ClienteBlogController.php">
          <span class="material-symbols-rounded" aria-hidden="true">article</span>
          <span class="im-nav-item__texto">Blog</span>
        </a>
        <a class="im-nav-item" href="/impulsa_emprende/controller/client/ClienteProductController.php">
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
          <div><p class="im-sobrelinea">Impulsa</p><h1>Productos cliente</h1></div>
        </div>
        <div class="im-barra-superior__acciones">
          <button class="im-boton-icono im-boton-icono--principal im-tooltip" type="button" data-abrir-config-tema aria-label="Configurar temas" data-tooltip="Configurar estilos"></button>
          <button class="im-boton-icono material-symbols-rounded im-tooltip" type="button" data-abrir-perfil aria-label="Mi perfil" data-tooltip="Mi perfil">account_circle</button>
          <a class="im-boton-icono material-symbols-rounded im-tooltip im-accion-salir" href="/auth/logout.php" aria-label="Salir" data-tooltip="Salir">logout</a>
        </div>
      </header>
      <main class="im-contenido">
        <?php require __DIR__ . '/../../partials/api_product/api_productoController.php'; ?>
      </main>
    </div>
  </div>
  <?php require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilView.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
  <script src="/assets/impulsa_material/js/material.js"></script>
  <script>
    document.querySelectorAll('[data-quill-form]').forEach((form) => {
      const editorNode = form.querySelector('[data-quill-editor]');
      const hidden = form.querySelector('[data-quill-hidden]');
      if (!editorNode || !hidden || typeof Quill === 'undefined' || form.dataset.quillInitialized === 'true') return;
      const quill = new Quill(editorNode, { theme: 'bubble' });
      quill.root.innerHTML = hidden.value || '<p></p>';
      form.dataset.quillInitialized = 'true';
      form.addEventListener('submit', () => { hidden.value = quill.root.innerHTML; });
    });
  </script>
</body>
</html>
