<?php
$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Usuario';
$tareas = $tareas ?? [];
$usuariosTareas = $usuariosTareas ?? [];
$estado = $estado ?? '';
$h = static fn(mixed $valor): string => htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
$toJson = static fn(mixed $valor): string => htmlspecialchars((string) json_encode($valor, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8');
$mensajesEstado = [
    'tarea_creada' => ['tipo' => 'exito', 'texto' => 'Tarea creada correctamente.'],
    'tarea_actualizada' => ['tipo' => 'exito', 'texto' => 'Tarea actualizada correctamente.'],
    'tarea_eliminada' => ['tipo' => 'exito', 'texto' => 'Tarea eliminada correctamente.'],
    'tarea_no_encontrada' => ['tipo' => 'error', 'texto' => 'La tarea seleccionada ya no existe.'],
    'tarea_id_invalido' => ['tipo' => 'error', 'texto' => 'La tarea seleccionada no es valida.'],
    'tarea_nombre_invalido' => ['tipo' => 'error', 'texto' => 'El nombre de la tarea es obligatorio.'],
    'tarea_nombre_largo' => ['tipo' => 'error', 'texto' => 'El nombre de la tarea no puede superar 180 caracteres.'],
    'tarea_responsable_invalido' => ['tipo' => 'error', 'texto' => 'Debes seleccionar un responsable valido.'],
    'tarea_descripcion_invalida' => ['tipo' => 'error', 'texto' => 'La descripcion de la tarea es obligatoria.'],
    'tarea_fecha_invalida' => ['tipo' => 'error', 'texto' => 'La fecha de entrega no es valida.'],
    'tarea_prioridad_invalida' => ['tipo' => 'error', 'texto' => 'La prioridad DEFCON debe estar entre 1 y 5.'],
    'tarea_reporta_invalido' => ['tipo' => 'error', 'texto' => 'El campo reporta a es obligatorio.'],
    'tarea_reporta_largo' => ['tipo' => 'error', 'texto' => 'El campo reporta a no puede superar 180 caracteres.'],
    'tarea_estado_invalido' => ['tipo' => 'error', 'texto' => 'El estado seleccionado no es valido.'],
    'tarea_usuario_creador_invalido' => ['tipo' => 'error', 'texto' => 'No se pudo identificar al usuario creador.'],
    'tarea_error_crear' => ['tipo' => 'error', 'texto' => 'No se pudo crear la tarea.'],
    'tarea_error_actualizar' => ['tipo' => 'error', 'texto' => 'No se pudo actualizar la tarea.'],
    'tarea_error_eliminar' => ['tipo' => 'error', 'texto' => 'No se pudo eliminar la tarea.'],
    'tarea_accion_invalida' => ['tipo' => 'error', 'texto' => 'La accion solicitada no es valida.'],
];
$mensajeEstado = $mensajesEstado[$estado] ?? null;
$opcionesEstado = [
    'pendiente' => 'Pendiente',
    'en_progreso' => 'En progreso',
    'completada' => 'Completada',
    'cancelada' => 'Cancelada',
];
$estadoChip = static function (string $estado): string {
    return [
        'pendiente' => 'im-chip--alerta',
        'en_progreso' => 'im-chip',
        'completada' => 'im-chip--exito',
        'cancelada' => 'im-chip--alerta',
    ][$estado] ?? 'im-chip';
};
$prioridadChip = static function (int $prioridad): string {
    return match (true) {
        $prioridad >= 5 => 'im-chip--exito',
        $prioridad === 4 => 'im-chip',
        $prioridad === 3 => 'im-chip',
        default => 'im-chip--alerta',
    };
};
$formatearEstado = static function (string $estado): string {
    return [
        'pendiente' => 'Pendiente',
        'en_progreso' => 'En progreso',
        'completada' => 'Completada',
        'cancelada' => 'Cancelada',
    ][$estado] ?? ucfirst(str_replace('_', ' ', $estado));
};
$formatearFecha = static function (?string $fecha, bool $conHora = false): string {
    if (!$fecha) {
        return '-';
    }

    return date($conHora ? 'd/m/Y H:i' : 'd/m/Y', strtotime($fecha));
};
$nombreUsuario = static function (array $usuario): string {
    $nombreCompleto = trim((string) ($usuario['nombre'] ?? '') . ' ' . (string) ($usuario['apellido'] ?? ''));
    $apodo = trim((string) ($usuario['apodo'] ?? ''));

    if ($nombreCompleto !== '') {
        return $nombreCompleto;
    }

    if ($apodo !== '') {
        return $apodo;
    }

    return (string) ($usuario['correo'] ?? 'Usuario');
};
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tareas Admin</title>
    <link rel="icon" href="<?= htmlspecialchars(obtenerFaviconHref(), ENT_QUOTES, 'UTF-8'); ?>" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,400,0,0&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../assets/impulsa_material/css/material.css">
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

        .im-nav-item__icono[data-icon]::before {
            content: attr(data-icon);
        }

        .im-alerta--exito {
            background: color-mix(in srgb, var(--im-color-exito) 14%, var(--im-color-superficie));
            color: var(--im-color-exito);
        }

        .im-alerta--error {
            background: #fdecec;
            color: #ba1a1a;
        }

        .im-tarea-modal {
            width: min(560px, calc(100vw - 2rem));
        }

        .im-tarea-modal form {
            display: contents;
        }

        .im-tarea-accion-eliminar {
            color: #ba1a1a;
        }

        .im-tarea-sheet__meta {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .75rem;
            margin-bottom: 1rem;
        }

        .im-tarea-sheet__meta article {
            padding: .85rem;
            border: 1px solid var(--im-color-borde);
            border-radius: var(--im-radio-chico);
            background: var(--im-color-superficie);
        }

        .im-tarea-sheet__meta span {
            display: block;
            color: var(--im-color-texto-suave);
            font-size: .8rem;
        }

        .im-tarea-sheet__meta strong {
            display: block;
            margin-top: .2rem;
        }

        .im-tarea-sheet__form {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .9rem;
        }

        .im-tarea-sheet__form .im-campo--ancho {
            grid-column: 1 / -1;
        }

        .im-tarea-detalle-sheet {
            max-width: 920px;
        }

        .im-tarea-detalle {
            display: grid;
            gap: 1rem;
        }

        .im-tarea-detalle__hero {
            display: grid;
            gap: .75rem;
            padding: 1rem;
            border: 1px solid var(--im-color-borde);
            border-radius: var(--im-radio);
            background: color-mix(in srgb, var(--im-color-superficie-2) 55%, var(--im-color-superficie));
        }

        .im-tarea-detalle__hero h4,
        .im-tarea-detalle__hero p {
            margin: 0;
        }

        .im-tarea-detalle__hero p {
            color: var(--im-color-texto-suave);
            white-space: pre-wrap;
        }

        .im-tarea-detalle__chips {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
        }

        .im-tarea-detalle__grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .9rem;
        }

        .im-tarea-detalle__bloque {
            display: grid;
            gap: .35rem;
            padding: 1rem;
            border: 1px solid var(--im-color-borde);
            border-radius: var(--im-radio);
            background: var(--im-color-superficie);
        }

        .im-tarea-detalle__bloque span {
            color: var(--im-color-texto-suave);
            font-size: .8rem;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .im-tarea-detalle__bloque strong,
        .im-tarea-detalle__bloque p {
            margin: 0;
        }

        .im-tarea-detalle__bloque p {
            white-space: pre-wrap;
        }

        .im-tarea-sheet--editar .im-config-tema__grupo {
            display: grid;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .im-tarea-sheet--editar .im-config-tema__grupo h4,
        .im-tarea-sheet--editar .im-config-tema__grupo p {
            margin: 0;
        }

        .im-tarea-sheet--editar .im-config-tema__grupo p {
            color: var(--im-color-texto-suave);
        }

        .im-tabla-tareas__acciones {
            overflow: visible;
            position: relative;
        }

        .im-menu-tabla[data-im-menu].abierto {
            z-index: 120;
        }

        .im-menu-tabla[data-im-menu]>.im-menu-tabla__panel {
            z-index: 130;
        }

        @media (max-width: 760px) {

            .im-tarea-sheet__form,
            .im-tarea-sheet__meta,
            .im-tarea-detalle__grid {
                grid-template-columns: 1fr;
            }
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
                <a class="im-nav-item activo" href="/impulsa_emprende/controller/admin/adminTareasController.php">
                    <span class="im-nav-item__icono" data-icon="task_alt" aria-hidden="true"></span>
                    <span class="im-nav-item__texto">Tareas</span>
                </a>
                <a class="im-nav-item" href="/impulsa_emprende/controller/admin/adminMarketingController.php">
                    <span class="im-nav-item__icono" data-icon="campaign" aria-hidden="true"></span>
                    <span class="im-nav-item__texto">Marketing</span>
                </a>
                <a class="im-nav-item" href="/impulsa_emprende/controller/admin/adminAPIconfigurationController.php">
                    <span class="im-nav-item__icono" data-icon="key" aria-hidden="true"></span>
                    <span class="im-nav-item__texto">Integraciones API</span>
                </a>
                <a class="im-nav-item" href="/impulsa_emprende/controller/admin/adminCorreosEnviadosController.php">
                    <span class="im-nav-item__icono" data-icon="mail" aria-hidden="true"></span>
                    <span class="im-nav-item__texto">Correos enviados</span>
                </a>
                <a class="im-nav-item" href="/impulsa_emprende/controller/admin/adminChatbotController.php">
                    <span class="im-nav-item__icono" data-icon="forum" aria-hidden="true"></span>
                    <span class="im-nav-item__texto">Chatbots</span>
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
                        <h1>Tareas</h1>
                    </div>
                </div>
                <div class="im-barra-superior__acciones">
                    <button class="im-boton-icono im-boton-icono--principal im-tooltip" type="button" data-abrir-config-tema aria-label="Configurar temas" data-tooltip="Configurar estilos"></button>
                    <button class="im-boton-icono material-symbols-rounded im-tooltip" type="button" data-abrir-perfil aria-label="Mi perfil" data-tooltip="Mi perfil">account_circle</button>
                    <a class="im-boton-icono material-symbols-rounded im-tooltip im-accion-salir" href="/auth/logout.php" aria-label="Salir" data-tooltip="Salir">logout</a>
                </div>
            </header>
            <main class="im-contenido">
                <section class="im-seccion-documento activa" id="tareas">
                    <div class="im-encabezado-seccion">
                        <div>
                            <p class="im-sobrelinea">Administracion</p>
                            <h2>Gestion de tareas</h2>
                        </div>
                        <div class="im-barra-superior__acciones">
                            <button class="im-boton im-boton--principal" type="button" data-abrir-tarea-sheet>
                                Anadir tarea
                            </button>
                            <span class="im-chip"><?= number_format(count($tareas), 0, ',', '.') ?> tareas</span>
                        </div>
                    </div>

                    <?php if ($mensajeEstado): ?>
                        <div class="im-alerta im-alerta--<?= $h($mensajeEstado['tipo']) ?>" role="status">
                            <?= $h($mensajeEstado['texto']) ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($tareas): ?>
                        <article class="im-tabla-tareas__tarjeta">
                            <div class="im-tabla-tareas__cabecera">
                                <div>
                                    <h3>Tareas registradas</h3>
                                    <p>Listado general con responsable, prioridad, seguimiento y estado.</p>
                                </div>
                            </div>
                            <div class="im-tabla-tareas__scroll">
                                <table class="im-tabla-tareas">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tarea</th>
                                            <th>Entrega</th>
                                            <th>Prioridad</th>
                                            <th>Estado</th>
                                            <th>Completada</th>
                                            <th class="im-tabla-tareas__acciones">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($tareas as $tarea): ?>
                                            <?php
                                            $responsableNombre = $nombreUsuario([
                                                'nombre' => $tarea['responsable_nombre'] ?? '',
                                                'apellido' => $tarea['responsable_apellido'] ?? '',
                                                'apodo' => $tarea['responsable_apodo'] ?? '',
                                                'correo' => $tarea['responsable_correo'] ?? '',
                                            ]);
                                            $creadorNombre = $nombreUsuario([
                                                'nombre' => $tarea['creador_nombre'] ?? '',
                                                'apellido' => $tarea['creador_apellido'] ?? '',
                                                'apodo' => $tarea['creador_apodo'] ?? '',
                                                'correo' => $tarea['creador_correo'] ?? '',
                                            ]);
                                            ?>
                                            <tr>
                                                <td><?= (int) ($tarea['id'] ?? 0) ?></td>
                                                <td class="im-tabla-tareas__nombre">
                                                    <?= $h($tarea['nombre_tarea'] ?? '') ?>
                                                </td>
                                                <td><?= $h($formatearFecha($tarea['fecha_entrega'] ?? null)) ?></td>
                                                <td><span class="im-chip <?= $h($prioridadChip((int) ($tarea['prioridad_defcon'] ?? 0))) ?>">DEFCON <?= (int) ($tarea['prioridad_defcon'] ?? 0) ?></span></td>
                                                <td><span class="im-chip <?= $h($estadoChip((string) ($tarea['estado'] ?? ''))) ?>"><?= $h($formatearEstado((string) ($tarea['estado'] ?? ''))) ?></span></td>
                                                <td><?= $h($formatearFecha($tarea['completed_at'] ?? null, true)) ?></td>
                                                <td class="im-tabla-tareas__acciones">
                                                    <div class="im-menu-tabla" data-im-menu>
                                                        <button class="im-boton-icono im-boton-icono--menu-tabla material-symbols-rounded" type="button" data-im-menu-trigger aria-label="Opciones de tabla" aria-haspopup="menu" aria-expanded="false">more_horiz</button>
                                                        <div class="im-menu-flotante im-menu-tabla__panel" role="menu" data-im-menu-panel>
                                                            <button type="button" role="menuitem" data-ver-tarea="<?= $toJson($tarea) ?>">
                                                                <span class="material-symbols-rounded" aria-hidden="true">visibility</span>
                                                                Ver tarea
                                                            </button>
                                                            <button type="button" role="menuitem" data-editar-tarea="<?= $toJson($tarea) ?>">
                                                                <span class="material-symbols-rounded" aria-hidden="true">edit</span>
                                                                Editar tarea
                                                            </button>
                                                            <button class="im-tarea-accion-eliminar" type="button" role="menuitem" data-eliminar-tarea="<?= (int) ($tarea['id'] ?? 0) ?>" data-tarea-nombre="<?= $h($tarea['nombre_tarea'] ?? '') ?>">
                                                                <span class="material-symbols-rounded" aria-hidden="true">delete</span>
                                                                Borrar tarea
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </article>
                    <?php else: ?>
                        <article class="im-tarjeta">
                            <h3>No hay tareas para mostrar.</h3>
                            <p>Cuando crees la primera tarea, aparecera en este listado.</p>
                        </article>
                    <?php endif; ?>
                </section>
            </main>
        </div>
    </div>

    <div class="im-modal-cortina" data-cerrar-eliminar-tarea></div>
    <section class="im-dialog im-tarea-modal" role="dialog" aria-modal="true" aria-labelledby="eliminar-tarea-titulo" aria-hidden="true" data-modal-eliminar-tarea>
        <header class="im-dialog__cabecera">
            <div>
                <p class="im-sobrelinea">Accion irreversible</p>
                <h3 id="eliminar-tarea-titulo">Borrar tarea</h3>
            </div>
            <button class="im-boton-icono" type="button" data-cerrar-eliminar-tarea aria-label="Cerrar dialog"></button>
        </header>
        <form method="post" action="/impulsa_emprende/controller/admin/adminTareasController.php">
            <input type="hidden" name="accion" value="eliminar_tarea">
            <input type="hidden" name="tarea_id" value="" data-eliminar-tarea-id>
            <div class="im-dialog__contenido">
                <p><strong data-eliminar-tarea-nombre>Tarea seleccionada</strong></p>
                <p>Esta accion eliminara la tarea definitivamente. No se puede deshacer.</p>
            </div>
            <footer class="im-dialog__acciones">
                <button class="im-boton im-boton--texto" type="button" data-cerrar-eliminar-tarea>Cancelar</button>
                <button class="im-boton im-boton--principal im-tarea-accion-eliminar" type="submit">Confirmar eliminacion</button>
            </footer>
        </form>
    </section>

    <div class="im-bottom-sheet-cortina" data-cerrar-detalle-tarea></div>
    <section class="im-bottom-sheet im-bottom-sheet--config im-tarea-detalle-sheet" role="dialog" aria-modal="true" aria-labelledby="detalle-tarea-titulo" aria-hidden="true" data-detalle-tarea-sheet>
        <header class="im-bottom-sheet__cabecera">
            <div>
                <h3 id="detalle-tarea-titulo">Detalle de tarea</h3>
                <p>Vista completa con informacion operativa y de seguimiento.</p>
            </div>
            <button class="im-boton-icono" type="button" data-cerrar-detalle-tarea aria-label="Cerrar dialog"></button>
        </header>
        <div class="im-tarea-detalle">
            <section class="im-tarea-detalle__hero">
                <div>
                    <p class="im-sobrelinea">Tarea</p>
                    <h4 data-detalle-tarea-nombre>Tarea seleccionada</h4>
                </div>
                <div class="im-tarea-detalle__chips">
                    <span class="im-chip" data-detalle-tarea-prioridad>DEFCON 5</span>
                    <span class="im-chip" data-detalle-tarea-estado>Pendiente</span>
                </div>
                <p data-detalle-tarea-descripcion>Sin descripcion.</p>
            </section>
            <section class="im-tarea-detalle__grid">
                <article class="im-tarea-detalle__bloque">
                    <span>ID</span>
                    <strong data-detalle-tarea-id>-</strong>
                </article>
                <article class="im-tarea-detalle__bloque">
                    <span>Fecha de entrega</span>
                    <strong data-detalle-tarea-fecha-entrega>-</strong>
                </article>
                <article class="im-tarea-detalle__bloque">
                    <span>Responsable</span>
                    <strong data-detalle-tarea-responsable>-</strong>
                    <p data-detalle-tarea-responsable-correo></p>
                </article>
                <article class="im-tarea-detalle__bloque">
                    <span>Reporta a</span>
                    <strong data-detalle-tarea-reporta>-</strong>
                </article>
                <article class="im-tarea-detalle__bloque">
                    <span>Creada por</span>
                    <strong data-detalle-tarea-creador>-</strong>
                    <p data-detalle-tarea-creador-correo></p>
                </article>
                <article class="im-tarea-detalle__bloque">
                    <span>Completada</span>
                    <strong data-detalle-tarea-completada>-</strong>
                </article>
                <article class="im-tarea-detalle__bloque">
                    <span>Creada</span>
                    <strong data-detalle-tarea-creada>-</strong>
                </article>
                <article class="im-tarea-detalle__bloque">
                    <span>Actualizada</span>
                    <strong data-detalle-tarea-actualizada>-</strong>
                </article>
            </section>
        </div>
        <div class="im-config-tema__acciones">
            <button class="im-boton im-boton--texto" type="button" data-cerrar-detalle-tarea>Cerrar</button>
        </div>
    </section>

    <div class="im-bottom-sheet-cortina" data-cerrar-tarea-sheet></div>
    <section class="im-bottom-sheet im-bottom-sheet--config im-tarea-sheet--editar" role="dialog" aria-modal="true" aria-labelledby="tarea-sheet-titulo" aria-hidden="true" data-tarea-sheet>
        <header class="im-bottom-sheet__cabecera">
            <div>
                <h3 id="tarea-sheet-titulo" data-tarea-sheet-titulo>Anadir tarea</h3>
                <p data-tarea-sheet-subtitulo>Completa los datos para registrar una nueva tarea administrativa.</p>
            </div>
            <button class="im-boton-icono" type="button" data-cerrar-tarea-sheet aria-label="Cerrar dialog"></button>
        </header>
        <form class="im-config-tema" method="post" action="/impulsa_emprende/controller/admin/adminTareasController.php" data-form-tarea>
            <input type="hidden" name="accion" value="crear_tarea" data-tarea-accion>
            <input type="hidden" name="tarea_id" value="" data-tarea-id>
            <div class="im-config-tema__grupo">
                <div>
                    <h4>Datos principales</h4>
                    <p>Formulario limpio para alta o edicion de la tarea.</p>
                </div>
                <div class="im-tarea-sheet__form">
                    <label class="im-campo im-campo-material im-campo--ancho" data-im-campo="generico">
                        <span>Nombre de la tarea</span>
                        <input type="text" name="nombre_tarea" maxlength="180" required data-tarea-nombre placeholder="Seguimiento de contrato">
                        <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">task</i>
                        <small data-im-error>Nombre requerido.</small>
                    </label>
                    <label class="im-campo im-campo-material" data-im-campo="generico">
                        <span>Responsable</span>
                        <select name="responsable_user_id" required data-tarea-responsable>
                            <option value="">Seleccionar</option>
                            <?php foreach ($usuariosTareas as $usuarioTarea): ?>
                                <option value="<?= (int) ($usuarioTarea['id'] ?? 0) ?>"><?= $h($nombreUsuario($usuarioTarea)) ?> - <?= $h($usuarioTarea['correo'] ?? '') ?></option>
                            <?php endforeach; ?>
                        </select>
                        <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">person</i>
                        <small data-im-error>Responsable requerido.</small>
                    </label>
                    <label class="im-campo im-campo-material" data-im-campo="generico">
                        <span>Fecha de entrega</span>
                        <input type="date" name="fecha_entrega" required data-tarea-fecha>
                        <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">event</i>
                        <small data-im-error>Fecha requerida.</small>
                    </label>
                    <label class="im-campo im-campo-material" data-im-campo="generico">
                        <span>Prioridad DEFCON</span>
                        <select name="prioridad_defcon" required data-tarea-prioridad>
                            <option value="5">DEFCON 5</option>
                            <option value="4">DEFCON 4</option>
                            <option value="3">DEFCON 3</option>
                            <option value="2">DEFCON 2</option>
                            <option value="1">DEFCON 1</option>
                        </select>
                        <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">priority_high</i>
                        <small data-im-error>Prioridad requerida.</small>
                    </label>
                    <label class="im-campo im-campo-material" data-im-campo="generico">
                        <span>Estado</span>
                        <select name="estado" required data-tarea-estado>
                            <?php foreach ($opcionesEstado as $valorEstado => $labelEstado): ?>
                                <option value="<?= $h($valorEstado) ?>"><?= $h($labelEstado) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">flag</i>
                        <small data-im-error>Estado requerido.</small>
                    </label>
                    <label class="im-campo im-campo-material im-campo--ancho" data-im-campo="generico">
                        <span>Reporta a</span>
                        <input type="text" name="reporta_a" maxlength="180" required data-tarea-reporta placeholder="Direccion general">
                        <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">north</i>
                        <small data-im-error>Campo requerido.</small>
                    </label>
                    <label class="im-campo im-campo-material im-campo--ancho" data-im-campo="generico">
                        <span>Descripcion</span>
                        <textarea name="descripcion" rows="6" required data-tarea-descripcion placeholder="Detalle operativo de la tarea"></textarea>
                        <small data-im-error>Descripcion requerida.</small>
                    </label>
                </div>
            </div>
            <div class="im-config-tema__acciones">
                <button class="im-boton im-boton--texto" type="button" data-cerrar-tarea-sheet>Cancelar</button>
                <button class="im-boton im-boton--principal" type="submit" data-tarea-submit>Guardar tarea</button>
            </div>
        </form>
    </section>

    <?php require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilView.php'; ?>
    <script src="../../../assets/impulsa_material/js/material.js"></script>
    <script>
        (() => {
            const sheet = document.querySelector('[data-tarea-sheet]');
            const cortina = document.querySelector('[data-cerrar-tarea-sheet].im-bottom-sheet-cortina');
            const form = document.querySelector('[data-form-tarea]');
            if (!sheet || !cortina || !form) {
                return;
            }

            const detalleSheet = document.querySelector('[data-detalle-tarea-sheet]');
            const detalleCortina = document.querySelector('[data-cerrar-detalle-tarea].im-bottom-sheet-cortina');
            const campos = {
                accion: form.querySelector('[data-tarea-accion]'),
                id: form.querySelector('[data-tarea-id]'),
                titulo: document.querySelector('[data-tarea-sheet-titulo]'),
                subtitulo: document.querySelector('[data-tarea-sheet-subtitulo]'),
                submit: form.querySelector('[data-tarea-submit]'),
                nombre: form.querySelector('[data-tarea-nombre]'),
                responsable: form.querySelector('[data-tarea-responsable]'),
                fecha: form.querySelector('[data-tarea-fecha]'),
                prioridad: form.querySelector('[data-tarea-prioridad]'),
                estado: form.querySelector('[data-tarea-estado]'),
                reporta: form.querySelector('[data-tarea-reporta]'),
                descripcion: form.querySelector('[data-tarea-descripcion]'),
            };

            const alternar = (abrir) => {
                sheet.classList.toggle('abierto', abrir);
                cortina.classList.toggle('abierto', abrir);
                sheet.setAttribute('aria-hidden', abrir ? 'false' : 'true');
            };

            const alternarDetalle = (abrir) => {
                if (!detalleSheet || !detalleCortina) {
                    return;
                }
                detalleSheet.classList.toggle('abierto', abrir);
                detalleCortina.classList.toggle('abierto', abrir);
                detalleSheet.setAttribute('aria-hidden', abrir ? 'false' : 'true');
            };

            const resetear = () => {
                form.reset();
                campos.accion.value = 'crear_tarea';
                campos.id.value = '';
                campos.titulo.textContent = 'Anadir tarea';
                campos.subtitulo.textContent = 'Completa los datos para registrar una nueva tarea administrativa.';
                campos.submit.textContent = 'Guardar tarea';
                campos.prioridad.value = '5';
                campos.estado.value = 'pendiente';
            };

            const formatearFecha = (valor) => {
                if (!valor) {
                    return '-';
                }

                const fecha = new Date(String(valor).replace(' ', 'T'));
                return Number.isNaN(fecha.getTime()) ? valor : fecha.toLocaleString('es-AR');
            };

            const cargarEdicion = (data) => {
                campos.accion.value = 'actualizar_tarea';
                campos.id.value = data.id || '';
                campos.titulo.textContent = 'Editar tarea';
                campos.subtitulo.textContent = 'Actualiza la informacion de la tarea seleccionada.';
                campos.submit.textContent = 'Guardar cambios';
                campos.nombre.value = data.nombre_tarea || '';
                campos.responsable.value = data.responsable_user_id || '';
                campos.fecha.value = data.fecha_entrega || '';
                campos.prioridad.value = data.prioridad_defcon || '5';
                campos.estado.value = data.estado || 'pendiente';
                campos.reporta.value = data.reporta_a || '';
                campos.descripcion.value = data.descripcion || '';
            };

            const obtenerNombreVisible = (data, prefijo) => {
                const nombre = [data[`${prefijo}_nombre`], data[`${prefijo}_apellido`]].filter(Boolean).join(' ').trim();
                if (nombre) {
                    return nombre;
                }
                return data[`${prefijo}_apodo`] || data[`${prefijo}_correo`] || '-';
            };

            const cargarDetalle = (data) => {
                const prioridadNode = detalleSheet?.querySelector('[data-detalle-tarea-prioridad]');
                const estadoNode = detalleSheet?.querySelector('[data-detalle-tarea-estado]');
                if (!detalleSheet || !prioridadNode || !estadoNode) {
                    return;
                }

                detalleSheet.querySelector('[data-detalle-tarea-id]').textContent = data.id || '-';
                detalleSheet.querySelector('[data-detalle-tarea-nombre]').textContent = data.nombre_tarea || 'Tarea seleccionada';
                detalleSheet.querySelector('[data-detalle-tarea-descripcion]').textContent = data.descripcion || 'Sin descripcion.';
                detalleSheet.querySelector('[data-detalle-tarea-fecha-entrega]').textContent = formatearFecha(data.fecha_entrega || '');
                detalleSheet.querySelector('[data-detalle-tarea-reporta]').textContent = data.reporta_a || '-';
                detalleSheet.querySelector('[data-detalle-tarea-completada]').textContent = formatearFecha(data.completed_at || '');
                detalleSheet.querySelector('[data-detalle-tarea-creada]').textContent = formatearFecha(data.created_at || '');
                detalleSheet.querySelector('[data-detalle-tarea-actualizada]').textContent = formatearFecha(data.updated_at || '');
                detalleSheet.querySelector('[data-detalle-tarea-responsable]').textContent = obtenerNombreVisible(data, 'responsable');
                detalleSheet.querySelector('[data-detalle-tarea-responsable-correo]').textContent = data.responsable_correo || '';
                detalleSheet.querySelector('[data-detalle-tarea-creador]').textContent = obtenerNombreVisible(data, 'creador');
                detalleSheet.querySelector('[data-detalle-tarea-creador-correo]').textContent = data.creador_correo || '';

                prioridadNode.textContent = `DEFCON ${data.prioridad_defcon || '-'}`;
                estadoNode.textContent = data.estado ? ({
                    pendiente: 'Pendiente',
                    en_progreso: 'En progreso',
                    completada: 'Completada',
                    cancelada: 'Cancelada'
                }[data.estado] || data.estado) : '-';
                prioridadNode.className = `im-chip ${data.prioridad_defcon >= 5 ? 'im-chip--exito' : (data.prioridad_defcon <= 2 ? 'im-chip--alerta' : '')}`.trim();
                estadoNode.className = `im-chip ${data.estado === 'completada' ? 'im-chip--exito' : (data.estado === 'cancelada' || data.estado === 'pendiente' ? 'im-chip--alerta' : '')}`.trim();
            };

            resetear();

            document.addEventListener('click', (evento) => {
                if (evento.target.closest('[data-abrir-tarea-sheet]')) {
                    resetear();
                    alternarDetalle(false);
                    alternar(true);
                }

                if (evento.target.closest('[data-cerrar-detalle-tarea]')) {
                    alternarDetalle(false);
                }

                const botonVer = evento.target.closest('[data-ver-tarea]');
                if (botonVer) {
                    try {
                        const data = JSON.parse(botonVer.dataset.verTarea || '{}');
                        alternar(false);
                        cargarDetalle(data);
                        alternarDetalle(true);
                    } catch (error) {
                        console.error(error);
                    }
                }

                if (evento.target.closest('[data-cerrar-tarea-sheet]')) {
                    alternar(false);
                }

                const botonEditar = evento.target.closest('[data-editar-tarea]');
                if (botonEditar) {
                    try {
                        const data = JSON.parse(botonEditar.dataset.editarTarea || '{}');
                        resetear();
                        cargarEdicion(data);
                        alternarDetalle(false);
                        alternar(true);
                    } catch (error) {
                        console.error(error);
                    }
                }
            });
        })();

        (() => {
            const modal = document.querySelector('[data-modal-eliminar-tarea]');
            const cortina = document.querySelector('[data-cerrar-eliminar-tarea].im-modal-cortina');
            const inputId = document.querySelector('[data-eliminar-tarea-id]');
            const nombre = document.querySelector('[data-eliminar-tarea-nombre]');
            if (!modal || !cortina || !inputId || !nombre) {
                return;
            }

            const alternar = (abrir) => {
                modal.classList.toggle('abierto', abrir);
                cortina.classList.toggle('abierto', abrir);
                modal.setAttribute('aria-hidden', abrir ? 'false' : 'true');
            };

            document.addEventListener('click', (evento) => {
                const botonEliminar = evento.target.closest('[data-eliminar-tarea]');
                if (botonEliminar) {
                    inputId.value = botonEliminar.dataset.eliminarTarea || '';
                    nombre.textContent = botonEliminar.dataset.tareaNombre || 'Tarea seleccionada';
                    alternar(true);
                }

                if (evento.target.closest('[data-cerrar-eliminar-tarea]')) {
                    alternar(false);
                }
            });
        })();
    </script>
</body>

</html>
