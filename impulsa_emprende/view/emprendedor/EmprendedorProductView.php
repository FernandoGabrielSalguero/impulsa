<?php
$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Emprendedor';
$emprendedorActivePage = 'productos';
$h = static fn (mixed $valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Productos emprendedor | Impulsa</title>
  <link rel="icon" href="<?= htmlspecialchars(obtenerFaviconHref(), ENT_QUOTES, 'UTF-8'); ?>" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.bubble.css">
  <link rel="stylesheet" href="/assets/impulsa_material/css/material.css?v=icons-local-1">
  <style>
    .im-marca__isotipo img { width: 100%; height: 100%; border-radius: inherit; object-fit: cover; }
    .im-bottom-sheet--perfil { max-width: 860px; max-height: min(760px, calc(100vh - 2rem)); overflow: auto; }
  </style>
</head>
<body>
  <div class="im-aplicacion" data-menu-colapsado="false">
    <?php require __DIR__ . '/EmprendedorMenu.php'; ?>
    <div class="im-cortina" data-cerrar-menu></div>
    <div class="im-contenedor">
      <header class="im-barra-superior">
        <div class="im-barra-superior__grupo">
          <button class="im-boton-icono" type="button" data-alternar-menu aria-label="Menu"></button>
          <div><p class="im-sobrelinea">Impulsa Emprende</p><h1>Productos</h1></div>
        </div>
        <div class="im-barra-superior__acciones">
          <button class="im-boton-icono im-boton-icono--principal im-tooltip" type="button" data-abrir-config-tema aria-label="Configurar temas" data-tooltip="Configurar estilos"></button>
          <button class="im-boton-icono material-symbols-rounded im-tooltip" type="button" data-abrir-perfil aria-label="Mi perfil" data-tooltip="Mi perfil">account_circle</button>
          <a class="im-boton-icono material-symbols-rounded im-tooltip im-accion--eliminar" href="/auth/logout.php" aria-label="Salir" data-tooltip="Salir">logout</a>
        </div>
      </header>
      <main class="im-contenido">
        <?php require __DIR__ . '/../../partials/api_product/api_productoController.php'; ?>
      </main>
    </div>
  </div>
  <?php require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilView.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
  <script src="/assets/impulsa_material/js/material.js?v=panel-default-1"></script>
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
