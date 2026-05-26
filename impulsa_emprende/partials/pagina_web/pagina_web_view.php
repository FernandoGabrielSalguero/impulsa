<?php
$paginaWebSolicitud = $paginaWebSolicitud ?? [];
$paginaWebCategorias = $paginaWebCategorias ?? [];
$paginaWebSubcategorias = $paginaWebSubcategorias ?? [];
$paginaWebUbicaciones = $paginaWebUbicaciones ?? [];

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

if (!function_exists('paginaWebSelected')) {
    function paginaWebSelected(mixed $actual, mixed $esperado): string
    {
        return (string) $actual === (string) $esperado ? ' selected' : '';
    }
}

$paginaWebTieneEspacioFisico = (int) ($paginaWebSolicitud['espacio_fisico'] ?? 0) === 1;
?>
<form class="im-formulario im-formulario--tres-columnas im-stepper__contenido" action="" method="post" data-pagina-web-form>
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
    <span>Inicio de actividades</span>
    <input type="date" name="fecha_inicio" value="<?= paginaWebCampo($paginaWebSolicitud, 'fecha_inicio') ?>" required>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">calendar_month</i>
  </label>
  <label class="im-campo im-campo-material">
    <span>Nombre de la persona fundadora</span>
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
    <span>Rubro</span>
    <select name="rubro_categoria_id" data-pagina-web-rubro>
      <option value="">Seleccionar</option>
      <?php foreach ($paginaWebCategorias as $categoria): ?>
        <option value="<?= (int) $categoria['id'] ?>" <?= (int) ($paginaWebSolicitud['rubro_categoria_id'] ?? 0) === (int) $categoria['id'] ? 'selected' : '' ?>><?= paginaWebCampo($categoria, 'nombre') ?></option>
      <?php endforeach; ?>
    </select>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">category</i>
  </label>
  <label class="im-campo im-campo-material">
    <span>Subcategoria</span>
    <select name="rubro_subcategoria_id" data-pagina-web-subcategoria <?= empty($paginaWebSolicitud['rubro_categoria_id']) ? 'disabled' : '' ?>>
      <option value="">Selecciona primero un rubro</option>
      <?php foreach ($paginaWebSubcategorias as $subcategoria): ?>
        <option value="<?= (int) $subcategoria['id'] ?>" data-categoria-id="<?= (int) ($subcategoria['categoria_id'] ?? 0) ?>" <?= (int) ($paginaWebSolicitud['rubro_subcategoria_id'] ?? 0) === (int) $subcategoria['id'] ? 'selected' : '' ?>><?= paginaWebCampo($subcategoria, 'nombre') ?></option>
      <?php endforeach; ?>
    </select>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">sell</i>
  </label>
  <label class="im-campo im-campo-material">
    <span>Pais</span>
    <select name="pais" data-pagina-web-pais>
      <option value="">Seleccionar pais</option>
      <?php foreach (array_keys($paginaWebUbicaciones) as $pais): ?>
        <option value="<?= paginaWebCampo(['pais' => $pais], 'pais') ?>"<?= paginaWebSelected($paginaWebSolicitud['pais'] ?? '', $pais) ?>><?= paginaWebCampo(['pais' => $pais], 'pais') ?></option>
      <?php endforeach; ?>
    </select>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">public</i>
  </label>
  <label class="im-campo im-campo-material">
    <span>Provincia</span>
    <select name="provincia" data-pagina-web-provincia <?= empty($paginaWebSolicitud['pais']) ? 'disabled' : '' ?>>
      <option value="">Selecciona primero un pais</option>
    </select>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">map</i>
  </label>
  <label class="im-campo im-campo-material">
    <span>Localidad</span>
    <select name="localidad" data-pagina-web-localidad <?= empty($paginaWebSolicitud['provincia']) ? 'disabled' : '' ?>>
      <option value="">Selecciona primero una provincia</option>
    </select>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">location_city</i>
  </label>

  <label class="im-campo im-campo-material">
    <span>¿Tenés espacio físico?</span>
    <select name="espacio_fisico" data-pagina-web-espacio-fisico required>
      <option value="">Seleccionar</option>
      <option value="1"<?= paginaWebSelected($paginaWebSolicitud['espacio_fisico'] ?? '', '1') ?>>Sí</option>
      <option value="0"<?= paginaWebSelected($paginaWebSolicitud['espacio_fisico'] ?? '', '0') ?>>No</option>
    </select>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">store</i>
  </label>
  <label class="im-campo im-campo-material" data-pagina-web-direccion <?= $paginaWebTieneEspacioFisico ? '' : 'hidden' ?>>
    <span>Calle</span>
    <input type="text" name="calle" value="<?= paginaWebCampo($paginaWebSolicitud, 'calle') ?>" placeholder="Calle" data-im-placeholder <?= $paginaWebTieneEspacioFisico ? 'required' : '' ?>>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">route</i>
  </label>
  <label class="im-campo im-campo-material" data-pagina-web-direccion <?= $paginaWebTieneEspacioFisico ? '' : 'hidden' ?>>
    <span>Numero</span>
    <input type="text" name="numero" value="<?= paginaWebCampo($paginaWebSolicitud, 'numero') ?>" placeholder="Numero" data-im-placeholder <?= $paginaWebTieneEspacioFisico ? 'required' : '' ?>>
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

  <div class="im-formulario__acciones im-campo--ancho">
    <button class="im-boton im-boton--principal" type="submit">Enviar solicitud</button>
  </div>
</form>
<script type="application/json" id="pagina-web-form-data">
<?= json_encode([
    'ubicaciones' => $paginaWebUbicaciones,
    'seleccion' => [
        'pais' => $paginaWebSolicitud['pais'] ?? '',
        'provincia' => $paginaWebSolicitud['provincia'] ?? '',
        'localidad' => $paginaWebSolicitud['localidad'] ?? '',
        'rubro_subcategoria_id' => $paginaWebSolicitud['rubro_subcategoria_id'] ?? '',
    ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>
</script>
<script src="/assets/impulsa_material/js/pagina-web-form.js?v=dependencias-1"></script>
