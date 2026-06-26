<?php

declare(strict_types=1);

$h = isset($h) && is_callable($h)
    ? $h
    : static fn (mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Emprendedor';
$emprendedorActivePage = (string) ($emprendedorActivePage ?? 'dashboard');

$emprendedorMenuItems = [
    ['key' => 'dashboard', 'href' => '/impulsa_emprende/controller/emprendedor/EmprendedorDashboardController.php', 'icon' => 'dashboard', 'label' => 'Dashboard'],
    ['key' => 'definicion', 'href' => '/impulsa_emprende/controller/emprendedor/emprendedorDefinicionController.php', 'icon' => 'psychology', 'label' => 'Definicion'],
    ['key' => 'pagina_web', 'href' => '/impulsa_emprende/controller/emprendedor/EmprendedorPaginaWebController.php', 'icon' => 'web', 'label' => 'Pagina web'],
    ['key' => 'metricas', 'href' => '/impulsa_emprende/controller/emprendedor/EmprendedorMetricasController.php', 'icon' => 'monitoring', 'label' => 'Metricas'],
    ['key' => 'marketing', 'href' => '/impulsa_emprende/controller/emprendedor/EmprendedorMarketingController.php', 'icon' => 'campaign', 'label' => 'Marketing'],
    ['key' => 'chatbot', 'href' => '/impulsa_emprende/controller/emprendedor/EmprendedorChatbotController.php', 'icon' => 'forum', 'label' => 'Chatbot'],
    ['key' => 'blog', 'href' => '/impulsa_emprende/controller/emprendedor/EmprendedorBlogController.php', 'icon' => 'article', 'label' => 'Blog'],
    ['key' => 'productos', 'href' => '/impulsa_emprende/controller/emprendedor/EmprendedorProductController.php', 'icon' => 'inventory_2', 'label' => 'Productos'],
];
?>
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
    <?php foreach ($emprendedorMenuItems as $item): ?>
      <a class="im-nav-item <?= $emprendedorActivePage === $item['key'] ? 'activo' : '' ?>" href="<?= $h($item['href']) ?>">
        <span class="material-symbols-rounded" aria-hidden="true"><?= $h($item['icon']) ?></span>
        <span class="im-nav-item__texto"><?= $h($item['label']) ?></span>
      </a>
    <?php endforeach; ?>
  </nav>
</aside>
