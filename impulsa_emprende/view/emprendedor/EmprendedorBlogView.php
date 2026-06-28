<?php
$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Emprendedor';
$emprendedorActivePage = 'blog';
$h = static fn (mixed $valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Blog emprendedor | Impulsa</title>
  <link rel="icon" href="<?= htmlspecialchars(obtenerFaviconHref(), ENT_QUOTES, 'UTF-8'); ?>" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css">
  <link rel="stylesheet" href="<?= htmlspecialchars(obtenerImpulsaMaterialCssHref(), ENT_QUOTES, 'UTF-8'); ?>">
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
          <div><p class="im-sobrelinea">Impulsa Emprende</p><h1>Blog</h1></div>
        </div>
        <div class="im-barra-superior__acciones">
          <button class="im-boton-icono im-boton-icono--principal im-tooltip" type="button" data-abrir-config-tema aria-label="Configurar temas" data-tooltip="Configurar estilos"></button>
          <button class="im-boton-icono material-symbols-rounded im-tooltip" type="button" data-abrir-perfil aria-label="Mi perfil" data-tooltip="Mi perfil">account_circle</button>
          <a class="im-boton-icono material-symbols-rounded im-tooltip im-accion--eliminar" href="/auth/logout.php" aria-label="Salir" data-tooltip="Salir">logout</a>
        </div>
      </header>
      <main class="im-contenido">
        <?php require __DIR__ . '/../../partials/api_blog/api_blogController.php'; ?>
      </main>
    </div>
  </div>
  <?php require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilView.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
  <script src="<?= htmlspecialchars(obtenerImpulsaMaterialJsSrc(), ENT_QUOTES, 'UTF-8'); ?>"></script>
  <script>
    document.querySelectorAll('[data-quill-form]').forEach((form) => {
      const editorNode = form.querySelector('[data-quill-editor]');
      const htmlHidden = form.querySelector('[data-quill-hidden]');
      const excerptHidden = form.querySelector('[data-blog-excerpt-hidden]');
      const excerptEditor = form.querySelector('[data-blog-excerpt-editor]');
      if (!editorNode || !htmlHidden || typeof Quill === 'undefined' || form.dataset.quillInitialized === 'true') return;

      let hasTableSupport = false;
      try {
        hasTableSupport = Boolean(Quill.import('formats/table'));
      } catch (error) {
        hasTableSupport = false;
      }

      const toolbarOptions = [
        [{ header: [1, 2, 3, false] }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ color: [] }, { background: [] }],
        [{ list: 'ordered' }, { list: 'bullet' }, { indent: '-1' }, { indent: '+1' }],
        [{ align: [] }],
        ['blockquote', 'code-block'],
        ['link', 'image', 'video']
      ];

      if (hasTableSupport) {
        toolbarOptions.push(['table']);
      }

      toolbarOptions.push(['clean']);

      const quill = new Quill(editorNode, {
        theme: 'snow',
        placeholder: editorNode.dataset.placeholder || '',
        modules: {
          toolbar: toolbarOptions,
          ...(hasTableSupport ? { table: true } : {})
        }
      });

      quill.root.innerHTML = htmlHidden.value || '<p></p>';
      form._quillInstance = quill;

      editorNode.addEventListener('click', () => quill.focus());

      const syncFields = () => {
        htmlHidden.value = quill.root.innerHTML;
      };

      if (excerptEditor && excerptHidden) {
        const syncExcerpt = () => {
          excerptHidden.value = excerptEditor.value.slice(0, 300);
        };

        excerptEditor.addEventListener('input', syncExcerpt);
        syncExcerpt();
      }

      form.dataset.quillInitialized = 'true';
      quill.on('text-change', syncFields);
      form.addEventListener('submit', syncFields);
      syncFields();
    });
  </script>
</body>
</html>
