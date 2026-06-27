<?php

declare(strict_types=1);

require_once __DIR__ . '/../../partials/components/admin/GestorDeMenu/admin_gestorMenuModel.php';

$h = isset($h) && is_callable($h)
    ? $h
    : static fn (mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Emprendedor';
$emprendedorActivePage = (string) ($emprendedorActivePage ?? 'dashboard');

$emprendedorMenuItems = adminGestorMenuCatalogoBase('impulsa_emprendedor');
if (isset($pdo, $usuario['id'])) {
    $menuConfig = adminGestorMenuObtenerConfiguracionUsuario($pdo, (int) $usuario['id'], 'impulsa_emprendedor');
    $emprendedorMenuItems = $menuConfig['visible_items'] ?? $emprendedorMenuItems;
}
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
