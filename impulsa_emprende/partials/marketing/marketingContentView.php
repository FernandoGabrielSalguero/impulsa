<?php
$marketingGestiona = $marketingGestiona ?? false;
$marketingMensaje = $marketingMensaje ?? null;
$h = static fn (mixed $valor): string => htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
?>
<section class="im-seccion-documento activa">
  <article class="im-tarjeta marketing-tabs">
    <div class="im-tabs" data-marketing-tabs>
      <?php if ($marketingGestiona): ?>
        <button class="activo" type="button">Constructor</button>
        <button type="button">Planes publicados</button>
        <button type="button">Monitor</button>
        <button type="button">Resultados</button>
      <?php else: ?>
        <button class="activo" type="button">Planes</button>
        <button type="button">Resultados</button>
      <?php endif; ?>
    </div>

    <?php if ($marketingGestiona): ?>
      <div class="im-tab-panel activo"><?php require __DIR__ . '/constructor de planes/constructorPlanesMarketingView.php'; ?></div>
      <div class="im-tab-panel"><?php require __DIR__ . '/visualizador de planes/visualizadorPlanesMarketingView.php'; ?></div>
      <div class="im-tab-panel"><?php require __DIR__ . '/monitor de planes/monitorPlanesMarketingView.php'; ?></div>
      <div class="im-tab-panel"><?php require __DIR__ . '/visualizador de resultados/visualizadorResultadosMarketingView.php'; ?></div>
    <?php else: ?>
      <div class="im-tab-panel activo"><?php require __DIR__ . '/visualizador de planes/visualizadorPlanesMarketingView.php'; ?></div>
      <div class="im-tab-panel"><?php require __DIR__ . '/visualizador de resultados/visualizadorResultadosMarketingView.php'; ?></div>
    <?php endif; ?>
  </article>
</section>

<div class="im-modal-cortina im-drawer-cortina" data-marketing-dialog-backdrop></div>
<aside class="im-drawer marketing-plan-detail-drawer" role="dialog" aria-modal="true" aria-labelledby="marketing-plan-detail-title" aria-hidden="true" data-marketing-plan-detail-modal>
  <header class="im-drawer__cabecera">
    <h3 id="marketing-plan-detail-title">Detalle del plan</h3>
    <button class="im-boton-icono material-symbols-rounded" type="button" data-marketing-close-plan-detail aria-label="Cerrar dialog">close</button>
  </header>
  <div class="im-drawer__contenido" data-marketing-plan-detail-content></div>
  <footer class="im-drawer__acciones">
    <button class="im-boton im-boton--tonal" type="button" data-marketing-close-plan-detail>Cerrar</button>
  </footer>
</aside>
<div
  class="im-snackbar"
  role="status"
  data-marketing-snackbar="<?= is_array($marketingMensaje) ? $h($marketingMensaje['mensaje'] ?? '') : '' ?>"
  data-estado="<?= is_array($marketingMensaje) ? $h($marketingMensaje['estado'] ?? 'ok') : 'ok' ?>"
><span></span><button type="button" data-cerrar-snackbar>Cerrar</button></div>
