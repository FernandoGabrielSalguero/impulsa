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
    .im-api-card__metricas { display: grid; grid-template-columns: repeat(4, minmax(120px, 1fr)); gap: .75rem; }
    .im-api-metrica { padding: .85rem; border: 1px solid var(--im-color-borde); border-radius: var(--im-radio-chico); background: var(--im-color-superficie); }
    .im-api-metrica span { display: block; color: var(--im-color-texto-suave); font-size: .82rem; }
    .im-api-metrica strong { display: block; margin-top: .25rem; font-size: 1.2rem; }
    .im-api-snippets { display: grid; grid-template-columns: repeat(2, minmax(260px, 1fr)); gap: .85rem; }
    .im-api-snippet { display: grid; gap: .5rem; }
    .im-api-snippet pre { margin: 0; padding: 1rem; overflow: auto; border-radius: var(--im-radio-chico); background: #111827; color: #f9fafb; font-size: .85rem; }
    .im-api-secret { word-break: break-all; }
    .im-api-inline-form { display: contents; }
    .im-api-form-secundario { display: grid; gap: .85rem; padding-top: .75rem; border-top: 1px solid var(--im-color-borde); }
    .im-api-form-secundario__acciones { display: flex; flex-wrap: wrap; gap: .5rem; }
    @media (max-width: 900px) {
      .im-api-form,
      .im-api-card__metricas,
      .im-api-snippets { grid-template-columns: 1fr; }
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
              <div class="im-api-lista">
                <?php foreach ($integraciones as $integracion): ?>
                  <?php
                    $integrationId = (int) ($integracion['id'] ?? 0);
                    $visitSnippet = "<script>\nwindow.IMPULSA_API_CONFIG = {\n  publicKey: \"" . ($integracion['public_key'] ?? '') . "\",\n  apiBaseUrl: \"" . rtrim($appBaseUrl, '/') . "/api\"\n};\n</script>\n<script src=\"" . rtrim($appBaseUrl, '/') . "/assets/impulsa_material/js/visit-tracker.js\"></script>";
                    $formSnippet = "fetch(\"" . rtrim($appBaseUrl, '/') . "/api/contact_form_landing_page/index.php\", {\n  method: \"POST\",\n  headers: {\n    \"Content-Type\": \"application/json\"\n  },\n  body: JSON.stringify({\n    public_key: \"" . ($integracion['public_key'] ?? '') . "\",\n    page: window.location.pathname,\n    contact_nombre: formName,\n    contact_email: formEmail,\n    contact_whatsapp: formPhone,\n    contact_description: formMessage\n  })\n});";
                  ?>
                  <article class="im-tarjeta im-api-card" id="integration-<?= $integrationId ?>">
                    <div class="im-api-card__cabecera">
                      <div>
                        <h3><?= $h($integracion['project_name'] ?? '') ?></h3>
                        <div class="im-api-card__meta">
                          <span>Dominio: <code><?= $h($integracion['allowed_domain'] ?? '') ?></code></span>
                          <span>Public key: <code><?= $h($integracion['public_key'] ?? '') ?></code></span>
                        </div>
                      </div>
                      <span class="im-chip <?= $h($estadoChip((string) ($integracion['status'] ?? 'inactive'))) ?>"><?= $h($estadoTexto((string) ($integracion['status'] ?? 'inactive'))) ?></span>
                    </div>

                    <div class="im-api-card__metricas">
                      <div class="im-api-metrica"><span>Visitas</span><strong><?= number_format((int) ($integracion['total_visits'] ?? 0), 0, ',', '.') ?></strong></div>
                      <div class="im-api-metrica"><span>Contactos</span><strong><?= number_format((int) ($integracion['total_contacts'] ?? 0), 0, ',', '.') ?></strong></div>
                      <div class="im-api-metrica"><span>Ultimo uso</span><strong><?= $h($formatearFecha($integracion['last_used_at'] ?? null)) ?></strong></div>
                      <div class="im-api-metrica"><span>Actualizada</span><strong><?= $h($formatearFecha($integracion['updated_at'] ?? null)) ?></strong></div>
                    </div>

                    <div class="im-api-card__acciones">
                      <form method="post" class="im-api-inline-form">
                        <input type="hidden" name="api_integration_action" value="toggle_status">
                        <input type="hidden" name="integration_id" value="<?= $integrationId ?>">
                        <button class="im-boton" type="submit"><?= ($integracion['status'] ?? '') === 'active' ? 'Desactivar' : 'Activar' ?></button>
                      </form>
                      <form method="post" class="im-api-inline-form">
                        <input type="hidden" name="api_integration_action" value="regenerate_public_key">
                        <input type="hidden" name="integration_id" value="<?= $integrationId ?>">
                        <button class="im-boton im-boton--texto" type="submit">Regenerar public key</button>
                      </form>
                      <form method="post" class="im-api-inline-form">
                        <input type="hidden" name="api_integration_action" value="regenerate_secret_key">
                        <input type="hidden" name="integration_id" value="<?= $integrationId ?>">
                        <button class="im-boton im-boton--texto" type="submit">Regenerar secret key</button>
                      </form>
                    </div>

                    <form method="post" class="im-api-form im-api-form-secundario">
                      <input type="hidden" name="api_integration_action" value="update">
                      <input type="hidden" name="integration_id" value="<?= $integrationId ?>">
                      <label class="im-campo im-campo-material" data-im-campo="generico">
                        <span>Proyecto o sitio</span>
                        <input type="text" name="project_name" maxlength="180" value="<?= $h($integracion['project_name'] ?? '') ?>" required>
                      </label>
                      <label class="im-campo im-campo-material" data-im-campo="generico">
                        <span>Dominio autorizado</span>
                        <input type="text" name="allowed_domain" value="<?= $h($integracion['allowed_domain'] ?? '') ?>" required>
                      </label>
                      <div class="im-api-form-secundario__acciones">
                        <button class="im-boton im-boton--principal" type="submit">Guardar cambios</button>
                      </div>
                    </form>

                    <div class="im-api-snippets">
                      <div class="im-api-snippet">
                        <div class="im-tarjeta__cabecera">
                          <div>
                            <h4>Snippet visitas</h4>
                            <p>Usa `visit-tracker.js` y solo expone la clave publica.</p>
                          </div>
                          <button class="im-boton im-boton--texto" type="button" data-copy-target="visit-snippet-<?= $integrationId ?>">Copiar</button>
                        </div>
                        <pre id="visit-snippet-<?= $integrationId ?>"><code><?= $h($visitSnippet) ?></code></pre>
                      </div>
                      <div class="im-api-snippet">
                        <div class="im-tarjeta__cabecera">
                          <div>
                            <h4>Snippet formulario</h4>
                            <p>Envia el contacto a `forms_clients_contact` asociado a esta integracion.</p>
                          </div>
                          <button class="im-boton im-boton--texto" type="button" data-copy-target="form-snippet-<?= $integrationId ?>">Copiar</button>
                        </div>
                        <pre id="form-snippet-<?= $integrationId ?>"><code><?= $h($formSnippet) ?></code></pre>
                      </div>
                    </div>
                  </article>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </section>
      </main>
    </div>
  </div>

  <?php require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilView.php'; ?>
  <script src="../../../assets/impulsa_material/js/material.js"></script>
  <script>
    document.addEventListener('click', async (event) => {
      const button = event.target.closest('[data-copy-target]');
      if (!button) {
        return;
      }

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
    });
  </script>
</body>
</html>
