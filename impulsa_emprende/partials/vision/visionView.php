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
<form class="im-formulario" action="" method="post">
  <input type="hidden" name="vision_accion" value="guardar_vision">
  <div class="im-tarjeta__cabecera">
    <div>
      <h3>Vision</h3>
      <p>Defini hacia donde queres llevar tu emprendimiento.</p>
    </div>
    <span class="im-chip <?= $visionCompleta ? 'im-chip--completado' : 'im-chip--pendiente' ?>"><?= $visionCompleta ? 'Completado' : 'Incompleto' ?></span>
  </div>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Conversion futura</span>
    <textarea name="conversion_futura" rows="4" placeholder="En que queres convertir tu emprendimiento" data-im-placeholder><?= visionCampo($visionDatos, 'conversion_futura') ?></textarea>
  </label>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Lugar en el mercado</span>
    <textarea name="lugar_mercado" rows="4" placeholder="Que lugar queres ocupar en tu mercado" data-im-placeholder><?= visionCampo($visionDatos, 'lugar_mercado') ?></textarea>
  </label>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Impacto generado</span>
    <textarea name="impacto_generado" rows="4" placeholder="Que impacto queres generar" data-im-placeholder><?= visionCampo($visionDatos, 'impacto_generado') ?></textarea>
  </label>
  <div class="im-formulario__acciones">
    <button class="im-boton im-boton--principal" type="submit">Guardar vision</button>
  </div>
</form>
