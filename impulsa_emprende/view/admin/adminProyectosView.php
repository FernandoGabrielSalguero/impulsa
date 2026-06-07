<?php
$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Usuario';
$proyectos = $proyectos ?? [];
$fasesPorProyecto = $fasesPorProyecto ?? [];
$objetivosPorProyecto = $objetivosPorProyecto ?? [];
$contratosPorProyecto = $contratosPorProyecto ?? [];
$mensajeEstadoProyectos = $mensajeEstadoProyectos ?? null;
$h = static fn (mixed $valor): string => htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
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
$claseEstadoProyecto = static function (?string $estado): string {
    return [
        'draft' => 'im-chip--estado-borrador',
        'planned' => 'im-chip--estado-planificado',
        'in_progress' => 'im-chip--estado-progreso',
        'paused' => 'im-chip--estado-pausado',
        'in_review' => 'im-chip--estado-revision',
        'completed' => 'im-chip--estado-completado',
        'cancelled' => 'im-chip--estado-cancelado',
    ][$estado ?? ''] ?? 'im-chip--estado-borrador';
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
    .im-chip--estado-borrador { background: var(--im-color-superficie-2); color: var(--im-color-texto-suave); }
    .im-chip--estado-planificado { background: var(--im-color-secundario-suave); color: var(--im-color-secundario); }
    .im-chip--estado-progreso { background: var(--im-color-principal-suave); color: var(--im-color-principal); }
    .im-chip--estado-pausado,
    .im-chip--estado-revision { background: var(--im-color-alerta-suave); color: var(--im-color-alerta); }
    .im-chip--estado-completado { background: var(--im-color-exito-suave); color: var(--im-color-exito); }
    .im-chip--estado-cancelado { background: #f3d8df; color: #ba1a1a; }
    .im-snackbar[data-estado="error"] { background: #ba1a1a; color: #fff; }
    .im-snackbar[data-estado="error"] button { color: #fff; }
    .im-proyecto-modal { width: min(1280px, calc(100vw - 2rem)); max-height: min(880px, calc(100vh - 2rem)); grid-template-rows: auto minmax(0, 1fr) auto; }
    .im-proyecto-modal .im-dialog__contenido { min-height: 0; overflow: auto; }
    .im-pm-contenido { display: grid; gap: 1rem; }
    .im-pm-cabecera h3 { margin: .1rem 0 0; }
    .im-pm-panel { display: grid; gap: .85rem; padding: 1rem; border: 1px solid var(--im-color-borde); border-radius: var(--im-radio-chico); background: var(--im-color-superficie); }
    .im-pm-panel__cabecera { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; }
    .im-pm-panel__cabecera h4 { margin: .15rem 0 .25rem; }
    .im-pm-panel__cabecera p { margin: 0; color: var(--im-color-texto-suave); }
    .im-pm-progreso { display: grid; justify-items: end; gap: .15rem; min-width: 190px; color: var(--im-color-texto-suave); text-align: right; }
    .im-pm-progreso strong { font-size: 1.5rem; color: var(--im-color-principal); }
    .im-pm-progreso__barra { width: min(220px, 100%); height: .45rem; overflow: hidden; border-radius: 999px; background: var(--im-color-superficie-2); }
    .im-pm-progreso__barra span { display: block; width: 0; height: 100%; border-radius: inherit; background: var(--im-color-principal); transition: width .2s ease; }
    .im-pm-form { display: grid; grid-template-columns: repeat(4, minmax(150px, 1fr)); gap: .75rem; align-items: start; }
    .im-pm-form[hidden] { display: none; }
    .im-pm-form--compacto { grid-template-columns: repeat(2, minmax(150px, 1fr)); padding: .85rem; border: 1px solid var(--im-color-borde); border-radius: var(--im-radio-chico); background: color-mix(in srgb, var(--im-color-superficie-2) 55%, var(--im-color-superficie)); }
    .im-pm-form--objetivo { margin-top: .65rem; }
    .im-pm-fase > .im-pm-form--compacto,
    .im-pm-objetivo .im-pm-form--compacto { grid-template-columns: 1fr; width: 100%; box-sizing: border-box; padding: .9rem; }
    .im-pm-form .im-campo,
    .im-pm-form .im-slide-toggle,
    .im-pm-form input,
    .im-pm-form select,
    .im-pm-form textarea { min-width: 0; max-width: 100%; box-sizing: border-box; }
    .im-pm-form .im-campo--ancho { grid-column: 1 / -1; }
    .im-pm-form textarea { resize: vertical; }
    .im-pm-form__acciones { grid-column: 1 / -1; }
    .im-pm-fase > .im-pm-form--compacto .im-pm-form__acciones,
    .im-pm-objetivo .im-pm-form--compacto .im-pm-form__acciones { position: sticky; bottom: -.9rem; z-index: 1; margin: .1rem -.9rem -.9rem; padding: .7rem .9rem; background: color-mix(in srgb, var(--im-color-superficie-2) 72%, var(--im-color-superficie)); border-top: 1px solid var(--im-color-borde); }
    .im-pm-toggle { align-self: center; min-height: 44px; }
    .im-pm-dato-calculado { display: grid; align-content: center; min-height: 56px; padding: .55rem .75rem; border: 1px solid var(--im-color-borde); border-radius: var(--im-radio-chico); background: var(--im-color-superficie); }
    .im-pm-dato-calculado span { color: var(--im-color-texto-suave); font-size: .78rem; font-weight: 700; }
    .im-pm-dato-calculado strong { margin-top: .15rem; color: var(--im-color-principal); }
    .im-pm-dato-calculado--compacto { min-height: auto; background: transparent; }
    .im-pm-tablero { display: grid; grid-auto-flow: column; grid-auto-columns: minmax(300px, 360px); align-items: start; gap: .85rem; overflow-x: auto; padding-bottom: .35rem; scroll-snap-type: x proximity; }
    .im-pm-fase { display: grid; align-content: start; gap: .75rem; min-height: 360px; max-height: min(680px, calc(100vh - 280px)); overflow-x: hidden; overflow-y: auto; padding: .9rem; border: 1px solid var(--im-color-borde); border-radius: var(--im-radio-chico); background: color-mix(in srgb, var(--im-color-superficie) 88%, var(--im-color-superficie-2)); scroll-snap-align: start; }
    .im-pm-fase__cabecera { display: flex; justify-content: space-between; gap: .75rem; }
    .im-pm-fase__cabecera h5 { margin: .45rem 0 .25rem; font-size: 1rem; }
    .im-pm-fase__cabecera p,
    .im-pm-objetivo p { margin: 0; color: var(--im-color-texto-suave); }
    .im-pm-acciones { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: .35rem; }
    .im-pm-accion-eliminar { color: #ba1a1a; }
    .im-pm-fase__meta { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .4rem; color: var(--im-color-texto-suave); font-size: .82rem; }
    .im-pm-objetivos { display: grid; gap: .55rem; min-height: 0; padding-right: .15rem; }
    .im-pm-objetivos__titulo { font-weight: 700; color: var(--im-color-texto); }
    .im-pm-objetivo { display: grid; gap: .55rem; overflow: hidden; padding: .75rem; border: 1px solid color-mix(in srgb, var(--im-color-borde) 82%, transparent); border-radius: var(--im-radio-chico); background: var(--im-color-superficie); }
    .im-pm-objetivo__cabecera { display: flex; align-items: flex-start; justify-content: space-between; gap: .5rem; }
    .im-pm-chips { display: flex; flex-wrap: wrap; gap: .35rem; }
    .im-pm-agregar { justify-self: start; }
    .im-pm-vacio { display: grid; place-items: center; gap: .35rem; min-height: 260px; padding: 2rem; border: 1px dashed var(--im-color-borde); border-radius: var(--im-radio-chico); color: var(--im-color-texto-suave); text-align: center; }
    .im-pm-vacio .material-symbols-rounded { font-size: 2rem; color: var(--im-color-principal); }
    .im-pm-estado--done,
    .im-pm-estado--delivered,
    .im-pm-estado--completed { background: var(--im-color-exito-suave); color: var(--im-color-exito); }
    .im-pm-estado--blocked,
    .im-pm-estado--paused,
    .im-pm-estado--cancelled { background: var(--im-color-alerta-suave); color: var(--im-color-alerta); }
    @media (max-width: 900px) {
      .im-pm-panel__cabecera { display: grid; }
      .im-pm-progreso { justify-items: start; text-align: left; }
      .im-pm-form,
      .im-pm-form--compacto { grid-template-columns: 1fr; }
      .im-pm-tablero { grid-auto-columns: minmax(280px, 88vw); }
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
              <p>Lista proyectos y abre el gestor compartido para administrar fases, objetivos y contrato.</p>
            </div>
            <span class="im-chip"><?= number_format(count($proyectos), 0, ',', '.') ?> proyectos</span>
          </div>

          <?php if (!$proyectos): ?>
            <article class="im-tarjeta"><h3>No hay proyectos para mostrar.</h3><p>Cuando se creen proyectos desde solicitudes o carga interna, apareceran en esta vista.</p></article>
          <?php else: ?>
            <article class="im-tabla-tareas__tarjeta">
              <div class="im-tabla-tareas__cabecera">
                <div>
                  <h3>Proyectos registrados</h3>
                  <p>Listado completo con accesos a gestion de proyecto y contrato.</p>
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
                        <td><span class="im-chip <?= $h($claseEstadoProyecto($proyecto['status'] ?? '')) ?>"><?= $h($estadoProyecto($proyecto['status'] ?? '')) ?></span></td>
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
                              <button type="button" role="menuitem" data-abrir-gestor-proyecto="<?= $projectId ?>"><span class="material-symbols-rounded" aria-hidden="true">account_tree</span>Gestor de proyectos</button>
                              <button type="button" role="menuitem" data-abrir-contrato-proyecto="<?= $projectId ?>"><span class="material-symbols-rounded" aria-hidden="true">contract</span>Contrato</button>
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

  <?php require __DIR__ . '/../../partials/components/admin/proyect manager/adminProyectManagerView.php'; ?>
  <?php require __DIR__ . '/../../partials/components/admin/contratos/adminContratoView.php'; ?>
  <?php require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilView.php'; ?>
  <script src="../../../assets/impulsa_material/js/material.js"></script>
  <?php if (is_array($mensajeEstadoProyectos) && trim((string) ($mensajeEstadoProyectos['mensaje'] ?? '')) !== ''): ?>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const snackbar = document.querySelector('.im-snackbar');
        const mensaje = <?= json_encode((string) ($mensajeEstadoProyectos['mensaje'] ?? ''), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        const estado = <?= json_encode((string) ($mensajeEstadoProyectos['estado'] ?? 'ok'), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

        if (!snackbar || !mensaje) {
          return;
        }

        snackbar.dataset.estado = estado === 'error' ? 'error' : 'ok';
        snackbar.querySelector('span').textContent = mensaje;
        snackbar.classList.add('abierto');
        window.clearTimeout(window.imProyectosSnackbarTimer);
        window.imProyectosSnackbarTimer = window.setTimeout(() => snackbar.classList.remove('abierto'), 4200);
      });
    </script>
  <?php endif; ?>
</body>
</html>
