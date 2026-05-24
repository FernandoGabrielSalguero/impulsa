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
  <div class="im-tarjeta__cabecera">
    <div>
      <h3>Vision</h3>
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
  <div class="im-formulario__acciones">
    <button class="im-boton im-boton--principal" type="submit">Guardar vision</button>
  </div>
</form>
