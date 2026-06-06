<?php
$usuarioCorreo = $usuarioCorreo ?? '';
$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Usuario';
$usuarios = $usuarios ?? [];
$estado = $estado ?? '';
$totalUsuarios = count($usuarios);
$h = static fn ($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
$mensajesEstado = [
    'usuario_eliminado' => ['tipo' => 'exito', 'texto' => 'Usuario eliminado correctamente.'],
    'usuario_error_eliminar' => ['tipo' => 'error', 'texto' => 'No se pudo eliminar el usuario. Revisa las relaciones asociadas e intenta nuevamente.'],
    'usuario_id_invalido' => ['tipo' => 'error', 'texto' => 'El usuario seleccionado no es valido.'],
    'usuario_no_autodelete' => ['tipo' => 'error', 'texto' => 'No podes eliminar el usuario con el que tenes la sesion activa.'],
];
$mensajeEstado = $mensajesEstado[$estado] ?? null;
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

    .im-usuario-accion-eliminar > span::before {
      content: "delete";
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
            <span class="im-chip"><?= number_format($totalUsuarios, 0, ',', '.') ?> usuarios</span>
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

  <?php require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilView.php'; ?>
  <script src="../../../assets/impulsa_material/js/material.js"></script>
  <script>
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
  </script>
</body>
</html>
