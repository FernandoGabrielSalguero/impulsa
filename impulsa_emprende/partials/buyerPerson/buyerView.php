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
<form class="im-formulario" action="" method="post">
  <input type="hidden" name="buyer_accion" value="guardar_buyer">
  <div class="im-tarjeta__cabecera">
    <div>
      <h3>Buyer Persona</h3>
      <p>Construi el perfil de la persona que tiene mas probabilidad de comprarte.</p>
    </div>
    <span class="im-chip <?= $buyerCompleto ? 'im-chip--completado' : 'im-chip--pendiente' ?>"><?= $buyerCompleto ? 'Completado' : 'Incompleto' ?></span>
  </div>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Cliente ideal</span>
    <textarea name="cliente_ideal" rows="3" placeholder="Quien es tu cliente ideal" data-im-placeholder><?= buyerCampo($buyerDatos, 'cliente_ideal') ?></textarea>
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
    <textarea name="problema_necesidad" rows="3" placeholder="Que necesita resolver" data-im-placeholder><?= buyerCampo($buyerDatos, 'problema_necesidad') ?></textarea>
  </label>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Preocupacion o frustracion</span>
    <textarea name="preocupacion_frustracion" rows="3" placeholder="Que le preocupa o frustra" data-im-placeholder><?= buyerCampo($buyerDatos, 'preocupacion_frustracion') ?></textarea>
  </label>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Objetivo de mejora</span>
    <textarea name="objetivo_mejora" rows="3" placeholder="Que quiere mejorar" data-im-placeholder><?= buyerCampo($buyerDatos, 'objetivo_mejora') ?></textarea>
  </label>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Motivacion de busqueda</span>
    <textarea name="motivacion_busqueda" rows="3" placeholder="Que lo motiva a buscar una solucion" data-im-placeholder><?= buyerCampo($buyerDatos, 'motivacion_busqueda') ?></textarea>
  </label>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Frenos o dudas</span>
    <textarea name="freno_dudas" rows="3" placeholder="Que dudas le impiden avanzar" data-im-placeholder><?= buyerCampo($buyerDatos, 'freno_dudas') ?></textarea>
  </label>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Criterio de eleccion</span>
    <textarea name="criterio_eleccion" rows="3" placeholder="Que compara antes de elegir" data-im-placeholder><?= buyerCampo($buyerDatos, 'criterio_eleccion') ?></textarea>
  </label>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Busqueda de informacion</span>
    <textarea name="busqueda_informacion" rows="3" placeholder="Donde se informa" data-im-placeholder><?= buyerCampo($buyerDatos, 'busqueda_informacion') ?></textarea>
  </label>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Decision de compra</span>
    <textarea name="decision_compra" rows="3" placeholder="Como toma la decision" data-im-placeholder><?= buyerCampo($buyerDatos, 'decision_compra') ?></textarea>
  </label>
  <label class="im-campo im-campo-material im-campo--ancho">
    <span>Motivo de eleccion</span>
    <textarea name="motivo_eleccion" rows="3" placeholder="Por que te elegiria" data-im-placeholder><?= buyerCampo($buyerDatos, 'motivo_eleccion') ?></textarea>
  </label>
  <div class="im-formulario__acciones">
    <button class="im-boton im-boton--principal" type="submit">Guardar buyer persona</button>
  </div>
</form>
