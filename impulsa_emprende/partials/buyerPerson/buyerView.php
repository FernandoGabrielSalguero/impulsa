<?php
$buyerDatos = $buyerDatos ?? [];
$buyerCompleto = $buyerCompleto ?? false;

if (!function_exists('buyerCampo')) {
    function buyerCampo(array $datos, string $campo): string
    {
        return htmlspecialchars((string) ($datos[$campo] ?? ''), ENT_QUOTES, 'UTF-8');
    }
}
?>
<form class="im-formulario im-stepper__contenido" action="" method="post">
  <input type="hidden" name="buyer_accion" value="guardar_buyer">
  <input type="hidden" name="buyer_persona_estructura" value="<?= buyerCampo($buyerDatos, 'buyer_persona_estructura') ?>" data-buyer-estructura>
  <div class="im-tarjeta__cabecera">
    <div>
      <h3>Buyer Persona</h3>
      <span class="im-etiqueta">Construi el perfil de la persona que tiene mas probabilidad de comprarte.</span>
    </div>
  </div>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Cliente ideal</span>
    <input type="text" name="cliente_ideal" value="<?= buyerCampo($buyerDatos, 'cliente_ideal') ?>" placeholder="Quien es tu cliente ideal" data-im-placeholder>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">groups</i>
  </label>
  <label class="im-campo im-campo-material">
    <span>Edad y etapa de vida</span>
    <input type="text" name="edad_etapa_vida" value="<?= buyerCampo($buyerDatos, 'edad_etapa_vida') ?>" placeholder="Ej: 28 a 40, emprendiendo" data-im-placeholder>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">person</i>
  </label>
  <label class="im-campo im-campo-material">
    <span>Ocupacion y realidad diaria</span>
    <input type="text" name="ocupacion_realidad_diaria" value="<?= buyerCampo($buyerDatos, 'ocupacion_realidad_diaria') ?>" placeholder="Trabajo, rutina, contexto" data-im-placeholder>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">work</i>
  </label>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Problema o necesidad</span>
    <input type="text" name="problema_necesidad" value="<?= buyerCampo($buyerDatos, 'problema_necesidad') ?>" placeholder="Que necesita resolver" data-im-placeholder>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">help</i>
  </label>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Preocupacion o frustracion</span>
    <input type="text" name="preocupacion_frustracion" value="<?= buyerCampo($buyerDatos, 'preocupacion_frustracion') ?>" placeholder="Que le preocupa o frustra" data-im-placeholder>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">sentiment_dissatisfied</i>
  </label>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Objetivo de mejora</span>
    <input type="text" name="objetivo_mejora" value="<?= buyerCampo($buyerDatos, 'objetivo_mejora') ?>" placeholder="Que quiere mejorar" data-im-placeholder>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">trending_up</i>
  </label>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Motivacion de busqueda</span>
    <input type="text" name="motivacion_busqueda" value="<?= buyerCampo($buyerDatos, 'motivacion_busqueda') ?>" placeholder="Que lo motiva a buscar una solucion" data-im-placeholder>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">search</i>
  </label>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Frenos o dudas</span>
    <input type="text" name="freno_dudas" value="<?= buyerCampo($buyerDatos, 'freno_dudas') ?>" placeholder="Que dudas le impiden avanzar" data-im-placeholder>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">block</i>
  </label>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Criterio de eleccion</span>
    <input type="text" name="criterio_eleccion" value="<?= buyerCampo($buyerDatos, 'criterio_eleccion') ?>" placeholder="Que compara antes de elegir" data-im-placeholder>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">rule</i>
  </label>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Busqueda de informacion</span>
    <input type="text" name="busqueda_informacion" value="<?= buyerCampo($buyerDatos, 'busqueda_informacion') ?>" placeholder="Donde se informa" data-im-placeholder>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">travel_explore</i>
  </label>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Decision de compra</span>
    <input type="text" name="decision_compra" value="<?= buyerCampo($buyerDatos, 'decision_compra') ?>" placeholder="Como toma la decision" data-im-placeholder>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">shopping_cart_checkout</i>
  </label>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Motivo de eleccion</span>
    <input type="text" name="motivo_eleccion" value="<?= buyerCampo($buyerDatos, 'motivo_eleccion') ?>" placeholder="Por que te elegiria" data-im-placeholder>
    <i class="im-campo__icono material-symbols-rounded" aria-hidden="true">verified</i>
  </label>
  <article class="im-tab-panel activo im-campo--ancho">
    <span class="im-etiqueta">Buyer persona construido</span>
    <p data-buyer-preview><?= buyerCampo($buyerDatos, 'buyer_persona_estructura') ?></p>
  </article>
  <div class="im-formulario__acciones">
    <button class="im-boton im-boton--principal" type="submit">Guardar buyer persona</button>
  </div>
</form>
<script>
  (() => {
    const form = document.currentScript.previousElementSibling;
    const get = (name) => {
      const field = form.querySelector(`[name="${name}"]`);
      return field ? field.value.trim() : '';
    };
    const preview = form.querySelector('[data-buyer-preview]');
    const hidden = form.querySelector('[data-buyer-estructura]');
    const fallback = 'Completa los campos para construir el buyer persona de la empresa.';

    const render = () => {
      const cliente = get('cliente_ideal');
      const edad = get('edad_etapa_vida');
      const ocupacion = get('ocupacion_realidad_diaria');
      const problema = get('problema_necesidad');
      const frustracion = get('preocupacion_frustracion');
      const objetivo = get('objetivo_mejora');
      const motivacion = get('motivacion_busqueda');
      const freno = get('freno_dudas');
      const criterio = get('criterio_eleccion');
      const busqueda = get('busqueda_informacion');
      const decision = get('decision_compra');
      const motivo = get('motivo_eleccion');
      const hayDatos = [cliente, edad, ocupacion, problema, frustracion, objetivo, motivacion, freno, criterio, busqueda, decision, motivo].some(Boolean);
      const texto = hayDatos
        ? `El buyer persona principal es ${cliente || 'un cliente potencial alineado con la propuesta de valor'}. Se encuentra en la etapa ${edad || 'adecuada para decidir una compra'} y su realidad diaria esta marcada por ${ocupacion || 'necesidades concretas y poco tiempo disponible'}. Necesita resolver ${problema || 'un problema relevante'}, le preocupa ${frustracion || 'equivocarse al elegir'} y busca mejorar ${objetivo || 'su situacion actual'}. Se motiva cuando ${motivacion || 'encuentra una solucion clara y confiable'}, aunque puede frenarse por ${freno || 'dudas sobre el resultado'}. Para elegir evalua ${criterio || 'confianza, precio, calidad y acompanamiento'}, se informa en ${busqueda || 'canales digitales y recomendaciones'}, decide comprar cuando ${decision || 'percibe valor y seguridad'} y elige la empresa porque ${motivo || 'la propuesta responde a su necesidad mejor que otras alternativas'}.`
        : fallback;
      preview.textContent = texto;
      hidden.value = texto === fallback ? '' : texto;
    };

    form.addEventListener('input', render);
    render();
  })();
</script>
