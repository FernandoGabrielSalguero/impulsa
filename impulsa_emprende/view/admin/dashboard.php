<?php
$usuarioCorreo = $usuarioCorreo ?? '';
$usuarioInicial = $usuarioInicial ?? '?';
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Admin | Impulsa Emprende</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,400,0,0&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../../assets/impulsa_material/css/material.css">
</head>
<body>
  <div class="im-aplicacion" data-menu-colapsado="false">
    <aside class="im-menu-lateral" id="menu-lateral" aria-label="Navegación principal">
      <div class="im-marca">
        <span class="im-marca__isotipo" aria-hidden="true">I</span>
        <div class="im-marca__texto">
          <strong>Impulsa Emprende</strong>
          <span>Administrador</span>
        </div>
      </div>
      <nav class="im-navegacion">
        <a class="im-nav-item activo" href="#dashboard">
          <span class="im-nav-item__icono" aria-hidden="true"></span>
          <span class="im-nav-item__texto">Dashboard</span>
        </a>
      </nav>
    </aside>
    <div class="im-cortina" data-cerrar-menu></div>
    <div class="im-contenedor">
      <header class="im-barra-superior">
        <div class="im-barra-superior__grupo">
          <button class="im-boton-icono material-symbols-rounded" type="button" data-alternar-menu aria-label="Alternar menú">menu</button>
          <div>
            <p class="im-sobrelinea">Impulsa Emprende</p>
            <h1>Dashboard</h1>
          </div>
        </div>
        <div class="im-barra-superior__acciones">
          <span class="im-chip im-chip--avatar">
            <b><?= htmlspecialchars($usuarioInicial, ENT_QUOTES, 'UTF-8') ?></b>
            <?= htmlspecialchars($usuarioCorreo, ENT_QUOTES, 'UTF-8') ?>
          </span>
          <a class="im-boton im-boton--tonal" href="/auth/logout.php">Salir</a>
        </div>
      </header>
      <main class="im-contenido">
        <section class="im-seccion-documento activa" id="dashboard">
          <div class="im-encabezado-seccion">
            <div>
              <p class="im-sobrelinea">Inicio</p>
              <h2>Bienvenido</h2>
              <p>Panel inicial del administrador de Impulsa Emprende.</p>
            </div>
          </div>
        </section>
      </main>
    </div>
  </div>
  <script src="../../../assets/impulsa_material/js/material.js"></script>
</body>
</html>
