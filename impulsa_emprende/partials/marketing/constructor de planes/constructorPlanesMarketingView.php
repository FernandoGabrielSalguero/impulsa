<?php
$h = $h ?? static fn (mixed $valor): string => htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
$plan = $marketingPlanActivo ?? [];
$planes = $marketingPlanesAdmin ?? [];
?>
<section class="marketing-plan-builder">
  <div class="im-encabezado-seccion">
    <div>
      <p class="im-sobrelinea">Constructor</p>
      <h2>Planes de marketing</h2>
      <p>Crea, publica o pausa planes con sus items y opciones de precio.</p>
    </div>
    <span class="im-chip"><?= number_format(count($planes), 0, ',', '.') ?> planes</span>
  </div>

  <div class="marketing-plan-builder__layout">
    <article class="im-tarjeta">
      <div class="im-tarjeta__cabecera">
        <div>
          <h3><?= !empty($plan['id']) ? 'Editar plan' : 'Nuevo plan' ?></h3>
          <p>La moneda se guarda como ARS.</p>
        </div>
      </div>
      <form class="marketing-form-grid" method="post" data-marketing-plan-form>
        <input type="hidden" name="marketing_action" value="plan_save">
        <input type="hidden" name="plan_id" value="<?= (int) ($plan['id'] ?? 0) ?>">
        <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Nombre', 'Nombre comercial del plan que van a ver administradores y clientes.') ?><input name="name" required value="<?= $h($plan['name'] ?? '') ?>"></label>
        <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Slug', 'Identificador corto para URLs o referencias internas. Si lo dejas vacio se genera desde el nombre.') ?><input name="slug" value="<?= $h($plan['slug'] ?? '') ?>"></label>
        <label class="im-campo im-campo-material im-campo--ancho"><?= marketingAyudaCampo('Descripcion corta', 'Resumen breve para la tarjeta del plan. Maximo 255 caracteres.') ?><input name="short_description" maxlength="255" value="<?= $h($plan['short_description'] ?? '') ?>"></label>
        <label class="im-campo im-campo-material im-campo--ancho"><?= marketingAyudaCampo('Descripcion completa', 'Detalle ampliado del alcance del plan, condiciones y entregables principales.') ?><textarea name="full_description" rows="4"><?= $h($plan['full_description'] ?? '') ?></textarea></label>
        <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Objetivo', 'Resultado principal que busca el plan, por ejemplo generar leads, ventas o posicionamiento.') ?><input name="objective" value="<?= $h($plan['objective'] ?? '') ?>"></label>
        <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Frecuencia de reporte', 'Cada cuanto se entregaran reportes al cliente, por ejemplo semanal o mensual.') ?><input name="report_frequency" value="<?= $h($plan['report_frequency'] ?? '') ?>"></label>
        <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Presupuesto minimo recomendado', 'Inversion publicitaria minima sugerida para que el plan tenga sentido. No es el precio del servicio.') ?><input type="number" step="0.01" name="recommended_ad_budget_min" value="<?= $h($plan['recommended_ad_budget_min'] ?? '') ?>"></label>
        <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Presupuesto maximo recomendado', 'Tope sugerido de inversion publicitaria para este plan. No se cobra como fee de Impulsa.') ?><input type="number" step="0.01" name="recommended_ad_budget_max" value="<?= $h($plan['recommended_ad_budget_max'] ?? '') ?>"></label>
        <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Setup fee', 'Cargo inicial del plan si corresponde. Se guarda en ARS.') ?><input type="number" step="0.01" name="setup_fee" value="<?= $h($plan['setup_fee'] ?? '0') ?>"></label>
        <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Periodo de cobro', 'Periodo usado para facturar el servicio. El valor por defecto es monthly.') ?><input name="billing_period" value="<?= $h($plan['billing_period'] ?? 'monthly') ?>"></label>
        <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Nivel de soporte', 'Tipo de acompanamiento incluido, por ejemplo basico, prioritario o personalizado.') ?><input name="support_level" value="<?= $h($plan['support_level'] ?? '') ?>"></label>
        <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Estado', 'Controla el ciclo de vida del plan: borrador, publicado, pausado o archivado.') ?><select name="status">
          <?php foreach (['draft', 'published', 'paused', 'archived'] as $estado): ?>
            <option value="<?= $h($estado) ?>" <?= ($plan['status'] ?? 'draft') === $estado ? 'selected' : '' ?>><?= $h(marketingEstadoPlanEtiqueta($estado)) ?></option>
          <?php endforeach; ?>
        </select></label>
        <label class="im-slide-toggle im-campo--ancho"><input type="checkbox" name="is_visible_to_clients" value="1" <?= (int) ($plan['is_visible_to_clients'] ?? 0) === 1 ? 'checked' : '' ?>><span></span> Visible para clientes <?= marketingAyudaCampo('', 'Permite que emprendedores y clientes vean este plan si ademas esta publicado.') ?></label>
        <div class="marketing-form-actions">
          <button class="im-boton im-boton--principal" type="submit">Guardar plan</button>
          <?php if (!empty($plan['id'])): ?>
            <button class="im-boton im-boton--texto" type="submit" name="marketing_action" value="plan_delete" data-marketing-confirm="Eliminar plan y sus items?">Eliminar</button>
          <?php endif; ?>
        </div>
      </form>
    </article>

    <div class="marketing-plan-list">
      <?php if (!$planes): ?>
        <div class="marketing-empty"><span class="material-symbols-rounded">sell</span><strong>No hay planes.</strong><span>Crea el primero desde el formulario.</span></div>
      <?php endif; ?>
      <?php foreach ($planes as $item): ?>
        <?php $payload = [
          'plan_id' => $item['id'] ?? 0,
          'name' => $item['name'] ?? '',
          'slug' => $item['slug'] ?? '',
          'short_description' => $item['short_description'] ?? '',
          'full_description' => $item['full_description'] ?? '',
          'objective' => $item['objective'] ?? '',
          'recommended_ad_budget_min' => $item['recommended_ad_budget_min'] ?? '',
          'recommended_ad_budget_max' => $item['recommended_ad_budget_max'] ?? '',
          'setup_fee' => $item['setup_fee'] ?? '',
          'billing_period' => $item['billing_period'] ?? 'monthly',
          'report_frequency' => $item['report_frequency'] ?? '',
          'support_level' => $item['support_level'] ?? '',
          'is_visible_to_clients' => $item['is_visible_to_clients'] ?? 0,
          'status' => $item['status'] ?? 'draft',
        ]; ?>
        <article class="im-tarjeta marketing-plan-card">
          <div class="im-tarjeta__cabecera">
            <div><h3><?= $h($item['name'] ?? '') ?></h3><p><?= $h($item['short_description'] ?? '') ?></p></div>
            <span class="im-chip <?= $h(marketingChipEstadoClase($item['status'] ?? '')) ?>"><?= $h(marketingEstadoPlanEtiqueta($item['status'] ?? '')) ?></span>
          </div>
          <div class="marketing-inline-actions">
            <button class="im-boton im-boton--tonal" type="button" data-marketing-load-plan="<?= marketingJson($payload) ?>">Editar</button>
            <span class="im-chip"><?= (int) ($item['features_total'] ?? 0) ?> items</span>
            <span class="im-chip"><?= (int) ($item['precios_total'] ?? 0) ?> precios</span>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>

  <?php if (!empty($plan['id'])): ?>
    <div class="im-grilla im-grilla--dos-columnas">
      <article class="im-tarjeta">
        <div class="im-tarjeta__cabecera"><div><h3>Items incluidos</h3><p>Beneficios, entregables y condiciones del plan.</p></div></div>
        <form class="marketing-form-grid" method="post">
          <input type="hidden" name="marketing_action" value="feature_save">
          <input type="hidden" name="plan_id" value="<?= (int) $plan['id'] ?>">
          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Item', 'Beneficio, entregable o tarea incluida dentro del plan.') ?><input name="feature_name" required></label>
          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Orden', 'Numero usado para ordenar los items en la tarjeta del plan.') ?><input type="number" name="feature_order" value="0"></label>
          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Cantidad', 'Cantidad incluida para este item, si aplica.') ?><input type="number" step="0.01" name="quantity"></label>
          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Unidad', 'Unidad de la cantidad, por ejemplo posteos, reuniones, campanias o horas.') ?><input name="unit"></label>
          <label class="im-campo im-campo-material im-campo--ancho"><?= marketingAyudaCampo('Descripcion', 'Aclaracion corta sobre el alcance de este item.') ?><input name="feature_description"></label>
          <label class="im-slide-toggle im-campo--ancho"><input type="checkbox" name="is_highlighted" value="1"><span></span> Destacado <?= marketingAyudaCampo('', 'Marca este item como importante para resaltarlo visualmente.') ?></label>
          <div class="marketing-form-actions"><button class="im-boton im-boton--principal" type="submit">Agregar item</button></div>
        </form>
        <ul class="marketing-plan-card__features">
          <?php foreach (($plan['features'] ?? []) as $feature): ?>
            <li>
              <span><?= $h($feature['feature_name'] ?? '') ?> <?= $feature['quantity'] !== null ? '(' . $h($feature['quantity']) . ' ' . $h($feature['unit'] ?? '') . ')' : '' ?></span>
              <form method="post">
                <input type="hidden" name="marketing_action" value="feature_delete">
                <input type="hidden" name="plan_id" value="<?= (int) $plan['id'] ?>">
                <input type="hidden" name="feature_id" value="<?= (int) ($feature['id'] ?? 0) ?>">
                <button class="im-boton-icono material-symbols-rounded" type="submit" aria-label="Eliminar">delete</button>
              </form>
            </li>
          <?php endforeach; ?>
        </ul>
      </article>

      <article class="im-tarjeta">
        <div class="im-tarjeta__cabecera"><div><h3>Opciones de precio</h3><p>Duracion, precio mensual y valor total.</p></div></div>
        <form class="marketing-form-grid" method="post">
          <input type="hidden" name="marketing_action" value="pricing_save">
          <input type="hidden" name="plan_id" value="<?= (int) $plan['id'] ?>">
          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Duracion meses', 'Cantidad de meses que dura esta opcion de contratacion.') ?><input type="number" name="duration_months" min="1" required></label>
          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Precio mensual ARS', 'Valor mensual del servicio. Siempre se guarda como ARS.') ?><input type="number" step="0.01" name="monthly_price" required></label>
          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Total ARS', 'Valor total del contrato para esta duracion. Si queda vacio se calcula mensual por meses.') ?><input type="number" step="0.01" name="total_price"></label>
          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Setup fee ARS', 'Cargo inicial especifico para esta opcion de precio.') ?><input type="number" step="0.01" name="setup_fee" value="0"></label>
          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Orden', 'Posicion en la que aparece esta opcion frente a otras duraciones.') ?><input type="number" name="display_order" value="0"></label>
          <label class="im-slide-toggle"><input type="checkbox" name="is_default" value="1"><span></span> Default <?= marketingAyudaCampo('', 'Opcion preseleccionada o recomendada por defecto.') ?></label>
          <label class="im-slide-toggle im-campo--ancho"><input type="checkbox" name="is_featured" value="1"><span></span> Destacado <?= marketingAyudaCampo('', 'Resalta esta opcion de precio en la tarjeta del plan.') ?></label>
          <div class="marketing-form-actions"><button class="im-boton im-boton--principal" type="submit">Agregar precio</button></div>
        </form>
        <div class="marketing-pricing-list">
          <?php foreach (($plan['pricing_options'] ?? []) as $precio): ?>
            <article class="marketing-pricing-option">
              <strong class="marketing-pricing-option__price"><?= $h(marketingFormatoMoneda($precio['monthly_price'] ?? 0, $precio['currency'] ?? 'ARS')) ?>/mes</strong>
              <span><?= (int) ($precio['duration_months'] ?? 0) ?> meses · Total <?= $h(marketingFormatoMoneda($precio['total_price'] ?? 0, $precio['currency'] ?? 'ARS')) ?></span>
              <form method="post">
                <input type="hidden" name="marketing_action" value="pricing_delete">
                <input type="hidden" name="plan_id" value="<?= (int) $plan['id'] ?>">
                <input type="hidden" name="pricing_id" value="<?= (int) ($precio['id'] ?? 0) ?>">
                <button class="im-boton im-boton--texto" type="submit">Eliminar</button>
              </form>
            </article>
          <?php endforeach; ?>
        </div>
      </article>
    </div>
  <?php endif; ?>
</section>
