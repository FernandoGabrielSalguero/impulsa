<?php
$usuarioCorreo = $usuarioCorreo ?? '';
$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Usuario';
$ingresos = $ingresos ?? [];
$totalIngresos = count($ingresos);
$adminActiveMenu = 'ingresos';

$h = static fn ($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');

$filtroNombre = trim((string) ($_GET['nombre'] ?? ''));
$filtroRol = trim((string) ($_GET['rol'] ?? ''));
$filtroFecha = trim((string) ($_GET['fecha'] ?? ''));

$rolesDisponibles = [
    'impulsa_administrador',
    'impulsa_colaborador',
    'impulsa_emprendedor',
    'impulsa_usuario',
    'impulsa_marketing',
    'impulsa_cliente',
];

$formatearRol = static function (string $rol): string {
    return ucwords(str_replace('_', ' ', $rol));
};
$formatearFecha = static function (string $fecha): string {
    return date('d/m/Y', strtotime($fecha));
};
$formatearHora = static function (string $hora): string {
    $parts = explode(':', $hora);
    return (count($parts) >= 2) ? $parts[0] . ':' . $parts[1] : $hora;
};?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ingresos de usuarios - Admin</title>
  <link rel="icon" href="<?= htmlspecialchars(obtenerFaviconHref(), ENT_QUOTES, 'UTF-8'); ?>" type="image/x-icon">
  <?= renderImpulsaMaterialFonts() ?>
  <link rel="stylesheet" href="<?= htmlspecialchars(obtenerImpulsaMaterialCssHref(), ENT_QUOTES, 'UTF-8'); ?>">
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

    .im-filtros-ajax .im-campo {
      margin-bottom: 0;
    }

    @media (max-width: 760px) {
      .im-tabla-tareas__scroll {
        max-height: none;
      }

      .im-filtros-ajax {
        flex-direction: column;
      }

      .im-filtros-ajax .im-campo {
        min-width: 100% !important;
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
              <span class="im-chip" id="ingresos-total"><?= number_format($totalIngresos, 0, ',', '.') ?> ingresos</span>
            </div>
          </div>

          <div id="ingresos-empty" <?= $totalIngresos > 0 ? 'style="display:none"' : '' ?>>
            <article class="im-tarjeta">
              <h3>No hay ingresos registrados</h3>
              <p>Cuando los usuarios ingresen al sistema, sus accesos apareceran registrados en esta tabla.</p>
            </article>
          </div>

          <div id="ingresos-table" <?= $totalIngresos === 0 ? 'style="display:none"' : '' ?>>
            <article class="im-tabla-tareas__tarjeta">
              <div class="im-tabla-tareas__cabecera">
                <div>
                  <h3>Ingresos registrados</h3>
                  <p>Ultimos accesos de usuarios al sistema.</p>
                </div>
              </div>

              <div class="im-filtros-ajax" style="display:flex;flex-wrap:wrap;gap:.75rem;padding:0 1.25rem 1rem;align-items:end;">
                <label class="im-campo im-campo-material" style="min-width:180px;flex:1;">
                  <span>Nombre de usuario</span>
                  <input type="search" id="filtro-nombre" name="nombre" value="<?= $h($filtroNombre) ?>" placeholder="Buscar por nombre..." autocomplete="off">
                  <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">search</i>
                </label>
                <label class="im-campo im-campo-material" style="min-width:160px;flex:0 1 auto;">
                  <span>Rol</span>
                  <select id="filtro-rol" name="rol">
                    <option value="">Todos los roles</option>
                    <?php foreach ($rolesDisponibles as $rolOption): ?>
                      <option value="<?= $h($rolOption) ?>" <?= $filtroRol === $rolOption ? 'selected' : '' ?>>
                        <?= $h($formatearRol($rolOption)) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">admin_panel_settings</i>
                </label>
                <label class="im-campo im-campo-material" style="min-width:160px;flex:0 1 auto;">
                  <span>Fecha</span>
                  <input type="date" id="filtro-fecha" name="fecha" value="<?= $h($filtroFecha) ?>">
                  <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">calendar_month</i>
                </label>
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
                  <tbody id="ingresos-tbody">
                    <?php foreach ($ingresos as $ingreso): ?>
                      <tr>
                        <td class="im-tabla-tareas__nombre"><?= $h($ingreso['nombre_usuario'] ?? '') ?></td>
                        <td><span class="im-chip"><?= $h($formatearRol($ingreso['rol'] ?? '')) ?></span></td>
                        <td><?= $h($formatearFecha($ingreso['fecha_ingreso'] ?? '')) ?></td>
                        <td><?= $h($formatearHora($ingreso['hora_ingreso'] ?? '')) ?></td>
                        <td><?= $h($formatearFecha($ingreso['created_at'] ?? '')) ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </article>
          </div>
        </section>
      </main>
    </div>
  </div>

  <?php require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilView.php'; ?>
  <script src="<?= htmlspecialchars(obtenerImpulsaMaterialJsSrc(), ENT_QUOTES, 'UTF-8'); ?>"></script>
  <script>
    (function() {
      var nombreInput = document.getElementById('filtro-nombre');
      var rolSelect = document.getElementById('filtro-rol');
      var fechaInput = document.getElementById('filtro-fecha');
      var tbody = document.getElementById('ingresos-tbody');
      var totalSpan = document.getElementById('ingresos-total');
      var tableContainer = document.getElementById('ingresos-table');
      var emptyContainer = document.getElementById('ingresos-empty');

      if (!nombreInput || !rolSelect || !fechaInput || !tbody || !totalSpan || !tableContainer || !emptyContainer) return;

      var debounceTimer = null;

      function filtrar() {
        var params = new URLSearchParams();
        params.set('ajax', 'ingresos');

        var nombre = nombreInput.value.trim();
        if (nombre !== '') params.set('nombre', nombre);

        var rol = rolSelect.value;
        if (rol !== '') params.set('rol', rol);

        var fecha = fechaInput.value;
        if (fecha !== '') params.set('fecha', fecha);

        fetch('/impulsa_emprende/controller/admin/adminIngresosController.php?' + params.toString())
          .then(function(res) { return res.json(); })
          .then(function(data) {
            if (!data.ok) return;

            totalSpan.textContent = Number(data.total).toLocaleString('es-AR') + ' ingresos';

            if (data.total === 0) {
              tableContainer.style.display = 'none';
              emptyContainer.style.display = '';
            } else {
              tableContainer.style.display = '';
              emptyContainer.style.display = 'none';

              var html = '';
              for (var i = 0; i < data.ingresos.length; i++) {
                var ing = data.ingresos[i];
                html += '<tr>' +
                  '<td class="im-tabla-tareas__nombre">' + ing.nombre_usuario + '</td>' +
                  '<td><span class="im-chip">' + ing.rol + '</span></td>' +
                  '<td>' + ing.fecha_ingreso + '</td>' +
                  '<td>' + ing.hora_ingreso + '</td>' +
                  '<td>' + ing.created_at + '</td>' +
                '</tr>';
              }
              tbody.innerHTML = html;
            }
          })
          .catch(function() {});
      }

      function onChange() {
        if (debounceTimer) clearTimeout(debounceTimer);
        debounceTimer = setTimeout(filtrar, 300);
      }

      nombreInput.addEventListener('input', onChange);
      rolSelect.addEventListener('change', onChange);
      fechaInput.addEventListener('change', onChange);
    })();
  </script>
</body>
</html>
