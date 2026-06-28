<?php
$usuarioCorreo = $usuarioCorreo ?? '';
$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Usuario';
$ingresos = $ingresos ?? [];
$totalIngresos = count($ingresos);
$adminActiveMenu = 'ingresos';

$h = static fn ($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
$formatearRol = static function (string $rol): string {
    return ucwords(str_replace('_', ' ', $rol));
};
$formatearFecha = static function (string $fecha): string {
    return date('d/m/Y', strtotime($fecha));
};
$formatearHora = static function (string $hora): string {
    return date('H:i', strtotime($hora));
};?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ingresos de usuarios - Admin</title>
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

    .im-nav-item__icono[data-icon]::before {
      content: attr(data-icon);
    }

    @media (max-width: 760px) {
      .im-tabla-tareas__scroll {
        max-height: none;
      }
    }
  </style>
</head>
<body>
  <div class="im-aplicacion" data-menu-colapsado="false">
    <?php require __DIR__ . '/adminMenu.php'; ?>
    <div class="im-cortina" data-cerrar-menu></div>
    <div class="im-contenedor">
      <header class="im-barra-superior">
        <div class="im-barra-superior__grupo">
          <button class="im-boton-icono" type="button" data-alternar-menu aria-label="Menu"></button>
          <div>
            <p class="im-sobrelinea">Impulsa</p>
            <h1>Ingresos de usuarios</h1>
          </div>
        </div>
        <div class="im-barra-superior__acciones">
          <button class="im-boton-icono im-boton-icono--principal im-tooltip" type="button" data-abrir-config-tema aria-label="Configurar temas" data-tooltip="Configurar estilos"></button>
          <button class="im-boton-icono material-symbols-rounded im-tooltip" type="button" data-abrir-perfil aria-label="Mi perfil" data-tooltip="Mi perfil">account_circle</button>
          <a class="im-boton-icono material-symbols-rounded im-tooltip im-accion-salir" href="/auth/logout.php" aria-label="Salir" data-tooltip="Salir">logout</a>
        </div>
      </header>
      <main class="im-contenido">
        <section class="im-seccion-documento activa" id="ingresos">
          <div class="im-encabezado-seccion">
            <div>
              <p class="im-sobrelinea">Administracion</p>
              <h2>Registro de ingresos</h2>
              <p>Control de usuarios que ingresaron al sistema, ordenados por fecha y hora.</p>
            </div>
            <div class="im-barra-superior__acciones">
              <span class="im-chip"><?= number_format($totalIngresos, 0, ',', '.') ?> ingresos</span>
            </div>
          </div>

          <?php if ($totalIngresos > 0): ?>
            <article class="im-tabla-tareas__tarjeta">
              <div class="im-tabla-tareas__cabecera">
                <div>
                  <h3>Ingresos registrados</h3>
                  <p>Ultimos accesos de usuarios al sistema.</p>
                </div>
              </div>
              <div class="im-tabla-tareas__scroll">
                <table class="im-tabla-tareas">
                  <thead>
                    <tr>
                      <th>Usuario</th>
                      <th>Rol</th>
                      <th>Fecha de ingreso</th>
                      <th>Hora de ingreso</th>
                      <th>Registrado el</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($ingresos as $ingreso): ?>
                      <tr>
                        <td class="im-tabla-tareas__nombre">
                          <?= $h($ingreso['nombre_usuario'] ?? '') ?>
                        </td>
                        <td>
                          <span class="im-chip"><?= $h($formatearRol($ingreso['rol'] ?? '')) ?></span>
                        </td>
                        <td><?= $h($formatearFecha($ingreso['fecha_ingreso'] ?? '')) ?></td>
                        <td><?= $h($formatearHora($ingreso['hora_ingreso'] ?? '')) ?></td>
                        <td><?= $h($formatearFecha($ingreso['created_at'] ?? '')) ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </article>
          <?php else: ?>
            <article class="im-tarjeta">
              <h3>No hay ingresos registrados</h3>
              <p>Cuando los usuarios ingresen al sistema, sus accesos apareceran registrados en esta tabla.</p>
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
