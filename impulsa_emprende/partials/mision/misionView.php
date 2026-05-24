<?php
$misionDatos = $misionDatos ?? [];
$misionCompleta = $misionCompleta ?? false;

if (!function_exists('misionCampo')) {
    function misionCampo(array $datos, string $campo): string
    {
        return htmlspecialchars((string) ($datos[$campo] ?? ''), ENT_QUOTES, 'UTF-8');
    }
}
?>
<form class="im-formulario" action="" method="post">
  <input type="hidden" name="mision_accion" value="guardar_mision">
  <div class="im-tarjeta__cabecera">
    <div>
      <h3>Mision</h3>
      <p>Defini a quien ayudas, que problema resolves y como lo haces.</p>
    </div>
    <span class="im-chip <?= $misionCompleta ? 'im-chip--completado' : 'im-chip--pendiente' ?>"><?= $misionCompleta ? 'Completado' : 'Incompleto' ?></span>
  </div>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>A quien ayudo</span>
    <input type="text" name="a_quien_ayudo" value="<?= misionCampo($misionDatos, 'a_quien_ayudo') ?>" placeholder="Ej: emprendedores gastronomicos" data-im-placeholder>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">group</i>
  </label>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Que problema resuelvo</span>
    <textarea name="que_problema_resuelvo" rows="4" placeholder="Describi el problema principal" data-im-placeholder><?= misionCampo($misionDatos, 'que_problema_resuelvo') ?></textarea>
  </label>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Como lo resuelvo</span>
    <textarea name="como_lo_resuelvo" rows="4" placeholder="Explica tu solucion o metodo" data-im-placeholder><?= misionCampo($misionDatos, 'como_lo_resuelvo') ?></textarea>
  </label>
  <div class="im-formulario__acciones">
    <button class="im-boton im-boton--principal" type="submit">Guardar mision</button>
  </div>
</form>
