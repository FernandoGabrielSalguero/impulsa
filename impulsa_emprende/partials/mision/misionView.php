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
<form class="im-formulario im-stepper__contenido" action="" method="post">
  <input type="hidden" name="mision_accion" value="guardar_mision">
  <input type="hidden" name="mision_estructura" value="<?= misionCampo($misionDatos, 'mision_estructura') ?>" data-mision-estructura>
  <div class="im-tarjeta__cabecera">
    <div>
      <h3>Mision</h3>
      <span class="im-etiqueta">Defini a quien ayudas, que problema resolves y como lo haces.</span>
    </div>
  </div>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>A quien ayudo</span>
    <input type="text" name="a_quien_ayudo" value="<?= misionCampo($misionDatos, 'a_quien_ayudo') ?>" placeholder="Ej: emprendedores gastronomicos" data-im-placeholder>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">group</i>
  </label>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Que problema resuelvo</span>
    <input type="text" name="que_problema_resuelvo" value="<?= misionCampo($misionDatos, 'que_problema_resuelvo') ?>" placeholder="Describi el problema principal" data-im-placeholder>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">problem</i>
  </label>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Como lo resuelvo</span>
    <input type="text" name="como_lo_resuelvo" value="<?= misionCampo($misionDatos, 'como_lo_resuelvo') ?>" placeholder="Explica tu solucion o metodo" data-im-placeholder>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">build_circle</i>
  </label>
  <article class="im-tab-panel activo im-campo--ancho">
    <span class="im-etiqueta">Mision construida</span>
    <p data-mision-preview><?= misionCampo($misionDatos, 'mision_estructura') ?></p>
  </article>
  <div class="im-formulario__acciones">
    <button class="im-boton im-boton--principal" type="submit">Guardar mision</button>
  </div>
</form>
<script>
  (() => {
    const form = document.currentScript.previousElementSibling;
    const get = (name) => {
      const field = form.querySelector(`[name="${name}"]`);
      return field ? field.value.trim() : '';
    };
    const preview = form.querySelector('[data-mision-preview]');
    const hidden = form.querySelector('[data-mision-estructura]');
    const fallback = 'Completa los campos para construir la mision de la empresa.';

    const render = () => {
      const aQuien = get('a_quien_ayudo');
      const problema = get('que_problema_resuelvo');
      const como = get('como_lo_resuelvo');
      const texto = [aQuien, problema, como].some(Boolean)
        ? `Nuestra mision es ayudar a ${aQuien || 'nuestros clientes'} a resolver ${problema || 'sus principales desafios'} mediante ${como || 'soluciones claras, utiles y sostenibles'}, creando valor real en cada etapa de su crecimiento.`
        : fallback;
      preview.textContent = texto;
      hidden.value = texto === fallback ? '' : texto;
    };

    form.addEventListener('input', render);
    render();
  })();
</script>
