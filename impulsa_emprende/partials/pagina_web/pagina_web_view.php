<?php
$paginaWebSolicitud = $paginaWebSolicitud ?? [];
$paginaWebCategorias = $paginaWebCategorias ?? [];
$paginaWebSubcategorias = $paginaWebSubcategorias ?? [];

if (!function_exists('paginaWebCampo')) {
    function paginaWebCampo(array $datos, string $campo): string
    {
        return htmlspecialchars((string) ($datos[$campo] ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('paginaWebChecked')) {
    function paginaWebChecked(array $datos, string $campo): string
    {
        return (int) ($datos[$campo] ?? 0) === 1 ? ' checked' : '';
    }
}
?>
<form class="im-formulario im-formulario--tres-columnas im-stepper__contenido" action="" method="post">
  <input type="hidden" name="pagina_web_accion" value="guardar_pagina_web">

  <div class="im-tarjeta__cabecera im-campo--ancho">
    <div>
      <span class="im-etiqueta">Datos principales del emprendimiento</span>
    </div>
  </div>

  <label class="im-campo im-campo-material">
    <span>Nombre del emprendimiento</span>
    <input type="text" name="nombre_emprendimiento" value="<?= paginaWebCampo($paginaWebSolicitud, 'nombre_emprendimiento') ?>" placeholder="Nombre comercial" data-im-placeholder required>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">storefront</i>
  </label>
  <label class="im-campo im-campo-material">
    <span>Fecha de inicio</span>
    <input type="date" name="fecha_inicio" value="<?= paginaWebCampo($paginaWebSolicitud, 'fecha_inicio') ?>" required>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">calendar_month</i>
  </label>
  <label class="im-campo im-campo-material">
    <span>Nombre del fundador</span>
    <input type="text" name="nombre_fundador" value="<?= paginaWebCampo($paginaWebSolicitud, 'nombre_fundador') ?>" placeholder="Nombre y apellido" data-im-placeholder required>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">person</i>
  </label>
  <label class="im-campo im-campo-material">
    <span>Cantidad de colaboradores</span>
    <input type="number" name="cantidad_colaboradores" min="1" step="1" value="<?= paginaWebCampo($paginaWebSolicitud, 'cantidad_colaboradores') ?: '1' ?>" placeholder="1" data-im-placeholder required>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">groups</i>
  </label>
  <label class="im-campo im-campo-material">
    <span>Telefono de contacto</span>
    <input type="tel" name="telefono_contacto" value="<?= paginaWebCampo($paginaWebSolicitud, 'telefono_contacto') ?>" placeholder="+54 11 1234 5678" data-im-placeholder required>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">phone_iphone</i>
  </label>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Descripcion</span>
    <textarea name="descripcion" rows="4" placeholder="Contanos que hace tu emprendimiento, que ofrece y que necesita comunicar la pagina." data-im-placeholder required><?= paginaWebCampo($paginaWebSolicitud, 'descripcion') ?></textarea>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">description</i>
  </label>

  <div class="im-formulario__separador im-campo--ancho">Rubro y ubicacion</div>

  <label class="im-campo im-campo-material">
    <span>Rubro categoria</span>
    <select name="rubro_categoria_id">
      <option value="">Seleccionar</option>
      <?php foreach ($paginaWebCategorias as $categoria): ?>
        <option value="<?= (int) $categoria['id'] ?>" <?= (int) ($paginaWebSolicitud['rubro_categoria_id'] ?? 0) === (int) $categoria['id'] ? 'selected' : '' ?>><?= paginaWebCampo($categoria, 'nombre') ?></option>
      <?php endforeach; ?>
    </select>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">category</i>
  </label>
  <label class="im-campo im-campo-material">
    <span>Rubro subcategoria</span>
    <select name="rubro_subcategoria_id">
      <option value="">Seleccionar</option>
      <?php foreach ($paginaWebSubcategorias as $subcategoria): ?>
        <option value="<?= (int) $subcategoria['id'] ?>" <?= (int) ($paginaWebSolicitud['rubro_subcategoria_id'] ?? 0) === (int) $subcategoria['id'] ? 'selected' : '' ?>><?= paginaWebCampo($subcategoria, 'nombre') ?></option>
      <?php endforeach; ?>
    </select>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">sell</i>
  </label>
  <label class="im-campo im-campo-material">
    <span>Pais</span>
    <input type="text" name="pais" value="<?= paginaWebCampo($paginaWebSolicitud, 'pais') ?>" placeholder="Argentina" data-im-placeholder>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">public</i>
  </label>
  <label class="im-campo im-campo-material">
    <span>Provincia</span>
    <input type="text" name="provincia" value="<?= paginaWebCampo($paginaWebSolicitud, 'provincia') ?>" placeholder="Provincia" data-im-placeholder>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">map</i>
  </label>
  <label class="im-campo im-campo-material">
    <span>Localidad</span>
    <input type="text" name="localidad" value="<?= paginaWebCampo($paginaWebSolicitud, 'localidad') ?>" placeholder="Localidad" data-im-placeholder>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">location_city</i>
  </label>
  <label class="im-campo im-campo-material">
    <span>Calle</span>
    <input type="text" name="calle" value="<?= paginaWebCampo($paginaWebSolicitud, 'calle') ?>" placeholder="Calle" data-im-placeholder>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">route</i>
  </label>
  <label class="im-campo im-campo-material">
    <span>Numero</span>
    <input type="text" name="numero" value="<?= paginaWebCampo($paginaWebSolicitud, 'numero') ?>" placeholder="Numero" data-im-placeholder>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">pin</i>
  </label>

  <div class="im-formulario__separador im-campo--ancho">Condiciones actuales</div>

  <label class="im-checkbox">
    <input type="checkbox" name="dominio_registrado" value="1"<?= paginaWebChecked($paginaWebSolicitud, 'dominio_registrado') ?>>
    <span>Tengo dominio registrado</span>
  </label>
  <label class="im-checkbox">
    <input type="checkbox" name="hosting_propio" value="1"<?= paginaWebChecked($paginaWebSolicitud, 'hosting_propio') ?>>
    <span>Tengo hosting propio</span>
  </label>
  <label class="im-checkbox">
    <input type="checkbox" name="vende_productos" value="1"<?= paginaWebChecked($paginaWebSolicitud, 'vende_productos') ?>>
    <span>Vendo productos</span>
  </label>
  <label class="im-checkbox">
    <input type="checkbox" name="vende_servicios" value="1"<?= paginaWebChecked($paginaWebSolicitud, 'vende_servicios') ?>>
    <span>Vendo servicios</span>
  </label>
  <label class="im-checkbox">
    <input type="checkbox" name="ya_factura" value="1"<?= paginaWebChecked($paginaWebSolicitud, 'ya_factura') ?>>
    <span>Ya facturo</span>
  </label>
  <label class="im-checkbox">
    <input type="checkbox" name="espacio_fisico" value="1"<?= paginaWebChecked($paginaWebSolicitud, 'espacio_fisico') ?>>
    <span>Tengo espacio fisico</span>
  </label>

  <div class="im-formulario__acciones im-campo--ancho">
    <button class="im-boton im-boton--principal" type="submit">Enviar solicitud</button>
  </div>
</form>
