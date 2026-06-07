<?php
$visitPageResumen = $visitPageResumen ?? [];
$visitPageTotalIntegraciones = $visitPageTotalIntegraciones ?? 0;
$visitPageResumen = array_reverse($visitPageResumen);
?>
<article class="im-tarjeta">
  <div class="im-tarjeta__cabecera">
    <div>
      <h3>Visitas de la pagina</h3>
      <p>Resumen mensual de visitas asociado a tus integraciones activas y proyectos visibles.</p>
    </div>
    <span class="im-chip"><?= number_format((int) $visitPageTotalIntegraciones, 0, ',', '.') ?> integraciones</span>
  </div>
  <div class="im-grilla im-grilla--metricas">
    <?php foreach ($visitPageResumen as $resumenMes): ?>
      <article class="im-tarjeta im-tarjeta--metrica">
        <span class="im-etiqueta"><?= htmlspecialchars((string) ($resumenMes['label'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
        <strong><?= number_format((int) ($resumenMes['total'] ?? 0), 0, ',', '.') ?></strong>
        <small>Visitas registradas</small>
      </article>
    <?php endforeach; ?>
    <article class="im-tarjeta im-tarjeta--metrica im-metricas-kpi-desactivado" aria-disabled="true">
      <span class="im-etiqueta">Proximo KPI</span>
      <strong>--</strong>
      <small>Solicitar nuevos KPI</small>
    </article>
  </div>
</article>
