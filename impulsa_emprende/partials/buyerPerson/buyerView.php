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
  <div class="im-formulario__acciones">
    <button class="im-boton im-boton--principal" type="submit">Guardar buyer persona</button>
  </div>
</form>
