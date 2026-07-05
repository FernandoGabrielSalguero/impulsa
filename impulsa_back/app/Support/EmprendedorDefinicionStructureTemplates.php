<?php

namespace App\Support;

class EmprendedorDefinicionStructureTemplates
{
    /** @param array<string, mixed> $fields */
    public static function mision(array $fields): string
    {
        $aQuien = trim((string) ($fields['a_quien_ayudo'] ?? ''));
        $problema = trim((string) ($fields['que_problema_resuelvo'] ?? ''));
        $como = trim((string) ($fields['como_lo_resuelvo'] ?? ''));

        if ($aQuien === '' && $problema === '' && $como === '') {
            return '';
        }

        return trim(sprintf(
            'Nuestra misión es ayudar a %s a resolver %s mediante %s, creando valor real en cada etapa del proceso.',
            $aQuien !== '' ? $aQuien : 'nuestros clientes',
            $problema !== '' ? $problema : 'sus principales desafíos',
            $como !== '' ? $como : 'soluciones claras, útiles y sostenibles',
        ));
    }

    /** @param array<string, mixed> $fields */
    public static function vision(array $fields): string
    {
        $conversion = trim((string) ($fields['conversion_futura'] ?? ''));
        $lugar = trim((string) ($fields['lugar_mercado'] ?? ''));
        $impacto = trim((string) ($fields['impacto_generado'] ?? ''));

        if ($conversion === '' && $lugar === '' && $impacto === '') {
            return '';
        }

        return trim(sprintf(
            'Nuestra visión es convertirnos en %s, ocupando %s y generando %s para clientes, equipo y comunidad.',
            $conversion !== '' ? $conversion : 'una empresa referente',
            $lugar !== '' ? $lugar : 'un lugar relevante en el mercado',
            $impacto !== '' ? $impacto : 'un impacto positivo y medible',
        ));
    }

    /** @param array<string, mixed> $fields */
    public static function buyerPersona(array $fields): string
    {
        $cliente = trim((string) ($fields['cliente_ideal'] ?? ''));
        $edad = trim((string) ($fields['edad_etapa_vida'] ?? ''));
        $ocupacion = trim((string) ($fields['ocupacion_realidad_diaria'] ?? ''));
        $problema = trim((string) ($fields['problema_necesidad'] ?? ''));
        $frustracion = trim((string) ($fields['preocupacion_frustracion'] ?? ''));
        $objetivo = trim((string) ($fields['objetivo_mejora'] ?? ''));
        $motivacion = trim((string) ($fields['motivacion_busqueda'] ?? ''));
        $freno = trim((string) ($fields['freno_dudas'] ?? ''));
        $criterio = trim((string) ($fields['criterio_eleccion'] ?? ''));
        $busqueda = trim((string) ($fields['busqueda_informacion'] ?? ''));
        $decision = trim((string) ($fields['decision_compra'] ?? ''));
        $motivo = trim((string) ($fields['motivo_eleccion'] ?? ''));

        $hayDatos = collect([
            $cliente, $edad, $ocupacion, $problema, $frustracion, $objetivo,
            $motivacion, $freno, $criterio, $busqueda, $decision, $motivo,
        ])->contains(static fn ($v): bool => $v !== '');

        if (! $hayDatos) {
            return '';
        }

        return trim(sprintf(
            'El buyer persona principal es %s. Se encuentra en la etapa %s y su realidad diaria está marcada por %s. Necesita resolver %s, le preocupa %s y busca mejorar %s. Se motiva cuando %s, aunque puede frenarse por %s. Para elegir evalúa %s, se informa en %s, decide comprar cuando %s y elige la empresa porque %s.',
            $cliente !== '' ? $cliente : 'un cliente potencial alineado con la propuesta de valor',
            $edad !== '' ? $edad : 'adecuada para decidir una compra',
            $ocupacion !== '' ? $ocupacion : 'necesidades concretas y poco tiempo disponible',
            $problema !== '' ? $problema : 'un problema relevante',
            $frustracion !== '' ? $frustracion : 'equivocarse al elegir',
            $objetivo !== '' ? $objetivo : 'su situación actual',
            $motivacion !== '' ? $motivacion : 'encuentra una solución clara y confiable',
            $freno !== '' ? $freno : 'dudas sobre el resultado',
            $criterio !== '' ? $criterio : 'confianza, precio, calidad y acompañamiento',
            $busqueda !== '' ? $busqueda : 'canales digitales y recomendaciones',
            $decision !== '' ? $decision : 'percibe valor y seguridad',
            $motivo !== '' ? $motivo : 'la propuesta responde a su necesidad mejor que otras alternativas',
        ));
    }
}
