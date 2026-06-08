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
$chatbotBuilderNavItems = $chatbotBuilderNavItems ?? [];
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

  <div class="im-grilla im-grilla--dashboard">
    <article class="im-tarjeta">
      <div class="im-tarjeta__cabecera">
        <div>
          <h3>Integracion asociada</h3>
          <p>El chatbot siempre pertenece a una API/proyecto puntual.</p>
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

    <article class="im-tarjeta">
      <div class="im-tarjeta__cabecera">
        <div>
          <h3>Regla critica</h3>
          <p>Este constructor impacta directamente en la web vinculada.</p>
        </div>
      </div>
      <div class="im-alerta im-alerta--info">
        <strong>Los cambios guardados se veran automaticamente en tu web.</strong>
      </div>
      <div class="im-tabla-contenedor">
        <table class="im-tabla">
          <tbody>
            <tr><th>Chatbot actual</th><td><?= $h($chatbotBuilderChatbotActual['name'] ?? 'Sin crear') ?></td></tr>
            <tr><th>WhatsApp sugerido</th><td><?= $h($chatbotBuilderWhatsappSugerido !== '' ? $chatbotBuilderWhatsappSugerido : 'Sin dato cargado') ?></td></tr>
            <tr><th>Widget</th><td>Botones, respuestas y navegacion sin texto libre</td></tr>
          </tbody>
        </table>
      </div>
    </article>
  </div>

  <?php if (is_array($chatbotBuilderSelectedIntegration)): ?>
    <article class="im-tarjeta">
      <div class="im-tarjeta__cabecera">
        <div>
          <h3>Constructor compartido</h3>
          <p>Configura el widget del dominio <?= $h($chatbotBuilderSelectedIntegration['allowed_domain'] ?? '') ?>.</p>
        </div>
      </div>

      <form method="post" class="im-formulario" data-chatbot-builder-form>
        <?php if ($chatbotBuilderPostAction !== ''): ?>
          <input type="hidden" name="chatbot_builder_action" value="<?= $h($chatbotBuilderPostAction) ?>">
        <?php endif; ?>
        <input type="hidden" name="api_integration_id" value="<?= (int) ($chatbotBuilderSelectedIntegration['id'] ?? 0) ?>">
        <input type="hidden" name="nodes_json" value="" data-chatbot-builder-nodes-json>

        <div class="im-grilla im-grilla--dos-columnas">
          <label class="im-campo im-campo-material">
            <span>Nombre del chatbot</span>
            <input type="text" name="name" maxlength="180" value="<?= $h($chatbotBuilderChatbotActual['name'] ?? ('Chatbot ' . ($chatbotBuilderSelectedIntegration['project_name'] ?? ''))) ?>" required>
          </label>
          <label class="im-campo im-campo-material">
            <span>Avatar URL</span>
            <input type="text" name="avatar_url" maxlength="255" value="<?= $h($chatbotBuilderChatbotActual['avatar_url'] ?? '') ?>" placeholder="https://...">
          </label>
          <label class="im-campo im-campo-material">
            <span>WhatsApp de derivacion</span>
            <input type="text" name="whatsapp" maxlength="80" value="<?= $h($chatbotBuilderChatbotActual['whatsapp'] ?? $chatbotBuilderWhatsappSugerido) ?>" required>
          </label>
          <label class="im-campo im-campo-material">
            <span>Estado</span>
            <select name="status">
              <option value="active" <?= ($chatbotBuilderChatbotActual['status'] ?? 'inactive') === 'active' ? 'selected' : '' ?>>Activo</option>
              <option value="inactive" <?= ($chatbotBuilderChatbotActual['status'] ?? 'inactive') !== 'active' ? 'selected' : '' ?>>Inactivo</option>
            </select>
          </label>
          <label class="im-campo im-campo-material im-campo--ancho">
            <span>Mensaje inicial</span>
            <textarea name="initial_message" rows="3" maxlength="1000" required><?= $h($chatbotBuilderChatbotActual['initial_message'] ?? 'Hola, soy el asistente del sitio. Elegi una opcion para continuar.') ?></textarea>
          </label>
        </div>

        <div class="im-tarjeta" style="margin-top:1rem">
          <div class="im-tarjeta__cabecera">
            <div>
              <h3>Nodos y opciones</h3>
              <p>Cada nodo necesita al menos una opcion. Acciones disponibles: ir a nodo, WhatsApp, reinicio y cierre.</p>
            </div>
            <button class="im-boton im-boton--tonal" type="button" data-chatbot-builder-add-node>Agregar nodo</button>
          </div>
          <div data-chatbot-builder-nodes></div>
        </div>

        <div class="im-formulario__acciones" style="margin-top:1rem">
          <button class="im-boton im-boton--principal" type="submit" name="chatbot_builder_submit" value="save">Guardar chatbot</button>
        </div>
      </form>

      <?php if ($chatbotBuilderChatbotActual): ?>
        <form method="post" class="im-formulario__acciones" style="margin-top:1rem">
          <?php if ($chatbotBuilderPostAction !== ''): ?>
            <input type="hidden" name="chatbot_builder_action" value="<?= $h($chatbotBuilderPostAction) ?>">
          <?php endif; ?>
          <input type="hidden" name="api_integration_id" value="<?= (int) ($chatbotBuilderSelectedIntegration['id'] ?? 0) ?>">
          <input type="hidden" name="target_status" value="<?= ($chatbotBuilderChatbotActual['status'] ?? 'inactive') === 'active' ? 'inactive' : 'active' ?>">
          <button class="im-boton" type="submit" name="chatbot_builder_submit" value="toggle">
            <?= ($chatbotBuilderChatbotActual['status'] ?? 'inactive') === 'active' ? 'Desactivar chatbot' : 'Activar chatbot' ?>
          </button>
        </form>
      <?php endif; ?>
    </article>

    <script type="application/json" data-chatbot-builder-seed><?= json_encode($chatbotBuilderInitialNodes, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
  <?php endif; ?>
</section>
