<?php
$usuarioCorreo = $usuarioCorreo ?? '';
$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Usuario';
$usuarios = $usuarios ?? [];
$totalUsuarios = count($usuarios);
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
          <span class="im-nav-item__icono" aria-hidden="true"></span>
          <span class="im-nav-item__texto">Dashboard</span>
        </a>
        <a class="im-nav-item activo" href="/impulsa_emprende/controller/admin/adminListUserController.php">
          <span class="im-nav-item__icono" aria-hidden="true"></span>
          <span class="im-nav-item__texto">Usuarios</span>
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

          <?php if ($totalUsuarios > 0): ?>
            <article class="im-tabla-tareas__tarjeta">
              <div class="im-tabla-tareas__cabecera">
                <div>
                  <h3>Usuarios registrados</h3>
                  <p>Ordenados por fecha de alta mas reciente.</p>
                </div>
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
                    </tr>
                  </thead>
                  <tbody>
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
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
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
  <?php require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilView.php'; ?>
  <script src="../../../assets/impulsa_material/js/material.js"></script>
</body>
</html>
