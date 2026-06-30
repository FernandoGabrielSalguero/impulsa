<?php
$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Usuario';
$integraciones = $integraciones ?? [];
$usuariosPropietarios = $usuariosPropietarios ?? [];
$opcionesProyectoSitio = $opcionesProyectoSitio ?? [];
$flashIntegraciones = $flashIntegraciones ?? null;
$appBaseUrl = $appBaseUrl ?? '';
$totalIntegraciones = count($integraciones);
$adminActiveMenu = 'api';
$h = static fn (mixed $valor): string => htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
$formatearFecha = static function (?string $fecha): string {
    if (!$fecha) {
        return '-';
    }

    return date('d/m/Y H:i', strtotime($fecha));
};
$estadoChip = static function (string $estado): string {
    return $estado === 'active' ? 'im-chip--completado' : 'im-chip--alerta';
};
$estadoTexto = static function (string $estado): string {
    return $estado === 'active' ? 'Activa' : 'Inactiva';
};
$resolverDueno = static function (array $integracion): array {
    $nombre = trim((string) ($integracion['owner_nombre'] ?? '') . ' ' . (string) ($integracion['owner_apellido'] ?? ''));
    $apodo = trim((string) ($integracion['owner_apodo'] ?? ''));
    $correoContacto = trim((string) ($integracion['owner_contacto_correo'] ?? ''));
    $correoAuth = trim((string) ($integracion['owner_auth_correo'] ?? ''));
    $correo = $correoContacto !== '' ? $correoContacto : $correoAuth;

    if ($nombre === '') {
        $nombre = $apodo !== '' ? $apodo : ($correo !== '' ? $correo : 'Sin dueno');
    }

    return [
        'name' => $nombre,
        'email' => $correo,
    ];
};
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Integraciones API Admin</title>
  <link rel="icon" href="<?= htmlspecialchars(obtenerFaviconHref(), ENT_QUOTES, 'UTF-8'); ?>" type="image/x-icon">
  <?= renderImpulsaMaterialFonts() ?>
  <link rel="stylesheet" href="<?= htmlspecialchars(obtenerImpulsaMaterialCssHref(), ENT_QUOTES, 'UTF-8'); ?>">
  <style>
    .im-marca__isotipo img { width: 100%; height: 100%; border-radius: inherit; object-fit: cover; }
    .im-accion-salir { color: #ba1a1a; }
    .im-bottom-sheet--perfil { max-width: 860px; max-height: min(760px, calc(100vh - 2rem)); overflow: auto; }
    .im-nav-item__icono[data-icon]::before { content: attr(data-icon); }
    .im-alerta--ok { background: color-mix(in srgb, var(--im-color-exito) 14%, var(--im-color-superficie)); color: var(--im-color-exito); }
    .im-alerta--error { background: #fdecec; color: #ba1a1a; }
    .im-api-grid { display: grid; gap: 1rem; }
    .im-api-form { display: grid; grid-template-columns: repeat(2, minmax(220px, 1fr)); gap: .85rem; }
    .im-api-form .im-campo--full,
    .im-api-form__acciones { grid-column: 1 / -1; }
    .im-api-ayuda { margin: -.15rem 0 0; color: var(--im-color-texto-suave); font-size: .82rem; }
    .im-api-lista { display: grid; gap: 1rem; }
    .im-api-card { display: grid; gap: 1rem; }
    .im-api-card__cabecera,
    .im-api-card__meta,
    .im-api-card__acciones { display: flex; flex-wrap: wrap; gap: .75rem; justify-content: space-between; align-items: flex-start; }
    .im-api-card__meta { color: var(--im-color-texto-suave); }
    .im-api-tabla-modal { width: min(1080px, calc(100vw - 2rem)); max-height: min(860px, calc(100vh - 2rem)); grid-template-rows: auto minmax(0, 1fr) auto; }
    .im-api-tabla-modal .im-dialog__contenido { min-height: 0; overflow: auto; display: grid; gap: 1rem; }
    .im-api-tabla-modal__grid { display: grid; grid-template-columns: repeat(2, minmax(240px, 1fr)); gap: .85rem; }
    .im-api-dato { padding: .85rem; border: 1px solid var(--im-color-borde); border-radius: var(--im-radio-chico); background: var(--im-color-superficie); }
    .im-api-dato span { display: block; color: var(--im-color-texto-suave); font-size: .82rem; }
    .im-api-dato strong,
    .im-api-dato code { display: block; margin-top: .25rem; word-break: break-all; }
    .im-api-card__metricas { display: grid; grid-template-columns: repeat(4, minmax(120px, 1fr)); gap: .75rem; }
    .im-api-metrica { padding: .85rem; border: 1px solid var(--im-color-borde); border-radius: var(--im-radio-chico); background: var(--im-color-superficie); }
    .im-api-metrica span { display: block; color: var(--im-color-texto-suave); font-size: .82rem; }
    .im-api-metrica strong { display: block; margin-top: .25rem; font-size: 1.2rem; }
    .im-api-layout { display: grid; grid-template-columns: minmax(0, 1.2fr) minmax(300px, .8fr); gap: 1rem; align-items: start; }
    .im-api-panel { display: grid; gap: .85rem; padding: 1rem; border: 1px solid var(--im-color-borde); border-radius: var(--im-radio-mediano); background: var(--im-color-superficie); }
    .im-api-panel__cabecera { display: flex; flex-wrap: wrap; gap: .75rem; justify-content: space-between; align-items: flex-start; }
    .im-api-panel__cabecera p,
    .im-api-panel__intro,
    .im-api-doc__texto,
    .im-api-doc__uso,
    .im-api-doc__consideracion { margin: 0; color: var(--im-color-texto-suave); font-size: .85rem; line-height: 1.5; }
    .im-api-panel pre { margin: 0; padding: 1rem; overflow: auto; border-radius: var(--im-radio-chico); background: #111827; color: #f9fafb; font-size: .85rem; min-height: 420px; }
    .im-api-docs { display: grid; gap: .85rem; max-height: 100%; overflow: auto; }
    .im-api-doc { display: grid; gap: .75rem; padding: .9rem; border: 1px solid var(--im-color-borde); border-radius: var(--im-radio-chico); background: color-mix(in srgb, var(--im-color-superficie) 94%, var(--im-color-primario) 6%); }
    .im-api-doc__lista { display: grid; gap: .5rem; }
    .im-api-doc__item { display: grid; gap: .15rem; }
    .im-api-doc__item strong { font-size: .92rem; }
    .im-api-doc__item span { color: var(--im-color-texto-suave); font-size: .82rem; line-height: 1.45; }
    .im-api-secret { word-break: break-all; }
    .im-api-secret--protegida { color: var(--im-color-texto-suave); font-size: .9rem; }
    .im-api-copy-linea { display: flex; align-items: center; gap: .5rem; min-width: 0; }
    .im-api-copy-linea code { flex: 1; min-width: 0; overflow-wrap: anywhere; }
    .im-api-inline-form { display: contents; }
    .im-api-form-secundario { display: grid; gap: .85rem; padding-top: .75rem; border-top: 1px solid var(--im-color-borde); }
    .im-api-form-secundario__acciones { display: flex; flex-wrap: wrap; gap: .5rem; }
    @media (max-width: 900px) {
      .im-api-form,
      .im-api-card__metricas,
      .im-api-layout,
      .im-api-tabla-modal__grid { grid-template-columns: 1fr; }
      .im-api-panel pre { min-height: 320px; }
      .im-api-docs { max-height: none; overflow: visible; }
    }
  </style>
</head>
<body>
  <div class="im-aplicacion" data-menu-colapsado="false">
    <?php require __DIR__ . '/adminMenu.php'; ?>
    <div class="im-cortina" data-cerrar-menu></div>
    <div class="im-contenedor">
      <header class="im-barra-superior">
        <div class="im-barra-superior__grupo">
          <button class="im-boton-icono" type="button" data-alternar-menu aria-label="Menu"></button>
          <div>
            <p class="im-sobrelinea">Impulsa</p>
            <h1>Integraciones API</h1>
          </div>
        </div>
        <div class="im-barra-superior__acciones">
          <button class="im-boton-icono im-boton-icono--principal im-tooltip" type="button" data-abrir-config-tema aria-label="Configurar temas" data-tooltip="Configurar estilos"></button>
          <button class="im-boton-icono material-symbols-rounded im-tooltip" type="button" data-abrir-perfil aria-label="Mi perfil" data-tooltip="Mi perfil">account_circle</button>
          <a class="im-boton-icono material-symbols-rounded im-tooltip im-accion-salir" href="/auth/logout.php" aria-label="Salir" data-tooltip="Salir">logout</a>
        </div>
      </header>
      <main class="im-contenido">
        <section class="im-seccion-documento activa" id="integraciones-api">
          <div class="im-encabezado-seccion">
            <div>
              <p class="im-sobrelinea">Administracion</p>
              <h2>Configuracion de APIs externas</h2>
              <p>Administra claves publicas por sitio, dominios autorizados y el seguimiento de visitas y formularios externos.</p>
            </div>
            <span class="im-chip"><?= number_format($totalIntegraciones, 0, ',', '.') ?> integraciones</span>
          </div>

          <div class="im-api-grid">
            <?php if (is_array($flashIntegraciones) && trim((string) ($flashIntegraciones['mensaje'] ?? '')) !== ''): ?>
              <div class="im-alerta im-alerta--<?= $h(($flashIntegraciones['estado'] ?? 'ok') === 'error' ? 'error' : 'ok') ?>" role="status">
                <strong><?= $h($flashIntegraciones['mensaje'] ?? '') ?></strong>
                <?php if (!empty($flashIntegraciones['public_key'])): ?>
                  <div>Clave publica nueva: <code><?= $h($flashIntegraciones['public_key']) ?></code></div>
                <?php endif; ?>
                <?php if (!empty($flashIntegraciones['secret_key'])): ?>
                  <div class="im-api-secret">Clave secreta generada una sola vez: <code><?= $h($flashIntegraciones['secret_key']) ?></code></div>
                <?php endif; ?>
              </div>
            <?php endif; ?>

            <article class="im-tarjeta">
              <div class="im-tarjeta__cabecera">
                <div>
                  <h3>Nueva integracion</h3>
                  <p>Crea una clave publica para un dominio externo y una clave secreta opcional para backend.</p>
                </div>
              </div>
              <form method="post" class="im-api-form">
                <input type="hidden" name="api_integration_action" value="create">
                <label class="im-campo im-campo-material" data-im-campo="generico">
                  <span>Proyecto o sitio</span>
                  <input type="text" name="project_name" maxlength="180" list="api-project-options" autocomplete="off" required>
                </label>
                <label class="im-campo im-campo-material" data-im-campo="generico">
                  <span>Dominio autorizado</span>
                  <input type="text" name="allowed_domain" placeholder="https://mi-landing.com" required>
                </label>
                <label class="im-campo im-campo-material" data-im-campo="generico">
                  <span>Dueno de la integracion</span>
                  <select name="owner_user_auth_id" required>
                    <option value="">Selecciona un usuario</option>
                    <?php foreach ($usuariosPropietarios as $ownerOption): ?>
                      <option value="<?= (int) ($ownerOption['id'] ?? 0) ?>">
                        <?= $h(($ownerOption['display_name'] ?? 'Usuario') . ' - ' . ($ownerOption['display_email'] ?? '') . ' (' . ($ownerOption['rol'] ?? '') . ')') ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </label>
                <p class="im-api-ayuda">Escribi al menos 4 caracteres para filtrar coincidencias o abrí el desplegable completo.</p>
                <div class="im-api-form__acciones">
                  <button class="im-boton im-boton--principal" type="submit">Crear integracion</button>
                </div>
              </form>
              <?php if ($opcionesProyectoSitio): ?>
                <datalist id="api-project-options">
                  <?php foreach ($opcionesProyectoSitio as $opcion): ?>
                    <option value="<?= $h($opcion['nombre'] ?? '') ?>"><?= $h(($opcion['origen'] ?? '') . ' - ' . ($opcion['nombre'] ?? '')) ?></option>
                  <?php endforeach; ?>
                </datalist>
              <?php endif; ?>
            </article>

            <?php if (!$integraciones): ?>
              <article class="im-tarjeta">
                <h3>No hay integraciones registradas.</h3>
                <p>Cuando crees la primera integracion, vas a poder copiar snippets listos para landings externas.</p>
              </article>
            <?php else: ?>
              <article class="im-tabla-tareas__tarjeta">
                <div class="im-tabla-tareas__cabecera">
                  <div>
                    <h3>Integraciones registradas</h3>
                    <p>Tabla compacta con keys visibles y detalle completo dentro de un modal por fila.</p>
                  </div>
                </div>
                <div class="im-tabla-tareas__scroll">
                  <table class="im-tabla-tareas">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Proyecto o sitio</th>
                        <th>Dueno</th>
                        <th>Dominio</th>
                        <th>Public key</th>
                        <th>Secret key</th>
                        <th>Estado</th>
                        <th>Visitas</th>
                        <th>Contactos</th>
                        <th>Ultimo uso</th>
                        <th class="im-tabla-tareas__acciones">Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($integraciones as $integracion): ?>
                        <?php
                          $integrationId = (int) ($integracion['id'] ?? 0);
                          $owner = $resolverDueno($integracion);
                          $apiBase = rtrim($appBaseUrl, '/') . "/api";
                          $visitTrackerSrc = rtrim($appBaseUrl, '/') . "/assets/impulsa_material/js/visit-tracker.js";
                          $chatbotUrl = $apiBase . "/chatbot_widget/widget.js?public_key=" . ($integracion['public_key'] ?? '');
                          $visitSnippet = "<script>\nwindow.IMPULSA_API_CONFIG = {\n  publicKey: \"" . ($integracion['public_key'] ?? '') . "\",\n  apiBaseUrl: \"" . $apiBase . "\"\n};\n</script>\n<script src=\"" . $visitTrackerSrc . "\"></script>";
                          $formSnippet = "fetch(\"" . rtrim($appBaseUrl, '/') . "/api/contact_form_landing_page/index.php\", {\n  method: \"POST\",\n  headers: {\n    \"Content-Type\": \"application/json\"\n  },\n  body: JSON.stringify({\n    public_key: \"" . ($integracion['public_key'] ?? '') . "\",\n    page: window.location.pathname,\n    contact_nombre: formName,\n    contact_email: formEmail,\n    contact_whatsapp: formPhone,\n    contact_description: formMessage,\n    contact_consultation: formConsultation\n  })\n});";
                          $chatbotSnippet = "<script src=\"" . $chatbotUrl . "\"></script>";
                          $blogListSnippet = "fetch(\"" . rtrim($appBaseUrl, '/') . "/api/blog_api/index.php\", {\n  method: \"POST\",\n  headers: {\n    \"Content-Type\": \"application/json\"\n  },\n  body: JSON.stringify({\n    action: \"list\",\n    public_key: \"" . ($integracion['public_key'] ?? '') . "\"\n  })\n});";
                          $blogDetailSnippet = "fetch(\"" . rtrim($appBaseUrl, '/') . "/api/blog_api/index.php\", {\n  method: \"POST\",\n  headers: {\n    \"Content-Type\": \"application/json\"\n  },\n  body: JSON.stringify({\n    action: \"detail\",\n    public_key: \"" . ($integracion['public_key'] ?? '') . "\",\n    slug: \"mi-post\"\n  })\n});";
                          $productoListSnippet = "fetch(\"" . rtrim($appBaseUrl, '/') . "/api/producto_api/index.php\", {\n  method: \"POST\",\n  headers: {\n    \"Content-Type\": \"application/json\"\n  },\n  body: JSON.stringify({\n    action: \"list\",\n    public_key: \"" . ($integracion['public_key'] ?? '') . "\"\n  })\n});";
                          $productoDetailSnippet = "fetch(\"" . rtrim($appBaseUrl, '/') . "/api/producto_api/index.php\", {\n  method: \"POST\",\n  headers: {\n    \"Content-Type\": \"application/json\"\n  },\n  body: JSON.stringify({\n    action: \"detail\",\n    public_key: \"" . ($integracion['public_key'] ?? '') . "\",\n    slug: \"mi-producto\"\n  })\n});";
                          $allSnippets = "(function () {\n"
                              . "  const IMPULSA_CONFIG = {\n"
                              . "    publicKey: \"" . ($integracion['public_key'] ?? '') . "\",\n"
                              . "    apiBaseUrl: \"" . $apiBase . "\",\n"
                              . "    visitTrackerSrc: \"" . $visitTrackerSrc . "\",\n"
                              . "    chatbotScriptUrl: \"" . $chatbotUrl . "\"\n"
                              . "  };\n\n"
                              . "  window.IMPULSA_API_CONFIG = {\n"
                              . "    publicKey: IMPULSA_CONFIG.publicKey,\n"
                              . "    apiBaseUrl: IMPULSA_CONFIG.apiBaseUrl\n"
                              . "  };\n\n"
                              . "  const postJson = (endpoint, payload) => fetch(endpoint, {\n"
                              . "    method: \"POST\",\n"
                              . "    headers: {\n"
                              . "      \"Content-Type\": \"application/json\"\n"
                              . "    },\n"
                              . "    body: JSON.stringify(payload)\n"
                              . "  });\n\n"
                              . "  const ensureVisitTracker = () => {\n"
                              . "    if (document.querySelector('script[data-impulsa-visit-tracker]')) {\n"
                              . "      return;\n"
                              . "    }\n\n"
                              . "    const script = document.createElement(\"script\");\n"
                              . "    script.src = IMPULSA_CONFIG.visitTrackerSrc;\n"
                              . "    script.async = true;\n"
                              . "    script.dataset.impulsaVisitTracker = \"true\";\n"
                              . "    document.body.appendChild(script);\n"
                              . "  };\n\n"
                              . "  const mountChatbot = () => {\n"
                              . "    if (document.querySelector('script[data-impulsa-chatbot]')) {\n"
                              . "      return;\n"
                              . "    }\n\n"
                              . "    const script = document.createElement(\"script\");\n"
                              . "    script.src = IMPULSA_CONFIG.chatbotScriptUrl;\n"
                              . "    script.async = true;\n"
                              . "    script.dataset.impulsaChatbot = \"true\";\n"
                              . "    document.body.appendChild(script);\n"
                              . "  };\n\n"
                              . "  window.ImpulsaAPI = {\n"
                              . "    config: IMPULSA_CONFIG,\n"
                              . "    trackVisits: ensureVisitTracker,\n"
                              . "    sendContact: ({\n"
                              . "      page = window.location.pathname,\n"
                              . "      contact_nombre,\n"
                              . "      contact_email,\n"
                              . "      contact_whatsapp = \"\",\n"
                              . "      contact_description = \"\",\n"
                              . "      contact_consultation = \"\"\n"
                              . "    }) => postJson(IMPULSA_CONFIG.apiBaseUrl + \"/contact_form_landing_page/index.php\", {\n"
                              . "      public_key: IMPULSA_CONFIG.publicKey,\n"
                              . "      page,\n"
                              . "      contact_nombre,\n"
                              . "      contact_email,\n"
                              . "      contact_whatsapp,\n"
                              . "      contact_description,\n"
                              . "      contact_consultation\n"
                              . "    }),\n"
                              . "    getBlogList: () => postJson(IMPULSA_CONFIG.apiBaseUrl + \"/blog_api/index.php\", {\n"
                              . "      action: \"list\",\n"
                              . "      public_key: IMPULSA_CONFIG.publicKey\n"
                              . "    }),\n"
                              . "    getBlogDetail: ({ slug }) => postJson(IMPULSA_CONFIG.apiBaseUrl + \"/blog_api/index.php\", {\n"
                              . "      action: \"detail\",\n"
                              . "      public_key: IMPULSA_CONFIG.publicKey,\n"
                              . "      slug: slug || \"mi-post\"\n"
                              . "    }),\n"
                              . "    getProductList: () => postJson(IMPULSA_CONFIG.apiBaseUrl + \"/producto_api/index.php\", {\n"
                              . "      action: \"list\",\n"
                              . "      public_key: IMPULSA_CONFIG.publicKey\n"
                              . "    }),\n"
                              . "    getProductDetail: ({ slug }) => postJson(IMPULSA_CONFIG.apiBaseUrl + \"/producto_api/index.php\", {\n"
                              . "      action: \"detail\",\n"
                              . "      public_key: IMPULSA_CONFIG.publicKey,\n"
                              . "      slug: slug || \"mi-producto\"\n"
                              . "    }),\n"
                              . "    mountChatbot\n"
                              . "  };\n\n"
                              . "  window.ImpulsaAPI.trackVisits();\n"
                              . "})();";
                          $payloadModal = [
                              'id' => $integrationId,
                              'project_name' => (string) ($integracion['project_name'] ?? ''),
                              'owner_user_auth_id' => (int) ($integracion['user_auth_id'] ?? 0),
                              'owner_name' => $owner['name'],
                              'owner_email' => $owner['email'],
                              'allowed_domain' => (string) ($integracion['allowed_domain'] ?? ''),
                              'public_key' => (string) ($integracion['public_key'] ?? ''),
                              'status' => (string) ($integracion['status'] ?? 'inactive'),
                              'total_visits' => (int) ($integracion['total_visits'] ?? 0),
                              'total_contacts' => (int) ($integracion['total_contacts'] ?? 0),
                              'last_used_at' => $formatearFecha($integracion['last_used_at'] ?? null),
                              'updated_at' => $formatearFecha($integracion['updated_at'] ?? null),
                              'all_snippets' => $allSnippets,
                          ];
                        ?>
                        <tr id="integration-<?= $integrationId ?>">
                          <td><?= $integrationId ?></td>
                          <td class="im-tabla-tareas__nombre"><?= $h($integracion['project_name'] ?? '') ?></td>
                          <td>
                            <strong><?= $h($owner['name']) ?></strong>
                            <br><small><?= $h($owner['email'] !== '' ? $owner['email'] : 'Sin correo') ?></small>
                          </td>
                          <td><code><?= $h($integracion['allowed_domain'] ?? '') ?></code></td>
                          <td>
                            <div class="im-api-copy-linea">
                              <code><?= $h($integracion['public_key'] ?? '') ?></code>
                              <button class="im-boton-icono material-symbols-rounded" type="button" aria-label="Copiar public key" data-copy-text="<?= $h($integracion['public_key'] ?? '') ?>">content_copy</button>
                            </div>
                          </td>
                          <td>
                            <div class="im-api-secret--protegida">
                              Protegida por hash
                              <br><small>Solo visible al crear o regenerar.</small>
                            </div>
                          </td>
                          <td><span class="im-chip <?= $h($estadoChip((string) ($integracion['status'] ?? 'inactive'))) ?>"><?= $h($estadoTexto((string) ($integracion['status'] ?? 'inactive'))) ?></span></td>
                          <td><?= number_format((int) ($integracion['total_visits'] ?? 0), 0, ',', '.') ?></td>
                          <td><?= number_format((int) ($integracion['total_contacts'] ?? 0), 0, ',', '.') ?></td>
                          <td><?= $h($formatearFecha($integracion['last_used_at'] ?? null)) ?></td>
                          <td class="im-tabla-tareas__acciones">
                            <div class="im-menu-tabla" data-im-menu>
                              <button class="im-boton-icono im-boton-icono--menu-tabla material-symbols-rounded" type="button" data-im-menu-trigger aria-label="Opciones de tabla" aria-haspopup="menu" aria-expanded="false">more_horiz</button>
                              <div class="im-menu-flotante im-menu-tabla__panel" role="menu" data-im-menu-panel>
                                <button type="button" role="menuitem" data-abrir-api-detalle='<?= $h(json_encode($payloadModal, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_APOS | JSON_HEX_QUOT)) ?>'><span class="material-symbols-rounded" aria-hidden="true">visibility</span>Ver detalle</button>
                                <button type="button" role="menuitem" data-abrir-api-detalle='<?= $h(json_encode($payloadModal, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_APOS | JSON_HEX_QUOT)) ?>' data-api-detalle-mode="domain"><span class="material-symbols-rounded" aria-hidden="true">link</span>Cambiar URL</button>
                              </div>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </article>
            <?php endif; ?>
          </div>
        </section>
      </main>
    </div>
  </div>

  <div class="im-modal-cortina" data-cerrar-api-detalle></div>
  <section class="im-dialog im-api-tabla-modal" role="dialog" aria-modal="true" aria-labelledby="api-detalle-titulo" aria-hidden="true" data-modal-api-detalle>
    <header class="im-dialog__cabecera">
      <div>
        <p class="im-sobrelinea">Integracion API</p>
        <h3 id="api-detalle-titulo" data-api-detalle-titulo>Detalle de integracion</h3>
      </div>
      <button class="im-boton-icono" type="button" data-cerrar-api-detalle aria-label="Cerrar dialog"></button>
    </header>
    <div class="im-dialog__contenido">
      <div class="im-api-tabla-modal__grid">
        <div class="im-api-dato">
          <span>Proyecto o sitio</span>
          <strong data-api-detalle-proyecto></strong>
        </div>
        <div class="im-api-dato">
          <span>Dominio autorizado</span>
          <code data-api-detalle-dominio></code>
        </div>
        <div class="im-api-dato">
          <span>Dueno de la integracion</span>
          <strong data-api-detalle-owner-name></strong>
          <span data-api-detalle-owner-email></span>
        </div>
        <div class="im-api-dato">
          <span>Public key</span>
          <div class="im-api-copy-linea">
            <code data-api-detalle-public-key></code>
            <button class="im-boton-icono material-symbols-rounded" type="button" aria-label="Copiar public key" data-api-detalle-copy-public>content_copy</button>
          </div>
        </div>
        <div class="im-api-dato">
          <span>Secret key</span>
          <strong class="im-api-secret--protegida">No recuperable. Se almacena como hash y solo se ve al crear o regenerar.</strong>
        </div>
        <div class="im-api-dato">
          <span>Estado</span>
          <strong data-api-detalle-estado></strong>
        </div>
        <div class="im-api-dato">
          <span>Ultimo uso</span>
          <strong data-api-detalle-ultimo-uso></strong>
        </div>
      </div>

      <div class="im-api-card__metricas">
        <div class="im-api-metrica"><span>Visitas</span><strong data-api-detalle-visitas></strong></div>
        <div class="im-api-metrica"><span>Contactos</span><strong data-api-detalle-contactos></strong></div>
        <div class="im-api-metrica"><span>Actualizada</span><strong data-api-detalle-actualizada></strong></div>
      </div>

      <form method="post" class="im-api-form im-api-form-secundario" data-api-detalle-form>
        <input type="hidden" name="api_integration_action" value="update">
        <input type="hidden" name="integration_id" value="" data-api-detalle-id>
        <label class="im-campo im-campo-material" data-im-campo="generico">
          <span>Proyecto o sitio</span>
          <input type="text" name="project_name" maxlength="180" list="api-project-options" data-api-detalle-input-proyecto required>
        </label>
        <label class="im-campo im-campo-material" data-im-campo="generico">
          <span>Dominio autorizado</span>
          <input type="text" name="allowed_domain" data-api-detalle-input-dominio required>
        </label>
        <label class="im-campo im-campo-material" data-im-campo="generico">
          <span>Dueno de la integracion</span>
          <select name="owner_user_auth_id" data-api-detalle-input-owner>
            <option value="">Sin asignar</option>
            <?php foreach ($usuariosPropietarios as $ownerOption): ?>
              <option value="<?= (int) ($ownerOption['id'] ?? 0) ?>">
                <?= $h(($ownerOption['display_name'] ?? 'Usuario') . ' - ' . ($ownerOption['display_email'] ?? '') . ' (' . ($ownerOption['rol'] ?? '') . ')') ?>
              </option>
            <?php endforeach; ?>
          </select>
        </label>
        <p class="im-api-ayuda im-campo--full" data-api-detalle-ayuda-dominio>Al cambiar esta URL, la integracion dejara de aceptar requests desde el dominio anterior y pasara a validar el nuevo.</p>
        <div class="im-api-form-secundario__acciones">
          <button class="im-boton im-boton--principal" type="submit" data-api-detalle-submit-text>Guardar cambios</button>
        </div>
      </form>

      <div class="im-api-card__acciones">
        <form method="post">
          <input type="hidden" name="api_integration_action" value="toggle_status">
          <input type="hidden" name="integration_id" value="" data-api-detalle-toggle-id>
          <button class="im-boton" type="submit" data-api-detalle-toggle-text>Activar</button>
        </form>
        <form method="post">
          <input type="hidden" name="api_integration_action" value="regenerate_public_key">
          <input type="hidden" name="integration_id" value="" data-api-detalle-public-id>
          <button class="im-boton im-boton--texto" type="submit">Regenerar public key</button>
        </form>
        <form method="post">
          <input type="hidden" name="api_integration_action" value="regenerate_secret_key">
          <input type="hidden" name="integration_id" value="" data-api-detalle-secret-id>
          <button class="im-boton im-boton--texto" type="submit">Regenerar secret key</button>
        </form>
      </div>

      <div class="im-api-layout">
        <section class="im-api-panel" aria-labelledby="api-detalle-integracion-total">
          <div class="im-api-panel__cabecera">
            <div>
              <h4 id="api-detalle-integracion-total">Integracion completa</h4>
              <p>Un solo bloque con toda la configuracion y helpers para visitas, formulario, blog, productos y chatbot.</p>
            </div>
            <button class="im-boton im-boton--texto" type="button" data-copy-target="api-detalle-all-snippets">Copiar todo el JS</button>
          </div>
          <p class="im-api-panel__intro">Pegalo una vez en tu web, idealmente despues de cargar el DOM. El bloque crea `window.ImpulsaAPI`, autoactiva el tracker de visitas y deja disponibles los helpers para el resto de endpoints.</p>
          <pre id="api-detalle-all-snippets"><code data-api-detalle-all-snippets></code></pre>
        </section>

        <aside class="im-api-docs" aria-label="Documentacion de APIs">
          <article class="im-api-doc">
            <div>
              <h4>API de visitas</h4>
              <p class="im-api-doc__texto">Registra visitas de la pagina usando la clave publica de la integracion.</p>
            </div>
            <div class="im-api-doc__lista">
              <div class="im-api-doc__item">
                <strong>Campos esperados</strong>
                <span>`publicKey` y `apiBaseUrl` dentro de `window.IMPULSA_API_CONFIG`.</span>
              </div>
              <div class="im-api-doc__item">
                <strong>Obligatorios</strong>
                <span>`publicKey`: string. `apiBaseUrl`: URL absoluta terminada en `/api`.</span>
              </div>
              <div class="im-api-doc__item">
                <strong>Opcionales</strong>
                <span>No requiere payload adicional en el uso base.</span>
              </div>
              <div class="im-api-doc__item">
                <strong>Uso</strong>
                <span>El bloque unificado ejecuta `trackVisits()` automaticamente y carga `visit-tracker.js` una sola vez.</span>
              </div>
              <div class="im-api-doc__item">
                <strong>Consideracion</strong>
                <span>La pagina debe correr dentro del dominio autorizado para que la integracion sea valida.</span>
              </div>
            </div>
          </article>

          <article class="im-api-doc">
            <div>
              <h4>API de formulario</h4>
              <p class="im-api-doc__texto">Envia leads externos al endpoint de contactos de la landing.</p>
            </div>
            <div class="im-api-doc__lista">
              <div class="im-api-doc__item">
                <strong>Campos esperados</strong>
                <span>`page`, `contact_nombre`, `contact_email`, `contact_whatsapp`, `contact_description`, `contact_consultation`.</span>
              </div>
              <div class="im-api-doc__item">
                <strong>Obligatorios</strong>
                <span>`page`, `contact_nombre`, `contact_email`. Todos como string; `contact_email` debe tener formato email.</span>
              </div>
              <div class="im-api-doc__item">
                <strong>Opcionales</strong>
                <span>`contact_whatsapp`, `contact_description` y `contact_consultation`, todos string.</span>
              </div>
              <div class="im-api-doc__item">
                <strong>Uso</strong>
                <span>Llama `window.ImpulsaAPI.sendContact({...})` en el `submit` del formulario y envia JSON.</span>
              </div>
              <div class="im-api-doc__item">
                <strong>Consideracion</strong>
                <span>Valida antes de enviar y reemplaza los valores de ejemplo por datos reales del formulario.</span>
              </div>
            </div>
          </article>

          <article class="im-api-doc">
            <div>
              <h4>API de chatbot</h4>
              <p class="im-api-doc__texto">Inserta el widget remoto del chatbot asociado a la clave publica.</p>
            </div>
            <div class="im-api-doc__lista">
              <div class="im-api-doc__item">
                <strong>Campos esperados</strong>
                <span>Usa `public_key` en la URL del script remoto.</span>
              </div>
              <div class="im-api-doc__item">
                <strong>Obligatorios</strong>
                <span>`public_key`: string, embebido en `chatbotScriptUrl`.</span>
              </div>
              <div class="im-api-doc__item">
                <strong>Opcionales</strong>
                <span>No requiere payload adicional.</span>
              </div>
              <div class="im-api-doc__item">
                <strong>Uso</strong>
                <span>Ejecuta `window.ImpulsaAPI.mountChatbot()` solo en las paginas donde quieras mostrar el widget.</span>
              </div>
              <div class="im-api-doc__item">
                <strong>Consideracion</strong>
                <span>El helper evita duplicados; no cargues otro script del mismo widget manualmente.</span>
              </div>
            </div>
          </article>

          <article class="im-api-doc">
            <div>
              <h4>API de blog</h4>
              <p class="im-api-doc__texto">Permite listar publicaciones activas y pedir el detalle de una nota puntual.</p>
            </div>
            <div class="im-api-doc__lista">
              <div class="im-api-doc__item">
                <strong>Ruta</strong>
                <span>`POST <?= $h(rtrim($appBaseUrl, '/')) ?>/api/producto_api/index.php` enviando JSON con `Content-Type: application/json`.</span>
              </div>
              <div class="im-api-doc__item">
                <strong>Campos esperados</strong>
                <span>`action`, `public_key` y, para detalle, `slug`.</span>
              </div>
              <div class="im-api-doc__item">
                <strong>Obligatorios</strong>
                <span>`action`: string con `list` o `detail`; `public_key`: string; `slug`: string URL-friendly para detalle.</span>
              </div>
              <div class="im-api-doc__item">
                <strong>Opcionales</strong>
                <span>No hay extras en los snippets actuales.</span>
              </div>
              <div class="im-api-doc__item">
                <strong>Uso</strong>
                <span>Usa `getBlogList()` para grillas y `getBlogDetail({ slug })` para la pagina individual.</span>
              </div>
              <div class="im-api-doc__item">
                <strong>Consideracion</strong>
                <span>Reemplaza `mi-post` por el slug real y procesa la respuesta antes de renderizar HTML.</span>
              </div>
            </div>
          </article>

          <article class="im-api-doc">
            <div>
              <h4>API de productos</h4>
              <p class="im-api-doc__texto">Expone el catalogo activo y el detalle de un producto concreto.</p>
            </div>
            <div class="im-api-doc__lista">
              <div class="im-api-doc__item">
                <strong>Campos esperados</strong>
                <span>`action`, `public_key` y, para detalle, `slug`.</span>
              </div>
              <div class="im-api-doc__item">
                <strong>Obligatorios</strong>
                <span>`action`: string con `list` o `detail`; `public_key`: string; `slug`: string URL-friendly para detalle.</span>
              </div>
              <div class="im-api-doc__item">
                <strong>Opcionales</strong>
                <span>No hay campos adicionales en la integracion actual.</span>
              </div>
              <div class="im-api-doc__item">
                <strong>Uso</strong>
                <span>Usa esa ruta con `action: "list"` para vitrinas y con `action: "detail"` mas `slug` para fichas individuales. Los helpers `getProductList()` y `getProductDetail({ slug })` ya apuntan a ese endpoint.</span>
              </div>
              <div class="im-api-doc__item">
                <strong>Consideracion</strong>
                <span>Reemplaza `mi-producto` por el slug real y renderiza la respuesta con manejo de errores en frontend.</span>
              </div>
            </div>
          </article>
        </aside>
      </div>
    </div>
    <footer class="im-dialog__acciones">
      <button class="im-boton im-boton--texto" type="button" data-cerrar-api-detalle>Cerrar</button>
    </footer>
  </section>

  <?php require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilView.php'; ?>
  <script src="<?= htmlspecialchars(obtenerImpulsaMaterialJsSrc(), ENT_QUOTES, 'UTF-8'); ?>"></script>
  <script>
    document.addEventListener('click', async (event) => {
      const copyButton = event.target.closest('[data-copy-text]');
      if (copyButton) {
        const text = copyButton.getAttribute('data-copy-text') || '';

        try {
          await navigator.clipboard.writeText(text);
          copyButton.textContent = 'done';
          window.setTimeout(() => {
            copyButton.textContent = 'content_copy';
          }, 1400);
        } catch (error) {
          copyButton.textContent = 'error';
        }

        return;
      }

      const button = event.target.closest('[data-copy-target]');
      if (!button) {
      } else {
        const target = document.getElementById(button.getAttribute('data-copy-target'));
        if (!target) {
          return;
        }

        const text = target.textContent || '';

        try {
          await navigator.clipboard.writeText(text);
          button.textContent = 'Copiado';
          window.setTimeout(() => {
            button.textContent = 'Copiar';
          }, 1800);
        } catch (error) {
          button.textContent = 'Error';
        }

        return;
      }
    });

    document.addEventListener('DOMContentLoaded', () => {
      const modal = document.querySelector('[data-modal-api-detalle]');
      const cortina = document.querySelector('[data-cerrar-api-detalle].im-modal-cortina');

      if (!modal || !cortina) {
        return;
      }

      const alternar = (abrir) => {
        modal.classList.toggle('abierto', abrir);
        cortina.classList.toggle('abierto', abrir);
        modal.setAttribute('aria-hidden', abrir ? 'false' : 'true');
      };

      const poblarModal = (data, mode = 'detail') => {
        const titulo = modal.querySelector('[data-api-detalle-titulo]');
        const proyecto = modal.querySelector('[data-api-detalle-proyecto]');
        const dominio = modal.querySelector('[data-api-detalle-dominio]');
        const ownerName = modal.querySelector('[data-api-detalle-owner-name]');
        const ownerEmail = modal.querySelector('[data-api-detalle-owner-email]');
        const publicKey = modal.querySelector('[data-api-detalle-public-key]');
        const copyPublic = modal.querySelector('[data-api-detalle-copy-public]');
        const estado = modal.querySelector('[data-api-detalle-estado]');
        const ultimoUso = modal.querySelector('[data-api-detalle-ultimo-uso]');
        const visitas = modal.querySelector('[data-api-detalle-visitas]');
        const contactos = modal.querySelector('[data-api-detalle-contactos]');
        const actualizada = modal.querySelector('[data-api-detalle-actualizada]');
        const inputId = modal.querySelector('[data-api-detalle-id]');
        const toggleId = modal.querySelector('[data-api-detalle-toggle-id]');
        const publicId = modal.querySelector('[data-api-detalle-public-id]');
        const secretId = modal.querySelector('[data-api-detalle-secret-id]');
        const inputProyecto = modal.querySelector('[data-api-detalle-input-proyecto]');
        const inputDominio = modal.querySelector('[data-api-detalle-input-dominio]');
        const inputOwner = modal.querySelector('[data-api-detalle-input-owner]');
        const toggleText = modal.querySelector('[data-api-detalle-toggle-text]');
        const submitText = modal.querySelector('[data-api-detalle-submit-text]');

        titulo.textContent = mode === 'domain' ? `Cambiar URL #${data.id}` : `Detalle #${data.id}`;
        proyecto.textContent = data.project_name || '';
        dominio.textContent = data.allowed_domain || '';
        ownerName.textContent = data.owner_name || 'Sin dueno';
        ownerEmail.textContent = data.owner_email || 'Sin correo configurado';
        publicKey.textContent = data.public_key || '';
        copyPublic.setAttribute('data-copy-text', data.public_key || '');
        estado.textContent = data.status === 'active' ? 'Activa' : 'Inactiva';
        ultimoUso.textContent = data.last_used_at || '-';
        visitas.textContent = String(data.total_visits ?? 0);
        contactos.textContent = String(data.total_contacts ?? 0);
        actualizada.textContent = data.updated_at || '-';
        inputId.value = String(data.id || '');
        toggleId.value = String(data.id || '');
        publicId.value = String(data.id || '');
        secretId.value = String(data.id || '');
        inputProyecto.value = data.project_name || '';
        inputDominio.value = data.allowed_domain || '';
        inputOwner.value = String(data.owner_user_auth_id || '');
        toggleText.textContent = data.status === 'active' ? 'Desactivar' : 'Activar';
        submitText.textContent = mode === 'domain' ? 'Actualizar URL' : 'Guardar cambios';
        modal.querySelector('[data-api-detalle-all-snippets]').textContent = data.all_snippets || '';
      };

      document.querySelectorAll('[data-abrir-api-detalle]').forEach((button) => {
        button.addEventListener('click', () => {
          const raw = button.getAttribute('data-abrir-api-detalle') || '';
          const mode = button.getAttribute('data-api-detalle-mode') || 'detail';

          if (!raw) {
            return;
          }

          let data = null;

          try {
            data = JSON.parse(raw);
          } catch (error) {
            return;
          }
          poblarModal(data, mode);
          alternar(true);

          if (mode === 'domain') {
            const inputDominio = modal.querySelector('[data-api-detalle-input-dominio]');
            inputDominio?.focus();
            inputDominio?.select();
            modal.querySelector('[data-api-detalle-form]')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
          }
        });
      });

      document.querySelectorAll('[data-cerrar-api-detalle]').forEach((button) => {
        button.addEventListener('click', () => alternar(false));
      });
    });
  </script>
</body>
</html>
