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
    .im-proyecto-modal { width: min(1100px, calc(100vw - 2rem)); max-height: min(820px, calc(100vh - 2rem)); grid-template-rows: auto minmax(0, 1fr) auto; }
    .im-proyecto-modal form { display: contents; }
    .im-proyecto-modal .im-dialog__contenido { min-height: 0; overflow-y: auto; }
    .im-proyecto-lista { display: grid; gap: .85rem; }
    .im-proyecto-lista__item { display: grid; gap: .65rem; padding: .75rem; border: 1px solid var(--im-color-borde); border-radius: var(--im-radio-chico); }
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
</body>
</html>
