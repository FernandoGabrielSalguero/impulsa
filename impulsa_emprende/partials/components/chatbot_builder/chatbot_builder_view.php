<?php

declare(strict_types=1);

$chatbotBuilderFlash = $chatbotBuilderFlash ?? null;
$chatbotBuilderIntegraciones = $chatbotBuilderIntegraciones ?? [];
$chatbotBuilderSelectedIntegration = $chatbotBuilderSelectedIntegration ?? null;
$chatbotBuilderChatbotActual = $chatbotBuilderChatbotActual ?? null;
$chatbotBuilderWhatsappSugerido = $chatbotBuilderWhatsappSugerido ?? '';
$chatbotBuilderRoleLabel = $chatbotBuilderRoleLabel ?? 'Usuario';
$chatbotBuilderPageTitle = $chatbotBuilderPageTitle ?? 'Chatbot';
$chatbotBuilderPageDescription = $chatbotBuilderPageDescription ?? '';
$chatbotBuilderBackHref = $chatbotBuilderBackHref ?? '#';
$chatbotBuilderBackLabel = $chatbotBuilderBackLabel ?? 'Volver';
$chatbotBuilderPostAction = $chatbotBuilderPostAction ?? '';
$chatbotBuilderInitialNodes = [];

if (is_array($chatbotBuilderChatbotActual['nodes'] ?? null) && $chatbotBuilderChatbotActual['nodes'] !== []) {
    foreach ($chatbotBuilderChatbotActual['nodes'] as $node) {
        $clientKey = 'node-' . (int) ($node['id'] ?? 0);
        $chatbotBuilderInitialNodes[] = [
            'client_key' => $clientKey,
            'title' => (string) ($node['title'] ?? ''),
            'body' => (string) ($node['body'] ?? ''),
            'sort_order' => (int) ($node['sort_order'] ?? 1),
            'status' => (string) ($node['status'] ?? 'active'),
            'is_start' => (int) ($node['is_start'] ?? 0) === 1,
            'options' => array_map(
                static function (array $option): array {
                    return [
                        'label' => (string) ($option['label'] ?? ''),
                        'action_type' => (string) ($option['action_type'] ?? 'go_to_node'),
                        'target_node_key' => isset($option['target_node_id']) && (int) $option['target_node_id'] > 0 ? 'node-' . (int) $option['target_node_id'] : '',
                        'sort_order' => (int) ($option['sort_order'] ?? 1),
                    ];
                },
                is_array($node['options'] ?? null) ? $node['options'] : []
            ),
        ];
    }
}

if ($chatbotBuilderInitialNodes === []) {
    $chatbotBuilderInitialNodes[] = [
        'client_key' => 'node-1',
        'title' => 'Inicio',
        'body' => 'Bienvenido. Elegi una opcion para continuar.',
        'sort_order' => 1,
        'status' => 'active',
        'is_start' => true,
        'options' => [
            [
                'label' => 'Hablar por WhatsApp',
                'action_type' => 'whatsapp',
                'target_node_key' => '',
                'sort_order' => 1,
            ],
        ],
    ];
}

$avatarPathActual = (string) ($chatbotBuilderChatbotActual['avatar_url'] ?? '');
$avatarPlaceholder = function_exists('mb_substr')
    ? mb_strtoupper(mb_substr((string) ($chatbotBuilderChatbotActual['name'] ?? 'C'), 0, 1, 'UTF-8'), 'UTF-8')
    : strtoupper(substr((string) ($chatbotBuilderChatbotActual['name'] ?? 'C'), 0, 1));
