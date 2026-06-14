<?php
$h = $h ?? static fn (mixed $valor): string => htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
$planes = $marketingPlanesPublicados ?? [];
$puedeSolicitarPlan = marketingUsuarioPuedeVerCliente($usuario['rol'] ?? null);
?>
<section class="marketing-dashboard-grid">
  <div class="im-encabezado-seccion">
    <div>
      <p class="im-sobrelinea">Planes</p>
      <h2>Elegir plan de marketing</h2>
      <p>Planes publicados disponibles para solicitar seguimiento del equipo Impulsa.</p>
    </div>
  </div>

  <div data-marketing-published-plans data-can-request="<?= $puedeSolicitarPlan ? '1' : '0' ?>">
    <?php if (!$planes): ?>
      <div class="marketing-empty"><span class="material-symbols-rounded">campaign</span><strong>No hay planes publicados.</strong><span>Cuando marketing publique un plan, aparecera aca.</span></div>
    <?php else: ?>
      <div class="im-grilla im-grilla--tres-columnas">
        <?php foreach ($planes as $plan): ?>
          <article class="im-tarjeta marketing-plan-card">
            <div class="im-tarjeta__cabecera">
              <div>
                <h3><?= $h($plan['name'] ?? '') ?></h3>
                <p><?= $h($plan['short_description'] ?? '') ?></p>
              </div>
              <span class="im-chip im-chip--exito">Publicado</span>
            </div>
            <?php if (!empty($plan['objective'])): ?><p><strong>Objetivo:</strong> <?= $h($plan['objective']) ?></p><?php endif; ?>
            <ul class="marketing-plan-card__features">
              <?php foreach (($plan['features'] ?? []) as $feature): ?>
                <li class="<?= (int) ($feature['is_highlighted'] ?? 0) === 1 ? 'marketing-plan-card__feature--highlighted' : '' ?>"><?= $h($feature['feature_name'] ?? '') ?></li>
              <?php endforeach; ?>
            </ul>
            <div class="marketing-pricing-list">
              <?php foreach (($plan['pricing_options'] ?? []) as $precio): ?>
                <form class="marketing-pricing-option <?= (int) ($precio['is_featured'] ?? 0) === 1 ? 'marketing-pricing-option--featured' : '' ?>" method="post">
                  <input type="hidden" name="marketing_action" value="subscription_request">
                  <input type="hidden" name="plan_id" value="<?= (int) ($plan['id'] ?? 0) ?>">
                  <input type="hidden" name="pricing_option_id" value="<?= (int) ($precio['id'] ?? 0) ?>">
                  <strong class="marketing-pricing-option__price">$<?= $h(number_format((float) ($precio['monthly_price'] ?? 0), 0, ',', '.')) ?>/mes</strong>
                  <span><?= (int) ($precio['duration_months'] ?? 0) ?> meses - $<?= $h(number_format((float) ($precio['total_price'] ?? 0), 0, ',', '.')) ?> total</span>
                  <?php if ((int) ($precio['is_featured'] ?? 0) === 1): ?><span class="im-chip im-chip--exito">Destacada</span><?php endif; ?>
                  <?php if ($puedeSolicitarPlan): ?>
                    <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Nota opcional', 'Comentario para que el equipo entienda el contexto de tu solicitud.') ?><input name="notes" placeholder="Contexto para el equipo"></label>
                    <button class="im-boton im-boton--principal" type="submit">Solicitar plan</button>
                    <button class="im-boton im-boton--tonal" type="button" data-marketing-view-plan="<?= marketingJson($plan) ?>"><span class="material-symbols-rounded" aria-hidden="true">visibility</span>Ver plan completo</button>
                  <?php endif; ?>
                </form>
              <?php endforeach; ?>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

</section>
