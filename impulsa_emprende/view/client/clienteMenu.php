<?php

declare(strict_types=1);

$h = isset($h) && is_callable($h)
    ? $h
    : static fn (mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Cliente';
$clienteActivePage = (string) ($clienteActivePage ?? 'dashboard');

$clienteMenuItems = [
    ['key' => 'dashboard', 'href' => '/impulsa_emprende/controller/client/ClienteDashboardController.php', 'icon' => 'dashboard', 'label' => 'Dashboard'],
    ['key' => 'metricas', 'href' => '/impulsa_emprende/controller/client/ClienteMetricasController.php', 'icon' => 'monitoring', 'label' => 'Metricas'],
    ['key' => 'marketing', 'href' => '/impulsa_emprende/controller/client/ClienteMarketingController.php', 'icon' => 'campaign', 'label' => 'Marketing'],
    ['key' => 'chatbot', 'href' => '/impulsa_emprende/controller/client/ClienteChatbotController.php', 'icon' => 'forum', 'label' => 'Chatbot'],
    ['key' => 'blog', 'href' => '/impulsa_emprende/controller/client/ClienteBlogController.php', 'icon' => 'article', 'label' => 'Blog'],
    ['key' => 'productos', 'href' => '/impulsa_emprende/controller/client/ClienteProductController.php', 'icon' => 'inventory_2', 'label' => 'Productos'],
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
      <span>Cliente</span>
    </div>
  </div>
  <nav class="im-navegacion">
    <?php foreach ($clienteMenuItems as $item): ?>
      <a class="im-nav-item <?= $clienteActivePage === $item['key'] ? 'activo' : '' ?>" href="<?= $h($item['href']) ?>">
        <span class="material-symbols-rounded" aria-hidden="true"><?= $h($item['icon']) ?></span>
        <span class="im-nav-item__texto"><?= $h($item['label']) ?></span>
      </a>
    <?php endforeach; ?>
  </nav>
</aside>
