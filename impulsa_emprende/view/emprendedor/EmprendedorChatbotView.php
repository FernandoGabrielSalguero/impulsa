<?php
$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Emprendedor';
$h = static fn (mixed $valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Chatbot emprendedor | Impulsa</title>
  <link rel="icon" href="<?= htmlspecialchars(obtenerFaviconHref(), ENT_QUOTES, 'UTF-8'); ?>" type="image/x-icon">
  <link rel="stylesheet" href="/assets/impulsa_material/css/material.css?v=icons-local-1">
  <style>
    .im-marca__isotipo img { width: 100%; height: 100%; border-radius: inherit; object-fit: cover; }
    .im-bottom-sheet--perfil { max-width: 860px; max-height: min(760px, calc(100vh - 2rem)); overflow: auto; }
  </style>
</head>
<body>
  <div class="im-aplicacion" data-menu-colapsado="false">
    <aside class="im-menu-lateral" id="menu-lateral" aria-label="Navegacion principal">
      <div class="im-marca">
        <span class="im-marca__isotipo" aria-hidden="true">
          <?php if ($usuarioAvatarUrl): ?>
            <img src="<?= $h($usuarioAvatarUrl) ?>" alt="">
          <?php else: ?>
            <?= $h($usuarioInicial) ?>
          <?php endif; ?>
        </span>
        <div class="im-marca__texto">
          <strong><?= $h($usuarioMarcaNombre) ?></strong>
          <span>Emprendedor</span>
        </div>
      </div>
      <nav class="im-navegacion">
        <a class="im-nav-item" href="/impulsa_emprende/controller/emprendedor/EmprendedorDashboardController.php">
          <span class="material-symbols-rounded" aria-hidden="true">dashboard</span>
          <span class="im-nav-item__texto">Dashboard</span>
        </a>
        <a class="im-nav-item" href="/impulsa_emprende/controller/emprendedor/emprendedorDefinicionController.php">
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
        <a class="im-nav-item activo" href="/impulsa_emprende/controller/emprendedor/EmprendedorChatbotController.php">
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
            <h1>Chatbot</h1>
          </div>
        </div>
        <div class="im-barra-superior__acciones">
          <button class="im-boton-icono im-boton-icono--principal im-tooltip" type="button" data-abrir-config-tema aria-label="Configurar temas" data-tooltip="Configurar estilos"></button>
          <button class="im-boton-icono material-symbols-rounded im-tooltip" type="button" data-abrir-perfil aria-label="Mi perfil" data-tooltip="Mi perfil">account_circle</button>
          <a class="im-boton-icono material-symbols-rounded im-tooltip im-accion--eliminar" href="/auth/logout.php" aria-label="Salir" data-tooltip="Salir">logout</a>
        </div>
      </header>

      <main class="im-contenido">
        <?php require __DIR__ . '/../../partials/components/chatbot_builder/chatbot_builder_controller.php'; ?>
      </main>
    </div>
  </div>

  <?php require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilView.php'; ?>
  <script src="/assets/impulsa_material/js/material.js?v=panel-default-1"></script>
  <script src="/impulsa_emprende/partials/components/chatbot_builder/chatbot_builder.js"></script>
</body>
</html>
