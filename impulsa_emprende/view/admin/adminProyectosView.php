<?php
$usuarioCorreo = $usuarioCorreo ?? '';
$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Usuario';
$proyectos = $proyectos ?? [];
$fasesPorProyecto = $fasesPorProyecto ?? [];
$objetivosPorProyecto = $objetivosPorProyecto ?? [];
$contratosPorProyecto = $contratosPorProyecto ?? [];
$mensajeEstadoProyectos = $mensajeEstadoProyectos ?? null;
$h = static fn (mixed $valor): string => htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
$fecha = static function (?string $valor): string {
    $valor = trim((string) $valor);
    if ($valor === '') {
        return '-';
    }
    $timestamp = strtotime($valor);
    return $timestamp ? date('d/m/Y', $timestamp) : $valor;
};
$estadoProyecto = static function (?string $estado): string {
    return [
        'draft' => 'Borrador',
        'planned' => 'Planificado',
        'in_progress' => 'En progreso',
        'paused' => 'Pausado',
        'in_review' => 'En revision',
        'completed' => 'Completado',
        'cancelled' => 'Cancelado',
    ][$estado ?? ''] ?? ucfirst(str_replace('_', ' ', (string) $estado));
};
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Proyectos Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,400,0,0&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../../assets/impulsa_material/css/material.css">
  <style>
    .im-marca__isotipo img { width: 100%; height: 100%; border-radius: inherit; object-fit: cover; }
    .im-accion-salir { color: #ba1a1a; }
    .im-bottom-sheet--perfil { max-width: 860px; max-height: min(760px, calc(100vh - 2rem)); overflow: auto; }
    .im-nav-item__icono[data-icon]::before { content: attr(data-icon); }
    .im-proyecto-modal { width: min(860px, calc(100vw - 2rem)); max-height: min(760px, calc(100vh - 2rem)); grid-template-rows: auto minmax(0, 1fr) auto; }
    .im-proyecto-modal form { display: contents; }
    .im-proyecto-modal .im-dialog__contenido { min-height: 0; overflow-y: auto; }
    .im-proyecto-lista { display: grid; gap: .75rem; }
    .im-proyecto-lista__item { display: grid; gap: .25rem; padding: .75rem; border: 1px solid var(--im-color-borde); border-radius: var(--im-radio-chico); }
    .im-proyecto-lista__item p { margin: 0; color: var(--im-color-texto-suave); white-space: pre-wrap; }
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
        <a class="im-nav-item activo" href="/impulsa_emprende/controller/admin/adminProyectosController.php">
          <span class="im-nav-item__icono" data-icon="work" aria-hidden="true"></span>
          <span class="im-nav-item__texto">Proyectos</span>
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
            <h1>Proyectos</h1>
          </div>
        </div>
        <div class="im-barra-superior__acciones">
          <button class="im-boton-icono im-boton-icono--principal im-tooltip" type="button" data-abrir-config-tema aria-label="Configurar temas" data-tooltip="Configurar estilos"></button>
          <button class="im-boton-icono material-symbols-rounded im-tooltip" type="button" data-abrir-perfil aria-label="Mi perfil" data-tooltip="Mi perfil">account_circle</button>
          <a class="im-boton-icono material-symbols-rounded im-tooltip im-accion-salir" href="/auth/logout.php" aria-label="Salir" data-tooltip="Salir">logout</a>
        </div>
      </header>

      <main class="im-contenido">
        <section class="im-seccion-documento activa" id="proyectos">
          <div class="im-encabezado-seccion">
            <div>
              <p class="im-sobrelinea">Administracion</p>
              <h2>Gestion de proyectos</h2>
              <p>Administra fases, objetivos y contrato de los proyectos visibles para clientes.</p>
            </div>
            <span class="im-chip"><?= number_format(count($proyectos), 0, ',', '.') ?> proyectos</span>
          </div>

          <?php if (is_array($mensajeEstadoProyectos) && trim((string) ($mensajeEstadoProyectos['mensaje'] ?? '')) !== ''): ?>
            <div class="im-alerta im-alerta--info" role="status"><?= $h($mensajeEstadoProyectos['mensaje'] ?? '') ?></div>
          <?php endif; ?>

          <?php if (!$proyectos): ?>
            <article class="im-tarjeta"><h3>No hay proyectos para mostrar.</h3><p>Cuando se creen proyectos desde solicitudes o carga interna, apareceran en esta vista.</p></article>
          <?php else: ?>
            <article class="im-tabla-tareas__tarjeta">
              <div class="im-tabla-tareas__cabecera">
                <div>
                  <h3>Proyectos registrados</h3>
                  <p>Listado completo con accesos a gestion de fases, objetivos y contrato.</p>
                </div>
              </div>
              <div class="im-tabla-tareas__scroll">
                <table class="im-tabla-tareas">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Proyecto</th>
                      <th>Cliente</th>
                      <th>Estado</th>
                      <th>Avance</th>
                      <th>Fases</th>
                      <th>Objetivos</th>
                      <th>Contrato</th>
                      <th class="im-tabla-tareas__acciones">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($proyectos as $proyecto): ?>
                      <?php
                        $projectId = (int) ($proyecto['id'] ?? 0);
                        $fases = $fasesPorProyecto[$projectId] ?? [];
                        $objetivos = $objetivosPorProyecto[$projectId] ?? [];
                        $contrato = $contratosPorProyecto[$projectId] ?? null;
                      ?>
                      <tr>
                        <td><?= $projectId ?></td>
                        <td class="im-tabla-tareas__nombre">
                          <?= $h($proyecto['project_name'] ?? '') ?>
                          <br><small><?= $h($proyecto['project_type'] ?? '') ?></small>
                        </td>
                        <td>
                          <?= $h($proyecto['client_name'] ?? '') ?>
                          <br><small><?= $h($proyecto['client_email'] ?? '') ?></small>
                        </td>
                        <td><span class="im-chip"><?= $h($estadoProyecto($proyecto['status'] ?? '')) ?></span></td>
                        <td><?= (int) ($proyecto['progress_percent'] ?? 0) ?>%</td>
                        <td><?= count($fases) ?></td>
                        <td><?= count($objetivos) ?></td>
                        <td>
                          <?php if ($contrato): ?>
                            <span class="im-chip <?= (int) ($contrato['is_signed'] ?? 0) === 1 ? 'im-chip--completado' : 'im-chip--pendiente' ?>"><?= (int) ($contrato['is_signed'] ?? 0) === 1 ? 'Firmado' : 'Pendiente' ?></span>
                          <?php else: ?>
                            <span class="im-chip im-chip--alerta">Sin contrato</span>
                          <?php endif; ?>
                        </td>
                        <td class="im-tabla-tareas__acciones">
                          <div class="im-menu-tabla" data-im-menu>
                            <button class="im-boton-icono im-boton-icono--menu-tabla material-symbols-rounded" type="button" data-im-menu-trigger aria-label="Opciones de tabla" aria-haspopup="menu" aria-expanded="false">more_horiz</button>
                            <div class="im-menu-flotante im-menu-tabla__panel" role="menu" data-im-menu-panel>
                              <button type="button" role="menuitem" data-gestionar-fases="<?= $projectId ?>"><span class="material-symbols-rounded" aria-hidden="true">timeline</span>Fases</button>
                              <button type="button" role="menuitem" data-gestionar-objetivos="<?= $projectId ?>"><span class="material-symbols-rounded" aria-hidden="true">flag</span>Objetivos</button>
                              <button type="button" role="menuitem" data-gestionar-contrato="<?= $projectId ?>"><span class="material-symbols-rounded" aria-hidden="true">contract</span>Contrato</button>
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
        </section>
      </main>
    </div>
  </div>

  <div class="im-modal-cortina" data-cerrar-proyecto-modal></div>

  <section class="im-dialog im-proyecto-modal" role="dialog" aria-modal="true" aria-labelledby="modal-fases-titulo" aria-hidden="true" data-modal-fases>
    <header class="im-dialog__cabecera">
      <div><p class="im-sobrelinea" data-fases-proyecto></p><h3 id="modal-fases-titulo">Fases</h3></div>
      <button class="im-boton-icono" type="button" data-cerrar-proyecto-modal aria-label="Cerrar dialog"></button>
    </header>
    <form method="post" action="/impulsa_emprende/controller/admin/adminProyectosController.php">
      <input type="hidden" name="accion_proyecto" value="crear_fase">
      <input type="hidden" name="project_id" data-fase-project-id>
      <div class="im-dialog__contenido">
        <div class="im-proyecto-lista" data-fases-lista></div>
        <div class="im-formulario">
          <label class="im-campo im-campo-material im-campo--ancho"><span>Titulo</span><input type="text" name="title" maxlength="180" required></label>
          <label class="im-campo im-campo-material im-campo--ancho"><span>Descripcion</span><textarea name="description" rows="3"></textarea></label>
          <label class="im-campo im-campo-material"><span>Orden</span><input type="number" name="phase_order" min="1" value="1" required></label>
          <label class="im-campo im-campo-material"><span>Duracion dias</span><input type="number" name="duration_days" min="1"></label>
          <label class="im-campo im-campo-material"><span>Vencimiento</span><input type="date" name="due_date"></label>
          <label class="im-campo im-campo-material"><span>Estado</span><select name="status"><option value="pending">Pendiente</option><option value="in_progress">En progreso</option><option value="blocked">Bloqueada</option><option value="done">Finalizada</option></select></label>
        </div>
      </div>
      <footer class="im-dialog__acciones"><button class="im-boton im-boton--texto" type="button" data-cerrar-proyecto-modal>Cancelar</button><button class="im-boton im-boton--principal" type="submit">Crear fase</button></footer>
    </form>
  </section>

  <section class="im-dialog im-proyecto-modal" role="dialog" aria-modal="true" aria-labelledby="modal-objetivos-titulo" aria-hidden="true" data-modal-objetivos>
    <header class="im-dialog__cabecera">
      <div><p class="im-sobrelinea" data-objetivos-proyecto></p><h3 id="modal-objetivos-titulo">Objetivos</h3></div>
      <button class="im-boton-icono" type="button" data-cerrar-proyecto-modal aria-label="Cerrar dialog"></button>
    </header>
    <form method="post" action="/impulsa_emprende/controller/admin/adminProyectosController.php">
      <input type="hidden" name="accion_proyecto" value="crear_objetivo">
      <input type="hidden" name="project_id" data-objetivo-project-id>
      <div class="im-dialog__contenido">
        <div class="im-alerta im-alerta--info">La base no tiene tabla project_objectives; estos objetivos se guardan como entregables visibles del proyecto.</div>
        <div class="im-proyecto-lista" data-objetivos-lista></div>
        <div class="im-formulario">
          <label class="im-campo im-campo-material im-campo--ancho"><span>Titulo</span><input type="text" name="title" maxlength="180" required></label>
          <label class="im-campo im-campo-material im-campo--ancho"><span>Descripcion</span><textarea name="description" rows="3"></textarea></label>
          <label class="im-campo im-campo-material"><span>Fase</span><select name="phase_id" data-objetivo-fases><option value="0">Sin fase asociada</option></select></label>
          <label class="im-campo im-campo-material"><span>Tipo</span><select name="deliverable_type"><option value="document">Documento</option><option value="design">Diseno</option><option value="development">Desarrollo</option><option value="deployment">Publicacion</option><option value="training">Capacitacion</option><option value="other">Otro</option></select></label>
          <label class="im-campo im-campo-material"><span>Estado</span><select name="status"><option value="pending">Pendiente</option><option value="in_progress">En progreso</option><option value="ready_for_review">Listo para revision</option><option value="delivered">Entregado</option></select></label>
          <label class="im-campo im-campo-material"><span>Vencimiento</span><input type="date" name="due_date"></label>
          <label class="im-slide-toggle"><input type="checkbox" name="client_visible" checked><span></span>Visible para cliente</label>
        </div>
      </div>
      <footer class="im-dialog__acciones"><button class="im-boton im-boton--texto" type="button" data-cerrar-proyecto-modal>Cancelar</button><button class="im-boton im-boton--principal" type="submit">Crear objetivo</button></footer>
    </form>
  </section>

  <section class="im-dialog im-proyecto-modal" role="dialog" aria-modal="true" aria-labelledby="modal-contrato-titulo" aria-hidden="true" data-modal-contrato>
    <header class="im-dialog__cabecera">
      <div><p class="im-sobrelinea" data-contrato-proyecto></p><h3 id="modal-contrato-titulo">Contrato</h3></div>
      <button class="im-boton-icono" type="button" data-cerrar-proyecto-modal aria-label="Cerrar dialog"></button>
    </header>
    <form method="post" action="/impulsa_emprende/controller/admin/adminProyectosController.php">
      <input type="hidden" name="accion_proyecto" value="guardar_contrato">
      <input type="hidden" name="project_id" data-contrato-project-id>
      <div class="im-dialog__contenido">
        <div class="im-alerta im-alerta--info" data-contrato-estado></div>
        <label class="im-campo im-campo-material im-campo--ancho"><span>Nombre del contrato</span><input type="text" name="contract_name" maxlength="180" required data-contrato-nombre></label>
        <label class="im-campo im-campo-material im-campo--ancho"><span>Contenido</span><textarea name="contract_text" rows="12" required data-contrato-texto></textarea></label>
      </div>
      <footer class="im-dialog__acciones"><button class="im-boton im-boton--texto" type="button" data-cerrar-proyecto-modal>Cancelar</button><button class="im-boton im-boton--principal" type="submit">Guardar contrato</button></footer>
    </form>
  </section>

  <?php require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilView.php'; ?>
  <script src="../../../assets/impulsa_material/js/material.js"></script>
  <script>
    (() => {
      const proyectos = <?= json_encode($proyectos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
      const fases = <?= json_encode($fasesPorProyecto, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
      const objetivos = <?= json_encode($objetivosPorProyecto, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
      const contratos = <?= json_encode($contratosPorProyecto, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
      const proyectosPorId = new Map(proyectos.map((proyecto) => [String(proyecto.id), proyecto]));
      const cortina = document.querySelector('[data-cerrar-proyecto-modal].im-modal-cortina');
      const modales = [...document.querySelectorAll('[data-modal-fases], [data-modal-objetivos], [data-modal-contrato]')];
      const escapeHtml = (value) => String(value ?? '').replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;').replaceAll('"', '&quot;').replaceAll("'", '&#039;');
      const abrirModal = (modal) => { modales.forEach((item) => item.classList.remove('abierto')); modal.classList.add('abierto'); cortina?.classList.add('abierto'); modal.setAttribute('aria-hidden', 'false'); };
      const cerrarModales = () => { modales.forEach((item) => { item.classList.remove('abierto'); item.setAttribute('aria-hidden', 'true'); }); cortina?.classList.remove('abierto'); };
      const estado = (value) => String(value || '').replaceAll('_', ' ');
      const proyectoNombre = (id) => proyectosPorId.get(String(id))?.project_name || `Proyecto #${id}`;

      document.querySelectorAll('[data-cerrar-proyecto-modal]').forEach((boton) => boton.addEventListener('click', cerrarModales));
      document.addEventListener('keydown', (event) => { if (event.key === 'Escape') cerrarModales(); });

      document.querySelectorAll('[data-gestionar-fases]').forEach((boton) => {
        boton.addEventListener('click', () => {
          const id = boton.dataset.gestionarFases;
          const modal = document.querySelector('[data-modal-fases]');
          modal.querySelector('[data-fase-project-id]').value = id;
          modal.querySelector('[data-fases-proyecto]').textContent = proyectoNombre(id);
          modal.querySelector('[data-fases-lista]').innerHTML = (fases[id] || []).map((fase) => `
            <div class="im-proyecto-lista__item"><strong>${escapeHtml(fase.phase_order)}. ${escapeHtml(fase.title)}</strong><p>${escapeHtml(fase.description || 'Sin descripcion')}</p><div class="im-chip-lista"><span class="im-chip">${escapeHtml(estado(fase.status))}</span><span class="im-chip">Vence: ${escapeHtml(fase.due_date || '-')}</span></div></div>
          `).join('') || '<div class="im-alerta im-alerta--info">Todavia no hay fases cargadas.</div>';
          abrirModal(modal);
        });
      });

      document.querySelectorAll('[data-gestionar-objetivos]').forEach((boton) => {
        boton.addEventListener('click', () => {
          const id = boton.dataset.gestionarObjetivos;
          const modal = document.querySelector('[data-modal-objetivos]');
          const selectFases = modal.querySelector('[data-objetivo-fases]');
          modal.querySelector('[data-objetivo-project-id]').value = id;
          modal.querySelector('[data-objetivos-proyecto]').textContent = proyectoNombre(id);
          selectFases.innerHTML = '<option value="0">Sin fase asociada</option>' + (fases[id] || []).map((fase) => `<option value="${Number(fase.id)}">${escapeHtml(fase.title)}</option>`).join('');
          modal.querySelector('[data-objetivos-lista]').innerHTML = (objetivos[id] || []).map((objetivo) => `
            <div class="im-proyecto-lista__item"><strong>${escapeHtml(objetivo.title)}</strong><p>${escapeHtml(objetivo.description || 'Sin descripcion')}</p><div class="im-chip-lista"><span class="im-chip">${escapeHtml(estado(objetivo.status))}</span><span class="im-chip">${escapeHtml(objetivo.phase_title || 'Sin fase')}</span><span class="im-chip">Vence: ${escapeHtml(objetivo.due_date || '-')}</span></div></div>
          `).join('') || '<div class="im-alerta im-alerta--info">Todavia no hay objetivos cargados.</div>';
          abrirModal(modal);
        });
      });

      document.querySelectorAll('[data-gestionar-contrato]').forEach((boton) => {
        boton.addEventListener('click', () => {
          const id = boton.dataset.gestionarContrato;
          const modal = document.querySelector('[data-modal-contrato]');
          const contrato = contratos[id] || null;
          modal.querySelector('[data-contrato-project-id]').value = id;
          modal.querySelector('[data-contrato-proyecto]').textContent = proyectoNombre(id);
          modal.querySelector('[data-contrato-estado]').textContent = contrato ? `Contrato existente. Se guardara como version ${Number(contrato.version_number || 1) + 1}.` : 'No hay contrato cargado. Se creara el contrato del proyecto.';
          modal.querySelector('[data-contrato-nombre]').value = contrato?.contract_name || `Contrato - ${proyectoNombre(id)}`;
          modal.querySelector('[data-contrato-texto]').value = contrato?.contract_text || '';
          abrirModal(modal);
        });
      });
    })();
  </script>
</body>
</html>
