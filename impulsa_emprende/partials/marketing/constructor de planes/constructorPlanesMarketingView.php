<?php
$h = $h ?? static fn(mixed $valor): string => htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
$plan = $marketingPlanActivo ?? [];
$planes = $marketingPlanesCompletos ?? [];
$objetivosPlan = [
  'Ganar visibilidad',
  'Generar consultas',
  'Aumentar ventas',
  'Captar leads',
  'Fidelizar clientes',
  'Posicionar marca'
];
$frecuenciasReporte = [
  'Semanal',
  'Quincenal',
  'Mensual',
  'Bimestral',
  'Trimestral',
  'Al finalizar campana'
];
$nivelesSoporte = [
  'Basico',
  'Estandar',
  'Prioritario',
  'Estrategico',
  'Premium',
  'A medida'
];
$periodosCobro = [
  'Mensual',
  'Bimestral',
  'Trimestral',
  'Semestral',
  'Anual',
  'Pago unico'
];
$unidadesFeature = [
  'Acción',
  'Anuncio',
  'Asesoramiento',
  'Campaña',
  'Diseño Gráfico',
  'Estrategia',
  'Gestión Publicitaria',
  'Historia',
  'Hora',
  'Informe',
  'Minutos',
  'Optimización',
  'Otro',
  'Posteo',
  'Publicación',
  'Reel',
  'Reunión',
  'Subir contenido'
];
$monedasPrecio = ['ARS', 'USD', 'EUR'];
$planFeaturesIniciales = $plan['features'] ?? [];
$planPreciosIniciales = $plan['pricing_options'] ?? [];
?>
<section class="marketing-plan-builder">
  <div class="im-encabezado-seccion">
    <div>
      <p class="im-sobrelinea">Constructor</p>
      <h2>Planes de marketing</h2>
      <p>Crea un plan completo con datos base, items incluidos y alternativas comerciales.</p>
    </div>
    <div class="marketing-inline-actions">
      <button class="im-boton im-boton--tonal im-tooltip" type="button" data-marketing-open-plans data-tooltip="Abrir listado de planes existentes">
        <span class="material-symbols-rounded" aria-hidden="true">folder_open</span>
        Ver planes existentes
      </button>
      <span class="im-chip"><?= number_format(count($planes), 0, ',', '.') ?> planes</span>
    </div>
  </div>

  <article class="im-tarjeta marketing-plan-builder__card">
    <div class="im-tarjeta__cabecera">
      <div>
        <h3 data-marketing-builder-title><?= !empty($plan['id']) ? 'Editar plan' : 'Crear plan' ?></h3>
        <p data-marketing-builder-subtitle><?= !empty($plan['id']) ? 'Estas actualizando un plan existente.' : 'Estas creando un plan nuevo desde cero.' ?></p>
      </div>
      <button class="im-boton im-boton--tonal" type="button" data-marketing-new-plan>Nuevo plan</button>
    </div>

    <form class="marketing-plan-builder__form" method="post" data-marketing-plan-form data-initial-features="<?= marketingJson($planFeaturesIniciales) ?>" data-initial-pricing="<?= marketingJson($planPreciosIniciales) ?>">
      <input type="hidden" name="marketing_action" value="plan_save_full">
      <input type="hidden" name="plan_id" value="<?= (int) ($plan['id'] ?? 0) ?>">

      <section class="marketing-builder-section">
        <div class="marketing-builder-section__header">
          <div>
            <h4>Datos del plan</h4>
            <p>Informacion principal que se usara para publicar y vender el plan.</p>
          </div>
        </div>
        <div class="marketing-form-grid">
          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Nombre', 'Nombre comercial del plan que van a ver administradores y clientes.') ?><input name="name" required value="<?= $h($plan['name'] ?? '') ?>"></label>
          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Slug', 'Identificador corto para URLs o referencias internas. Si lo dejas vacio se genera desde el nombre.') ?><input name="slug" value="<?= $h($plan['slug'] ?? '') ?>"></label>
          <label class="im-campo im-campo-material im-campo--ancho"><?= marketingAyudaCampo('Descripcion corta', 'Resumen breve para la tarjeta del plan. Maximo 255 caracteres.') ?><input name="short_description" maxlength="255" value="<?= $h($plan['short_description'] ?? '') ?>"></label>
          <label class="im-campo im-campo-material im-campo--ancho"><?= marketingAyudaCampo('Descripcion completa', 'Detalle ampliado del alcance del plan, condiciones y entregables principales.') ?><textarea name="full_description" rows="4"><?= $h($plan['full_description'] ?? '') ?></textarea></label>

          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Objetivo', 'Resultado principal que busca el plan.') ?><select name="objective">
              <option value="">Seleccionar</option>
              <?php foreach ($objetivosPlan as $opcion): ?><option value="<?= $h($opcion) ?>" <?= ($plan['objective'] ?? '') === $opcion ? 'selected' : '' ?>><?= $h($opcion) ?></option><?php endforeach; ?>
            </select></label>
          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Frecuencia de reporte', 'Cada cuanto se entregaran reportes al cliente.') ?><select name="report_frequency">
              <option value="">Seleccionar</option>
              <?php foreach ($frecuenciasReporte as $opcion): ?><option value="<?= $h($opcion) ?>" <?= ($plan['report_frequency'] ?? '') === $opcion ? 'selected' : '' ?>><?= $h($opcion) ?></option><?php endforeach; ?>
            </select></label>
          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Nivel de soporte', 'Tipo de acompanamiento incluido en el plan.') ?><select name="support_level">
              <option value="">Seleccionar</option>
              <?php foreach ($nivelesSoporte as $opcion): ?><option value="<?= $h($opcion) ?>" <?= ($plan['support_level'] ?? '') === $opcion ? 'selected' : '' ?>><?= $h($opcion) ?></option><?php endforeach; ?>
            </select></label>
          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Periodo de cobro', 'Frecuencia con la que se cobrara el plan cuando se active el sistema de pagos.') ?><select name="billing_period">
              <?php foreach ($periodosCobro as $opcion): ?><option value="<?= $h($opcion) ?>" <?= ($plan['billing_period'] ?? 'Mensual') === $opcion ? 'selected' : '' ?>><?= $h($opcion) ?></option><?php endforeach; ?>
            </select></label>

          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Presupuesto minimo recomendado', 'Inversion publicitaria minima sugerida. No es el precio del servicio.') ?><input type="number" step="1" name="recommended_ad_budget_min" value="<?= $h((string) (int) ($plan['recommended_ad_budget_min'] ?? 0)) ?>"><b class="im-campo__prefijo" aria-hidden="true">$</b></label>
          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Presupuesto maximo recomendado', 'Tope sugerido de inversion publicitaria. No se cobra como fee de Impulsa.') ?><input type="number" step="1" name="recommended_ad_budget_max" value="<?= $h((string) (int) ($plan['recommended_ad_budget_max'] ?? 0)) ?>"><b class="im-campo__prefijo" aria-hidden="true">$</b></label>
          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Setup fee', 'Costo inicial de configuracion del plan. Usar 0 si no aplica.') ?><input type="number" step="1" name="setup_fee" value="<?= $h((string) (int) ($plan['setup_fee'] ?? 0)) ?>"><b class="im-campo__prefijo" aria-hidden="true">$</b></label>
          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Estado', 'Controla si el plan esta en borrador, publicado, pausado o archivado.') ?><select name="status">
              <?php foreach (['draft', 'published', 'paused', 'archived'] as $estado): ?><option value="<?= $h($estado) ?>" <?= ($plan['status'] ?? 'draft') === $estado ? 'selected' : '' ?>><?= $h(marketingEstadoPlanEtiqueta($estado)) ?></option><?php endforeach; ?>
            </select></label>
          <label class="im-slide-toggle im-campo--ancho"><input type="checkbox" name="is_visible_to_clients" value="1" <?= (int) ($plan['is_visible_to_clients'] ?? 0) === 1 ? 'checked' : '' ?>><span></span> Visible para clientes <?= marketingAyudaCampo('', 'Permite que emprendedores y clientes vean este plan si ademas esta publicado.') ?></label>
        </div>
      </section>

      <section class="marketing-builder-section">
        <div class="marketing-builder-section__header">
          <div>
            <h4>Items incluidos</h4>
            <p>Agrega beneficios o entregables. Se guardan todos junto con el plan.</p>
          </div>
        </div>
        <div class="marketing-form-grid" data-marketing-feature-editor>
          <input type="hidden" data-feature-field="id">
          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Nombre', 'Nombre del beneficio, entregable o tarea incluida.') ?><input data-feature-field="feature_name"></label>
          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Cantidad', 'Cantidad incluida para este item, si aplica.') ?><input type="number" step="1" min="0" data-feature-field="quantity"></label>
          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Unidad', 'Unidad de la cantidad incluida.') ?><select data-feature-field="unit">
              <option value="">Seleccionar</option>
              <?php foreach ($unidadesFeature as $unidad): ?><option value="<?= $h($unidad) ?>"><?= $h($unidad) ?></option><?php endforeach; ?>
            </select></label>
          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Orden', 'Numero usado para ordenar los items en la tarjeta del plan.') ?><input type="number" step="1" data-feature-field="feature_order" value="0"></label>
          <label class="im-campo im-campo-material im-campo--ancho"><?= marketingAyudaCampo('Descripcion', 'Aclaracion corta sobre el alcance de este item.') ?><input data-feature-field="feature_description"></label>
          <label class="im-slide-toggle"><input type="checkbox" data-feature-field="is_highlighted" value="1"><span></span> Destacado <?= marketingAyudaCampo('', 'Marca este item como importante para resaltarlo visualmente.') ?></label>
          <div class="marketing-form-actions"><button class="im-boton im-boton--tonal" type="button" data-marketing-add-feature>Agregar item</button></div>
        </div>
        <div class="marketing-builder-list" data-marketing-feature-list></div>
      </section>

      <section class="marketing-builder-section">
        <div class="marketing-builder-section__header">
          <div>
            <h4>Precios y duracion del plan</h4>
            <p>Podes crear varias opciones para el mismo plan, por ejemplo mensual, trimestral o anual.</p>
          </div>
        </div>
        <div class="marketing-form-grid" data-marketing-pricing-editor>
          <input type="hidden" data-pricing-field="id">
          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Duracion en meses', 'Cantidad de meses que dura esta alternativa comercial.') ?><input type="number" step="1" min="1" data-pricing-field="duration_months"></label>
          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Precio mensual', 'Valor mensual del servicio para esta opcion.') ?><input type="number" step="1" min="0" data-pricing-field="monthly_price"><b class="im-campo__prefijo" aria-hidden="true">$</b></label>
          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Precio total', 'Se calcula automaticamente multiplicando duracion en meses por precio mensual.') ?><input type="number" step="1" min="0" data-pricing-field="total_price" readonly><b class="im-campo__prefijo" aria-hidden="true">$</b></label>
          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Costo inicial', 'Costo inicial de configuracion para esta opcion. Usar 0 si no aplica.') ?><input type="number" step="1" min="0" data-pricing-field="setup_fee" value="0"><b class="im-campo__prefijo" aria-hidden="true">$</b></label>
          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Moneda', 'Moneda en la que se guardara esta opcion comercial.') ?><select data-pricing-field="currency">
              <?php foreach ($monedasPrecio as $moneda): ?><option value="<?= $h($moneda) ?>"><?= $h($moneda) ?></option><?php endforeach; ?>
            </select></label>
          <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Orden', 'Posicion en la que aparece esta opcion frente a otras duraciones.') ?><input type="number" step="1" data-pricing-field="display_order" value="0"></label>
          <label class="im-slide-toggle"><input type="checkbox" data-pricing-field="is_featured" value="1"><span></span> Opcion destacada <?= marketingAyudaCampo('', 'Resalta esta opcion en la tarjeta del plan.') ?></label>
          <label class="im-slide-toggle"><input type="checkbox" data-pricing-field="is_default" value="1"><span></span> Opcion predeterminada <?= marketingAyudaCampo('', 'Alternativa recomendada por defecto.') ?></label>
          <div class="marketing-form-actions"><button class="im-boton im-boton--tonal" type="button" data-marketing-add-pricing>Agregar precio</button></div>
        </div>
        <div class="marketing-builder-list" data-marketing-pricing-list></div>
      </section>

      <div class="marketing-form-actions marketing-form-actions--sticky">
        <button class="im-boton im-boton--principal" type="submit">Guardar plan completo</button>
        <button class="im-boton im-boton--texto" type="submit" name="marketing_action" value="plan_delete" data-marketing-delete-plan <?= empty($plan['id']) ? 'hidden' : '' ?> data-marketing-confirm="Si el plan tiene suscripciones no se eliminara. Recomendamos pausarlo o archivarlo.">Eliminar plan</button>
      </div>
    </form>
  </article>

  <section class="im-dialog marketing-plan-modal" role="dialog" aria-modal="true" aria-labelledby="marketing-planes-modal-titulo" aria-hidden="true" data-marketing-plans-modal>
    <header class="im-dialog__cabecera">
      <h3 id="marketing-planes-modal-titulo">Planes existentes</h3>
      <button class="im-boton-icono material-symbols-rounded" type="button" data-marketing-close-plans aria-label="Cerrar dialog">close</button>
    </header>
    <div class="im-dialog__contenido">
      <?php if (!$planes): ?>
        <div class="marketing-empty"><span class="material-symbols-rounded">sell</span><strong>No hay planes.</strong><span>Crea el primero desde el formulario.</span></div>
      <?php else: ?>
        <div class="im-grilla im-grilla--tres-columnas">
          <?php foreach ($planes as $item): ?>
            <article class="im-tarjeta marketing-plan-card">
              <div class="im-tarjeta__cabecera">
                <div>
                  <h3><?= $h($item['name'] ?? '') ?></h3>
                  <p><?= $h($item['short_description'] ?? '') ?></p>
                </div>
                <span class="im-chip <?= $h(marketingChipEstadoClase($item['status'] ?? '')) ?>"><?= $h(marketingEstadoPlanEtiqueta($item['status'] ?? '')) ?></span>
              </div>
              <ul class="marketing-plan-card__features">
                <?php foreach (array_slice(($item['features'] ?? []), 0, 4) as $feature): ?><li><?= $h($feature['feature_name'] ?? '') ?></li><?php endforeach; ?>
              </ul>
              <div class="marketing-pricing-list">
                <?php foreach (array_slice(($item['pricing_options'] ?? []), 0, 2) as $precio): ?>
                  <span class="im-chip"><?= (int) ($precio['duration_months'] ?? 0) ?> meses - $<?= $h(number_format((float) ($precio['monthly_price'] ?? 0), 0, ',', '.')) ?>/mes</span>
                <?php endforeach; ?>
              </div>
              <button class="im-boton im-boton--principal" type="button" data-marketing-load-plan="<?= marketingJson($item) ?>">Editar este plan</button>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>
</section>
