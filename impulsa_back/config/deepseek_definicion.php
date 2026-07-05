<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Prompt del sistema (común a misión, visión y buyer persona)
    |--------------------------------------------------------------------------
    |
    | Instrucciones generales que DeepSeek recibe en cada solicitud.
    | Editá este texto para cambiar el tono o las reglas de respuesta.
    |
    */
    'system_prompt' => env(
        'DEEPSEEK_DEFINICION_SYSTEM_PROMPT',
        'Sos un consultor de estrategia para emprendimientos en Argentina. '
        . 'Respondé únicamente con una sola frase clara, profesional y en español rioplatense. '
        . 'No uses comillas, viñetas ni encabezados.',
    ),

    /*
    |--------------------------------------------------------------------------
    | Prompts por módulo (mensaje del usuario enviado a DeepSeek)
    |--------------------------------------------------------------------------
    |
    | Placeholders disponibles:
    | - Misión: {a_quien_ayudo}, {que_problema_resuelvo}, {como_lo_resuelvo}
    | - Visión: {conversion_futura}, {lugar_mercado}, {impacto_generado}
    | - Buyer persona: {cliente_ideal}, {edad_etapa_vida}, {ocupacion_realidad_diaria},
    |   {problema_necesidad}, {preocupacion_frustracion}, {objetivo_mejora},
    |   {motivacion_busqueda}, {freno_dudas}, {criterio_eleccion},
    |   {busqueda_informacion}, {decision_compra}, {motivo_eleccion}
    |
    */
    'prompts' => [
        'mision' => "Redactá la misión del emprendimiento en una sola frase usando estos datos:\n"
            . "- A quién ayuda: {a_quien_ayudo}\n"
            . "- Problema que resuelve: {que_problema_resuelvo}\n"
            . "- Cómo lo resuelve: {como_lo_resuelvo}",

        'vision' => "Redactá la visión del emprendimiento en una sola frase usando estos datos:\n"
            . "- Conversión futura: {conversion_futura}\n"
            . "- Lugar en el mercado: {lugar_mercado}\n"
            . "- Impacto generado: {impacto_generado}",

        'buyer_persona' => "Redactá el buyer persona del emprendimiento en una sola frase descriptiva usando estos datos:\n"
            . "- Cliente ideal: {cliente_ideal}\n"
            . "- Edad y etapa de vida: {edad_etapa_vida}\n"
            . "- Ocupación y realidad diaria: {ocupacion_realidad_diaria}\n"
            . "- Problema o necesidad: {problema_necesidad}\n"
            . "- Preocupación o frustración: {preocupacion_frustracion}\n"
            . "- Objetivo de mejora: {objetivo_mejora}\n"
            . "- Motivación de búsqueda: {motivacion_busqueda}\n"
            . "- Freno o dudas: {freno_dudas}\n"
            . "- Criterio de elección: {criterio_eleccion}\n"
            . "- Búsqueda de información: {busqueda_informacion}\n"
            . "- Decisión de compra: {decision_compra}\n"
            . "- Motivo de elección: {motivo_eleccion}",
    ],

];
