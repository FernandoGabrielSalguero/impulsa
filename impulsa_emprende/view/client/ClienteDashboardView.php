<?php
$usuarioCorreo = $usuarioCorreo ?? '';
$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Cliente';
$dashboardData = $dashboardData ?? [];
$resumen = $dashboardData['resumen'] ?? [];
$proyectos = $dashboardData['proyectos'] ?? [];
$actualizaciones = $dashboardData['actualizaciones'] ?? [];
$contratos = $dashboardData['contratos'] ?? [];

$h = static fn (mixed $valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
$fecha = static function (?string $valor): string {
    $valor = trim((string) $valor);
    if ($valor === '') {
        return 'Sin fecha';
    }

    $timestamp = strtotime($valor);
    return $timestamp ? date('d/m/Y', $timestamp) : $valor;
};
$estadoProyecto = static function (?string $estado): array {
    $mapa = [
        'draft' => ['Borrador', 'im-chip--pendiente'],
        'planned' => ['Planificado', 'im-chip--activo'],
        'in_progress' => ['En progreso', 'im-chip--activo'],
        'paused' => ['Pausado', 'im-chip--alerta'],
        'in_review' => ['En revision', 'im-chip--alerta'],
        'completed' => ['Completado', 'im-chip--completado'],
        'cancelled' => ['Cancelado', 'im-chip--pendiente'],
    ];

    return $mapa[$estado ?? ''] ?? [ucfirst(str_replace('_', ' ', (string) $estado)), 'im-chip'];
};
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard cliente | Impulsa</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,400,0,0&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/impulsa_material/css/material.css">
  <style>
    .im-marca__isotipo img {
      width: 100%;
      height: 100%;
      border-radius: inherit;
      object-fit: cover;
    }

    .im-accion-salir {
      color: #ba1a1a;
    }

    .im-bottom-sheet--perfil {
      max-width: 860px;
      max-height: min(760px, calc(100vh - 2rem));
      overflow: auto;
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
          <span>Cliente</span>
        </div>
      </div>
      <nav class="im-navegacion">
        <a class="im-nav-item activo" href="#dashboard" data-seccion="dashboard">
          <span class="material-symbols-rounded" aria-hidden="true">dashboard</span>
          <span class="im-nav-item__texto">Dashboard</span>
        </a>
        <a class="im-nav-item" href="#proyectos" data-seccion="proyectos">
          <span class="material-symbols-rounded" aria-hidden="true">work</span>
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
            <h1>Dashboard cliente</h1>
          </div>
        </div>
        <div class="im-barra-superior__acciones">
          <button class="im-boton-icono im-boton-icono--principal im-tooltip" type="button" data-abrir-config-tema aria-label="Configurar temas" data-tooltip="Configurar estilos"></button>
          <button class="im-boton-icono material-symbols-rounded im-tooltip" type="button" data-abrir-perfil aria-label="Mi perfil" data-tooltip="Mi perfil">account_circle</button>
          <a class="im-boton-icono material-symbols-rounded im-tooltip im-accion-salir" href="/auth/logout.php" aria-label="Salir" data-tooltip="Salir">logout</a>
        </div>
      </header>

      <main class="im-contenido">
        <section class="im-seccion-documento activa" id="dashboard" data-panel="dashboard">
          <div class="im-encabezado-seccion">
            <div>
              <p class="im-sobrelinea">Inicio</p>
              <h2>Hola, <?= $h($usuarioMarcaNombre) ?></h2>
              <p>Seguimiento de proyectos, entregables y comunicaciones visibles para tu cuenta.</p>
            </div>
          </div>

          <div class="im-grilla im-grilla--metricas">
            <article class="im-tarjeta im-tarjeta--metrica"><span class="im-etiqueta">Proyectos</span><strong><?= (int) ($resumen['proyectos_total'] ?? 0) ?></strong><small>Visibles</small></article>
            <article class="im-tarjeta im-tarjeta--metrica"><span class="im-etiqueta">Activos</span><strong><?= (int) ($resumen['proyectos_activos'] ?? 0) ?></strong><small>En curso o revision</small></article>
            <article class="im-tarjeta im-tarjeta--metrica"><span class="im-etiqueta">Entregables</span><strong><?= (int) ($resumen['entregables_pendientes'] ?? 0) ?></strong><small>Pendientes</small></article>
            <article class="im-tarjeta im-tarjeta--metrica"><span class="im-etiqueta">Contratos</span><strong><?= (int) ($resumen['contratos_pendientes'] ?? 0) ?></strong><small>Pendientes</small></article>
          </div>

          <div class="im-grilla im-grilla--dashboard">
            <article class="im-tarjeta">
              <div class="im-tarjeta__cabecera">
                <div>
                  <h3>Proyectos recientes</h3>
                  <p>Estado general de los trabajos habilitados para tu cuenta.</p>
                </div>
              </div>
              <?php if (!$proyectos): ?>
                <div class="im-alerta im-alerta--info">Todavia no hay proyectos visibles para tu usuario.</div>
              <?php else: ?>
                <div class="im-tabla-contenedor">
                  <table class="im-tabla">
                    <thead><tr><th>Proyecto</th><th>Estado</th><th>Avance</th><th>Entrega objetivo</th></tr></thead>
                    <tbody>
                      <?php foreach (array_slice($proyectos, 0, 5) as $proyecto): ?>
                        <?php [$estadoTexto, $estadoClase] = $estadoProyecto($proyecto['status'] ?? ''); ?>
                        <tr>
                          <td><?= $h($proyecto['project_name'] ?? '') ?></td>
                          <td><span class="im-chip <?= $h($estadoClase) ?>"><?= $h($estadoTexto) ?></span></td>
                          <td><?= (int) ($proyecto['progress_percent'] ?? 0) ?>%</td>
                          <td><?= $fecha($proyecto['target_delivery_date'] ?? '') ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              <?php endif; ?>
            </article>

            <article class="im-tarjeta">
              <div class="im-tarjeta__cabecera">
                <div>
                  <h3>Ultimas actualizaciones</h3>
                  <p>Novedades publicadas por el equipo.</p>
                </div>
              </div>
              <?php if (!$actualizaciones): ?>
                <div class="im-alerta im-alerta--info">No hay actualizaciones visibles por el momento.</div>
              <?php else: ?>
                <ul class="im-lista">
                  <?php foreach ($actualizaciones as $actualizacion): ?>
                    <li>
                      <strong><?= $h($actualizacion['title'] ?? '') ?></strong>
                      <span><?= $h($actualizacion['project_name'] ?? '') ?> - <?= $fecha($actualizacion['created_at'] ?? '') ?></span>
                    </li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
            </article>
          </div>
        </section>

        <section class="im-seccion-documento" id="proyectos" data-panel="proyectos">
          <div class="im-encabezado-seccion">
            <div>
              <p class="im-sobrelinea">Seguimiento</p>
              <h2>Mis proyectos</h2>
              <p>Detalle de avances, entregables y contratos asociados a tu usuario cliente.</p>
            </div>
          </div>

          <div class="im-grilla im-grilla--dos-columnas">
            <?php foreach ($proyectos as $proyecto): ?>
              <?php [$estadoTexto, $estadoClase] = $estadoProyecto($proyecto['status'] ?? ''); ?>
              <article class="im-tarjeta">
                <div class="im-tarjeta__cabecera">
                  <div>
                    <h3><?= $h($proyecto['project_name'] ?? '') ?></h3>
                    <p><?= $h($proyecto['summary'] ?? $proyecto['scope_summary'] ?? '') ?></p>
                  </div>
                  <span class="im-chip <?= $h($estadoClase) ?>"><?= $h($estadoTexto) ?></span>
                </div>
                <div class="im-chip-lista">
                  <span class="im-chip"><?= (int) ($proyecto['progress_percent'] ?? 0) ?>% avance</span>
                  <span class="im-chip"><?= (int) ($proyecto['fases_total'] ?? 0) ?> fases</span>
                  <span class="im-chip"><?= (int) ($proyecto['entregables_total'] ?? 0) ?> entregables</span>
                  <span class="im-chip"><?= (int) ($proyecto['actualizaciones_total'] ?? 0) ?> actualizaciones</span>
                </div>
                <p>Entrega objetivo: <?= $fecha($proyecto['target_delivery_date'] ?? '') ?></p>
              </article>
            <?php endforeach; ?>
            <?php if (!$proyectos): ?>
              <article class="im-tarjeta"><h3>Sin proyectos visibles</h3><p>Cuando el equipo cree un proyecto para tu usuario, vas a verlo en esta seccion.</p></article>
            <?php endif; ?>
          </div>

          <article class="im-tarjeta">
            <div class="im-tarjeta__cabecera">
              <div>
                <h3>Contratos</h3>
                <p>Documentos contractuales asociados a tus proyectos visibles.</p>
              </div>
            </div>
            <?php if (!$contratos): ?>
              <div class="im-alerta im-alerta--info">No hay contratos vinculados a tus proyectos visibles.</div>
            <?php else: ?>
              <div class="im-tabla-contenedor">
                <table class="im-tabla">
                  <thead><tr><th>Contrato</th><th>Proyecto</th><th>Version</th><th>Estado</th><th>Firma</th></tr></thead>
                  <tbody>
                    <?php foreach ($contratos as $contrato): ?>
                      <tr>
                        <td><?= $h($contrato['contract_name'] ?? '') ?></td>
                        <td><?= $h($contrato['project_name'] ?? '') ?></td>
                        <td><?= (int) ($contrato['version_number'] ?? 1) ?></td>
                        <td><span class="im-chip <?= (int) ($contrato['is_signed'] ?? 0) === 1 ? 'im-chip--completado' : 'im-chip--pendiente' ?>"><?= (int) ($contrato['is_signed'] ?? 0) === 1 ? 'Firmado' : 'Pendiente' ?></span></td>
                        <td><?= $fecha($contrato['signed_at'] ?? '') ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>
          </article>
        </section>
      </main>
    </div>
  </div>

  <?php require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilView.php'; ?>
  <script src="/assets/impulsa_material/js/material.js"></script>
</body>
</html>
