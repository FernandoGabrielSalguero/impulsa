<?php
$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Usuario';
$integraciones = $integraciones ?? [];
$opcionesProyectoSitio = $opcionesProyectoSitio ?? [];
$flashIntegraciones = $flashIntegraciones ?? null;
$appBaseUrl = $appBaseUrl ?? '';
$totalIntegraciones = count($integraciones);
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
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Integraciones API Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,400,0,0&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../../assets/impulsa_material/css/material.css">
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
    .im-api-snippets { display: grid; grid-template-columns: repeat(2, minmax(260px, 1fr)); gap: .85rem; }
    .im-api-snippet { display: grid; gap: .5rem; }
    .im-api-snippet pre { margin: 0; padding: 1rem; overflow: auto; border-radius: var(--im-radio-chico); background: #111827; color: #f9fafb; font-size: .85rem; }
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
      .im-api-snippets,
      .im-api-tabla-modal__grid { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>
  <div class="im-aplicacion" data-menu-colapsado="false">
    <aside class="im-menu-lateral" id="menu-lateral" aria-label="Navegacion principal">
      <div class="im-marca">
        <span class="im-marca__isotipo" aria-hidden="true">
          <?php if ($usuarioAvatarUrl): ?>
            <img src="<?= $h($usuarioAvatarUrl) ?>" alt="">
          <?php else: ?>
            <?= $h($usuarioInicial) ?>
          <?php endif; ?>
        </span>
        <div class="im-marca__texto">
          <strong><?= $h($usuarioMarcaNombre) ?></strong>
          <span>Administrador</span>
        </div>
      </div>
      <nav class="im-navegacion">
        <a class="im-nav-item" href="/impulsa_emprende/controller/admin/dashboard.php">
          <span class="im-nav-item__icono" data-icon="dashboard" aria-hidden="true"></span>
          <span class="im-nav-item__texto">Dashboard</span>
        </a>
        <a class="im-nav-item" href="/impulsa_emprende/controller/admin/adminListUserController.php">
          <span class="im-nav-item__icono" data-icon="groups" aria-hidden="true"></span>
          <span class="im-nav-item__texto">Usuarios</span>
        </a>
        <a class="im-nav-item" href="/impulsa_emprende/controller/admin/adminSolicitudesPaginaWebSolicitudesController.php">
          <span class="im-nav-item__icono" data-icon="language" aria-hidden="true"></span>
          <span class="im-nav-item__texto">Solicitudes web</span>
        </a>
        <a class="im-nav-item" href="/impulsa_emprende/controller/admin/adminProyectosController.php">
          <span class="im-nav-item__icono" data-icon="work" aria-hidden="true"></span>
          <span class="im-nav-item__texto">Proyectos</span>
        </a>
        <a class="im-nav-item activo" href="/impulsa_emprende/controller/admin/adminAPIconfigurationController.php">
          <span class="im-nav-item__icono" data-icon="key" aria-hidden="true"></span>
          <span class="im-nav-item__texto">Integraciones API</span>
        </a>
      </nav>
    </aside>
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
                          $visitSnippet = "<script>\nwindow.IMPULSA_API_CONFIG = {\n  publicKey: \"" . ($integracion['public_key'] ?? '') . "\",\n  apiBaseUrl: \"" . rtrim($appBaseUrl, '/') . "/api\"\n};\n</script>\n<script src=\"" . rtrim($appBaseUrl, '/') . "/assets/impulsa_material/js/visit-tracker.js\"></script>";
                          $formSnippet = "fetch(\"" . rtrim($appBaseUrl, '/') . "/api/contact_form_landing_page/index.php\", {\n  method: \"POST\",\n  headers: {\n    \"Content-Type\": \"application/json\"\n  },\n  body: JSON.stringify({\n    public_key: \"" . ($integracion['public_key'] ?? '') . "\",\n    page: window.location.pathname,\n    contact_nombre: formName,\n    contact_email: formEmail,\n    contact_whatsapp: formPhone,\n    contact_description: formMessage\n  })\n});";
                          $payloadModal = [
                              'id' => $integrationId,
                              'project_name' => (string) ($integracion['project_name'] ?? ''),
                              'allowed_domain' => (string) ($integracion['allowed_domain'] ?? ''),
                              'public_key' => (string) ($integracion['public_key'] ?? ''),
                              'status' => (string) ($integracion['status'] ?? 'inactive'),
                              'total_visits' => (int) ($integracion['total_visits'] ?? 0),
                              'total_contacts' => (int) ($integracion['total_contacts'] ?? 0),
                              'last_used_at' => $formatearFecha($integracion['last_used_at'] ?? null),
                              'updated_at' => $formatearFecha($integracion['updated_at'] ?? null),
                              'visit_snippet' => $visitSnippet,
                              'form_snippet' => $formSnippet,
                          ];
                        ?>
                        <tr id="integration-<?= $integrationId ?>">
                          <td><?= $integrationId ?></td>
                          <td class="im-tabla-tareas__nombre"><?= $h($integracion['project_name'] ?? '') ?></td>
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
        <div class="im-api-form-secundario__acciones">
          <button class="im-boton im-boton--principal" type="submit">Guardar cambios</button>
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

      <div class="im-api-snippets">
        <div class="im-api-snippet">
          <div class="im-tarjeta__cabecera">
            <div>
              <h4>Snippet visitas</h4>
              <p>Usa `visit-tracker.js` y solo expone la clave publica.</p>
            </div>
            <button class="im-boton im-boton--texto" type="button" data-copy-target="api-detalle-visit-snippet">Copiar</button>
          </div>
          <pre id="api-detalle-visit-snippet"><code data-api-detalle-visit-snippet></code></pre>
        </div>
        <div class="im-api-snippet">
          <div class="im-tarjeta__cabecera">
            <div>
              <h4>Snippet formulario</h4>
              <p>Envia el contacto asociado a esta integracion.</p>
            </div>
            <button class="im-boton im-boton--texto" type="button" data-copy-target="api-detalle-form-snippet">Copiar</button>
          </div>
          <pre id="api-detalle-form-snippet"><code data-api-detalle-form-snippet></code></pre>
        </div>
      </div>
    </div>
    <footer class="im-dialog__acciones">
      <button class="im-boton im-boton--texto" type="button" data-cerrar-api-detalle>Cerrar</button>
    </footer>
  </section>

  <?php require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilView.php'; ?>
  <script src="../../../assets/impulsa_material/js/material.js"></script>
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

      document.querySelectorAll('[data-abrir-api-detalle]').forEach((button) => {
        button.addEventListener('click', () => {
          const raw = button.getAttribute('data-abrir-api-detalle') || '';

          if (!raw) {
            return;
          }

          let data = null;

          try {
            data = JSON.parse(raw);
          } catch (error) {
            return;
          }

          modal.querySelector('[data-api-detalle-titulo]').textContent = `Detalle #${data.id}`;
          modal.querySelector('[data-api-detalle-proyecto]').textContent = data.project_name || '';
          modal.querySelector('[data-api-detalle-dominio]').textContent = data.allowed_domain || '';
          modal.querySelector('[data-api-detalle-public-key]').textContent = data.public_key || '';
          modal.querySelector('[data-api-detalle-copy-public]').setAttribute('data-copy-text', data.public_key || '');
          modal.querySelector('[data-api-detalle-estado]').textContent = data.status === 'active' ? 'Activa' : 'Inactiva';
          modal.querySelector('[data-api-detalle-ultimo-uso]').textContent = data.last_used_at || '-';
          modal.querySelector('[data-api-detalle-visitas]').textContent = String(data.total_visits ?? 0);
          modal.querySelector('[data-api-detalle-contactos]').textContent = String(data.total_contacts ?? 0);
          modal.querySelector('[data-api-detalle-actualizada]').textContent = data.updated_at || '-';
          modal.querySelector('[data-api-detalle-id]').value = String(data.id || '');
          modal.querySelector('[data-api-detalle-toggle-id]').value = String(data.id || '');
          modal.querySelector('[data-api-detalle-public-id]').value = String(data.id || '');
          modal.querySelector('[data-api-detalle-secret-id]').value = String(data.id || '');
          modal.querySelector('[data-api-detalle-input-proyecto]').value = data.project_name || '';
          modal.querySelector('[data-api-detalle-input-dominio]').value = data.allowed_domain || '';
          modal.querySelector('[data-api-detalle-toggle-text]').textContent = data.status === 'active' ? 'Desactivar' : 'Activar';
          modal.querySelector('[data-api-detalle-visit-snippet]').textContent = data.visit_snippet || '';
          modal.querySelector('[data-api-detalle-form-snippet]').textContent = data.form_snippet || '';

          alternar(true);
        });
      });

      document.querySelectorAll('[data-cerrar-api-detalle]').forEach((button) => {
        button.addEventListener('click', () => alternar(false));
      });
    });
  </script>
</body>
</html>
