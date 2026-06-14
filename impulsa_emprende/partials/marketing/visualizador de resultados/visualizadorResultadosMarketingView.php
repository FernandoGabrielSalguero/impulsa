<?php
$h = $h ?? static fn (mixed $valor): string => htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
$resultados = $marketingResultados ?? [];
$reportes = $marketingReportes ?? [];
$totales = [
    'spent' => array_sum(array_map(static fn ($r) => (float) ($r['spent_total'] ?? 0), $resultados)),
    'impressions' => array_sum(array_map(static fn ($r) => (int) ($r['impressions_total'] ?? 0), $resultados)),
    'results' => array_sum(array_map(static fn ($r) => (float) ($r['results_total'] ?? 0), $resultados)),
    'revenue' => array_sum(array_map(static fn ($r) => (float) ($r['closed_revenue_total'] ?? 0), $resultados)),
];
?>
<section class="marketing-results-panel">
  <div class="im-encabezado-seccion">
    <div>
      <p class="im-sobrelinea">Resultados</p>
      <h2>Metricas y reportes</h2>
      <p>Resumen de campanias, gasto, alcance, resultados comerciales y reportes visibles.</p>
    </div>
  </div>

  <div class="marketing-kpi-grid">
    <article class="im-tarjeta im-tarjeta--metrica"><span class="im-etiqueta">Invertido</span><strong><?= $h(marketingFormatoMoneda($totales['spent'])) ?></strong><small>Meta Ads</small></article>
    <article class="im-tarjeta im-tarjeta--metrica"><span class="im-etiqueta">Impresiones</span><strong><?= number_format($totales['impressions'], 0, ',', '.') ?></strong><small>Campanias</small></article>
    <article class="im-tarjeta im-tarjeta--metrica"><span class="im-etiqueta">Resultados</span><strong><?= number_format($totales['results'], 0, ',', '.') ?></strong><small>Importados</small></article>
    <article class="im-tarjeta im-tarjeta--metrica"><span class="im-etiqueta">Ventas cerradas</span><strong><?= $h(marketingFormatoMoneda($totales['revenue'])) ?></strong><small>Comercial</small></article>
  </div>

  <article class="im-tabla-tareas__tarjeta">
    <div class="im-tabla-tareas__cabecera"><div><h3>Campanias</h3><p>Metricas consolidadas por campania y suscripcion.</p></div></div>
    <div class="im-tabla-tareas__scroll">
      <table class="im-tabla-tareas">
        <thead><tr><th>Plan</th><th>Campania</th><th>Estado</th><th>Ultima metrica</th><th>Invertido</th><th>Alcance</th><th>Resultados</th><th>Clientes cerrados</th></tr></thead>
        <tbody>
          <?php foreach ($resultados as $row): ?>
            <tr>
              <td class="im-tabla-tareas__nombre"><?= $h($row['plan_name'] ?? '') ?></td>
              <td><?= $h($row['campaign_name'] ?? 'Sin campania') ?></td>
              <td><span class="im-chip <?= $h(marketingChipEstadoClase($row['campaign_status'] ?? $row['subscription_status'] ?? '')) ?>"><?= $h(marketingEstadoSuscripcionEtiqueta($row['campaign_status'] ?? $row['subscription_status'] ?? '')) ?></span></td>
              <td><?= $h($row['last_metric_date'] ?? '-') ?></td>
              <td><?= $h(marketingFormatoMoneda($row['spent_total'] ?? 0)) ?></td>
              <td><?= number_format((int) ($row['reach_total'] ?? 0), 0, ',', '.') ?></td>
              <td><?= number_format((float) ($row['results_total'] ?? 0), 0, ',', '.') ?></td>
              <td><?= number_format((int) ($row['closed_clients_total'] ?? 0), 0, ',', '.') ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$resultados): ?>
            <tr><td colspan="8">No hay metricas disponibles para mostrar.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </article>

  <div class="marketing-results-list">
    <?php foreach ($reportes as $reporte): ?>
      <article class="im-tarjeta marketing-report-card">
        <div class="im-tarjeta__cabecera">
          <div><h3><?= $h($reporte['title'] ?? '') ?></h3><p><?= $h($reporte['plan_name'] ?? '') ?> · <?= $h($reporte['period_start'] ?? '') ?> a <?= $h($reporte['period_end'] ?? '') ?></p></div>
          <span class="im-chip <?= (int) ($reporte['visible_to_client'] ?? 0) === 1 ? 'im-chip--exito' : '' ?>"><?= (int) ($reporte['visible_to_client'] ?? 0) === 1 ? 'Visible' : 'Interno' ?></span>
        </div>
        <?php if (!empty($reporte['summary'])): ?><p><?= nl2br($h($reporte['summary'])) ?></p><?php endif; ?>
        <?php if (!empty($reporte['conclusions'])): ?><p><strong>Conclusiones:</strong> <?= nl2br($h($reporte['conclusions'])) ?></p><?php endif; ?>
        <?php if (!empty($reporte['next_actions'])): ?><p><strong>Proximas acciones:</strong> <?= nl2br($h($reporte['next_actions'])) ?></p><?php endif; ?>
      </article>
    <?php endforeach; ?>
  </div>
</section>
