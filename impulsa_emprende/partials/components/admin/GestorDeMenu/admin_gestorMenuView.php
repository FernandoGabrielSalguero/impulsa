<?php

declare(strict_types=1);

require_once __DIR__ . '/admin_gestorMenuModel.php';

$h = isset($h) && is_callable($h)
    ? $h
    : static fn(mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');

$adminMenuCatalogByRole = adminGestorMenuCatalogoPorRol();
$adminMenuRoleLabels = adminGestorMenuEtiquetasRol();
?>
<div class="im-modal-cortina" data-cerrar-gestor-menu></div>
<section class="im-dialog im-usuario-menu-modal" role="dialog" aria-modal="true" aria-labelledby="gestor-menu-titulo" aria-hidden="true" data-modal-gestor-menu>
    <header class="im-dialog__cabecera">
        <div>
            <p class="im-sobrelinea">Personalizacion</p>
            <h3 id="gestor-menu-titulo">Menú de usuario</h3>
        </div>
        <button class="im-boton-icono" type="button" data-cerrar-gestor-menu aria-label="Cerrar dialog"></button>
    </header>
    <form method="post" action="/impulsa_emprende/controller/admin/adminListUserController.php">
        <input type="hidden" name="accion" value="guardar_menu_usuario">
        <input type="hidden" name="usuario_id" value="" data-gestor-menu-usuario-id>
        <div class="im-dialog__contenido">
            <div class="im-usuario-menu-resumen">
                <p><strong data-gestor-menu-usuario-nombre>Usuario</strong></p>
                <p data-gestor-menu-usuario-rol>Rol</p>
            </div>

            <section class="im-usuario-menu-seccion">
                <div>
                    <h4>Pagina de inicio</h4>
                    <p>Debe estar incluida entre las opciones visibles para el usuario.</p>
                </div>
                <label class="im-campo im-campo-material" data-im-campo="generico">
                    <span>Seccion inicial</span>
                    <select name="pagina_inicio" data-gestor-menu-pagina-inicio required></select>
                    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">home</i>
                </label>
            </section>

            <section class="im-usuario-menu-seccion">
                <div>
                    <h4>Opciones visibles</h4>
                    <p>Dashboard siempre permanece activo. El resto se puede personalizar segun el rol.</p>
                </div>
                <div class="im-usuario-menu-opciones" data-gestor-menu-opciones></div>
            </section>
        </div>
        <footer class="im-dialog__acciones">
            <button class="im-boton im-boton--texto" type="button" data-cerrar-gestor-menu>Cancelar</button>
            <button class="im-boton im-boton--principal" type="submit">Guardar menú</button>
        </footer>
    </form>
</section>
<script>
    window.adminMenuCatalogByRole = <?= json_encode($adminMenuCatalogByRole, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    window.adminMenuRoleLabels = <?= json_encode($adminMenuRoleLabels, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
</script>