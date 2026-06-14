<?php
$h = $h ?? static fn (mixed $valor): string => htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
$planes = $marketingPlanesPublicados ?? [];
?>
<section class="marketing-dashboard-grid">
  <div class="im-encabezado-seccion">
    <div>
      <p class="im-sobrelinea">Planes</p>
      <h2>Elegir plan de marketing</h2>
      <p>Planes publicados disponibles para solicitar seguimiento del equipo Impulsa.</p>
    </div>
  </div>

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
              <li><?= $h($feature['feature_name'] ?? '') ?></li>
            <?php endforeach; ?>
          </ul>
          <div class="marketing-pricing-list">
            <?php foreach (($plan['pricing_options'] ?? []) as $precio): ?>
              <form class="marketing-pricing-option" method="post">
                <input type="hidden" name="marketing_action" value="subscription_request">
                <input type="hidden" name="plan_id" value="<?= (int) ($plan['id'] ?? 0) ?>">
                <input type="hidden" name="pricing_option_id" value="<?= (int) ($precio['id'] ?? 0) ?>">
                <strong class="marketing-pricing-option__price"><?= $h(marketingFormatoMoneda($precio['monthly_price'] ?? 0, $precio['currency'] ?? 'ARS')) ?>/mes</strong>
                <span><?= (int) ($precio['duration_months'] ?? 0) ?> meses · <?= $h(marketingFormatoMoneda($precio['total_price'] ?? 0, $precio['currency'] ?? 'ARS')) ?> total</span>
                <?php if (marketingUsuarioPuedeVerCliente($usuario['rol'] ?? null)): ?>
                  <label class="im-campo im-campo-material"><?= marketingAyudaCampo('Nota opcional', 'Comentario para que el equipo entienda el contexto de tu solicitud.') ?><input name="notes" placeholder="Contexto para el equipo"></label>
                  <button class="im-boton im-boton--principal" type="submit">Solicitar plan</button>
                <?php endif; ?>
              </form>
            <?php endforeach; ?>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
