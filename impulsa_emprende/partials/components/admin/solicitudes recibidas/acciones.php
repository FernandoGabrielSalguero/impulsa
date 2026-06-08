<?php
$solicitudAccion = $solicitudAccion ?? [];
$solicitudTipoAccion = (string) ($solicitudAccion['solicitud_tipo'] ?? 'externa');
$esSolicitudInternaAccion = $solicitudTipoAccion === 'interna';
$solicitudIdAccion = (int) ($solicitudAccion['id'] ?? 0);
$solicitudCorreoAccion = (string) ($solicitudAccion['correo'] ?? $solicitudAccion['usuario_correo'] ?? '');
$solicitudNombreAccion = (string) ($solicitudAccion['nombre'] ?? '');
$solicitudNombreSolicitanteAccion = (string) ($solicitudAccion['solicitante_nombre'] ?? $solicitudNombreAccion);
$solicitudProyectoAccion = (string) ($solicitudAccion['nombre_proyecto'] ?? $solicitudAccion['nombre_emprendimiento'] ?? '');
$clienteUserIdAccion = (int) ($solicitudAccion['cliente_user_id'] ?? $solicitudAccion['user_auth_id'] ?? 0);
$proyectoIdAccion = (int) ($solicitudAccion['proyecto_id'] ?? 0);
$atributoVerDetalleAccion = $esSolicitudInternaAccion ? 'data-ver-solicitud' : 'data-ver-solicitud-impulsa';
?>
<div class="im-menu-tabla" data-im-menu>
  <button class="im-boton-icono im-boton-icono--menu-tabla material-symbols-rounded" type="button" data-im-menu-trigger aria-label="Opciones de tabla" aria-haspopup="menu" aria-expanded="false">more_horiz</button>
  <div class="im-menu-flotante im-menu-tabla__panel" role="menu" data-im-menu-panel>
    <button type="button" role="menuitem" <?= $atributoVerDetalleAccion ?>="<?= $solicitudIdAccion ?>">
      <span class="material-symbols-rounded" aria-hidden="true">visibility</span>
      Ver detalle
    </button>
    <?php if (!$esSolicitudInternaAccion): ?>
      <button type="button" role="menuitem" data-alta-usuario-impulsa="<?= $solicitudIdAccion ?>" data-solicitud-correo="<?= $h($solicitudCorreoAccion) ?>" data-solicitud-nombre="<?= $h($solicitudNombreAccion) ?>" data-cliente-user-id="<?= $clienteUserIdAccion ?>">
        <span class="material-symbols-rounded" aria-hidden="true">person_add</span>
        Alta usuario
      </button>
    <?php endif; ?>
    <button type="button" role="menuitem" data-crear-proyecto="<?= $solicitudIdAccion ?>" data-solicitud-tipo="<?= $h($solicitudTipoAccion) ?>" data-solicitud-proyecto="<?= $h($solicitudProyectoAccion) ?>" data-solicitud-correo="<?= $h($solicitudCorreoAccion) ?>" data-solicitud-nombre="<?= $h($solicitudNombreSolicitanteAccion) ?>" data-cliente-user-id="<?= $clienteUserIdAccion ?>" data-proyecto-id="<?= $proyectoIdAccion ?>">
      <span class="material-symbols-rounded" aria-hidden="true">add_business</span>
      Generar proyecto
    </button>
  </div>
</div>
