<?php
$usuarioCorreo = $usuarioCorreo ?? '';
$usuarioInicial = $usuarioInicial ?? '?';
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Cliente';
$dashboardData = $dashboardData ?? [];

$resumen = $dashboardData['resumen'] ?? [];
$proyectos = $dashboardData['proyectos'] ?? [];
$actualizaciones = $dashboardData['actualizaciones'] ?? [];
$suscripcionesMarketing = $dashboardData['suscripcionesMarketing'] ?? [];
$reportesMarketing = $dashboardData['reportesMarketing'] ?? [];
$contratos = $dashboardData['contratos'] ?? [];
$definicion = $dashboardData['definicion'] ?? [];
$paginaWeb = $dashboardData['paginaWeb'] ?? [];

if (!function_exists('userDashH')) {
    function userDashH(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('userDashFecha')) {
    function userDashFecha(?string $fecha): string
    {
        $fecha = trim((string) $fecha);
        if ($fecha === '') {
            return 'Sin fecha';
        }

        $timestamp = strtotime($fecha);
        return $timestamp ? date('d/m/Y', $timestamp) : $fecha;
    }
}

if (!function_exists('userDashMoneda')) {
    function userDashMoneda(mixed $valor): string
    {
        if ($valor === null || $valor === '') {
            return 'Sin definir';
        }

        return '$ ' . number_format((float) $valor, 2, ',', '.');
    }
}

if (!function_exists('userDashEstado')) {
    function userDashEstado(?string $estado): array
    {
        $mapa = [
            'draft' => ['Borrador', 'im-chip--pendiente'],
            'planned' => ['Planificado', 'im-chip--activo'],
            'in_progress' => ['En progreso', 'im-chip--activo'],
            'paused' => ['Pausado', 'im-chip--alerta'],
            'in_review' => ['En revision', 'im-chip--alerta'],
            'completed' => ['Completado', 'im-chip--completado'],
            'cancelled' => ['Cancelado', 'im-chip--pendiente'],
            'requested' => ['Solicitado', 'im-chip--pendiente'],
            'meeting_scheduled' => ['Reunion agendada', 'im-chip--alerta'],
            'approved_manually' => ['Aprobado', 'im-chip--activo'],
            'pending_payment' => ['Pago pendiente', 'im-chip--alerta'],
            'active' => ['Activo', 'im-chip--activo'],
            'paid' => ['Pagado', 'im-chip--completado'],
            'not_required_yet' => ['No requerido', 'im-chip'],
            'failed' => ['Fallido', 'im-chip--pendiente'],
            'signed' => ['Firmado', 'im-chip--completado'],
            'unsigned' => ['Pendiente', 'im-chip--pendiente'],
        ];

        return $mapa[$estado ?? ''] ?? [ucfirst(str_replace('_', ' ', (string) $estado)), 'im-chip'];
    }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard | Impulsa Emprende</title>
  <link rel="icon" href="<?= htmlspecialchars(obtenerFaviconHref(), ENT_QUOTES, 'UTF-8'); ?>" type="image/x-icon">
  <link rel="stylesheet" href="/assets/impulsa_material/css/material.css?v=icons-local-1">
</head>
<body>
  <div class="im-aplicacion" data-menu-colapsado="false">
    <aside class="im-menu-lateral" id="menu-lateral" aria-label="Navegacion principal">
      <div class="im-marca">
        <span class="im-marca__isotipo" aria-hidden="true"><?= userDashH($usuarioInicial) ?></span>
        <div class="im-marca__texto">
          <strong><?= userDashH($usuarioMarcaNombre) ?></strong>
          <span>Emprendedor</span>
        </div>
      </div>
      <nav class="im-navegacion">
        <a class="im-nav-item activo" href="#dashboard" data-seccion="dashboard">
          <span class="material-symbols-rounded" aria-hidden="true">dashboard</span>
          <span class="im-nav-item__texto">Dashboard</span>
        </a>
        <a class="im-nav-item" href="/impulsa_emprende/controller/emprendedor/emprendedorDefinicionController.php">
          <span class="material-symbols-rounded" aria-hidden="true">psychology</span>
          <span class="im-nav-item__texto">Definicion</span>
        </a>
        <a class="im-nav-item" href="/impulsa_emprende/controller/emprendedor/EmprendedorPaginaWebController.php">
          <span class="material-symbols-rounded" aria-hidden="true">web</span>
          <span class="im-nav-item__texto">Pagina web</span>
        </a>
      </nav>
    </aside>

    <div class="im-cortina" data-cerrar-menu></div>

    <div class="im-contenedor">
      <header class="im-barra-superior">
        <div class="im-barra-superior__grupo">
          <button class="im-boton-icono" type="button" data-alternar-menu aria-label="Menu"></button>
          <div>
            <p class="im-sobrelinea">Impulsa Emprende</p>
            <h1>Dashboard</h1>
          </div>
        </div>
        <div class="im-barra-superior__acciones">
          <button class="im-boton-icono im-boton-icono--principal im-tooltip" type="button" data-abrir-config-tema aria-label="Configurar temas" data-tooltip="Configurar estilos"></button>
          <button class="im-boton-icono material-symbols-rounded im-tooltip" type="button" data-abrir-perfil aria-label="Mi perfil" data-tooltip="Mi perfil">account_circle</button>
          <a class="im-boton-icono material-symbols-rounded im-tooltip im-accion--eliminar" href="/auth/logout.php" aria-label="Salir" data-tooltip="Salir">logout</a>
        </div>
      </header>

      <main class="im-contenido">
        <section class="im-seccion-documento activa" id="dashboard" data-panel="dashboard">
          <div class="im-encabezado-seccion">
            <div>
              <p class="im-sobrelinea">Inicio</p>
              <h2>Hola, <?= userDashH($usuarioMarcaNombre) ?></h2>
              <p>Resumen de tus proyectos, entregables y servicios activos con Impulsa.</p>
            </div>
          </div>

          <?php /* Metricas generales ocultas temporalmente.
          <div class="im-grilla im-grilla--metricas">
            <article class="im-tarjeta im-tarjeta--metrica"><span class="im-etiqueta">Proyectos</span><strong><?= (int) ($resumen['proyectos_total'] ?? 0) ?></strong><small>Visibles en curso</small></article>
            <article class="im-tarjeta im-tarjeta--metrica"><span class="im-etiqueta">Activos</span><strong><?= (int) ($resumen['proyectos_activos'] ?? 0) ?></strong><small>En curso o revision</small></article>
            <article class="im-tarjeta im-tarjeta--metrica"><span class="im-etiqueta">Entregables</span><strong><?= (int) ($resumen['entregables_pendientes'] ?? 0) ?></strong><small>Pendientes o en revision</small></article>
            <article class="im-tarjeta im-tarjeta--metrica"><span class="im-etiqueta">Reportes</span><strong><?= (int) ($resumen['reportes_visibles'] ?? 0) ?></strong><small>Marketing visibles</small></article>
          </div>
          */ ?>

          <div class="im-grilla im-grilla--metricas">
            <?php
              $misionDef = $definicion['mision'] ?? ['resultado' => '', 'completado' => 0];
              $visionDef = $definicion['vision'] ?? ['resultado' => '', 'completado' => 0];
              $buyerDef = $definicion['buyer'] ?? ['resultado' => '', 'completado' => 0];
              $definicionCompleta = (int) ($misionDef['completado'] ?? 0) === 1
                && (int) ($visionDef['completado'] ?? 0) === 1
                && (int) ($buyerDef['completado'] ?? 0) === 1;
            ?>
            <article class="im-tarjeta">
              <div class="im-tarjeta__cabecera">
                <div>
                  <h3>Mision</h3>
                  <span class="im-etiqueta">Definicion estrategica</span>
                </div>
                <span class="im-chip <?= (int) ($misionDef['completado'] ?? 0) === 1 ? 'im-chip--completado' : 'im-chip--pendiente' ?>"><?= (int) ($misionDef['completado'] ?? 0) === 1 ? 'Completado' : 'Incompleto' ?></span>
              </div>
              <p><?= (int) ($misionDef['completado'] ?? 0) === 1 ? userDashH($misionDef['resultado'] ?? '') : 'Completa la mision para ver el resultado.' ?></p>
            </article>

            <article class="im-tarjeta">
              <div class="im-tarjeta__cabecera">
                <div>
                  <h3>Vision</h3>
                  <span class="im-etiqueta">Proyeccion de la empresa</span>
                </div>
                <span class="im-chip <?= (int) ($visionDef['completado'] ?? 0) === 1 ? 'im-chip--completado' : 'im-chip--pendiente' ?>"><?= (int) ($visionDef['completado'] ?? 0) === 1 ? 'Completado' : 'Incompleto' ?></span>
              </div>
              <p><?= (int) ($visionDef['completado'] ?? 0) === 1 ? userDashH($visionDef['resultado'] ?? '') : 'Completa la vision para ver el resultado.' ?></p>
            </article>

            <article class="im-tarjeta">
              <div class="im-tarjeta__cabecera">
                <div>
                  <h3>Buyer Persona</h3>
                  <span class="im-etiqueta">Cliente ideal</span>
                </div>
                <span class="im-chip <?= (int) ($buyerDef['completado'] ?? 0) === 1 ? 'im-chip--completado' : 'im-chip--pendiente' ?>"><?= (int) ($buyerDef['completado'] ?? 0) === 1 ? 'Completado' : 'Incompleto' ?></span>
              </div>
              <p><?= (int) ($buyerDef['completado'] ?? 0) === 1 ? userDashH($buyerDef['resultado'] ?? '') : 'Completa el buyer persona para ver el resultado.' ?></p>
            </article>

            <article class="im-tarjeta">
              <div class="im-tarjeta__cabecera">
                <div>
                  <h3>Pagina web</h3>
                  <span class="im-etiqueta">Solicitud Landing page</span>
                </div>
                <?php if ($paginaWeb): ?>
                  <span class="im-chip <?= (int) ($paginaWeb['completado'] ?? 0) === 1 ? 'im-chip--completado' : 'im-chip--pendiente' ?>"><?= (int) ($paginaWeb['completado'] ?? 0) === 1 ? 'Solicitada' : 'Pendiente' ?></span>
                <?php elseif ($definicionCompleta): ?>
                  <span class="im-chip im-chip--activo">Disponible</span>
                <?php else: ?>
                  <span class="im-chip im-chip--pendiente">Bloqueada</span>
                <?php endif; ?>
              </div>
              <?php if ($paginaWeb): ?>
                <p>Ya existe una solicitud para <?= userDashH($paginaWeb['nombre_emprendimiento'] ?? 'tu emprendimiento') ?>.</p>
              <?php elseif ($definicionCompleta): ?>
                <p>Ya podes completar el formulario para solicitar tu pagina web.</p>
                <div class="im-formulario__acciones">
                  <a class="im-boton im-boton--principal" href="/impulsa_emprende/controller/emprendedor/EmprendedorPaginaWebController.php">Solicitar</a>
                </div>
              <?php else: ?>
                <p>Completa primero mision, vision y buyer persona para habilitar la solicitud.</p>
              <?php endif; ?>
            </article>
          </div>

          <div class="im-grilla im-grilla--dashboard">
            <article class="im-tarjeta">
              <div class="im-tarjeta__cabecera">
                <div>
                  <h3>Proyectos recientes</h3>
                  <p>Estado general de los trabajos habilitados para tu cuenta.</p>
                </div>
                <span class="im-chip"><?= count($proyectos) ?> visibles</span>
              </div>
              <?php if (!$proyectos): ?>
                <div class="im-alerta im-alerta--info">Todavia no hay proyectos visibles para tu usuario.</div>
              <?php else: ?>
                <div class="im-tabla-contenedor">
                  <table class="im-tabla">
                    <thead><tr><th>Proyecto</th><th>Estado</th><th>Avance</th><th>Entrega objetivo</th></tr></thead>
                    <tbody>
                      <?php foreach (array_slice($proyectos, 0, 4) as $proyecto): ?>
                        <?php [$estadoTexto, $estadoClase] = userDashEstado($proyecto['status'] ?? ''); ?>
                        <tr>
                          <td><?= userDashH($proyecto['project_name'] ?? '') ?></td>
                          <td><span class="im-chip <?= userDashH($estadoClase) ?>"><?= userDashH($estadoTexto) ?></span></td>
                          <td><?= (int) ($proyecto['progress_percent'] ?? 0) ?>%</td>
                          <td><?= userDashFecha($proyecto['target_delivery_date'] ?? '') ?></td>
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
                  <p>Novedades publicadas por el equipo sobre tus proyectos.</p>
                </div>
              </div>
              <?php if (!$actualizaciones): ?>
                <div class="im-alerta im-alerta--info">No hay actualizaciones visibles por el momento.</div>
              <?php else: ?>
                <ul class="im-lista">
                  <?php foreach ($actualizaciones as $actualizacion): ?>
                    <li>
                      <strong><?= userDashH($actualizacion['title'] ?? '') ?></strong>
                      <span><?= userDashH($actualizacion['project_name'] ?? '') ?> - <?= userDashFecha($actualizacion['created_at'] ?? '') ?></span>
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
              <?php [$estadoTexto, $estadoClase] = userDashEstado($proyecto['status'] ?? ''); ?>
              <article class="im-tarjeta">
                <div class="im-tarjeta__cabecera">
                  <div>
                    <h3><?= userDashH($proyecto['project_name'] ?? '') ?></h3>
                    <p><?= userDashH($proyecto['summary'] ?? $proyecto['scope_summary'] ?? '') ?></p>
                  </div>
                  <span class="im-chip <?= userDashH($estadoClase) ?>"><?= userDashH($estadoTexto) ?></span>
                </div>
                <div class="im-progress-bar" aria-label="Avance <?= (int) ($proyecto['progress_percent'] ?? 0) ?> por ciento"><span></span></div>
                <div class="im-chip-lista">
                  <span class="im-chip"><?= (int) ($proyecto['progress_percent'] ?? 0) ?>% avance</span>
                  <span class="im-chip"><?= (int) ($proyecto['fases_total'] ?? 0) ?> fases</span>
                  <span class="im-chip"><?= (int) ($proyecto['entregables_total'] ?? 0) ?> entregables</span>
                </div>
                <p>Entrega objetivo: <?= userDashFecha($proyecto['target_delivery_date'] ?? '') ?></p>
              </article>
            <?php endforeach; ?>
            <?php if (!$proyectos): ?>
              <article class="im-tarjeta"><h3>Sin proyectos visibles</h3><p>Cuando el equipo habilite un proyecto para tu usuario, vas a verlo en esta seccion.</p></article>
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
                      <?php [$firmaTexto, $firmaClase] = userDashEstado((int) ($contrato['is_signed'] ?? 0) === 1 ? 'signed' : 'unsigned'); ?>
                      <tr>
                        <td><?= userDashH($contrato['contract_name'] ?? '') ?></td>
                        <td><?= userDashH($contrato['project_name'] ?? '') ?></td>
                        <td><?= (int) ($contrato['version_number'] ?? 1) ?></td>
                        <td><span class="im-chip <?= userDashH($firmaClase) ?>"><?= userDashH($firmaTexto) ?></span></td>
                        <td><?= userDashFecha($contrato['signed_at'] ?? '') ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>
          </article>
        </section>

        <section class="im-seccion-documento" id="marketing" data-panel="marketing">
          <div class="im-encabezado-seccion">
            <div>
              <p class="im-sobrelinea">Marketing</p>
              <h2>Planes y reportes</h2>
              <p>Suscripciones, campanias y reportes visibles para tu cuenta cliente.</p>
            </div>
          </div>

          <div class="im-grilla im-grilla--dos-columnas">
            <?php foreach ($suscripcionesMarketing as $suscripcion): ?>
              <?php [$estadoTexto, $estadoClase] = userDashEstado($suscripcion['status'] ?? ''); ?>
              <article class="im-tarjeta">
                <div class="im-tarjeta__cabecera">
                  <div>
                    <h3><?= userDashH($suscripcion['plan_name'] ?? '') ?></h3>
                    <p><?= userDashH($suscripcion['short_description'] ?? $suscripcion['objective'] ?? '') ?></p>
                  </div>
                  <span class="im-chip <?= userDashH($estadoClase) ?>"><?= userDashH($estadoTexto) ?></span>
                </div>
                <div class="im-chip-lista">
                  <span class="im-chip"><?= (int) ($suscripcion['campanias_total'] ?? 0) ?> campanias</span>
                  <span class="im-chip"><?= (int) ($suscripcion['reportes_total'] ?? 0) ?> reportes</span>
                  <span class="im-chip"><?= userDashMoneda($suscripcion['monthly_ad_budget'] ?? null) ?> pauta</span>
                </div>
                <p>Vigencia: <?= userDashFecha($suscripcion['start_date'] ?? '') ?> a <?= userDashFecha($suscripcion['end_date'] ?? '') ?></p>
              </article>
            <?php endforeach; ?>
            <?php if (!$suscripcionesMarketing): ?>
              <article class="im-tarjeta"><h3>Sin planes activos</h3><p>No hay suscripciones de marketing asociadas a tu usuario cliente.</p></article>
            <?php endif; ?>
          </div>

          <article class="im-tarjeta">
            <div class="im-tarjeta__cabecera">
              <div>
                <h3>Reportes visibles</h3>
                <p>Informes publicados para seguimiento comercial y de campanias.</p>
              </div>
            </div>
            <?php if (!$reportesMarketing): ?>
              <div class="im-alerta im-alerta--info">Todavia no hay reportes de marketing visibles.</div>
            <?php else: ?>
              <div class="im-tabla-contenedor">
                <table class="im-tabla">
                  <thead><tr><th>Reporte</th><th>Plan</th><th>Periodo</th><th>Resumen</th></tr></thead>
                  <tbody>
                    <?php foreach ($reportesMarketing as $reporte): ?>
                      <tr>
                        <td><?= userDashH($reporte['title'] ?? '') ?></td>
                        <td><?= userDashH($reporte['plan_name'] ?? '') ?></td>
                        <td><?= userDashFecha($reporte['period_start'] ?? '') ?> - <?= userDashFecha($reporte['period_end'] ?? '') ?></td>
                        <td><?= userDashH($reporte['summary'] ?? '') ?></td>
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
  <script src="/assets/impulsa_material/js/material.js?v=panel-default-1"></script>
</body>
</html>
