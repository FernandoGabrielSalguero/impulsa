<?php

declare(strict_types=1);

$h = isset($h) && is_callable($h)
    ? $h
    : static fn (mixed $value): string => htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');

$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Usuario';
$adminRolLabel = $adminRolLabel ?? 'Administrador';
$adminActiveMenu = (string) ($adminActiveMenu ?? 'dashboard');
$adminMenuItems = [
    ['key' => 'dashboard', 'href' => '/impulsa_emprende/controller/admin/dashboard.php', 'icon' => 'dashboard', 'label' => 'Dashboard'],
    ['key' => 'usuarios', 'href' => '/impulsa_emprende/controller/admin/adminListUserController.php', 'icon' => 'groups', 'label' => 'Usuarios'],
    ['key' => 'solicitudes-web', 'href' => '/impulsa_emprende/controller/admin/adminSolicitudesPaginaWebSolicitudesController.php', 'icon' => 'language', 'label' => 'Solicitudes web'],
    ['key' => 'proyectos', 'href' => '/impulsa_emprende/controller/admin/adminProyectosController.php', 'icon' => 'work', 'label' => 'Proyectos'],
    ['key' => 'tareas', 'href' => '/impulsa_emprende/controller/admin/adminTareasController.php', 'icon' => 'task_alt', 'label' => 'Tareas'],
    ['key' => 'marketing', 'href' => '/impulsa_emprende/controller/admin/adminMarketingController.php', 'icon' => 'campaign', 'label' => 'Marketing'],
    ['key' => 'api', 'href' => '/impulsa_emprende/controller/admin/adminAPIconfigurationController.php', 'icon' => 'key', 'label' => 'Integraciones API'],
    ['key' => 'correos', 'href' => '/impulsa_emprende/controller/admin/adminCorreosEnviadosController.php', 'icon' => 'mail', 'label' => 'Correos enviados'],
    ['key' => 'chatbots', 'href' => '/impulsa_emprende/controller/admin/adminChatbotController.php', 'icon' => 'forum', 'label' => 'Chatbots'],
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
      <span><?= $h($adminRolLabel) ?></span>
    </div>
  </div>
  <nav class="im-navegacion">
    <?php foreach ($adminMenuItems as $item): ?>
      <a class="im-nav-item <?= $adminActiveMenu === $item['key'] ? 'activo' : '' ?>" href="<?= $h($item['href']) ?>">
        <span class="im-nav-item__icono" data-icon="<?= $h($item['icon']) ?>" aria-hidden="true"></span>
        <span class="im-nav-item__texto"><?= $h($item['label']) ?></span>
      </a>
    <?php endforeach; ?>
  </nav>
</aside>
