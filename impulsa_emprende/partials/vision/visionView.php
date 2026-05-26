<?php
$visionDatos = $visionDatos ?? [];
$visionCompleta = $visionCompleta ?? false;

if (!function_exists('visionCampo')) {
    function visionCampo(array $datos, string $campo): string
    {
        return htmlspecialchars((string) ($datos[$campo] ?? ''), ENT_QUOTES, 'UTF-8');
    }
}
?>
<form class="im-formulario im-stepper__contenido" action="" method="post">
  <input type="hidden" name="vision_accion" value="guardar_vision">
  <input type="hidden" name="vision_estructura" value="<?= visionCampo($visionDatos, 'vision_estructura') ?>" data-vision-estructura>
  <div class="im-tarjeta__cabecera">
    <div>
      <span class="im-etiqueta">Defini hacia donde queres llevar tu emprendimiento.</span>
    </div>
  </div>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Conversion futura</span>
    <input type="text" name="conversion_futura" value="<?= visionCampo($visionDatos, 'conversion_futura') ?>" placeholder="En que queres convertir tu emprendimiento" data-im-placeholder>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">rocket_launch</i>
  </label>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Lugar en el mercado</span>
    <input type="text" name="lugar_mercado" value="<?= visionCampo($visionDatos, 'lugar_mercado') ?>" placeholder="Que lugar queres ocupar en tu mercado" data-im-placeholder>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">storefront</i>
  </label>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Impacto generado</span>
    <input type="text" name="impacto_generado" value="<?= visionCampo($visionDatos, 'impacto_generado') ?>" placeholder="Que impacto queres generar" data-im-placeholder>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">emoji_objects</i>
  </label>
  <article class="im-tab-panel activo im-campo--ancho">
    <span class="im-etiqueta">Vision construida</span>
    <p data-vision-preview><?= visionCampo($visionDatos, 'vision_estructura') ?></p>
  </article>
  <div class="im-formulario__acciones">
    <button class="im-boton im-boton--principal" type="submit">Guardar vision</button>
  </div>
</form>
<script>
  (() => {
    const form = document.currentScript.previousElementSibling;
    const get = (name) => {
      const field = form.querySelector(`[name="${name}"]`);
      return field ? field.value.trim() : '';
    };
    const preview = form.querySelector('[data-vision-preview]');
    const hidden = form.querySelector('[data-vision-estructura]');
    const fallback = 'Completa los campos para construir la vision de la empresa.';

    const render = () => {
      const conversion = get('conversion_futura');
      const lugar = get('lugar_mercado');
      const impacto = get('impacto_generado');
      const texto = [conversion, lugar, impacto].some(Boolean)
        ? `Nuestra vision es convertirnos en ${conversion || 'una empresa referente'}, ocupando ${lugar || 'un lugar relevante en el mercado'} y generando ${impacto || 'un impacto positivo y medible'} para clientes, equipo y comunidad.`
        : fallback;
      preview.textContent = texto;
      hidden.value = texto === fallback ? '' : texto;
    };

    form.addEventListener('input', render);
    render();
  })();
</script>
