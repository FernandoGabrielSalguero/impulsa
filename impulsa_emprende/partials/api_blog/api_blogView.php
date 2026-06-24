<?php

declare(strict_types=1);

$h = static fn (mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$apiBlogStatusMessage = trim((string) ($apiBlogStatusMessage ?? ''));
?>
<section class="im-seccion-documento activa" id="api-blog-builder" data-panel="api-blog-builder">
  <div class="im-encabezado-seccion">
    <div>
      <p class="im-sobrelinea"><?= $h($apiBlogRoleLabel ?? 'Cliente') ?></p>
      <h2><?= $h($apiBlogPageTitle ?? 'API Blog') ?></h2>
      <p>El modulo fue reducido a una verificacion minima de conexion.</p>
    </div>
  </div>

  <div class="im-grilla">
    <article class="im-tarjeta">
      <div class="im-tarjeta__cabecera">
        <div>
          <h3>Estado del modulo</h3>
          <p><?= $h($apiBlogStatusMessage !== '' ? $apiBlogStatusMessage : 'El modelo y el controlador estan conectados correctamente.') ?></p>
        </div>
      </div>
    </article>
  </div>
</section>
