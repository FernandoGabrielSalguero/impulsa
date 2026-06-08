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

  <?php if (is_array($chatbotBuilderSelectedIntegration)): ?>
      <form method="post" enctype="multipart/form-data" class="im-formulario" data-chatbot-builder-form>
        <?php if ($chatbotBuilderPostAction !== ''): ?>
          <input type="hidden" name="chatbot_builder_action" value="<?= $h($chatbotBuilderPostAction) ?>">
        <?php endif; ?>
        <input type="hidden" name="api_integration_id" value="<?= (int) ($chatbotBuilderSelectedIntegration['id'] ?? 0) ?>">
        <input type="hidden" name="nodes_json" value="" data-chatbot-builder-nodes-json>
        <input type="hidden" name="current_avatar_path" value="<?= $h($avatarPathActual) ?>">
        <input type="hidden" name="target_status" value="">

        <article class="im-tarjeta im-campo--ancho">
          <div class="im-tarjeta__cabecera">
            <div>
              <h3>Configuracion general del chatbot</h3>
              <p>Primero define identidad, estado y mensaje de bienvenida del widget para <?= $h($chatbotBuilderSelectedIntegration['allowed_domain'] ?? '') ?>.</p>
            </div>
            <div class="im-chip-lista">
              <span class="im-chip <?= ($chatbotBuilderSelectedIntegration['integration_status'] ?? '') === 'active' ? 'im-chip--activo' : 'im-chip--alerta' ?>">
                API <?= ($chatbotBuilderSelectedIntegration['integration_status'] ?? '') === 'active' ? 'activa' : 'inactiva' ?>
              </span>
              <span class="im-chip <?= ((int) ($chatbotBuilderSelectedIntegration['disabled_by_admin'] ?? 0) === 1) ? 'im-chip--alerta' : 'im-chip--completado' ?>">
                <?= ((int) ($chatbotBuilderSelectedIntegration['disabled_by_admin'] ?? 0) === 1) ? 'Desactivado por admin' : 'Sin bloqueo admin' ?>
              </span>
            </div>
          </div>

          <div class="im-grilla im-grilla--dos-columnas">
            <div>
              <div class="im-formulario">
                <label class="im-campo im-campo-material im-campo--ancho">
                  <span>Integracion asociada</span>
                  <select name="integration_id" onchange="window.location.search='?integration_id=' + this.value;">
                    <?php if ($chatbotBuilderIntegraciones === []): ?>
                      <option value=""><?= $h('No hay integraciones disponibles') ?></option>
                    <?php else: ?>
                      <?php foreach ($chatbotBuilderIntegraciones as $chatbotBuilderIntegrationItem): ?>
                        <option value="<?= (int) ($chatbotBuilderIntegrationItem['id'] ?? 0) ?>" <?= (int) ($chatbotBuilderIntegrationItem['id'] ?? 0) === (int) ($chatbotBuilderSelectedIntegration['id'] ?? 0) ? 'selected' : '' ?>>
                          <?= $h(($chatbotBuilderIntegrationItem['project_name'] ?? '') . ' - ' . ($chatbotBuilderIntegrationItem['allowed_domain'] ?? '')) ?>
                        </option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </label>

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
                <label class="im-campo im-campo-material im-campo--ancho">
                  <span>WhatsApp de derivacion</span>
                  <input type="text" name="whatsapp" maxlength="80" value="<?= $h($chatbotBuilderChatbotActual['whatsapp'] ?? $chatbotBuilderWhatsappSugerido) ?>" required>
                </label>
                <label class="im-campo im-campo-material im-campo--ancho">
                  <span>Mensaje inicial</span>
                  <textarea name="initial_message" rows="4" maxlength="1000" required><?= $h($chatbotBuilderChatbotActual['initial_message'] ?? 'Hola, soy el asistente del sitio. Elegi una opcion para continuar.') ?></textarea>
                </label>
              </div>
            </div>

            <div>
              <div class="im-chip-lista">
                <span class="im-chip im-chip--avatar">
                  <b data-chatbot-avatar-preview>
                    <?php if ($avatarPathActual !== ''): ?>
                      <img src="<?= $h($avatarPathActual) ?>" alt="" width="24" height="24">
                    <?php else: ?>
                      <?= $h($avatarPlaceholder) ?>
                    <?php endif; ?>
                  </b>
                  Avatar del chatbot
                </span>
                <span class="im-chip" data-chatbot-avatar-filename><?= $avatarPathActual !== '' ? $h(basename($avatarPathActual)) : 'Sin imagen cargada' ?></span>
              </div>

              <label class="im-campo im-campo-material im-campo--ancho">
                <span>Actualizar avatar</span>
                <input id="chatbot-avatar-file" type="file" name="avatar_file" accept=".jpg,.jpeg,.png,.webp" data-chatbot-avatar-input>
              </label>

              <p>Formatos permitidos: JPG, PNG o WEBP. Maximo 2MB.</p>

              <div class="im-chip-lista">
                <span class="im-chip"><?= $h($chatbotBuilderChatbotActual['name'] ?? 'Sin crear') ?></span>
                <span class="im-chip"><?= $h(($chatbotBuilderChatbotActual['whatsapp'] ?? $chatbotBuilderWhatsappSugerido) ?: 'WhatsApp sin precarga') ?></span>
              </div>

              <div class="im-formulario__acciones">
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
        </article>

        <div data-chatbot-builder-nodes hidden></div>
      </form>

      <script type="application/json" data-chatbot-builder-seed><?= json_encode($chatbotBuilderInitialNodes, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
  <?php endif; ?>
</section>
