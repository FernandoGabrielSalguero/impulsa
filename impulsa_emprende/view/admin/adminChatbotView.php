<?php
$usuarioInicial = $usuarioInicial ?? '?';
$usuarioAvatarUrl = $usuarioAvatarUrl ?? null;
$usuarioMarcaNombre = $usuarioMarcaNombre ?? 'Usuario';
$chatbotResumen = $chatbotResumen ?? [];
$chatbots = $chatbots ?? [];
$flashChatbots = $flashChatbots ?? null;
$h = static fn (mixed $value): string => htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
$fecha = static function (?string $value): string {
    if (!$value) {
        return '-';
    }

    return date('d/m/Y H:i', strtotime($value));
};
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Chatbots admin | Impulsa</title>
  <link rel="icon" href="<?= htmlspecialchars(obtenerFaviconHref(), ENT_QUOTES, 'UTF-8'); ?>" type="image/x-icon">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,400,0,0&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../../assets/impulsa_material/css/material.css">
  <style>
    .im-marca__isotipo img { width: 100%; height: 100%; border-radius: inherit; object-fit: cover; }
    .im-accion-salir { color: #ba1a1a; }
    .im-bottom-sheet--perfil { max-width: 860px; max-height: min(760px, calc(100vh - 2rem)); overflow: auto; }
    .im-nav-item__icono[data-icon]::before { content: attr(data-icon); }
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
        <a class="im-nav-item" href="/impulsa_emprende/controller/admin/dashboard.php"><span class="im-nav-item__icono" data-icon="dashboard" aria-hidden="true"></span><span class="im-nav-item__texto">Dashboard</span></a>
        <a class="im-nav-item" href="/impulsa_emprende/controller/admin/adminListUserController.php"><span class="im-nav-item__icono" data-icon="groups" aria-hidden="true"></span><span class="im-nav-item__texto">Usuarios</span></a>
        <a class="im-nav-item" href="/impulsa_emprende/controller/admin/adminSolicitudesPaginaWebSolicitudesController.php"><span class="im-nav-item__icono" data-icon="language" aria-hidden="true"></span><span class="im-nav-item__texto">Solicitudes web</span></a>
        <a class="im-nav-item" href="/impulsa_emprende/controller/admin/adminProyectosController.php"><span class="im-nav-item__icono" data-icon="work" aria-hidden="true"></span><span class="im-nav-item__texto">Proyectos</span></a>
        <a class="im-nav-item" href="/impulsa_emprende/controller/admin/adminMarketingController.php"><span class="im-nav-item__icono" data-icon="campaign" aria-hidden="true"></span><span class="im-nav-item__texto">Marketing</span></a>
        <a class="im-nav-item" href="/impulsa_emprende/controller/admin/adminAPIconfigurationController.php"><span class="im-nav-item__icono" data-icon="key" aria-hidden="true"></span><span class="im-nav-item__texto">Integraciones API</span></a>
        <a class="im-nav-item" href="/impulsa_emprende/controller/admin/adminCorreosEnviadosController.php"><span class="im-nav-item__icono" data-icon="mail" aria-hidden="true"></span><span class="im-nav-item__texto">Correos enviados</span></a>
        <a class="im-nav-item activo" href="/impulsa_emprende/controller/admin/adminChatbotController.php"><span class="im-nav-item__icono" data-icon="forum" aria-hidden="true"></span><span class="im-nav-item__texto">Chatbots</span></a>
      </nav>
    </aside>
    <div class="im-cortina" data-cerrar-menu></div>
    <div class="im-contenedor">
      <header class="im-barra-superior">
        <div class="im-barra-superior__grupo">
          <button class="im-boton-icono" type="button" data-alternar-menu aria-label="Menu"></button>
          <div>
            <p class="im-sobrelinea">Impulsa</p>
            <h1>Chatbots</h1>
          </div>
        </div>
        <div class="im-barra-superior__acciones">
          <button class="im-boton-icono im-boton-icono--principal im-tooltip" type="button" data-abrir-config-tema aria-label="Configurar temas" data-tooltip="Configurar estilos"></button>
          <button class="im-boton-icono material-symbols-rounded im-tooltip" type="button" data-abrir-perfil aria-label="Mi perfil" data-tooltip="Mi perfil">account_circle</button>
          <a class="im-boton-icono material-symbols-rounded im-tooltip im-accion-salir" href="/auth/logout.php" aria-label="Salir" data-tooltip="Salir">logout</a>
        </div>
      </header>
      <main class="im-contenido">
        <section class="im-seccion-documento activa" id="chatbots-admin" data-panel="chatbots-admin">
          <div class="im-encabezado-seccion">
            <div>
              <p class="im-sobrelinea">Administracion</p>
              <h2>Chatbots autogestionables</h2>
              <p>Control operativo, estado y metricas globales del widget de preguntas frecuentes.</p>
            </div>
          </div>

          <?php if (is_array($flashChatbots) && trim((string) ($flashChatbots['mensaje'] ?? '')) !== ''): ?>
            <div class="im-alerta <?= ($flashChatbots['estado'] ?? '') === 'error' ? 'im-alerta--info' : 'im-alerta--exito' ?>">
              <?= $h($flashChatbots['mensaje'] ?? '') ?>
            </div>
          <?php endif; ?>

          <div class="im-grilla im-grilla--metricas">
            <article class="im-tarjeta im-tarjeta--metrica"><span class="im-etiqueta">Chatbots</span><strong><?= (int) ($chatbotResumen['total_chatbots'] ?? 0) ?></strong><small>Registrados</small></article>
            <article class="im-tarjeta im-tarjeta--metrica"><span class="im-etiqueta">Activos</span><strong><?= (int) ($chatbotResumen['total_activos'] ?? 0) ?></strong><small>Disponibles</small></article>
            <article class="im-tarjeta im-tarjeta--metrica"><span class="im-etiqueta">Bloqueados</span><strong><?= (int) ($chatbotResumen['total_bloqueados'] ?? 0) ?></strong><small>Por admin</small></article>
            <article class="im-tarjeta im-tarjeta--metrica"><span class="im-etiqueta">Eventos</span><strong><?= (int) ($chatbotResumen['total_eventos'] ?? 0) ?></strong><small>Totales</small></article>
          </div>

          <article class="im-tabla-tareas__tarjeta">
            <div class="im-tabla-tareas__cabecera">
              <div>
                <h3>Listado</h3>
                <p>Vista consolidada por chatbot, proyecto e interacciones registradas.</p>
              </div>
            </div>
            <div class="im-tabla-tareas__scroll">
              <table class="im-tabla-tareas">
                <thead>
                  <tr>
                    <th>Chatbot</th>
                    <th>Proyecto</th>
                    <th>Dominio</th>
                    <th>Estado</th>
                    <th>Admin</th>
                    <th>widget_loaded</th>
                    <th>bubble_opened</th>
                    <th>question_viewed</th>
                    <th>option_clicked</th>
                    <th>whatsapp_clicked</th>
                    <th>Creado</th>
                    <th>Ultima actividad</th>
                    <th class="im-tabla-tareas__acciones">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($chatbots as $chatbot): ?>
                    <tr>
                      <td class="im-tabla-tareas__nombre"><?= $h($chatbot['name'] ?? '') ?></td>
                      <td><?= $h($chatbot['project_name'] ?? '') ?></td>
                      <td><code><?= $h($chatbot['allowed_domain'] ?? '') ?></code></td>
                      <td><span class="im-chip <?= ($chatbot['status'] ?? '') === 'active' && ($chatbot['integration_status'] ?? '') === 'active' && (int) ($chatbot['disabled_by_admin'] ?? 0) === 0 ? 'im-chip--activo' : 'im-chip--alerta' ?>"><?= ($chatbot['status'] ?? '') === 'active' ? 'Activo' : 'Inactivo' ?></span></td>
                      <td><span class="im-chip <?= (int) ($chatbot['disabled_by_admin'] ?? 0) === 1 ? 'im-chip--alerta' : 'im-chip--completado' ?>"><?= (int) ($chatbot['disabled_by_admin'] ?? 0) === 1 ? 'Bloqueado' : 'Libre' ?></span></td>
                      <td><?= (int) ($chatbot['total_widget_loaded'] ?? 0) ?></td>
                      <td><?= (int) ($chatbot['total_bubble_opened'] ?? 0) ?></td>
                      <td><?= (int) ($chatbot['total_question_viewed'] ?? 0) ?></td>
                      <td><?= (int) ($chatbot['total_option_clicked'] ?? 0) ?></td>
                      <td><?= (int) ($chatbot['total_whatsapp_clicked'] ?? 0) ?></td>
                      <td><?= $h($fecha($chatbot['created_at'] ?? null)) ?></td>
                      <td><?= $h($fecha($chatbot['last_activity'] ?? null)) ?></td>
                      <td class="im-tabla-tareas__acciones">
                        <form method="post">
                          <input type="hidden" name="chatbot_id" value="<?= (int) ($chatbot['id'] ?? 0) ?>">
                          <input type="hidden" name="admin_chatbot_action" value="<?= (int) ($chatbot['disabled_by_admin'] ?? 0) === 1 ? 'enable' : 'disable' ?>">
                          <button class="im-boton im-boton--texto" type="submit"><?= (int) ($chatbot['disabled_by_admin'] ?? 0) === 1 ? 'Reactivar' : 'Desactivar' ?></button>
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                  <?php if ($chatbots === []): ?>
                    <tr><td colspan="13">Todavia no hay chatbots creados.</td></tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </article>
        </section>
      </main>
    </div>
  </div>

  <?php require __DIR__ . '/../../partials/bottom_sheet_perfil/perfilView.php'; ?>
  <script src="../../../assets/impulsa_material/js/material.js"></script>
</body>
</html>
