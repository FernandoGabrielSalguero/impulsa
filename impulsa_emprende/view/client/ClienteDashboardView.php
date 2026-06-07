<?php
$usuarioCorreo = $usuarioCorreo ?? '';
$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Cliente';
$dashboardData = $dashboardData ?? [];
$mensajeContratoCliente = $mensajeContratoCliente ?? null;
$resumen = $dashboardData['resumen'] ?? [];
$proyectos = $dashboardData['proyectos'] ?? [];
$fasesPorProyecto = $dashboardData['fases'] ?? [];
$objetivosPorProyecto = $dashboardData['objetivos'] ?? [];
$actualizaciones = $dashboardData['actualizaciones'] ?? [];
$contratos = $dashboardData['contratos'] ?? [];
$contratosPorProyecto = [];
foreach ($contratos as $contrato) {
    $contratosPorProyecto[(int) ($contrato['project_id'] ?? 0)] = $contrato;
}

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
$estadoSimple = static fn (?string $estado): string => ucfirst(str_replace('_', ' ', (string) ($estado ?? '')));
$estadoSeguimiento = static function (?string $estado) use ($estadoSimple): array {
    $mapa = [
        'pending' => ['Pendiente', 'im-chip--pendiente', 'schedule'],
        'in_progress' => ['En progreso', 'im-chip--activo', 'sync'],
        'blocked' => ['Bloqueada', 'im-chip--alerta', 'error'],
        'done' => ['Finalizada', 'im-chip--completado', 'check_circle'],
        'ready_for_review' => ['Listo para revision', 'im-chip--alerta', 'visibility'],
        'delivered' => ['Entregado', 'im-chip--completado', 'task_alt'],
    ];

    return $mapa[$estado ?? ''] ?? [$estadoSimple($estado), 'im-chip', 'pending'];
};
$tipoObjetivo = static function (?string $tipo): string {
    $mapa = [
        'document' => 'Documento',
        'design' => 'Diseno',
        'development' => 'Desarrollo',
        'deployment' => 'Publicacion',
        'training' => 'Capacitacion',
        'other' => 'Objetivo',
    ];

    return $mapa[$tipo ?? ''] ?? 'Objetivo';
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

    .im-cliente-avance {
      display: grid;
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .im-cliente-avance__tarjeta {
      display: grid;
      gap: 1rem;
    }

    .im-cliente-avance__resumen {
      display: flex;
      flex-wrap: wrap;
      gap: .75rem;
      align-items: center;
      justify-content: space-between;
    }

    .im-cliente-avance__metricas {
      display: flex;
      flex-wrap: wrap;
      gap: .65rem;
    }

    .im-cliente-avance__barra {
      width: 100%;
      height: .7rem;
      border: 0;
      border-radius: 999px;
      overflow: hidden;
      background: var(--im-color-borde);
      appearance: none;
    }

    .im-cliente-avance__barra::-webkit-progress-bar {
      background: var(--im-color-borde);
      border-radius: 999px;
    }

    .im-cliente-avance__barra::-webkit-progress-value {
      background: var(--im-color-principal);
      border-radius: 999px;
    }

    .im-cliente-avance__barra::-moz-progress-bar {
      background: var(--im-color-principal);
      border-radius: 999px;
    }

    .im-cliente-avance__fases {
      display: grid;
      gap: .9rem;
    }

    .im-cliente-avance__fase {
      border: 1px solid var(--im-color-borde);
      border-radius: 20px;
      padding: 1rem;
      background: var(--im-color-superficie);
      display: grid;
      gap: .85rem;
    }

    .im-cliente-avance__fase-cabecera,
    .im-cliente-avance__objetivo {
      display: flex;
      gap: .75rem;
      align-items: flex-start;
      justify-content: space-between;
    }

    .im-cliente-avance__fase-titulo,
    .im-cliente-avance__objetivo-titulo {
      display: grid;
      gap: .25rem;
    }

    .im-cliente-avance__encabezado-item {
      display: flex;
      gap: .75rem;
      align-items: flex-start;
    }

    .im-cliente-avance__meta,
    .im-cliente-avance__objetivo-meta {
      display: flex;
      flex-wrap: wrap;
      gap: .5rem;
      color: var(--im-color-texto-suave);
      font-size: .92rem;
    }

    .im-cliente-avance__objetivos {
      display: grid;
      gap: .75rem;
    }

    .im-cliente-avance__objetivo {
      padding: .9rem 1rem;
      border-radius: 16px;
      background: var(--im-color-superficie-2);
    }

    .im-cliente-avance__icono {
      color: var(--im-color-texto-suave);
      font-size: 1.15rem;
      line-height: 1;
      margin-top: .2rem;
    }

    .im-cliente-avance__vacio {
      border: 1px dashed var(--im-color-borde);
      border-radius: 16px;
      padding: 1rem;
      color: var(--im-color-texto-suave);
      background: var(--im-color-superficie-2);
    }

    .im-cliente-contrato-barra {
      display: flex;
      gap: 1rem;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
    }

    .im-cliente-contrato-barra__texto {
      display: grid;
      gap: .2rem;
      flex: 1 1 320px;
    }

    .im-cliente-contrato-barra__acciones {
      display: flex;
      align-items: center;
      gap: .75rem;
      flex-wrap: wrap;
      justify-content: flex-end;
    }

    .im-cliente-contrato-modal {
      width: min(880px, calc(100vw - 2rem));
    }

    .im-cliente-contrato-modal .im-dialog__contenido {
      display: grid;
      gap: 1rem;
    }

    .im-cliente-contrato-contenido {
      max-height: min(60vh, 720px);
      overflow: auto;
      padding: 1rem;
      border-radius: 16px;
      border: 1px solid var(--im-color-borde);
      background: var(--im-color-superficie-2);
      color: var(--im-color-texto);
      white-space: pre-wrap;
    }

    .im-cliente-contrato-contenido > *:first-child {
      margin-top: 0;
    }

    .im-cliente-contrato-contenido > *:last-child {
      margin-bottom: 0;
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
              <p>Vista general de tus fases, objetivos y avances visibles para tu cuenta.</p>
            </div>
          </div>

          <?php if ($mensajeContratoCliente): ?>
            <div class="im-alerta <?= ($mensajeContratoCliente['estado'] ?? '') === 'ok' ? 'im-alerta--exito' : 'im-alerta--info' ?>">
              <?= $h($mensajeContratoCliente['mensaje'] ?? '') ?>
            </div>
          <?php endif; ?>

          <div class="im-grilla im-grilla--metricas">
            <article class="im-tarjeta im-tarjeta--metrica"><span class="im-etiqueta">Proyectos</span><strong><?= (int) ($resumen['proyectos_total'] ?? 0) ?></strong><small>Visibles</small></article>
            <article class="im-tarjeta im-tarjeta--metrica"><span class="im-etiqueta">Activos</span><strong><?= (int) ($resumen['proyectos_activos'] ?? 0) ?></strong><small>En curso o revision</small></article>
            <article class="im-tarjeta im-tarjeta--metrica"><span class="im-etiqueta">Entregables</span><strong><?= (int) ($resumen['entregables_pendientes'] ?? 0) ?></strong><small>Pendientes</small></article>
            <article class="im-tarjeta im-tarjeta--metrica"><span class="im-etiqueta">Contratos</span><strong><?= (int) ($resumen['contratos_pendientes'] ?? 0) ?></strong><small>Pendientes</small></article>
          </div>

          <div class="im-cliente-avance">
            <article class="im-tarjeta im-cliente-avance__tarjeta">
              <div class="im-tarjeta__cabecera">
                <div>
                  <h3>Mis proyectos</h3>
                  <p>Detalle de avances, entregables y contratos asociados a tu usuario cliente.</p>
                </div>
              </div>
              <?php if (!$proyectos): ?>
                <div class="im-alerta im-alerta--info">Todavia no hay proyectos visibles para tu usuario.</div>
              <?php else: ?>
                <div class="im-cliente-avance">
                  <?php foreach ($proyectos as $proyecto): ?>
                    <?php
                      $projectId = (int) ($proyecto['id'] ?? 0);
                      $fases = $fasesPorProyecto[$projectId] ?? [];
                      $objetivos = $objetivosPorProyecto[$projectId] ?? [];
                      $contratoProyecto = $contratosPorProyecto[$projectId] ?? null;
                      $objetivosCompletados = count(array_filter($objetivos, static fn (array $objetivo): bool => ($objetivo['status'] ?? '') === 'delivered'));
                      [$estadoTexto, $estadoClase] = $estadoProyecto($proyecto['status'] ?? '');
                    ?>
                    <article class="im-tarjeta im-cliente-avance__tarjeta">
                      <div class="im-cliente-avance__resumen">
                        <div>
                          <p class="im-sobrelinea">Proyecto</p>
                          <h4><?= $h($proyecto['project_name'] ?? '') ?></h4>
                          <p><?= $h($proyecto['summary'] ?? $proyecto['scope_summary'] ?? 'Sin descripcion visible.') ?></p>
                        </div>
                        <div class="im-cliente-avance__metricas">
                          <span class="im-chip <?= $h($estadoClase) ?>"><?= $h($estadoTexto) ?></span>
                          <span class="im-chip"><?= (int) ($proyecto['progress_percent'] ?? 0) ?>% avance</span>
                          <span class="im-chip"><?= (int) ($proyecto['fases_total'] ?? 0) ?> fases</span>
                          <span class="im-chip"><?= $objetivosCompletados ?> / <?= count($objetivos) ?> objetivos entregados</span>
                        </div>
                      </div>
                      <progress class="im-cliente-avance__barra" max="100" value="<?= (int) ($proyecto['progress_percent'] ?? 0) ?>"> <?= (int) ($proyecto['progress_percent'] ?? 0) ?>% </progress>
                      <div class="im-cliente-avance__meta">
                        <span>Entrega objetivo: <?= $fecha($proyecto['target_delivery_date'] ?? '') ?></span>
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
                              [$faseEstadoTexto, $faseEstadoClase, $faseEstadoIcono] = $estadoSeguimiento($fase['status'] ?? '');
                            ?>
                            <section class="im-cliente-avance__fase">
                              <div class="im-cliente-avance__fase-cabecera">
                                <div class="im-cliente-avance__encabezado-item">
                                  <span class="material-symbols-rounded im-cliente-avance__icono" aria-hidden="true"><?= $h($faseEstadoIcono) ?></span>
                                  <div class="im-cliente-avance__fase-titulo">
                                    <strong><?= $h(($fase['phase_order'] ?? '') . '. ' . ($fase['title'] ?? '')) ?></strong>
                                    <div class="im-cliente-avance__meta">
                                      <span><?= $h($faseEstadoTexto) ?></span>
                                      <span>Vence: <?= $fecha($fase['due_date'] ?? '') ?></span>
                                      <span><?= $objetivosFaseCompletados ?> / <?= count($objetivosFase) ?> objetivos entregados</span>
                                    </div>
                                  </div>
                                </div>
                                <span class="im-chip <?= $h($faseEstadoClase) ?>"><?= $h($faseEstadoTexto) ?></span>
                              </div>

                              <?php if ($objetivosFase): ?>
                                <div class="im-cliente-avance__objetivos">
                                  <?php foreach ($objetivosFase as $objetivo): ?>
                                    <?php [$objetivoEstadoTexto, $objetivoEstadoClase, $objetivoEstadoIcono] = $estadoSeguimiento($objetivo['status'] ?? ''); ?>
                                    <article class="im-cliente-avance__objetivo">
                                      <div class="im-cliente-avance__encabezado-item">
                                        <span class="material-symbols-rounded im-cliente-avance__icono" aria-hidden="true"><?= $h($objetivoEstadoIcono) ?></span>
                                        <div class="im-cliente-avance__objetivo-titulo">
                                          <strong><?= $h($objetivo['title'] ?? '') ?></strong>
                                          <div class="im-cliente-avance__objetivo-meta">
                                            <span><?= $h($tipoObjetivo($objetivo['deliverable_type'] ?? '')) ?></span>
                                            <span>Fecha limite: <?= $fecha($objetivo['due_date'] ?? '') ?></span>
                                          </div>
                                        </div>
                                      </div>
                                      <span class="im-chip <?= $h($objetivoEstadoClase) ?>"><?= $h($objetivoEstadoTexto) ?></span>
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

                      <?php if ($contratoProyecto): ?>
                        <div class="im-alerta im-alerta--info im-cliente-contrato-barra">
                          <div class="im-cliente-contrato-barra__texto">
                            <strong>Contrato: <?= $h($contratoProyecto['contract_name'] ?? '') ?> - <?= (int) ($contratoProyecto['is_signed'] ?? 0) === 1 ? 'firmado' : 'pendiente de firma' ?>.</strong>
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
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </article>
          </div>

          <div class="im-grilla im-grilla--dashboard">

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
          </div>
        </section>
      </main>
    </div>
  </div>

  <div class="im-modal-cortina" data-cerrar-contrato-cliente></div>
  <section class="im-dialog im-cliente-contrato-modal" role="dialog" aria-modal="true" aria-labelledby="cliente-contrato-titulo" aria-hidden="true" data-modal-contrato-cliente>
    <header class="im-dialog__cabecera">
      <div>
        <p class="im-sobrelinea" data-cliente-contrato-proyecto>Proyecto</p>
        <h3 id="cliente-contrato-titulo">Contrato</h3>
      </div>
      <button class="im-boton-icono" type="button" data-cerrar-contrato-cliente aria-label="Cerrar dialog"></button>
    </header>
    <form method="post" action="/impulsa_emprende/controller/client/ClienteDashboardController.php" data-form-firma-contrato>
      <input type="hidden" name="accion_cliente" value="firmar_contrato">
      <input type="hidden" name="contract_id" value="" data-cliente-contrato-id>
      <div class="im-dialog__contenido">
        <div class="im-alerta im-alerta--info" data-cliente-contrato-estado>Revisa el contenido completo antes de confirmar la firma.</div>
        <div class="im-cliente-contrato-contenido" data-cliente-contrato-contenido></div>
      </div>
      <footer class="im-dialog__acciones">
        <button class="im-boton im-boton--texto" type="button" data-cerrar-contrato-cliente>Cerrar</button>
        <button class="im-boton im-boton--principal" type="submit" data-cliente-contrato-confirmar>Confirmar firma</button>
      </footer>
    </form>
  </section>

  <?php require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilView.php'; ?>
  <script src="/assets/impulsa_material/js/material.js"></script>
  <script>
    (() => {
      const contratos = <?= json_encode($contratos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
      const modal = document.querySelector('[data-modal-contrato-cliente]');
      const cortina = document.querySelector('[data-cerrar-contrato-cliente].im-modal-cortina');
      const formulario = document.querySelector('[data-form-firma-contrato]');

      if (!modal || !cortina || !formulario) {
        return;
      }

      const contratosPorId = new Map(contratos.map((contrato) => [String(contrato.id), contrato]));
      const campoId = modal.querySelector('[data-cliente-contrato-id]');
      const proyecto = modal.querySelector('[data-cliente-contrato-proyecto]');
      const estado = modal.querySelector('[data-cliente-contrato-estado]');
      const contenido = modal.querySelector('[data-cliente-contrato-contenido]');
      const botonConfirmar = modal.querySelector('[data-cliente-contrato-confirmar]');

      const alternar = (abrir) => {
        modal.classList.toggle('abierto', abrir);
        cortina.classList.toggle('abierto', abrir);
        modal.setAttribute('aria-hidden', abrir ? 'false' : 'true');
      };

      document.querySelectorAll('[data-abrir-contrato-cliente]').forEach((boton) => {
        boton.addEventListener('click', () => {
          const contrato = contratosPorId.get(String(boton.dataset.abrirContratoCliente));
          if (!contrato) {
            return;
          }

          campoId.value = contrato.id || '';
          proyecto.textContent = contrato.project_name || 'Proyecto';
          estado.textContent = `Contrato ${contrato.contract_name || ''} listo para firma. Esta accion no se puede deshacer desde tu cuenta.`;
          contenido.innerHTML = contrato.contract_html || '';
          if (!contenido.innerHTML.trim()) {
            contenido.textContent = contrato.contract_text || 'No hay contenido disponible para este contrato.';
          }
          botonConfirmar.disabled = Number(contrato.is_signed || 0) === 1;
          alternar(true);
        });
      });

      formulario.addEventListener('submit', () => {
        botonConfirmar.disabled = true;
      });

      document.querySelectorAll('[data-cerrar-contrato-cliente]').forEach((elemento) => {
        elemento.addEventListener('click', () => alternar(false));
      });

      document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
          alternar(false);
        }
      });
    })();
  </script>
</body>
</html>
