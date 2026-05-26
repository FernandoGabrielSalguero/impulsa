<?php
$usuarioCorreo = $usuarioCorreo ?? '';
$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Usuario';
$usuariosPorRol = $usuariosPorRol ?? [];
$totalUsuarios = array_sum(array_map(static fn (array $rol): int => (int) ($rol['cantidad'] ?? 0), $usuariosPorRol));
$formatearRol = static function (string $rol): string {
    return ucwords(str_replace('_', ' ', $rol));
};
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Admin</title>
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
  </style>
</head>
<body>
  <div class="im-aplicacion" data-menu-colapsado="false">
    <aside class="im-menu-lateral" id="menu-lateral" aria-label="Navegación principal">
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
        <a class="im-nav-item activo" href="#dashboard">
          <span class="im-nav-item__icono" data-icon="dashboard" aria-hidden="true"></span>
          <span class="im-nav-item__texto">Dashboard</span>
        </a>
        <a class="im-nav-item" href="/impulsa_emprende/controller/admin/adminListUserController.php">
          <span class="im-nav-item__icono" data-icon="groups" aria-hidden="true"></span>
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
            <h1>Dashboard</h1>
          </div>
        </div>
        <div class="im-barra-superior__acciones">
          <button class="im-boton-icono im-boton-icono--principal im-tooltip" type="button" data-abrir-config-tema aria-label="Configurar temas" data-tooltip="Configurar estilos"></button>
          <button class="im-boton-icono material-symbols-rounded im-tooltip" type="button" data-abrir-perfil aria-label="Mi perfil" data-tooltip="Mi perfil">account_circle</button>
          <a class="im-boton-icono material-symbols-rounded im-tooltip im-accion-salir" href="/auth/logout.php" aria-label="Salir" data-tooltip="Salir">logout</a>
        </div>
      </header>
      <main class="im-contenido">
        <section class="im-seccion-documento activa" id="dashboard">
          <div class="im-encabezado-seccion">
            <div>
              <p class="im-sobrelinea">Inicio</p>
              <h2>Resumen de usuarios</h2>
              <p>Usuarios registrados agrupados por rol dentro de Impulsa Emprende.</p>
            </div>
          </div>
          <?php if ($totalUsuarios > 0): ?>
            <div class="im-grilla im-grilla--metricas">
              <article class="im-tarjeta im-tarjeta--metrica">
                <span class="im-etiqueta">Total usuarios</span>
                <strong><?= number_format($totalUsuarios, 0, ',', '.') ?></strong>
                <small>Registrados</small>
              </article>
              <?php foreach ($usuariosPorRol as $rolResumen): ?>
                <?php
                $rol = (string) ($rolResumen['rol'] ?? '');
                $cantidad = (int) ($rolResumen['cantidad'] ?? 0);
                ?>
                <article class="im-tarjeta im-tarjeta--metrica">
                  <span class="im-etiqueta"><?= htmlspecialchars($formatearRol($rol), ENT_QUOTES, 'UTF-8') ?></span>
                  <strong><?= number_format($cantidad, 0, ',', '.') ?></strong>
                  <small><?= htmlspecialchars($rol, ENT_QUOTES, 'UTF-8') ?></small>
                </article>
              <?php endforeach; ?>
            </div>

            <article class="im-tarjeta">
              <div class="im-tarjeta__cabecera">
                <div>
                  <h3>Detalle por rol</h3>
                  <p>Cantidad de usuarios asociados a cada rol registrado.</p>
                </div>
                <span class="im-chip"><?= number_format($totalUsuarios, 0, ',', '.') ?> usuarios</span>
              </div>
              <div class="im-tabla-contenedor">
                <table class="im-tabla">
                  <thead>
                    <tr>
                      <th>Rol</th>
                      <th>Cantidad</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($usuariosPorRol as $rolResumen): ?>
                      <?php
                      $rol = (string) ($rolResumen['rol'] ?? '');
                      $cantidad = (int) ($rolResumen['cantidad'] ?? 0);
                      ?>
                      <tr>
                        <td>
                          <span class="im-chip"><?= htmlspecialchars($formatearRol($rol), ENT_QUOTES, 'UTF-8') ?></span>
                        </td>
                        <td><?= number_format($cantidad, 0, ',', '.') ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </article>
          <?php else: ?>
            <article class="im-tarjeta">
              <h3>No hay usuarios registrados para mostrar.</h3>
              <p>Cuando existan registros en la tabla de usuarios, apareceran agrupados por rol en este panel.</p>
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
