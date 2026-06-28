<?php
$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Usuario';
$correos = $correos ?? [];
$filtros = $filtros ?? ['correo' => '', 'asunto' => ''];
$paginaActual = $paginaActual ?? 1;
$totalPaginas = $totalPaginas ?? 1;
$totalCorreos = $totalCorreos ?? count($correos);
$porPagina = $porPagina ?? 20;
$errorCargaCorreos = $errorCargaCorreos ?? null;
$estadoVista = $estadoVista ?? '';
$adminActiveMenu = 'correos';
$h = static fn (mixed $valor): string => htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
$formatearFecha = static function (?string $fecha): string {
    if (!$fecha) {
        return '-';
    }

    return date('d/m/Y H:i', strtotime($fecha));
};
$estadoClase = static function (string $estado): string {
    return $estado === 'enviado' ? 'im-chip--exito' : 'im-chip--alerta';
};
$estadoTexto = static function (string $estado): string {
    return $estado === 'enviado' ? 'Enviado' : 'Fallido';
};
$mensajesEstado = [
    'correo_reenviado' => ['tipo' => 'exito', 'texto' => 'Correo reenviado correctamente.'],
    'correo_reenvio_error' => ['tipo' => 'error', 'texto' => 'No se pudo reenviar el correo seleccionado.'],
];
$mensajeEstado = $mensajesEstado[$estadoVista] ?? null;
$buildPageUrl = static function (int $page) use ($filtros): string {
    $params = [];
    if (trim((string) ($filtros['correo'] ?? '')) !== '') {
        $params['correo'] = trim((string) $filtros['correo']);
    }
    if (trim((string) ($filtros['asunto'] ?? '')) !== '') {
        $params['asunto'] = trim((string) $filtros['asunto']);
    }
    if ($page > 1) {
        $params['page'] = $page;
    }

    $query = http_build_query($params);

    return '/impulsa_emprende/controller/admin/adminCorreosEnviadosController.php' . ($query !== '' ? '?' . $query : '');
};
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Correos enviados | Admin</title>
  <link rel="icon" href="<?= htmlspecialchars(obtenerFaviconHref(), ENT_QUOTES, 'UTF-8'); ?>" type="image/x-icon">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,400,0,0&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../../assets/impulsa_material/css/material.css">
  <style>
    .im-marca__isotipo img { width: 100%; height: 100%; border-radius: inherit; object-fit: cover; }
    .im-accion-salir { color: #ba1a1a; }
    .im-bottom-sheet--perfil { max-width: 860px; max-height: min(760px, calc(100vh - 2rem)); overflow: auto; }
    .im-nav-item__icono[data-icon]::before { content: attr(data-icon); }
    .im-alerta--error { background: #fdecec; color: #ba1a1a; }
    .im-alerta--exito { background: color-mix(in srgb, var(--im-color-exito) 14%, var(--im-color-superficie)); color: var(--im-color-exito); }
    .im-correos-filtros { display: grid; grid-template-columns: repeat(2, minmax(220px, 1fr)) auto auto; gap: .85rem; align-items: end; }
    .im-correos-filtros__acciones { display: flex; gap: .65rem; flex-wrap: wrap; }
    .im-correos-modal { width: min(1120px, calc(100vw - 2rem)); max-height: min(900px, calc(100vh - 2rem)); grid-template-rows: auto minmax(0, 1fr) auto; }
    .im-correos-modal .im-dialog__contenido { min-height: 0; overflow: auto; display: grid; gap: 1rem; }
    .im-correos-detalle-grid { display: grid; grid-template-columns: repeat(2, minmax(220px, 1fr)); gap: .85rem; }
    .im-correos-detalle-dato { padding: .85rem; border: 1px solid var(--im-color-borde); border-radius: var(--im-radio-chico); background: var(--im-color-superficie); }
    .im-correos-detalle-dato span { display: block; color: var(--im-color-texto-suave); font-size: .82rem; }
    .im-correos-detalle-dato strong { display: block; margin-top: .2rem; word-break: break-word; }
    .im-correos-contenido { margin: 0; padding: 1rem; border: 1px solid var(--im-color-borde); border-radius: var(--im-radio-chico); background: color-mix(in srgb, var(--im-color-superficie-2) 55%, var(--im-color-superficie)); color: var(--im-color-texto); white-space: pre-wrap; word-break: break-word; }
    .im-correos-contenido--visor { width: 100%; min-height: 480px; height: min(62vh, 720px); padding: 0; background: #fff; overflow: auto; resize: vertical; }
    .im-correos-paginacion { display: flex; justify-content: space-between; gap: 1rem; align-items: center; flex-wrap: wrap; margin-top: 1rem; }
    .im-correos-paginacion__acciones { display: flex; gap: .65rem; flex-wrap: wrap; }
    .im-correos-toolbar { display: flex; justify-content: space-between; gap: 1rem; align-items: flex-start; flex-wrap: wrap; }
    @media (max-width: 980px) {
      .im-correos-filtros,
      .im-correos-detalle-grid { grid-template-columns: 1fr; }
      .im-correos-contenido--visor { min-height: 360px; height: min(56vh, 560px); }
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
            <h1>Correos enviados</h1>
          </div>
        </div>
        <div class="im-barra-superior__acciones">
          <button class="im-boton-icono im-boton-icono--principal im-tooltip" type="button" data-abrir-config-tema aria-label="Configurar temas" data-tooltip="Configurar estilos"></button>
          <button class="im-boton-icono material-symbols-rounded im-tooltip" type="button" data-abrir-perfil aria-label="Mi perfil" data-tooltip="Mi perfil">account_circle</button>
          <a class="im-boton-icono material-symbols-rounded im-tooltip im-accion-salir" href="/auth/logout.php" aria-label="Salir" data-tooltip="Salir">logout</a>
        </div>
      </header>
      <main class="im-contenido">
        <section class="im-seccion-documento activa" id="correos-enviados">
          <div class="im-encabezado-seccion">
            <div>
              <p class="im-sobrelinea">Administracion</p>
              <h2>Historial de correos a clientes</h2>
              <p>Consulta los registros reales de <code>correos_log</code>, filtra por destinatario o asunto y abre el detalle completo del envio.</p>
            </div>
            <span class="im-chip"><?= number_format((int) $totalCorreos, 0, ',', '.') ?> correos</span>
          </div>

          <?php if ($errorCargaCorreos): ?>
            <div class="im-alerta im-alerta--error" role="alert"><?= $h($errorCargaCorreos) ?></div>
          <?php endif; ?>
          <?php if ($mensajeEstado): ?>
            <div class="im-alerta im-alerta--<?= $h($mensajeEstado['tipo']) ?>" role="status"><?= $h($mensajeEstado['texto']) ?></div>
          <?php endif; ?>

          <article class="im-tarjeta">
            <div class="im-tarjeta__cabecera">
              <div>
                <h3>Filtros</h3>
                <p>Las coincidencias por correo y asunto son parciales y combinables.</p>
              </div>
            </div>
            <form method="get" class="im-correos-filtros" data-correos-filtros-form>
              <label class="im-campo im-campo-material" data-im-campo="email">
                <span>Correo electronico</span>
                <input type="search" name="correo" value="<?= $h($filtros['correo'] ?? '') ?>" placeholder="cliente@dominio.com" autocomplete="off">
                <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">mail</i>
              </label>
              <label class="im-campo im-campo-material" data-im-campo="generico">
                <span>Asunto</span>
                <input type="search" name="asunto" value="<?= $h($filtros['asunto'] ?? '') ?>" placeholder="Buscar por asunto" autocomplete="off">
                <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">subject</i>
              </label>
              <div class="im-correos-filtros__acciones">
                <button class="im-boton im-boton--principal" type="submit" data-correos-submit>Buscar</button>
                <a class="im-boton im-boton--texto" href="/impulsa_emprende/controller/admin/adminCorreosEnviadosController.php">Limpiar filtros</a>
              </div>
            </form>
          </article>

          <?php if (!$errorCargaCorreos && $correos !== []): ?>
            <article class="im-tabla-tareas__tarjeta">
              <div class="im-tabla-tareas__cabecera im-correos-toolbar">
                <div>
                  <h3>Correos registrados</h3>
                  <p>Ordenados por fecha de envio mas reciente.</p>
                </div>
                <span class="im-chip">Pagina <?= (int) $paginaActual ?> de <?= (int) $totalPaginas ?></span>
              </div>
              <div class="im-tabla-tareas__scroll">
                <table class="im-tabla-tareas">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Fecha</th>
                      <th>Usuario</th>
                      <th>Correo</th>
                      <th>Asunto</th>
                      <th>Template</th>
                      <th>Estado</th>
                      <th class="im-tabla-tareas__acciones">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($correos as $correo): ?>
                      <?php
                      $payloadDetalle = [
                          'id' => (int) ($correo['id'] ?? 0),
                          'correo' => (string) ($correo['correo'] ?? ''),
                          'asunto' => (string) ($correo['asunto'] ?? ''),
                          'estado' => (string) ($correo['estado'] ?? ''),
                          'fecha' => $formatearFecha($correo['created_at'] ?? null),
                          'template' => (string) ($correo['template'] ?? ''),
                          'usuario_relacionado' => (string) ($correo['usuario_relacionado'] ?? '-'),
                          'error' => (string) ($correo['error'] ?? ''),
                          'meta' => (string) ($correo['meta_legible'] ?? ''),
                          'contenido' => (string) ($correo['contenido_legible'] ?? ''),
                          'contenido_html' => (string) ($correo['contenido_html'] ?? ''),
                      ];
                      ?>
                      <tr>
                        <td><?= (int) ($correo['id'] ?? 0) ?></td>
                        <td><?= $h($formatearFecha($correo['created_at'] ?? null)) ?></td>
                        <td><?= $h($correo['usuario_relacionado'] ?? '-') ?></td>
                        <td class="im-tabla-tareas__nombre"><?= $h($correo['correo'] ?? '-') ?></td>
                        <td><?= $h($correo['asunto'] ?? '-') ?></td>
                        <td><?= $h($correo['template'] ?? '-') ?></td>
                        <td><span class="im-chip <?= $h($estadoClase((string) ($correo['estado'] ?? 'fallido'))) ?>"><?= $h($estadoTexto((string) ($correo['estado'] ?? 'fallido'))) ?></span></td>
                        <td class="im-tabla-tareas__acciones">
                          <div class="im-menu-tabla" data-im-menu>
                            <button class="im-boton-icono im-boton-icono--menu-tabla material-symbols-rounded" type="button" data-im-menu-trigger aria-label="Opciones de tabla" aria-haspopup="menu" aria-expanded="false">more_horiz</button>
                            <div class="im-menu-flotante im-menu-tabla__panel" role="menu" data-im-menu-panel>
                              <button type="button" role="menuitem" data-ver-correo='<?= $h(json_encode($payloadDetalle, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT)) ?>'>
                                <span class="material-symbols-rounded" aria-hidden="true">visibility</span>Ver correo
                              </button>
                              <form method="post" action="/impulsa_emprende/controller/admin/adminCorreosEnviadosController.php">
                                <input type="hidden" name="accion" value="reenviar_correo">
                                <input type="hidden" name="correo_id" value="<?= (int) ($correo['id'] ?? 0) ?>">
                                <button type="submit" role="menuitem">
                                  <span class="material-symbols-rounded" aria-hidden="true">send</span>Reenviar correo
                                </button>
                              </form>
                            </div>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>

              <div class="im-correos-paginacion">
                <p>Mostrando <?= number_format(count($correos), 0, ',', '.') ?> registros de <?= number_format((int) $totalCorreos, 0, ',', '.') ?>.</p>
                <div class="im-correos-paginacion__acciones">
                  <?php if ($paginaActual > 1): ?>
                    <a class="im-boton im-boton--texto" href="<?= $h($buildPageUrl($paginaActual - 1)) ?>">Anterior</a>
                  <?php endif; ?>
                  <?php if ($paginaActual < $totalPaginas): ?>
                    <a class="im-boton im-boton--principal" href="<?= $h($buildPageUrl($paginaActual + 1)) ?>">Siguiente</a>
                  <?php endif; ?>
                </div>
              </div>
            </article>
          <?php elseif (!$errorCargaCorreos): ?>
            <article class="im-tarjeta">
              <h3>No encontramos correos con esos filtros.</h3>
              <p>Ajusta el correo o el asunto buscado, o limpia los filtros para volver a ver todo el historial.</p>
            </article>
          <?php endif; ?>
        </section>
      </main>
    </div>
  </div>

  <div class="im-modal-cortina" data-cerrar-correo-detalle></div>
  <section class="im-dialog im-correos-modal" role="dialog" aria-modal="true" aria-labelledby="correo-detalle-titulo" aria-hidden="true" tabindex="-1" data-modal-correo-detalle>
    <header class="im-dialog__cabecera">
      <div>
        <p class="im-sobrelinea">Detalle del envio</p>
        <h3 id="correo-detalle-titulo">Correo</h3>
      </div>
      <button class="im-boton-icono" type="button" data-cerrar-correo-detalle aria-label="Cerrar dialog"></button>
    </header>
    <div class="im-dialog__contenido">
      <div class="im-correos-detalle-grid">
        <div class="im-correos-detalle-dato"><span>Destinatario</span><strong data-correo-detalle-correo>-</strong></div>
        <div class="im-correos-detalle-dato"><span>Estado</span><strong data-correo-detalle-estado>-</strong></div>
        <div class="im-correos-detalle-dato"><span>Fecha de envio</span><strong data-correo-detalle-fecha>-</strong></div>
        <div class="im-correos-detalle-dato"><span>Template</span><strong data-correo-detalle-template>-</strong></div>
        <div class="im-correos-detalle-dato"><span>Usuario relacionado</span><strong data-correo-detalle-usuario>-</strong></div>
        <div class="im-correos-detalle-dato"><span>Error</span><strong data-correo-detalle-error>-</strong></div>
      </div>
      <div>
        <h4>Asunto</h4>
        <p class="im-correos-contenido" data-correo-detalle-asunto>-</p>
      </div>
      <div>
        <h4>Contenido</h4>
        <iframe class="im-correos-contenido im-correos-contenido--visor" title="Vista HTML del correo" sandbox="allow-same-origin" referrerpolicy="no-referrer" data-correo-detalle-html hidden></iframe>
        <pre class="im-correos-contenido im-correos-contenido--visor" data-correo-detalle-contenido>-</pre>
      </div>
      <div>
        <h4>Meta</h4>
        <pre class="im-correos-contenido" data-correo-detalle-meta>Sin metadata adicional.</pre>
      </div>
    </div>
    <footer class="im-dialog__acciones">
      <button class="im-boton im-boton--texto" type="button" data-cerrar-correo-detalle>Cerrar</button>
    </footer>
  </section>

  <?php require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilView.php'; ?>
  <script src="../../../assets/impulsa_material/js/material.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.querySelector('[data-correos-filtros-form]');
      const submit = document.querySelector('[data-correos-submit]');
      const modal = document.querySelector('[data-modal-correo-detalle]');
      const cortina = document.querySelector('[data-cerrar-correo-detalle].im-modal-cortina');

      if (form && submit) {
        form.addEventListener('submit', () => {
          submit.disabled = true;
          submit.textContent = 'Buscando...';
        });
      }

      if (!modal || !cortina) {
        return;
      }

      let ultimoDisparadorModal = null;
      const focoInicialModal = modal.querySelector('[data-cerrar-correo-detalle]');
      if ('inert' in modal) {
        modal.inert = true;
      }

      const alternarModal = (abrir) => {
        if (!abrir) {
          const elementoActivo = document.activeElement;
          if (elementoActivo instanceof HTMLElement && modal.contains(elementoActivo)) {
            elementoActivo.blur();
          }
        }

        modal.classList.toggle('abierto', abrir);
        cortina.classList.toggle('abierto', abrir);
        modal.setAttribute('aria-hidden', abrir ? 'false' : 'true');

        if ('inert' in modal) {
          modal.inert = !abrir;
        }

        if (abrir) {
          window.requestAnimationFrame(() => {
            if (focoInicialModal instanceof HTMLElement) {
              focoInicialModal.focus();
              return;
            }
            modal.focus();
          });
          return;
        }

        if (ultimoDisparadorModal instanceof HTMLElement) {
          window.requestAnimationFrame(() => ultimoDisparadorModal.focus());
        }
      };

      const setText = (selector, value, fallback = '-') => {
        const node = modal.querySelector(selector);
        if (node) {
          node.textContent = value && String(value).trim() !== '' ? String(value) : fallback;
        }
      };
      const iframeHtml = modal.querySelector('[data-correo-detalle-html]');
      const contenidoTexto = modal.querySelector('[data-correo-detalle-contenido]');
      const sanitizarHtmlCorreo = (html) => {
        const valor = String(html || '').trim();
        if (valor === '') {
          return '';
        }

        const parser = new DOMParser();
        const doc = parser.parseFromString(valor, 'text/html');
        doc.querySelectorAll('script, noscript, template, iframe, object, embed, form').forEach((node) => node.remove());
        doc.querySelectorAll('*').forEach((node) => {
          Array.from(node.attributes).forEach((attr) => {
            const nombre = attr.name.toLowerCase();
            const valorAttr = attr.value.trim().toLowerCase();
            if (nombre.startsWith('on')) {
              node.removeAttribute(attr.name);
              return;
            }
            if ((nombre === 'href' || nombre === 'src' || nombre === 'xlink:href') && valorAttr.startsWith('javascript:')) {
              node.removeAttribute(attr.name);
            }
          });
        });

        return doc.documentElement.outerHTML;
      };
      const htmlTieneContenidoVisible = (html) => {
        const valor = sanitizarHtmlCorreo(html);
        if (valor === '') {
          return false;
        }

        const parser = new DOMParser();
        const doc = parser.parseFromString(valor, 'text/html');
        doc.querySelectorAll('script, style, noscript, template, meta, link, title, head').forEach((node) => node.remove());
        const texto = (doc.body?.textContent || '').replace(/\s+/g, ' ').trim();

        if (texto !== '') {
          return true;
        }

        return Boolean(doc.body?.querySelector('img, table, a, button, svg, section, article, .card, .wrap, .container'));
      };
      const mostrarContenidoTexto = (contenido) => {
        if (iframeHtml) {
          iframeHtml.hidden = true;
          iframeHtml.removeAttribute('srcdoc');
        }
        if (contenidoTexto) {
          contenidoTexto.hidden = false;
        }
        setText('[data-correo-detalle-contenido]', contenido, 'No hay contenido disponible para este correo.');
      };
      const mostrarContenidoHtml = (html, contenidoFallback) => {
        const htmlSeguro = sanitizarHtmlCorreo(html);
        if (!iframeHtml || !contenidoTexto || !htmlTieneContenidoVisible(htmlSeguro)) {
          mostrarContenidoTexto(contenidoFallback);
          return;
        }

        contenidoTexto.hidden = true;
        iframeHtml.hidden = false;
        iframeHtml.onload = () => {
          try {
            const doc = iframeHtml.contentDocument;
            const texto = (doc?.body?.textContent || '').replace(/\s+/g, ' ').trim();
            const tieneElementosVisibles = Boolean(doc?.body?.querySelector('img, table, a, button, svg, section, article, .card, .wrap, .container'));
            if (texto === '' && !tieneElementosVisibles) {
              mostrarContenidoTexto(contenidoFallback);
            }
          } catch (error) {
            mostrarContenidoTexto(contenidoFallback);
          }
        };
        iframeHtml.srcdoc = htmlSeguro;
      };

      document.addEventListener('click', (evento) => {
        const botonVer = evento.target.closest('[data-ver-correo]');
        if (botonVer) {
          ultimoDisparadorModal = botonVer instanceof HTMLElement ? botonVer : null;
          const raw = botonVer.getAttribute('data-ver-correo') || '';
          let data = null;

          try {
            data = JSON.parse(raw);
          } catch (error) {
            return;
          }

          setText('#correo-detalle-titulo', `Correo #${data.id || ''}`, 'Correo');
          setText('[data-correo-detalle-correo]', data.correo);
          setText('[data-correo-detalle-estado]', data.estado === 'enviado' ? 'Enviado' : 'Fallido');
          setText('[data-correo-detalle-fecha]', data.fecha);
          setText('[data-correo-detalle-template]', data.template, 'Sin template');
          setText('[data-correo-detalle-usuario]', data.usuario_relacionado);
          setText('[data-correo-detalle-error]', data.error, 'Sin error registrado');
          setText('[data-correo-detalle-asunto]', data.asunto);
          mostrarContenidoHtml(data.contenido_html, data.contenido);
          setText('[data-correo-detalle-meta]', data.meta, 'Sin metadata adicional.');
          alternarModal(true);
          return;
        }

        if (evento.target.closest('[data-cerrar-correo-detalle]')) {
          alternarModal(false);
        }
      });

      document.addEventListener('keydown', (evento) => {
        if (evento.key === 'Escape') {
          alternarModal(false);
        }
      });
    });
  </script>
</body>
</html>
