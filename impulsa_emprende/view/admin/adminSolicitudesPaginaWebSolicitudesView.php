<?php
$usuarioCorreo = $usuarioCorreo ?? '';
$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Usuario';
$solicitudesPaginaWeb = $solicitudesPaginaWeb ?? [];
$solicitudesPaginaWebExternas = $solicitudesPaginaWebExternas ?? [];
$mensajeEstadoSolicitudesWeb = $mensajeEstadoSolicitudesWeb ?? null;
$totalSolicitudes = count($solicitudesPaginaWeb);
$totalSolicitudesExternas = count($solicitudesPaginaWebExternas);
$totalSolicitudesTodas = $totalSolicitudes + $totalSolicitudesExternas;
$h = static fn ($valor): string => htmlspecialchars((string) ($valor ?? ''), ENT_QUOTES, 'UTF-8');
$formatearFecha = static function (?string $fecha): string {
    if (!$fecha) {
        return '-';
    }

    return date('d/m/Y H:i', strtotime($fecha));
};
$preguntasSolicitud = [
    'nombre_emprendimiento' => 'Nombre del emprendimiento',
    'fecha_inicio' => 'Fecha de inicio',
    'descripcion' => 'Descripcion',
    'nombre_fundador' => 'Nombre del fundador',
    'telefono_contacto' => 'Telefono de contacto',
    'cantidad_colaboradores' => 'Cantidad de colaboradores',
    'rubro_categoria' => 'Rubro',
    'rubro_subcategoria' => 'Subrubro',
    'vende_productos' => 'Vende productos',
    'vende_servicios' => 'Vende servicios',
    'ya_factura' => 'Ya factura',
    'dominio_registrado' => 'Dominio registrado',
    'hosting_propio' => 'Hosting propio',
    'espacio_fisico' => 'Tiene espacio fisico',
    'pais' => 'Pais',
    'provincia' => 'Provincia',
    'localidad' => 'Localidad',
    'calle' => 'Calle',
    'numero' => 'Numero',
    'completado' => 'Formulario completado',
];
$camposBooleanosSolicitud = [
    'vende_productos',
    'vende_servicios',
    'ya_factura',
    'dominio_registrado',
    'hosting_propio',
    'espacio_fisico',
    'completado',
];
$preguntasSolicitudExterna = [
    'id' => 'ID',
    'nombre' => 'Nombre',
    'nombre_proyecto' => 'Nombre del proyecto',
    'correo' => 'Correo',
    'whatsapp' => 'WhatsApp',
    'q1_nombre_comercial' => 'Nombre comercial',
    'q2_actividad' => 'Actividad',
    'q3_objetivo' => 'Objetivo',
    'q4_publico' => 'Publico',
    'q5_accion_principal' => 'Accion principal',
    'q6_propuestas_destacar' => 'Propuestas a destacar',
    'q7_diferencial' => 'Diferencial',
    'q8_secciones' => 'Secciones',
    'q9_textos' => 'Textos',
    'q10_contacto' => 'Contacto',
    'q11_material_marca' => 'Material de marca',
    'q12_estilo_visual' => 'Estilo visual',
    'q13_referencias' => 'Referencias',
    'q14_recursos_visuales' => 'Recursos visuales',
    'q15_imagenes_apoyo' => 'Imagenes de apoyo',
    'q16_dominio_hosting' => 'Dominio y hosting',
    'q17_correos_corporativos' => 'Correos corporativos',
    'q18_requerimientos_adicionales' => 'Requerimientos adicionales',
    'form_source' => 'Origen del formulario',
    'ip_address' => 'IP',
    'user_agent' => 'Navegador',
    'created_at' => 'Fecha de ingreso',
];
$nombreSolicitante = static function (array $solicitud): string {
    $nombreCompleto = trim((string) ($solicitud['usuario_nombre'] ?? '') . ' ' . (string) ($solicitud['usuario_apellido'] ?? ''));
    if ($nombreCompleto !== '') {
        return $nombreCompleto;
    }

    $apodo = trim((string) ($solicitud['usuario_apodo'] ?? ''));
    return $apodo !== '' ? $apodo : 'Sin nombre';
};
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Solicitudes Web Admin</title>
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

    .im-perfil-check {
      color: var(--im-color-exito);
    }

    .im-nav-item__icono[data-icon]::before {
      content: attr(data-icon);
    }

    .im-solicitud-modal {
      width: min(920px, calc(100vw - 2rem));
      max-height: min(760px, calc(100vh - 2rem));
      grid-template-rows: auto minmax(0, 1fr) auto;
    }

    .im-solicitud-modal .im-dialog__contenido {
      min-height: 0;
      overflow-y: auto;
    }

    .im-solicitud-detalle {
      display: grid;
      gap: .75rem;
    }

    .im-solicitud-detalle__item {
      display: grid;
      gap: .25rem;
      padding-bottom: .75rem;
      border-bottom: 1px solid var(--im-color-borde);
    }

    .im-solicitud-detalle__item strong {
      color: var(--im-color-texto);
      font-size: .9rem;
    }

    .im-solicitud-detalle__item p {
      margin: 0;
      color: var(--im-color-texto-suave);
      white-space: pre-wrap;
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
        <a class="im-nav-item activo" href="/impulsa_emprende/controller/admin/adminSolicitudesPaginaWebSolicitudesController.php">
          <span class="im-nav-item__icono" data-icon="language" aria-hidden="true"></span>
          <span class="im-nav-item__texto">Solicitudes web</span>
        </a>
        <a class="im-nav-item" href="/impulsa_emprende/controller/admin/adminProyectosController.php">
          <span class="im-nav-item__icono" data-icon="work" aria-hidden="true"></span>
          <span class="im-nav-item__texto">Proyectos</span>
        </a>
        <a class="im-nav-item" href="/impulsa_emprende/controller/admin/adminAPIconfigurationController.php">
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
            <h1>Solicitudes web</h1>
          </div>
        </div>
        <div class="im-barra-superior__acciones">
          <button class="im-boton-icono im-boton-icono--principal im-tooltip" type="button" data-abrir-config-tema aria-label="Configurar temas" data-tooltip="Configurar estilos"></button>
          <button class="im-boton-icono material-symbols-rounded im-tooltip" type="button" data-abrir-perfil aria-label="Mi perfil" data-tooltip="Mi perfil">account_circle</button>
          <a class="im-boton-icono material-symbols-rounded im-tooltip im-accion-salir" href="/auth/logout.php" aria-label="Salir" data-tooltip="Salir">logout</a>
        </div>
      </header>
      <main class="im-contenido">
        <section class="im-seccion-documento activa" id="solicitudes-web">
          <div class="im-encabezado-seccion">
            <div>
              <p class="im-sobrelinea">Pagina web</p>
              <h2>Solicitudes recibidas</h2>
              <p>Solicitudes cargadas desde el formulario interno de landing page para coordinar reuniones fuera del sistema.</p>
            </div>
            <span class="im-chip"><?= number_format($totalSolicitudesTodas, 0, ',', '.') ?> solicitudes</span>
          </div>

          <?php if (is_array($mensajeEstadoSolicitudesWeb) && trim((string) ($mensajeEstadoSolicitudesWeb['mensaje'] ?? '')) !== ''): ?>
            <div class="im-alerta im-alerta--info" role="status">
              <?= $h($mensajeEstadoSolicitudesWeb['mensaje'] ?? '') ?>
            </div>
          <?php endif; ?>

          <?php if ($totalSolicitudes > 0): ?>
            <article class="im-tabla-tareas__tarjeta">
              <div class="im-tabla-tareas__cabecera">
                <div>
                  <h3>Solicitudes de páginas web Impulsa Emprende</h3>
                  <p>Ordenadas por fecha de ingreso mas reciente.</p>
                </div>
              </div>
              <div class="im-tabla-tareas__scroll">
                <table class="im-tabla-tareas">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Fecha</th>
                      <th>Emprendimiento</th>
                      <th>Solicitante</th>
                      <th>Correo</th>
                      <th>Telefono</th>
                      <th>Rubro</th>
                      <th>Estado</th>
                      <th class="im-tabla-tareas__acciones">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($solicitudesPaginaWeb as $solicitud): ?>
                      <tr>
                        <td><?= (int) ($solicitud['id'] ?? 0) ?></td>
                        <td><?= $h($formatearFecha($solicitud['created_at'] ?? null)) ?></td>
                        <td class="im-tabla-tareas__nombre"><?= $h($solicitud['nombre_emprendimiento'] ?? '-') ?></td>
                        <td><?= $h($nombreSolicitante($solicitud)) ?></td>
                        <td><?= $h($solicitud['usuario_correo'] ?? '-') ?></td>
                        <td><?= $h($solicitud['telefono_contacto'] ?? '-') ?></td>
                        <td><?= $h($solicitud['rubro_categoria'] ?? '-') ?></td>
                        <td>
                          <?php if ((int) ($solicitud['completado'] ?? 0) === 1): ?>
                            <span class="im-chip im-chip--exito">Completada</span>
                          <?php else: ?>
                            <span class="im-chip im-chip--alerta">Pendiente</span>
                          <?php endif; ?>
                        </td>
                        <td class="im-tabla-tareas__acciones">
                          <?php
                            $solicitudAccion = $solicitud;
                            $solicitudAccion['solicitud_tipo'] = 'interna';
                            $solicitudAccion['solicitante_nombre'] = $nombreSolicitante($solicitud);
                            require __DIR__ . '/../../partials/components/admin/solicitudes recibidas/acciones.php';
                          ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </article>
          <?php else: ?>
            <article class="im-tarjeta">
              <h3>No hay solicitudes de páginas web Impulsa Emprende para mostrar.</h3>
              <p>Cuando ingresen solicitudes desde el formulario de landing page, apareceran en esta tabla.</p>
            </article>
          <?php endif; ?>

          <?php if ($totalSolicitudesExternas > 0): ?>
            <article class="im-tabla-tareas__tarjeta">
              <div class="im-tabla-tareas__cabecera">
                <div>
                  <h3>Solicitudes de páginas web Impulsa</h3>
                  <p>Ordenadas por fecha de ingreso mas reciente.</p>
                </div>
              </div>
              <div class="im-tabla-tareas__scroll">
                <table class="im-tabla-tareas">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Fecha</th>
                      <th>Proyecto</th>
                      <th>Solicitante</th>
                      <th>Correo</th>
                      <th>WhatsApp</th>
                      <th>Origen</th>
                      <th class="im-tabla-tareas__acciones">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($solicitudesPaginaWebExternas as $solicitudExterna): ?>
                      <tr>
                        <td><?= (int) ($solicitudExterna['id'] ?? 0) ?></td>
                        <td><?= $h($formatearFecha($solicitudExterna['created_at'] ?? null)) ?></td>
                        <td class="im-tabla-tareas__nombre"><?= $h($solicitudExterna['nombre_proyecto'] ?? '-') ?></td>
                        <td><?= $h($solicitudExterna['nombre'] ?? '-') ?></td>
                        <td><?= $h($solicitudExterna['correo'] ?? '-') ?></td>
                        <td><?= $h($solicitudExterna['whatsapp'] ?? '-') ?></td>
                        <td><?= $h($solicitudExterna['form_source'] ?? '-') ?></td>
                        <td class="im-tabla-tareas__acciones">
                          <?php
                            $solicitudAccion = $solicitudExterna;
                            $solicitudAccion['solicitud_tipo'] = 'externa';
                            require __DIR__ . '/../../partials/components/admin/solicitudes recibidas/acciones.php';
                          ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </article>
          <?php else: ?>
            <article class="im-tarjeta">
              <h3>No hay solicitudes de páginas web Impulsa para mostrar.</h3>
              <p>Cuando ingresen solicitudes desde el formulario externo, apareceran en esta tabla.</p>
            </article>
          <?php endif; ?>
        </section>
      </main>
    </div>
  </div>

  <?php require __DIR__ . '/../../partials/components/admin/solicitudes recibidas/ver detalle externa/modal.php'; ?>
  <?php require __DIR__ . '/../../partials/components/admin/solicitudes recibidas/alta usuario/modal.php'; ?>
  <?php require __DIR__ . '/../../partials/components/admin/solicitudes recibidas/crear proyecto/modal.php'; ?>

  <?php require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilView.php'; ?>
  <script src="../../../assets/impulsa_material/js/material.js"></script>
</body>
</html>
