<?php
$h = $h ?? static fn (mixed $valor): string => htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
$suscripciones = $marketingSuscripciones ?? [];
$campanias = $marketingCampanias ?? [];
$estadosMonitor = ['requested', 'meeting_scheduled', 'active', 'paused', 'completed', 'cancelled'];
$campaniasAsignacion = array_map(static fn (array $campania): array => [
    'id' => (int) ($campania['id'] ?? 0),
    'name' => (string) ($campania['campaign_name'] ?? ''),
], $campanias);
?>
<section class="marketing-monitor-panel">
  <div class="im-encabezado-seccion">
    <div>
      <p class="im-sobrelinea">Monitor</p>
      <h2>Gestion de planes contratados</h2>
      <p>Seguimiento de solicitudes, estados, responsables e importaciones de Meta.</p>
    </div>
    <span class="im-chip"><?= number_format(count($suscripciones), 0, ',', '.') ?> suscripciones</span>
  </div>

  <article class="im-tabla-tareas__tarjeta">
    <div class="im-tabla-tareas__cabecera">
      <div><h3>Suscripciones</h3><p>Usuarios con planes solicitados o activos.</p></div>
    </div>
    <div class="im-tabla-tareas__scroll">
      <table class="im-tabla-tareas marketing-monitor-table">
        <thead><tr><th>ID</th><th>Plan</th><th>Usuario</th><th>Estado</th><th>Duracion</th><th>Fechas</th><th>Valor</th><th>Responsable</th><th class="im-tabla-tareas__acciones">Acciones</th></tr></thead>
        <tbody>
          <?php foreach ($suscripciones as $sub): ?>
            <tr>
              <td><?= (int) ($sub['id'] ?? 0) ?></td>
              <td class="im-tabla-tareas__nombre"><?= $h($sub['plan_name'] ?? '') ?><br><small><?= (int) ($sub['campaigns_total'] ?? 0) ?> campanias</small></td>
              <td><?= $h($sub['client_email'] ?: ($sub['entrepreneur_email'] ?? '')) ?></td>
              <td><span class="im-chip <?= $h(marketingChipEstadoClase($sub['status'] ?? '')) ?>"><?= $h(marketingEstadoSuscripcionEtiqueta($sub['status'] ?? '')) ?></span></td>
              <td><?= (int) ($sub['duration_months'] ?? 0) ?> meses</td>
              <td><?= $h($sub['start_date'] ?? '-') ?><br><small><?= $h($sub['end_date'] ?? '-') ?></small></td>
              <td><?= $h(marketingFormatoMoneda($sub['total_contract_value'] ?? 0, $sub['currency'] ?? 'ARS')) ?></td>
              <td><?= $h($sub['assigned_email'] ?? '-') ?></td>
              <td class="im-tabla-tareas__acciones">
                <form class="marketing-inline-actions" method="post">
                  <input type="hidden" name="marketing_action" value="subscription_status_save">
                  <input type="hidden" name="subscription_id" value="<?= (int) ($sub['id'] ?? 0) ?>">
                  <span class="marketing-field-label">Estado <?= marketingAyudaCampo('', 'Nuevo estado operativo de la suscripcion seleccionada.') ?></span>
                  <select name="status" aria-label="Estado">
                    <?php foreach ($estadosMonitor as $estado): ?>
                      <option value="<?= $h($estado) ?>" <?= ($sub['status'] ?? '') === $estado ? 'selected' : '' ?>><?= $h(marketingEstadoSuscripcionEtiqueta($estado)) ?></option>
                    <?php endforeach; ?>
                  </select>
                  <button class="im-boton-icono material-symbols-rounded" type="submit" aria-label="Guardar">save</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$suscripciones): ?>
            <tr><td colspan="9">No hay suscripciones para mostrar.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </article>

  <article class="im-tarjeta">
    <div class="im-tarjeta__cabecera">
      <div><h3>Importar CSV de Meta</h3><p>Preview en navegador con PapaParse e importacion real a BBDD con match por nombre exacto o asignacion manual.</p></div>
    </div>
    <form class="marketing-upload-preview" method="post" enctype="multipart/form-data">
      <input type="hidden" name="marketing_action" value="meta_csv_import">
      <label class="marketing-csv-drop">
        <strong class="marketing-field-label">Archivo CSV <?= marketingAyudaCampo('', 'Exportacion CSV de Meta Ads con columnas de campania, resultados, gasto, impresiones y alcance.') ?></strong>
        <input type="file" name="meta_csv" accept=".csv,text/csv" required data-marketing-csv-input>
      </label>
      <div data-marketing-csv-preview></div>
      <div data-marketing-csv-assignments data-marketing-campaign-map="<?= marketingJson($campaniasAsignacion) ?>"></div>
      <div class="marketing-form-actions"><button class="im-boton im-boton--principal" type="submit">Importar CSV</button></div>
    </form>
  </article>
</section>