$h = static fn (mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
?>
<style>
  .chatbot-builder { display: grid; gap: 1rem; }
  .chatbot-builder .im-campo input,
  .chatbot-builder .im-campo select { min-height: 48px; }
  .chatbot-builder .im-campo textarea { min-height: 88px; resize: vertical; }
  .chatbot-builder__hero-grid { display: grid; gap: 1rem; grid-template-columns: 1.15fr .85fr; }
  .chatbot-builder__stack { display: grid; gap: 1rem; }
  .chatbot-builder__config-card { gap: 1.1rem; }
  .chatbot-builder__config-grid { display: grid; gap: .9rem 1rem; grid-template-columns: minmax(220px, 1.5fr) minmax(160px, .7fr) minmax(280px, 1.25fr) minmax(220px, 1fr); align-items: start; }
  .chatbot-builder__full { grid-column: 1 / -1; }
  .chatbot-builder__action-bar { display: flex; flex-wrap: wrap; gap: .75rem; justify-content: space-between; align-items: center; }
  .chatbot-builder__action-group { display: flex; flex-wrap: wrap; gap: .75rem; }
  .chatbot-builder__avatar-box { display: grid; grid-template-columns: 72px 1fr; gap: .85rem; align-items: center; min-height: 48px; padding: .9rem; border: 1px solid var(--im-color-borde); border-radius: var(--im-radio); background: var(--im-color-superficie); }
  .chatbot-builder__avatar-preview { width: 72px; height: 72px; border-radius: 20px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: color-mix(in srgb, var(--im-color-principal) 12%, white); color: var(--im-color-principal); font-size: 1.45rem; font-weight: 800; }
  .chatbot-builder__avatar-preview img { width: 100%; height: 100%; object-fit: cover; }
  .chatbot-builder__avatar-actions { display: grid; gap: .35rem; }
  .chatbot-builder__avatar-help { margin: 0; color: var(--im-color-texto-suave); font-size: .84rem; }
  .chatbot-builder__file-input { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); border: 0; }
  .chatbot-builder__layout-grid { display: grid; gap: 1rem; grid-template-columns: repeat(4, minmax(0, 1fr)); align-items: start; }
  .chatbot-builder__layout-grid > .chatbot-builder__flow-card { grid-column: span 3; }
  .chatbot-builder__layout-grid > .chatbot-builder__preview { grid-column: span 1; }
  .chatbot-builder__flow-card { display: grid; gap: 1rem; }
  .chatbot-builder__questions { display: grid; gap: .9rem; }
  .chatbot-builder__question { border: 1px solid var(--im-color-borde); border-radius: var(--im-radio); background: var(--im-color-superficie); box-shadow: var(--im-sombra-1); overflow: hidden; }
  .chatbot-builder__question[open] { box-shadow: var(--im-sombra-2); }
  .chatbot-builder__summary { display: flex; align-items: center; gap: .85rem; justify-content: space-between; padding: 1rem 1.1rem; cursor: pointer; list-style: none; }
  .chatbot-builder__summary::-webkit-details-marker { display: none; }
  .chatbot-builder__summary-main { display: grid; gap: .2rem; min-width: 0; }
  .chatbot-builder__summary-title { font-weight: 700; color: var(--im-color-texto); }
  .chatbot-builder__summary-meta { display: flex; flex-wrap: wrap; gap: .45rem; align-items: center; color: var(--im-color-texto-suave); font-size: .88rem; }
  .chatbot-builder__summary-actions { display: flex; gap: .5rem; align-items: center; }
  .chatbot-builder__question-body { display: grid; gap: 1rem; padding: 0 1.1rem 1.1rem; border-top: 1px solid var(--im-color-borde); }
  .chatbot-builder__question-grid { display: grid; gap: .85rem; grid-template-columns: 1.2fr .8fr; }
  .chatbot-builder__toggle-row { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: .75rem; }
  .chatbot-builder__option-list { display: grid; gap: .75rem; }
  .chatbot-builder__option-row { display: grid; gap: .7rem; grid-template-columns: minmax(180px, 1.3fr) minmax(170px, .95fr) minmax(170px, .95fr) auto; align-items: end; padding: .9rem; border: 1px solid var(--im-color-borde); border-radius: 18px; background: color-mix(in srgb, var(--im-color-superficie) 92%, var(--im-color-principal-suave)); }
  .chatbot-builder__option-row--disabled-destination { grid-template-columns: minmax(180px, 1.3fr) minmax(170px, .95fr) auto; }
  .chatbot-builder__option-destination--hidden { display: none; }
  .chatbot-builder__sticky-actions { position: sticky; bottom: .75rem; z-index: 4; display: flex; justify-content: flex-end; margin-top: .25rem; }
  .chatbot-builder__sticky-actions .im-tarjeta { padding: .8rem 1rem; }
  .chatbot-builder__preview { display: grid; gap: .85rem; position: sticky; top: 1rem; }
  .chatbot-builder__phone { width: min(320px, 100%); margin: 0 auto; border-radius: 28px; border: 1px solid var(--im-color-borde); background: #f8fbff; overflow: hidden; box-shadow: var(--im-sombra-1); }
  .chatbot-builder__phone-head { display: flex; align-items: center; gap: .75rem; padding: 1rem; background: linear-gradient(135deg, var(--im-color-principal), var(--im-color-secundario)); color: white; }
  .chatbot-builder__phone-body { display: grid; gap: .75rem; padding: 1rem; }
  .chatbot-builder__bubble { padding: .85rem .95rem; border-radius: 18px; background: white; border: 1px solid var(--im-color-borde); }
  .chatbot-builder__preview-options { display: grid; gap: .55rem; }
  .chatbot-builder__preview-button { padding: .72rem .85rem; border-radius: 14px; background: color-mix(in srgb, var(--im-color-secundario) 22%, white); color: var(--im-color-principal); font-weight: 600; border: 1px solid color-mix(in srgb, var(--im-color-secundario) 30%, var(--im-color-borde)); }
  @media (max-width: 980px) {
    .chatbot-builder__hero-grid,
    .chatbot-builder__config-grid,
    .chatbot-builder__layout-grid,
    .chatbot-builder__question-grid,
    .chatbot-builder__option-row,
    .chatbot-builder__option-row--disabled-destination { grid-template-columns: 1fr; }
    .chatbot-builder__layout-grid > .chatbot-builder__flow-card,
    .chatbot-builder__layout-grid > .chatbot-builder__preview { grid-column: auto; }
    .chatbot-builder__preview { position: static; }
  }
</style>
<section class="im-seccion-documento activa" id="chatbot-builder" data-panel="chatbot-builder">
  <div class="im-encabezado-seccion">
    <div>
      <p class="im-sobrelinea"><?= $h($chatbotBuilderRoleLabel) ?></p>
      <h2><?= $h($chatbotBuilderPageTitle) ?></h2>
      <?php if ($chatbotBuilderPageDescription !== ''): ?>
        <p><?= $h($chatbotBuilderPageDescription) ?></p>
      <?php endif; ?>
    </div>
    <a class="im-boton im-boton--texto" href="<?= $h($chatbotBuilderBackHref) ?>"><?= $h($chatbotBuilderBackLabel) ?></a>
  </div>

  <?php if (is_array($chatbotBuilderFlash) && trim((string) ($chatbotBuilderFlash['mensaje'] ?? '')) !== ''): ?>
    <div class="im-alerta <?= ($chatbotBuilderFlash['estado'] ?? '') === 'error' ? 'im-alerta--info' : 'im-alerta--exito' ?>">
      <?= $h($chatbotBuilderFlash['mensaje'] ?? '') ?>
    </div>
  <?php endif; ?>

  <div class="chatbot-builder">
    <div class="chatbot-builder__hero-grid">
      <article class="im-tarjeta chatbot-builder__stack">
        <div class="im-tarjeta__cabecera">
          <div>
            <h3>Integracion asociada</h3>
            <p>El chatbot siempre pertenece a una API o proyecto puntual.</p>
          </div>
        </div>
        <?php if ($chatbotBuilderIntegraciones === []): ?>
          <div class="im-alerta im-alerta--info">No hay integraciones API asociadas a tu cuenta todavia.</div>
        <?php else: ?>
          <form method="get" class="im-formulario">
            <label class="im-campo im-campo-material">
              <span>Integracion</span>
              <select name="integration_id" onchange="this.form.submit()">
                <?php foreach ($chatbotBuilderIntegraciones as $chatbotBuilderIntegrationItem): ?>
                  <option value="<?= (int) ($chatbotBuilderIntegrationItem['id'] ?? 0) ?>" <?= (int) ($chatbotBuilderIntegrationItem['id'] ?? 0) === (int) ($chatbotBuilderSelectedIntegration['id'] ?? 0) ? 'selected' : '' ?>>
                    <?= $h(($chatbotBuilderIntegrationItem['project_name'] ?? '') . ' · ' . ($chatbotBuilderIntegrationItem['allowed_domain'] ?? '')) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </label>
          </form>
          <?php if (is_array($chatbotBuilderSelectedIntegration)): ?>
            <div class="im-chip-lista">
              <span class="im-chip <?= ($chatbotBuilderSelectedIntegration['integration_status'] ?? '') === 'active' ? 'im-chip--activo' : 'im-chip--alerta' ?>">
                API <?= ($chatbotBuilderSelectedIntegration['integration_status'] ?? '') === 'active' ? 'activa' : 'inactiva' ?>
              </span>
              <span class="im-chip <?= ((int) ($chatbotBuilderSelectedIntegration['disabled_by_admin'] ?? 0) === 1) ? 'im-chip--alerta' : 'im-chip--completado' ?>">
                <?= ((int) ($chatbotBuilderSelectedIntegration['disabled_by_admin'] ?? 0) === 1) ? 'Desactivado por admin' : 'Sin bloqueo admin' ?>
              </span>
              <span class="im-chip"><?= $h($chatbotBuilderSelectedIntegration['allowed_domain'] ?? '') ?></span>
            </div>
          <?php endif; ?>
        <?php endif; ?>
      </article>

      <article class="im-tarjeta chatbot-builder__stack">
        <div class="im-tarjeta__cabecera">
          <div>
            <h3>Impacto inmediato</h3>
            <p>Los cambios que guardes se publican sobre el sitio vinculado.</p>
          </div>
        </div>
        <div class="im-alerta im-alerta--info">
          <strong>Los cambios guardados se veran automaticamente en tu web.</strong>
        </div>
        <div class="im-chip-lista">
          <span class="im-chip"><?= $h($chatbotBuilderChatbotActual['name'] ?? 'Sin crear') ?></span>
          <span class="im-chip"><?= $h($chatbotBuilderWhatsappSugerido !== '' ? $chatbotBuilderWhatsappSugerido : 'WhatsApp sin precarga') ?></span>
          <span class="im-chip">FAQ navegable sin texto libre</span>
        </div>
      </article>
    </div>

    <?php if (is_array($chatbotBuilderSelectedIntegration)): ?>
      <form method="post" enctype="multipart/form-data" class="im-formulario chatbot-builder__stack" data-chatbot-builder-form>
        <?php if ($chatbotBuilderPostAction !== ''): ?>
          <input type="hidden" name="chatbot_builder_action" value="<?= $h($chatbotBuilderPostAction) ?>">
        <?php endif; ?>
        <input type="hidden" name="api_integration_id" value="<?= (int) ($chatbotBuilderSelectedIntegration['id'] ?? 0) ?>">
        <input type="hidden" name="nodes_json" value="" data-chatbot-builder-nodes-json>
        <input type="hidden" name="current_avatar_path" value="<?= $h($avatarPathActual) ?>">
        <input type="hidden" name="target_status" value="">

        <article class="im-tarjeta chatbot-builder__stack chatbot-builder__config-card">
          <div class="chatbot-builder__action-bar">
            <div>
              <h3>Configuracion general del chatbot</h3>
              <p>Primero define identidad, estado y mensaje de bienvenida del widget.</p>
            </div>
            <div class="chatbot-builder__action-group">
              <button class="im-boton im-boton--principal" type="submit" name="chatbot_builder_submit" value="save">Guardar chatbot</button>
            </div>
          </div>

          <div class="chatbot-builder__config-grid">
            <label class="im-campo im-campo-material">
              <span>Nombre del chatbot</span>
              <input type="text" name="name" maxlength="180" value="<?= $h($chatbotBuilderChatbotActual['name'] ?? ('Chatbot ' . ($chatbotBuilderSelectedIntegration['project_name'] ?? ''))) ?>" required>
            </label>
            <label class="im-campo im-campo-material">
              <span>Estado</span>
              <select name="status">
                <option value="active" <?= ($chatbotBuilderChatbotActual['status'] ?? 'inactive') === 'active' ? 'selected' : '' ?>>Activo</option>
                <option value="inactive" <?= ($chatbotBuilderChatbotActual['status'] ?? 'inactive') !== 'active' ? 'selected' : '' ?>>Inactivo</option>
              </select>
            </label>

            <div class="chatbot-builder__avatar-box">
              <div class="chatbot-builder__avatar-preview" data-chatbot-avatar-preview>
                <?php if ($avatarPathActual !== ''): ?>
                  <img src="<?= $h($avatarPathActual) ?>" alt="">
                <?php else: ?>
                  <?= $h($avatarPlaceholder) ?>
                <?php endif; ?>
              </div>
              <div class="chatbot-builder__avatar-actions">
                <strong>Avatar del chatbot</strong>
                <label class="im-boton im-boton--tonal" for="chatbot-avatar-file">Cambiar avatar</label>
                <input class="chatbot-builder__file-input" id="chatbot-avatar-file" type="file" name="avatar_file" accept=".jpg,.jpeg,.png,.webp" data-chatbot-avatar-input>
                <span data-chatbot-avatar-filename><?= $avatarPathActual !== '' ? $h(basename($avatarPathActual)) : 'Sin imagen cargada' ?></span>
                <p class="chatbot-builder__avatar-help">Formatos permitidos: JPG, PNG o WEBP. Maximo 2MB.</p>
              </div>
            </div>

            <label class="im-campo im-campo-material">
              <span>WhatsApp de derivacion</span>
              <input type="text" name="whatsapp" maxlength="80" value="<?= $h($chatbotBuilderChatbotActual['whatsapp'] ?? $chatbotBuilderWhatsappSugerido) ?>" required>
            </label>

            <label class="im-campo im-campo-material chatbot-builder__full">
              <span>Mensaje inicial</span>
              <textarea name="initial_message" rows="3" maxlength="1000" required><?= $h($chatbotBuilderChatbotActual['initial_message'] ?? 'Hola, soy el asistente del sitio. Elegi una opcion para continuar.') ?></textarea>
            </label>
          </div>
        </article>

        <div class="chatbot-builder__layout-grid">
          <article class="im-tarjeta chatbot-builder__flow-card">
            <div class="chatbot-builder__action-bar">
              <div>
                <h3>Flujo de preguntas y respuestas</h3>
                <p>Cada pregunta muestra una respuesta y luego ofrece opciones para continuar la navegacion.</p>
              </div>
              <div class="chatbot-builder__action-group">
                <button class="im-boton im-boton--tonal" type="button" data-chatbot-builder-add-node>Agregar pregunta</button>
              </div>
            </div>
            <div class="chatbot-builder__questions" data-chatbot-builder-nodes></div>
          </article>

          <article class="im-tarjeta chatbot-builder__preview">
            <div class="im-tarjeta__cabecera">
              <div>
                <h3>Vista previa</h3>
                <p>Referencia rapida de como se vera la primera pantalla del widget.</p>
              </div>
            </div>
            <div class="chatbot-builder__phone">
              <div class="chatbot-builder__phone-head">
                <div class="chatbot-builder__avatar-preview" data-chatbot-preview-avatar>
                  <?php if ($avatarPathActual !== ''): ?>
                    <img src="<?= $h($avatarPathActual) ?>" alt="">
                  <?php else: ?>
                    <?= $h($avatarPlaceholder) ?>
                  <?php endif; ?>
                </div>
                <div>
                  <strong data-chatbot-preview-name><?= $h($chatbotBuilderChatbotActual['name'] ?? ('Chatbot ' . ($chatbotBuilderSelectedIntegration['project_name'] ?? ''))) ?></strong>
                  <div>FAQ navegable</div>
                </div>
              </div>
              <div class="chatbot-builder__phone-body">
                <div class="chatbot-builder__bubble" data-chatbot-preview-message><?= $h($chatbotBuilderChatbotActual['initial_message'] ?? 'Hola, soy el asistente del sitio. Elegi una opcion para continuar.') ?></div>
                <div class="chatbot-builder__bubble" data-chatbot-preview-question>Inicio</div>
                <div class="chatbot-builder__preview-options" data-chatbot-preview-options></div>
              </div>
            </div>
          </article>
        </div>

        <div class="chatbot-builder__sticky-actions">
          <div class="im-tarjeta">
            <div class="chatbot-builder__action-group">
              <?php if ($chatbotBuilderChatbotActual): ?>
                <button
                  class="im-boton"
                  type="submit"
                  name="chatbot_builder_submit"
                  value="toggle"
                  formnovalidate
                  onclick="this.form.querySelector('[name=target_status]').value='<?= ($chatbotBuilderChatbotActual['status'] ?? 'inactive') === 'active' ? 'inactive' : 'active' ?>';"
                >
                  <?= ($chatbotBuilderChatbotActual['status'] ?? 'inactive') === 'active' ? 'Desactivar chatbot' : 'Activar chatbot' ?>
                </button>
              <?php endif; ?>
              <button class="im-boton im-boton--principal" type="submit" name="chatbot_builder_submit" value="save">Guardar cambios</button>
            </div>
          </div>
        </div>
      </form>

      <script type="application/json" data-chatbot-builder-seed><?= json_encode($chatbotBuilderInitialNodes, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
    <?php endif; ?>
  </div>
</section>
