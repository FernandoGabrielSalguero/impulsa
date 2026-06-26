<?php
$proyectosAvance = $proyectosAvance ?? [];
$fasesPorProyectoAvance = $fasesPorProyectoAvance ?? [];
$objetivosPorProyectoAvance = $objetivosPorProyectoAvance ?? [];
$contratosPorProyectoAvance = $contratosPorProyectoAvance ?? [];
$avanceTitulo = $avanceTitulo ?? 'Mis proyectos';
$avanceDescripcion = $avanceDescripcion ?? 'Detalle de avances, entregables y contratos asociados a tu cuenta.';
$avanceMensajeVacio = $avanceMensajeVacio ?? 'Todavia no hay proyectos visibles para tu usuario.';
$avancePermiteFirmaContrato = $avancePermiteFirmaContrato ?? false;
$avanceMostrarContenedor = $avanceMostrarContenedor ?? true;

if (!function_exists('projectProgressH')) {
    function projectProgressH(mixed $valor): string
    {
        return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('projectProgressFecha')) {
    function projectProgressFecha(?string $valor): string
    {
        $valor = trim((string) $valor);
        if ($valor === '') {
            return 'Sin fecha';
        }

        $timestamp = strtotime($valor);
        return $timestamp ? date('d/m/Y', $timestamp) : $valor;
    }
}

if (!function_exists('projectProgressEstadoProyecto')) {
    function projectProgressEstadoProyecto(?string $estado): array
    {
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
    }
}

if (!function_exists('projectProgressEstadoSeguimiento')) {
    function projectProgressEstadoSeguimiento(?string $estado): array
    {
        $mapa = [
            'pending' => ['Pendiente', 'im-chip--pendiente', 'schedule'],
            'in_progress' => ['En progreso', 'im-chip--activo', 'sync'],
            'blocked' => ['Bloqueada', 'im-chip--alerta', 'error'],
            'done' => ['Finalizada', 'im-chip--completado', 'check_circle'],
            'ready_for_review' => ['Listo para revision', 'im-chip--alerta', 'visibility'],
            'delivered' => ['Entregado', 'im-chip--completado', 'task_alt'],
        ];

        return $mapa[$estado ?? ''] ?? [ucfirst(str_replace('_', ' ', (string) ($estado ?? ''))), 'im-chip', 'pending'];
    }
}

if (!function_exists('projectProgressTipoObjetivo')) {
    function projectProgressTipoObjetivo(?string $tipo): string
    {
        $mapa = [
            'document' => 'Documento',
            'design' => 'Diseno',
            'development' => 'Desarrollo',
            'deployment' => 'Publicacion',
            'training' => 'Capacitacion',
            'other' => 'Objetivo',
        ];

        return $mapa[$tipo ?? ''] ?? 'Objetivo';
    }
}

if (!function_exists('projectProgressPercent')) {
    function projectProgressPercent(array $fases, array $objetivos): int
    {
        if ($objetivos !== []) {
            $totalObjetivos = count($objetivos);
            $objetivosEntregados = count(array_filter(
                $objetivos,
                static fn (array $objetivo): bool => ($objetivo['status'] ?? '') === 'delivered'
            ));

            return (int) round(($objetivosEntregados / $totalObjetivos) * 100);
        }

        if ($fases !== []) {
            $totalFases = count($fases);
            $fasesFinalizadas = count(array_filter(
                $fases,
                static fn (array $fase): bool => ($fase['status'] ?? '') === 'done'
            ));

            return (int) round(($fasesFinalizadas / $totalFases) * 100);
        }

        return 0;
    }
}

$renderAvance = static function () use (
    $proyectosAvance,
    $fasesPorProyectoAvance,
    $objetivosPorProyectoAvance,
    $contratosPorProyectoAvance,
    $avancePermiteFirmaContrato
): void {
    if (!$proyectosAvance) {
        return;
    }

    foreach ($proyectosAvance as $proyecto) {
        $projectId = (int) ($proyecto['id'] ?? 0);
        $fases = $fasesPorProyectoAvance[$projectId] ?? [];
        $objetivos = $objetivosPorProyectoAvance[$projectId] ?? [];
        $contratoProyecto = $contratosPorProyectoAvance[$projectId] ?? null;
        $avanceCalculado = projectProgressPercent($fases, $objetivos);
        $objetivosCompletados = count(array_filter($objetivos, static fn (array $objetivo): bool => ($objetivo['status'] ?? '') === 'delivered'));
        [$estadoTexto, $estadoClase] = projectProgressEstadoProyecto($proyecto['status'] ?? '');
        ?>
        <article class="im-tarjeta im-cliente-avance__tarjeta">
          <div class="im-cliente-avance__resumen">
            <div>
              <p class="im-sobrelinea">Proyecto</p>
              <h4><?= projectProgressH($proyecto['project_name'] ?? '') ?></h4>
              <p><?= projectProgressH($proyecto['summary'] ?? $proyecto['scope_summary'] ?? 'Sin descripcion visible.') ?></p>
            </div>
            <div class="im-cliente-avance__metricas">
              <span class="im-chip <?= projectProgressH($estadoClase) ?>"><?= projectProgressH($estadoTexto) ?></span>
              <span class="im-chip"><?= $avanceCalculado ?>% avance</span>
              <span class="im-chip"><?= count($fases) ?> fases</span>
              <span class="im-chip"><?= $objetivosCompletados ?> / <?= count($objetivos) ?> objetivos entregados</span>
            </div>
          </div>
          <progress class="im-cliente-avance__barra" max="100" value="<?= $avanceCalculado ?>"> <?= $avanceCalculado ?>% </progress>
          <div class="im-cliente-avance__meta">
            <span>Entrega objetivo: <?= projectProgressFecha($proyecto['target_delivery_date'] ?? '') ?></span>
            <span><?= count($fases) ?> fases visibles</span>
            <span><?= count($objetivos) ?> objetivos visibles</span>
            <span><?= (int) ($proyecto['actualizaciones_total'] ?? 0) ?> actualizaciones</span>
          </div>

          <?php if (!$fases): ?>
            <div class="im-cliente-avance__vacio">Todavia no hay fases visibles cargadas para este proyecto.</div>
          <?php else: ?>
            <div class="im-cliente-avance__fases">
              <?php foreach ($fases as $fase): ?>
                <?php
                  $faseId = (int) ($fase['id'] ?? 0);
                  $objetivosFase = array_values(array_filter(
                      $objetivos,
                      static fn (array $objetivo): bool => (int) ($objetivo['phase_id'] ?? 0) === $faseId
                  ));
                  $objetivosFaseCompletados = count(array_filter(
                      $objetivosFase,
                      static fn (array $objetivo): bool => ($objetivo['status'] ?? '') === 'delivered'
                  ));
                  [$faseEstadoTexto, $faseEstadoClase, $faseEstadoIcono] = projectProgressEstadoSeguimiento($fase['status'] ?? '');
                ?>
                <section class="im-cliente-avance__fase">
                  <div class="im-cliente-avance__fase-cabecera">
                    <div class="im-cliente-avance__encabezado-item">
                      <span class="material-symbols-rounded im-cliente-avance__icono" aria-hidden="true"><?= projectProgressH($faseEstadoIcono) ?></span>
                      <div class="im-cliente-avance__fase-titulo">
                        <strong><?= projectProgressH(($fase['phase_order'] ?? '') . '. ' . ($fase['title'] ?? '')) ?></strong>
                        <div class="im-cliente-avance__meta">
                          <span><?= projectProgressH($faseEstadoTexto) ?></span>
                          <span>Vence: <?= projectProgressFecha($fase['due_date'] ?? '') ?></span>
                          <span><?= $objetivosFaseCompletados ?> / <?= count($objetivosFase) ?> objetivos entregados</span>
                        </div>
                      </div>
                    </div>
                    <span class="im-chip <?= projectProgressH($faseEstadoClase) ?>"><?= projectProgressH($faseEstadoTexto) ?></span>
                  </div>

                  <?php if ($objetivosFase): ?>
                    <div class="im-cliente-avance__objetivos">
                      <?php foreach ($objetivosFase as $objetivo): ?>
                        <?php [$objetivoEstadoTexto, $objetivoEstadoClase, $objetivoEstadoIcono] = projectProgressEstadoSeguimiento($objetivo['status'] ?? ''); ?>
                        <article class="im-cliente-avance__objetivo">
                          <div class="im-cliente-avance__encabezado-item">
                            <span class="material-symbols-rounded im-cliente-avance__icono" aria-hidden="true"><?= projectProgressH($objetivoEstadoIcono) ?></span>
                            <div class="im-cliente-avance__objetivo-titulo">
                              <strong><?= projectProgressH($objetivo['title'] ?? '') ?></strong>
                              <div class="im-cliente-avance__objetivo-meta">
                                <span><?= projectProgressH(projectProgressTipoObjetivo($objetivo['deliverable_type'] ?? '')) ?></span>
                                <span>Fecha limite: <?= projectProgressFecha($objetivo['due_date'] ?? '') ?></span>
                              </div>
                            </div>
                          </div>
                          <span class="im-chip <?= projectProgressH($objetivoEstadoClase) ?>"><?= projectProgressH($objetivoEstadoTexto) ?></span>
                        </article>
                      <?php endforeach; ?>
                    </div>
                  <?php else: ?>
                    <div class="im-cliente-avance__vacio">Esta fase todavia no tiene objetivos visibles.</div>
                  <?php endif; ?>
                </section>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <?php if ($avancePermiteFirmaContrato && $contratoProyecto): ?>
            <div class="im-alerta im-alerta--info im-cliente-contrato-barra">
              <div class="im-cliente-contrato-barra__texto">
                <strong>Contrato: <?= projectProgressH($contratoProyecto['contract_name'] ?? '') ?> - <?= (int) ($contratoProyecto['is_signed'] ?? 0) === 1 ? 'firmado' : 'pendiente de firma' ?>.</strong>
              </div>
              <div class="im-cliente-contrato-barra__acciones">
                <?php if ((int) ($contratoProyecto['is_signed'] ?? 0) === 1): ?>
                  <span class="im-chip im-chip--completado">Contrato firmado</span>
                <?php else: ?>
                  <button
                    class="im-boton im-boton--principal"
                    type="button"
                    data-abrir-contrato-cliente="<?= (int) ($contratoProyecto['id'] ?? 0) ?>"
                  >
                    Firmar contrato
                  </button>
                <?php endif; ?>
              </div>
            </div>
          <?php endif; ?>
        </article>
        <?php
    }
};
?>

<?php if ($avanceMostrarContenedor): ?>
  <div class="im-cliente-avance">
    <article class="im-tarjeta im-cliente-avance__tarjeta">
      <div class="im-tarjeta__cabecera">
        <div>
          <h3><?= projectProgressH($avanceTitulo) ?></h3>
          <p><?= projectProgressH($avanceDescripcion) ?></p>
        </div>
      </div>
      <?php if (!$proyectosAvance): ?>
        <div class="im-alerta im-alerta--info"><?= projectProgressH($avanceMensajeVacio) ?></div>
      <?php else: ?>
        <div class="im-cliente-avance">
          <?php $renderAvance(); ?>
        </div>
      <?php endif; ?>
    </article>
  </div>
<?php else: ?>
  <?php if (!$proyectosAvance): ?>
    <div class="im-alerta im-alerta--info"><?= projectProgressH($avanceMensajeVacio) ?></div>
  <?php else: ?>
    <div class="im-cliente-avance">
      <?php $renderAvance(); ?>
    </div>
  <?php endif; ?>
<?php endif; ?>
