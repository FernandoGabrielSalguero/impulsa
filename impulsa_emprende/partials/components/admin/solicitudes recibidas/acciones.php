<?php
$solicitudAccion = $solicitudAccion ?? [];
$solicitudIdAccion = (int) ($solicitudAccion['id'] ?? 0);
$solicitudCorreoAccion = (string) ($solicitudAccion['correo'] ?? '');
$solicitudNombreAccion = (string) ($solicitudAccion['nombre'] ?? '');
$solicitudProyectoAccion = (string) ($solicitudAccion['nombre_proyecto'] ?? '');
$clienteUserIdAccion = (int) ($solicitudAccion['cliente_user_id'] ?? 0);
$proyectoIdAccion = (int) ($solicitudAccion['proyecto_id'] ?? 0);
?>
<div class="im-tabla-tareas__menu" data-im-menu>
  <button class="im-boton-icono im-boton-icono--tabla-opciones material-symbols-rounded" type="button" data-im-menu-trigger aria-label="Opciones" aria-haspopup="menu" aria-expanded="false">more_horiz</button>
  <div class="im-menu-flotante im-tabla-tareas__menu-panel" role="menu" data-im-menu-panel>
    <button type="button" role="menuitem" data-ver-solicitud-impulsa="<?= $solicitudIdAccion ?>">
      <span class="material-symbols-rounded" aria-hidden="true">visibility</span>
      Ver detalle
    </button>
    <button type="button" role="menuitem" data-alta-usuario-impulsa="<?= $solicitudIdAccion ?>" data-solicitud-correo="<?= $h($solicitudCorreoAccion) ?>" data-solicitud-nombre="<?= $h($solicitudNombreAccion) ?>" data-cliente-user-id="<?= $clienteUserIdAccion ?>">
      <span class="material-symbols-rounded" aria-hidden="true">person_add</span>
      Alta usuario
    </button>
    <button type="button" role="menuitem" data-crear-proyecto-impulsa="<?= $solicitudIdAccion ?>" data-solicitud-proyecto="<?= $h($solicitudProyectoAccion) ?>" data-solicitud-correo="<?= $h($solicitudCorreoAccion) ?>" data-cliente-user-id="<?= $clienteUserIdAccion ?>" data-proyecto-id="<?= $proyectoIdAccion ?>">
      <span class="material-symbols-rounded" aria-hidden="true">add_business</span>
      Crear proyecto
    </button>
  </div>
</div>
