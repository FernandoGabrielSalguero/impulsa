<?php

declare(strict_types=1);

$h = isset($h) && is_callable($h)
    ? $h
    : static fn (mixed $value): string => htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');

$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Marketing';
$marketingRolLabel = $marketingRolLabel ?? 'Marketing';
$marketingActivePage = (string) ($marketingActivePage ?? 'dashboard');
$marketingMenuItems = [
    ['key' => 'dashboard', 'href' => '/impulsa_emprende/controller/marketing/marketingDashboardController.php', 'icon' => 'sell', 'label' => 'Planes publicados'],
    ['key' => 'constructor', 'href' => '/impulsa_emprende/controller/marketing/marketingConstructorController.php', 'icon' => 'edit_note', 'label' => 'Constructor'],
    ['key' => 'monitor', 'href' => '/impulsa_emprende/controller/marketing/marketingMonitorController.php', 'icon' => 'monitoring', 'label' => 'Monitor'],
    ['key' => 'resultados', 'href' => '/impulsa_emprende/controller/marketing/marketingResultadosController.php', 'icon' => 'analytics', 'label' => 'Resultados'],
    ['key' => 'usuarios', 'href' => '/impulsa_emprende/controller/marketing/marketingUsuariosController.php', 'icon' => 'groups', 'label' => 'Usuarios'],
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
      <span><?= $h($marketingRolLabel) ?></span>
    </div>
  </div>
  <nav class="im-navegacion">
    <?php foreach ($marketingMenuItems as $item): ?>
      <a class="im-nav-item <?= $marketingActivePage === $item['key'] ? 'activo' : '' ?>" href="<?= $h($item['href']) ?>">
        <span class="im-nav-item__icono" data-icon="<?= $h($item['icon']) ?>" aria-hidden="true"></span>
        <span class="im-nav-item__texto"><?= $h($item['label']) ?></span>
      </a>
    <?php endforeach; ?>
  </nav>
</aside>
