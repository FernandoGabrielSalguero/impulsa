<?php
$usuarioCorreo = $usuarioCorreo ?? '';
$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Usuario';
$usuarios = $usuarios ?? [];
$estado = $estado ?? '';
$totalUsuarios = count($usuarios);
$h = static fn ($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
$toJson = static fn ($valor): string => htmlspecialchars((string) json_encode($valor, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8');
$mensajesEstado = [
    'usuario_eliminado' => ['tipo' => 'exito', 'texto' => 'Usuario eliminado correctamente.'],
    'usuario_error_eliminar' => ['tipo' => 'error', 'texto' => 'No se pudo eliminar el usuario. Revisa las relaciones asociadas e intenta nuevamente.'],
    'usuario_id_invalido' => ['tipo' => 'error', 'texto' => 'El usuario seleccionado no es valido.'],
    'usuario_no_autodelete' => ['tipo' => 'error', 'texto' => 'No podes eliminar el usuario con el que tenes la sesion activa.'],
    'usuario_creado' => ['tipo' => 'exito', 'texto' => 'Usuario creado correctamente y correo de credenciales enviado.'],
    'usuario_creado_correo_fallido' => ['tipo' => 'error', 'texto' => 'Usuario creado, pero no se pudo enviar el correo de credenciales.'],
    'usuario_actualizado' => ['tipo' => 'exito', 'texto' => 'Usuario actualizado correctamente.'],
    'usuario_error_actualizar' => ['tipo' => 'error', 'texto' => 'No se pudo actualizar el usuario.'],
    'usuario_correo_invalido' => ['tipo' => 'error', 'texto' => 'El correo ingresado no es valido.'],
    'usuario_correo_contacto_invalido' => ['tipo' => 'error', 'texto' => 'El correo de contacto no es valido.'],
    'usuario_correo_existente' => ['tipo' => 'error', 'texto' => 'Ya existe un usuario registrado con ese correo.'],
    'usuario_rol_invalido' => ['tipo' => 'error', 'texto' => 'El rol seleccionado no es valido.'],
    'usuario_tipo_invalido' => ['tipo' => 'error', 'texto' => 'El tipo de usuario seleccionado no es valido.'],
    'usuario_fecha_invalida' => ['tipo' => 'error', 'texto' => 'La fecha de nacimiento no es valida.'],
    'usuario_error_crear' => ['tipo' => 'error', 'texto' => 'No se pudo crear el usuario.'],
];
$mensajeEstado = $mensajesEstado[$estado] ?? null;
$rolesAltaUsuario = ['impulsa_administrador', 'impulsa_colaborador', 'impulsa_emprendedor', 'impulsa_usuario', 'impulsa_marketing', 'impulsa_cliente'];
$tiposUsuario = ['interno', 'externo'];
$formatearRol = static function (string $rol): string {
    return ucwords(str_replace('_', ' ', $rol));
};
$formatearFecha = static function (?string $fecha): string {
    if (!$fecha) {
        return '-';
    }

    return date('d/m/Y H:i', strtotime($fecha));
};
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Usuarios Admin</title>
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

    .im-alerta--exito {
      background: color-mix(in srgb, var(--im-color-exito) 14%, var(--im-color-superficie));
      color: var(--im-color-exito);
    }

    .im-alerta--error {
      background: #fdecec;
      color: #ba1a1a;
    }

    .im-usuario-modal {
      width: min(560px, calc(100vw - 2rem));
    }

    .im-usuario-modal form {
      display: contents;
    }

    .im-usuario-accion-eliminar {
      color: #ba1a1a;
    }

    .im-usuario-edicion-modal {
      width: min(760px, calc(100vw - 2rem));
      max-height: min(760px, calc(100vh - 2rem));
      grid-template-rows: auto minmax(0, 1fr);
    }

    .im-usuario-edicion-modal form {
      display: grid;
      grid-template-rows: minmax(0, 1fr) auto;
      min-height: 0;
      overflow: hidden;
    }

    .im-usuario-edicion-modal .im-dialog__contenido {
      display: grid;
      gap: 1rem;
      min-height: 0;
      overflow: auto;
      overscroll-behavior: contain;
      padding-bottom: 1.25rem;
    }

    .im-usuario-edicion-modal .im-dialog__acciones {
      position: relative;
      z-index: 1;
      background: var(--im-color-superficie);
    }

    .im-usuario-edicion-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: .9rem;
    }

    .im-usuario-edicion-grid .im-campo--ancho,
    .im-usuario-edicion-grid .im-usuario-edicion-switches {
      grid-column: 1 / -1;
    }

    .im-usuario-edicion-seccion {
      display: grid;
      gap: .85rem;
      padding: 1rem;
      border: 1px solid var(--im-color-borde);
      border-radius: var(--im-radio);
      background: var(--im-color-superficie);
    }

    .im-usuario-edicion-seccion h4,
    .im-usuario-edicion-seccion p {
      margin: 0;
    }

    .im-usuario-edicion-seccion p {
      color: var(--im-color-texto-suave);
    }

    .im-usuario-edicion-switches {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: .75rem;
    }

    .im-usuario-edicion-switches .im-switch {
      width: 100%;
      justify-content: space-between;
      padding: .85rem 1rem;
      border: 1px solid var(--im-color-borde);
      border-radius: var(--im-radio);
      background: var(--im-color-superficie-2);
    }

    .im-tabla-tareas__acciones {
      overflow: visible;
      position: relative;
    }

    .im-menu-tabla[data-im-menu].abierto {
      z-index: 120;
    }

    .im-menu-tabla[data-im-menu] > .im-menu-tabla__panel {
      z-index: 130;
    }

    @media (max-width: 760px) {
      .im-usuario-edicion-grid {
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
            <img src="<?= htmlspecialchars($usuarioAvatarUrl, ENT_QUOTES, 'UTF-8') ?>" alt="">
          <?php else: ?>
            <?= htmlspecialchars($usuarioInicial, ENT_QUOTES, 'UTF-8') ?>
          <?php endif; ?>
        </span>
        <div class="im-marca__texto">
          <strong><?= htmlspecialchars($usuarioMarcaNombre, ENT_QUOTES, 'UTF-8') ?></strong>
          <span>Administrador</span>
        </div>
      </div>
      <nav class="im-navegacion">
        <a class="im-nav-item" href="/impulsa_emprende/controller/admin/dashboard.php">
          <span class="im-nav-item__icono" data-icon="dashboard" aria-hidden="true"></span>
          <span class="im-nav-item__texto">Dashboard</span>
        </a>
        <a class="im-nav-item activo" href="/impulsa_emprende/controller/admin/adminListUserController.php">
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
            <h1>Usuarios</h1>
          </div>
        </div>
        <div class="im-barra-superior__acciones">
          <button class="im-boton-icono im-boton-icono--principal im-tooltip" type="button" data-abrir-config-tema aria-label="Configurar temas" data-tooltip="Configurar estilos"></button>
          <button class="im-boton-icono material-symbols-rounded im-tooltip" type="button" data-abrir-perfil aria-label="Mi perfil" data-tooltip="Mi perfil">account_circle</button>
          <a class="im-boton-icono material-symbols-rounded im-tooltip im-accion-salir" href="/auth/logout.php" aria-label="Salir" data-tooltip="Salir">logout</a>
        </div>
      </header>
      <main class="im-contenido">
        <section class="im-seccion-documento activa" id="usuarios">
          <div class="im-encabezado-seccion">
            <div>
              <p class="im-sobrelinea">Administracion</p>
              <h2>Listado de usuarios</h2>
              <p>Usuarios registrados con datos de acceso, perfil y contacto.</p>
            </div>
            <div class="im-barra-superior__acciones">
              <button class="im-boton im-boton--principal" type="button" data-abrir-alta-usuario>
                <span class="material-symbols-rounded" aria-hidden="true">person_add</span>
                Dar de alta usuario
              </button>
              <span class="im-chip"><?= number_format($totalUsuarios, 0, ',', '.') ?> usuarios</span>
            </div>
          </div>

          <?php if ($mensajeEstado): ?>
            <div class="im-alerta im-alerta--<?= $h($mensajeEstado['tipo']) ?>" role="status">
              <?= $h($mensajeEstado['texto']) ?>
            </div>
          <?php endif; ?>

          <?php if ($totalUsuarios > 0): ?>
            <article class="im-tabla-tareas__tarjeta">
              <div class="im-tabla-tareas__cabecera">
                <div>
                  <h3>Usuarios registrados</h3>
                  <p>Ordenados por fecha de alta mas reciente.</p>
                </div>
                <label class="im-campo im-campo-material" data-im-campo="generico">
                  <span>Buscar usuario</span>
                  <input type="search" data-buscar-usuarios placeholder="Nombre o correo" autocomplete="off">
                  <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">search</i>
                </label>
              </div>
              <div class="im-tabla-tareas__scroll">
                <table class="im-tabla-tareas">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Usuario</th>
                      <th>Rol</th>
                      <th>Correo</th>
                      <th>WhatsApp</th>
                      <th>Pagina inicio</th>
                      <th>Verificacion</th>
                      <th>Alta</th>
                      <th class="im-tabla-tareas__acciones">Acciones</th>
                    </tr>
                  </thead>
                  <tbody data-tabla-usuarios>
                    <?php foreach ($usuarios as $usuarioListado): ?>
                      <?php
                      $nombreCompleto = trim((string) ($usuarioListado['nombre'] ?? '') . ' ' . (string) ($usuarioListado['apellido'] ?? ''));
                      $apodo = trim((string) ($usuarioListado['apodo'] ?? ''));
                      $nombreVisible = $nombreCompleto !== '' ? $nombreCompleto : ($apodo !== '' ? $apodo : 'Sin nombre');
                      $correoLogin = (string) ($usuarioListado['correo_login'] ?? '');
                      $correoContacto = (string) ($usuarioListado['correo_contacto'] ?? '');
                      $rol = (string) ($usuarioListado['rol'] ?? '');
                      $emailVerificado = !empty($usuarioListado['email_verified_at']);
                      ?>
                      <tr>
                        <td><?= (int) ($usuarioListado['id'] ?? 0) ?></td>
                        <td class="im-tabla-tareas__nombre">
                          <?= htmlspecialchars($nombreVisible, ENT_QUOTES, 'UTF-8') ?>
                          <?php if ($apodo !== '' && $apodo !== $nombreVisible): ?>
                            <br><small><?= htmlspecialchars($apodo, ENT_QUOTES, 'UTF-8') ?></small>
                          <?php endif; ?>
                        </td>
                        <td><span class="im-chip"><?= htmlspecialchars($formatearRol($rol), ENT_QUOTES, 'UTF-8') ?></span></td>
                        <td>
                          <?= htmlspecialchars($correoLogin, ENT_QUOTES, 'UTF-8') ?>
                          <?php if ($correoContacto !== '' && $correoContacto !== $correoLogin): ?>
                            <br><small><?= htmlspecialchars($correoContacto, ENT_QUOTES, 'UTF-8') ?></small>
                          <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars((string) ($usuarioListado['whatsapp'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars((string) ($usuarioListado['pagina_inicio'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                          <?php if ($emailVerificado): ?>
                            <span class="im-chip im-chip--exito">Verificado</span>
                          <?php else: ?>
                            <span class="im-chip im-chip--alerta">Pendiente</span>
                          <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($formatearFecha($usuarioListado['created_at'] ?? null), ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="im-tabla-tareas__acciones">
                          <div class="im-menu-tabla" data-im-menu>
                            <button class="im-boton-icono im-boton-icono--menu-tabla material-symbols-rounded" type="button" data-im-menu-trigger aria-label="Opciones de tabla" aria-haspopup="menu" aria-expanded="false">more_horiz</button>
                            <div class="im-menu-flotante im-menu-tabla__panel" role="menu" data-im-menu-panel>
                              <button class="im-usuario-accion-modificar" type="button" role="menuitem" data-modificar-usuario="<?= $toJson($usuarioListado) ?>">
                                <span class="material-symbols-rounded" aria-hidden="true">edit</span>
                                Modificar usuario
                              </button>
                              <button class="im-usuario-accion-eliminar" type="button" role="menuitem" data-eliminar-usuario="<?= (int) ($usuarioListado['id'] ?? 0) ?>" data-usuario-nombre="<?= $h($nombreVisible) ?>" data-usuario-correo="<?= $h($correoLogin) ?>">
                                <span class="material-symbols-rounded" aria-hidden="true">delete</span>
                                Eliminar usuario
                              </button>
                            </div>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
              <p data-usuarios-mensaje hidden>No se encontraron usuarios para esa busqueda.</p>
            </article>
          <?php else: ?>
            <article class="im-tarjeta">
              <h3>No hay usuarios registrados para mostrar.</h3>
              <p>Cuando existan usuarios en el sistema, apareceran en esta tabla.</p>
            </article>
          <?php endif; ?>
        </section>
      </main>
    </div>
  </div>

  <div class="im-modal-cortina" data-cerrar-eliminar-usuario></div>
  <section class="im-dialog im-usuario-modal" role="dialog" aria-modal="true" aria-labelledby="eliminar-usuario-titulo" aria-hidden="true" data-modal-eliminar-usuario>
    <header class="im-dialog__cabecera">
      <div>
        <p class="im-sobrelinea">Accion irreversible</p>
        <h3 id="eliminar-usuario-titulo">Eliminar usuario</h3>
      </div>
      <button class="im-boton-icono" type="button" data-cerrar-eliminar-usuario aria-label="Cerrar dialog"></button>
    </header>
    <form method="post" action="/impulsa_emprende/controller/admin/adminListUserController.php">
      <input type="hidden" name="accion" value="eliminar_usuario">
      <input type="hidden" name="usuario_id" value="" data-eliminar-usuario-id>
      <div class="im-dialog__contenido">
        <p><strong data-eliminar-usuario-nombre>Usuario seleccionado</strong></p>
        <p data-eliminar-usuario-correo></p>
        <p>Estas seguro de que deseas eliminar este usuario? Esta accion eliminara permanentemente el usuario y toda la informacion relacionada. No se puede deshacer.</p>
      </div>
      <footer class="im-dialog__acciones">
        <button class="im-boton im-boton--texto" type="button" data-cerrar-eliminar-usuario>Cancelar</button>
        <button class="im-boton im-boton--principal im-usuario-accion-eliminar" type="submit">Confirmar eliminacion</button>
      </footer>
    </form>
  </section>

  <div class="im-modal-cortina" data-cerrar-editar-usuario></div>
  <section class="im-dialog im-usuario-edicion-modal" role="dialog" aria-modal="true" aria-labelledby="editar-usuario-titulo" aria-hidden="true" data-modal-editar-usuario>
    <header class="im-dialog__cabecera">
      <div>
        <p class="im-sobrelinea">Administracion</p>
        <h3 id="editar-usuario-titulo">Modificar usuario</h3>
      </div>
      <button class="im-boton-icono" type="button" data-cerrar-editar-usuario aria-label="Cerrar dialog"></button>
    </header>
    <form method="post" action="/impulsa_emprende/controller/admin/adminListUserController.php">
      <input type="hidden" name="accion" value="actualizar_usuario">
      <input type="hidden" name="usuario_id" value="" data-editar-usuario-id>
      <div class="im-dialog__contenido">
        <section class="im-usuario-edicion-seccion">
          <div>
            <h4>Acceso</h4>
            <p>Datos principales para ingreso y clasificacion del usuario.</p>
          </div>
          <div class="im-usuario-edicion-grid">
            <label class="im-campo im-campo-material im-campo--ancho" data-im-campo="email">
              <span>Correo de acceso</span>
              <input type="email" name="correo" required data-editar-usuario-correo placeholder="usuario@dominio.com">
              <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">mail</i>
              <small data-im-error>Correo requerido.</small>
            </label>
            <label class="im-campo im-campo-material" data-im-campo="generico">
              <span>Rol</span>
              <select name="rol" required data-editar-usuario-rol>
                <?php foreach ($rolesAltaUsuario as $rolAlta): ?>
                  <option value="<?= $h($rolAlta) ?>"><?= $h($formatearRol($rolAlta)) ?></option>
                <?php endforeach; ?>
              </select>
              <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">admin_panel_settings</i>
              <small data-im-error>Rol requerido.</small>
            </label>
            <label class="im-campo im-campo-material" data-im-campo="generico">
              <span>Tipo de usuario</span>
              <select name="usuario_tipo" required data-editar-usuario-tipo>
                <?php foreach ($tiposUsuario as $tipoUsuario): ?>
                  <option value="<?= $h($tipoUsuario) ?>"><?= $h(ucfirst($tipoUsuario)) ?></option>
                <?php endforeach; ?>
              </select>
              <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">badge</i>
              <small data-im-error>Tipo requerido.</small>
            </label>
            <label class="im-campo im-campo-material">
              <span>Pagina de inicio</span>
              <input name="pagina_inicio" data-editar-usuario-pagina placeholder="/mi-ruta">
              <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">home</i>
            </label>
            <label class="im-switch">
              <span>Email verificado</span>
              <input type="hidden" name="correo_verificado" value="0">
              <input type="checkbox" name="correo_verificado" value="1" data-editar-usuario-verificado>
            </label>
          </div>
        </section>

        <section class="im-usuario-edicion-seccion">
          <div>
            <h4>Perfil</h4>
            <p>Informacion visible del usuario dentro de la plataforma.</p>
          </div>
          <div class="im-usuario-edicion-grid">
            <label class="im-campo im-campo-material"><span>Nombre</span><input name="nombre" data-editar-usuario-nombre placeholder="Nombre"></label>
            <label class="im-campo im-campo-material"><span>Apellido</span><input name="apellido" data-editar-usuario-apellido placeholder="Apellido"></label>
            <label class="im-campo im-campo-material"><span>Apodo</span><input name="apodo" data-editar-usuario-apodo placeholder="Nombre visible"></label>
            <label class="im-campo im-campo-material"><span>Fecha de nacimiento</span><input type="date" name="fecha_nacimiento" data-editar-usuario-fecha></label>
          </div>
        </section>

        <section class="im-usuario-edicion-seccion">
          <div>
            <h4>Contacto</h4>
            <p>Canales y permisos asociados al perfil.</p>
          </div>
          <div class="im-usuario-edicion-grid">
            <label class="im-campo im-campo-material"><span>Correo de contacto</span><input type="email" name="correo_contacto" data-editar-usuario-correo-contacto placeholder="contacto@dominio.com"></label>
            <label class="im-campo im-campo-material"><span>WhatsApp</span><input name="whatsapp" data-editar-usuario-whatsapp placeholder="+54 11 1234 5678"></label>
            <div class="im-usuario-edicion-switches">
              <label class="im-switch">
                <span>Permitir contacto por correo</span>
                <input type="hidden" name="permison_correo" value="0">
                <input type="checkbox" name="permison_correo" value="1" data-editar-usuario-permiso-correo>
              </label>
              <label class="im-switch">
                <span>Permitir contacto por WhatsApp</span>
                <input type="hidden" name="permison_whatsapp" value="0">
                <input type="checkbox" name="permison_whatsapp" value="1" data-editar-usuario-permiso-whatsapp>
              </label>
            </div>
          </div>
        </section>
      </div>
      <footer class="im-dialog__acciones">
        <button class="im-boton im-boton--texto" type="button" data-cerrar-editar-usuario>Cancelar</button>
        <button class="im-boton im-boton--principal" type="submit">Guardar cambios</button>
      </footer>
    </form>
  </section>

  <div class="im-bottom-sheet-cortina" data-cerrar-alta-usuario></div>
  <section class="im-bottom-sheet im-bottom-sheet--config" role="dialog" aria-modal="true" aria-labelledby="alta-usuario-titulo" aria-hidden="true" data-alta-usuario-sheet>
    <header class="im-bottom-sheet__cabecera">
      <div>
        <h3 id="alta-usuario-titulo">Dar de alta usuario</h3>
        <p>La contrasena se genera automaticamente y se envia por correo.</p>
      </div>
      <button class="im-boton-icono" type="button" data-cerrar-alta-usuario aria-label="Cerrar dialog"></button>
    </header>
    <form class="im-config-tema" method="post" action="/impulsa_emprende/controller/admin/adminListUserController.php">
      <input type="hidden" name="accion" value="crear_usuario_manual">
      <div class="im-config-tema__grupo">
        <h4>Acceso</h4>
        <label class="im-campo im-campo-material" data-im-campo="email">
          <span>Correo</span>
          <input type="email" name="correo" required placeholder="usuario@dominio.com">
          <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">mail</i>
          <small data-im-error>Correo requerido.</small>
        </label>
        <label class="im-campo im-campo-material" data-im-campo="generico">
          <span>Rol</span>
          <select name="rol" required>
            <?php foreach ($rolesAltaUsuario as $rolAlta): ?>
              <option value="<?= $h($rolAlta) ?>"><?= $h($formatearRol($rolAlta)) ?></option>
            <?php endforeach; ?>
          </select>
          <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">admin_panel_settings</i>
          <small data-im-error>Rol requerido.</small>
        </label>
      </div>
      <div class="im-config-tema__grupo">
        <h4>Perfil y contacto</h4>
        <label class="im-campo im-campo-material"><span>Nombre</span><input name="nombre" placeholder="Nombre"></label>
        <label class="im-campo im-campo-material"><span>Apellido</span><input name="apellido" placeholder="Apellido"></label>
        <label class="im-campo im-campo-material"><span>Apodo</span><input name="apodo" placeholder="Nombre visible"></label>
        <label class="im-campo im-campo-material"><span>WhatsApp</span><input name="whatsapp" placeholder="+54 11 1234 5678"></label>
      </div>
      <div class="im-config-tema__acciones">
        <button class="im-boton im-boton--texto" type="button" data-cerrar-alta-usuario>Cancelar</button>
        <button class="im-boton im-boton--principal" type="submit">Crear usuario y enviar acceso</button>
      </div>
    </form>
  </section>

  <?php require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilView.php'; ?>
  <script src="../../../assets/impulsa_material/js/material.js"></script>
  <script>
    (() => {
      const sheet = document.querySelector('[data-alta-usuario-sheet]');
      const cortina = document.querySelector('[data-cerrar-alta-usuario].im-bottom-sheet-cortina');
      const alternar = (abrir) => {
        sheet?.classList.toggle('abierto', abrir);
        cortina?.classList.toggle('abierto', abrir);
        sheet?.setAttribute('aria-hidden', abrir ? 'false' : 'true');
      };
      document.addEventListener('click', (evento) => {
        if (evento.target.closest('[data-abrir-alta-usuario]')) {
          alternar(true);
        }
        if (evento.target.closest('[data-cerrar-alta-usuario]')) {
          alternar(false);
        }
      });
    })();

    (() => {
      const input = document.querySelector('[data-buscar-usuarios]');
      const tbody = document.querySelector('[data-tabla-usuarios]');
      const mensaje = document.querySelector('[data-usuarios-mensaje]');
      if (!input || !tbody || !mensaje) {
        return;
      }

      const filasIniciales = tbody.innerHTML;
      let timeoutId = null;
      let controller = null;

      const escapeHtml = (value) => String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');

      const formatearRol = (rol) => String(rol ?? '').replaceAll('_', ' ').replace(/\b\w/g, letra => letra.toUpperCase());

      const formatearFecha = (fecha) => {
        if (!fecha) {
          return '-';
        }

        const date = new Date(String(fecha).replace(' ', 'T'));
        if (Number.isNaN(date.getTime())) {
          return '-';
        }

        return new Intl.DateTimeFormat('es-AR', {
          day: '2-digit',
          month: '2-digit',
          year: 'numeric',
          hour: '2-digit',
          minute: '2-digit',
        }).format(date);
      };

      const renderAccionesUsuario = (usuario, nombreVisible, correoLogin) => `
        <div class="im-menu-tabla" data-im-menu data-im-menu-dinamico>
          <button class="im-boton-icono im-boton-icono--menu-tabla material-symbols-rounded" type="button" data-im-menu-trigger aria-label="Opciones de tabla" aria-haspopup="menu" aria-expanded="false">more_horiz</button>
          <div class="im-menu-flotante im-menu-tabla__panel" role="menu" data-im-menu-panel>
            <button class="im-usuario-accion-modificar" type="button" role="menuitem" data-modificar-usuario='${escapeHtml(JSON.stringify(usuario))}'>
              <span class="material-symbols-rounded" aria-hidden="true">edit</span>
              Modificar usuario
            </button>
            <button class="im-usuario-accion-eliminar" type="button" role="menuitem" data-eliminar-usuario="${Number(usuario.id ?? 0)}" data-usuario-nombre="${escapeHtml(nombreVisible)}" data-usuario-correo="${escapeHtml(correoLogin)}">
              <span class="material-symbols-rounded" aria-hidden="true">delete</span>
              Eliminar usuario
            </button>
          </div>
        </div>`;

      const renderUsuarios = (usuarios) => {
        mensaje.hidden = usuarios.length > 0;
        tbody.innerHTML = usuarios.map((usuario) => {
          const nombreCompleto = `${usuario.nombre ?? ''} ${usuario.apellido ?? ''}`.trim();
          const apodo = String(usuario.apodo ?? '').trim();
          const nombreVisible = nombreCompleto || apodo || 'Sin nombre';
          const correoLogin = String(usuario.correo_login ?? '');
          const correoContacto = String(usuario.correo_contacto ?? '');
          const contactoHtml = correoContacto && correoContacto !== correoLogin
            ? `<br><small>${escapeHtml(correoContacto)}</small>`
            : '';
          const apodoHtml = apodo && apodo !== nombreVisible
            ? `<br><small>${escapeHtml(apodo)}</small>`
            : '';
          const verificado = Boolean(usuario.email_verified_at);

          return `<tr>
            <td>${Number(usuario.id ?? 0)}</td>
            <td class="im-tabla-tareas__nombre">${escapeHtml(nombreVisible)}${apodoHtml}</td>
            <td><span class="im-chip">${escapeHtml(formatearRol(usuario.rol))}</span></td>
            <td>${escapeHtml(correoLogin)}${contactoHtml}</td>
            <td>${escapeHtml(usuario.whatsapp || '-')}</td>
            <td>${escapeHtml(usuario.pagina_inicio || '-')}</td>
            <td><span class="im-chip ${verificado ? 'im-chip--exito' : 'im-chip--alerta'}">${verificado ? 'Verificado' : 'Pendiente'}</span></td>
            <td>${escapeHtml(formatearFecha(usuario.created_at))}</td>
            <td class="im-tabla-tareas__acciones">${renderAccionesUsuario(usuario, nombreVisible, correoLogin)}</td>
          </tr>`;
        }).join('');
      };

      const buscarUsuarios = async (busqueda) => {
        if (controller) {
          controller.abort();
        }

        controller = new AbortController();
        const params = new URLSearchParams({
          ajax: 'usuarios',
          q: busqueda,
        });

        const response = await fetch(`${window.location.pathname}?${params.toString()}`, {
          headers: { Accept: 'application/json' },
          signal: controller.signal,
        });
        const data = await response.json();
        renderUsuarios(Array.isArray(data.usuarios) ? data.usuarios : []);
      };

      input.addEventListener('input', () => {
        const busqueda = input.value.trim();
        window.clearTimeout(timeoutId);

        if (busqueda.length < 4) {
          if (controller) {
            controller.abort();
          }
          tbody.innerHTML = filasIniciales;
          mensaje.hidden = true;
          return;
        }

        timeoutId = window.setTimeout(() => {
          buscarUsuarios(busqueda).catch((error) => {
            if (error.name !== 'AbortError') {
              mensaje.hidden = false;
            }
          });
        }, 250);
      });
    })();

    (() => {
      const modal = document.querySelector('[data-modal-eliminar-usuario]');
      const cortina = document.querySelector('[data-cerrar-eliminar-usuario].im-modal-cortina');
      const inputId = document.querySelector('[data-eliminar-usuario-id]');
      const nombre = document.querySelector('[data-eliminar-usuario-nombre]');
      const correo = document.querySelector('[data-eliminar-usuario-correo]');

      const cerrarMenus = () => {
        document.querySelectorAll('[data-im-menu]').forEach((menu) => {
          menu.classList.remove('abierto');
          menu.querySelector('[data-im-menu-panel]')?.classList.remove('abierto');
          menu.querySelector('[data-im-menu-trigger]')?.setAttribute('aria-expanded', 'false');
        });
      };

      const alternarModal = (abrir) => {
        if (!modal || !cortina) {
          return;
        }

        modal.classList.toggle('abierto', abrir);
        cortina.classList.toggle('abierto', abrir);
        modal.setAttribute('aria-hidden', abrir ? 'false' : 'true');
      };

      document.addEventListener('click', (evento) => {
        const trigger = evento.target.closest('[data-im-menu-dinamico] [data-im-menu-trigger]');
        if (trigger) {
          evento.stopPropagation();
          const menu = trigger.closest('[data-im-menu]');
          const panel = menu?.querySelector('[data-im-menu-panel]');
          const abrir = !panel?.classList.contains('abierto');
          cerrarMenus();
          menu?.classList.toggle('abierto', abrir);
          panel?.classList.toggle('abierto', abrir);
          trigger.setAttribute('aria-expanded', String(abrir));
          return;
        }

        const botonEliminar = evento.target.closest('[data-eliminar-usuario]');
        if (botonEliminar) {
          cerrarMenus();
          if (inputId && nombre && correo) {
            inputId.value = botonEliminar.dataset.eliminarUsuario || '';
            nombre.textContent = botonEliminar.dataset.usuarioNombre || 'Usuario seleccionado';
            correo.textContent = botonEliminar.dataset.usuarioCorreo || '';
          }
          alternarModal(true);
          return;
        }

        if (!evento.target.closest('[data-im-menu-dinamico]')) {
          cerrarMenus();
        }
      });

      document.querySelectorAll('[data-cerrar-eliminar-usuario]').forEach((boton) => {
        boton.addEventListener('click', () => alternarModal(false));
      });

      document.addEventListener('keydown', (evento) => {
        if (evento.key === 'Escape') {
          cerrarMenus();
          alternarModal(false);
        }
      });
    })();

    (() => {
      const modal = document.querySelector('[data-modal-editar-usuario]');
      const cortina = document.querySelector('[data-cerrar-editar-usuario].im-modal-cortina');
      if (!modal || !cortina) {
        return;
      }

      const fields = {
        id: modal.querySelector('[data-editar-usuario-id]'),
        correo: modal.querySelector('[data-editar-usuario-correo]'),
        rol: modal.querySelector('[data-editar-usuario-rol]'),
        tipo: modal.querySelector('[data-editar-usuario-tipo]'),
        pagina: modal.querySelector('[data-editar-usuario-pagina]'),
        verificado: modal.querySelector('[data-editar-usuario-verificado]'),
        nombre: modal.querySelector('[data-editar-usuario-nombre]'),
        apellido: modal.querySelector('[data-editar-usuario-apellido]'),
        apodo: modal.querySelector('[data-editar-usuario-apodo]'),
        fecha: modal.querySelector('[data-editar-usuario-fecha]'),
        correoContacto: modal.querySelector('[data-editar-usuario-correo-contacto]'),
        whatsapp: modal.querySelector('[data-editar-usuario-whatsapp]'),
        permisoCorreo: modal.querySelector('[data-editar-usuario-permiso-correo]'),
        permisoWhatsapp: modal.querySelector('[data-editar-usuario-permiso-whatsapp]'),
      };

      const alternarModal = (abrir) => {
        modal.classList.toggle('abierto', abrir);
        cortina.classList.toggle('abierto', abrir);
        modal.setAttribute('aria-hidden', abrir ? 'false' : 'true');
      };

      const setValue = (element, value) => {
        if (element) {
          element.value = value ?? '';
          element.dispatchEvent(new Event('input', { bubbles: true }));
          element.dispatchEvent(new Event('change', { bubbles: true }));
        }
      };

      const setChecked = (element, value) => {
        if (element) {
          element.checked = Boolean(value);
          element.dispatchEvent(new Event('change', { bubbles: true }));
        }
      };

      const cargarUsuario = (usuario) => {
        setValue(fields.id, usuario.id ?? '');
        setValue(fields.correo, usuario.correo_login ?? '');
        setValue(fields.rol, usuario.rol ?? 'impulsa_usuario');
        setValue(fields.tipo, usuario.usuario_tipo ?? 'externo');
        setValue(fields.pagina, usuario.pagina_inicio ?? '');
        setChecked(fields.verificado, Boolean(usuario.email_verified_at));
        setValue(fields.nombre, usuario.nombre ?? '');
        setValue(fields.apellido, usuario.apellido ?? '');
        setValue(fields.apodo, usuario.apodo ?? '');
        setValue(fields.fecha, usuario.fecha_nacimiento ?? '');
        setValue(fields.correoContacto, usuario.correo_contacto ?? usuario.correo_login ?? '');
        setValue(fields.whatsapp, usuario.whatsapp ?? '');
        setChecked(fields.permisoCorreo, Number(usuario.permison_correo ?? 1) === 1);
        setChecked(fields.permisoWhatsapp, Number(usuario.permison_whatsapp ?? 1) === 1);
      };

      document.addEventListener('click', (evento) => {
        const trigger = evento.target.closest('[data-modificar-usuario]');
        if (trigger) {
          try {
            cargarUsuario(JSON.parse(trigger.getAttribute('data-modificar-usuario') || '{}'));
            alternarModal(true);
          } catch (error) {
            return;
          }
        }

        if (evento.target.closest('[data-cerrar-editar-usuario]')) {
          alternarModal(false);
        }
      });

      document.addEventListener('keydown', (evento) => {
        if (evento.key === 'Escape') {
          alternarModal(false);
        }
      });
    })();
  </script>
</body>
</html>
