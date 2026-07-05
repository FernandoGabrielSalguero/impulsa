<?php

namespace App\Services\Emprendedor;

use App\Models\UserAuth;
use App\Support\EmprendedorDefinicionStructureTemplates;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EmprendedorDefinicionService
{
    public function show(UserAuth $user): array
    {
        return [
            'buyer_persona' => DB::table('emprendedor_buyer_persona')
                ->where('user_auth_id', $user->id)
                ->first(),
            'mision' => DB::table('emprendedor_mision')
                ->where('user_auth_id', $user->id)
                ->first(),
            'vision' => DB::table('emprendedor_vision')
                ->where('user_auth_id', $user->id)
                ->first(),
            'landing' => DB::table('landing_page_request')
                ->where('user_auth_id', $user->id)
                ->first(),
            'rubro_categories' => DB::table('rubro_emprendedor_categoria')
                ->orderBy('nombre')
                ->get(['id', 'nombre', 'descripcion']),
            'rubro_subcategories' => DB::table('rubro_emprendedor_subcategoria')
                ->orderBy('nombre')
                ->get(['id', 'nombre', 'descripcion']),
            'rubro_relaciones' => DB::table('rubro_emprendedor_relaciones')
                ->get(['categoria_id', 'subcategoria_id']),
        ];
    }

    /** @param array<string, mixed> $data */
    public function saveBuyerPersona(UserAuth $user, array $data): object
    {
        $payload = $this->buyerPersonaPayload($data);
        $estructura = trim((string) ($data['buyer_persona_estructura'] ?? ''));

        if ($estructura === '') {
            $estructura = EmprendedorDefinicionStructureTemplates::buyerPersona($payload);
        }

        $payload['buyer_persona_estructura'] = $estructura;
        $payload['user_auth_id'] = $user->id;
        $payload['completado'] = $this->isBuyerPersonaComplete($payload) ? 1 : 0;

        DB::table('emprendedor_buyer_persona')->updateOrInsert(
            ['user_auth_id' => $user->id],
            $payload,
        );

        return DB::table('emprendedor_buyer_persona')->where('user_auth_id', $user->id)->first();
    }

    /** @param array<string, mixed> $data */
    public function saveMision(UserAuth $user, array $data): object
    {
        $payload = [
            'a_quien_ayudo' => trim((string) $data['a_quien_ayudo']),
            'que_problema_resuelvo' => trim((string) $data['que_problema_resuelvo']),
            'como_lo_resuelvo' => trim((string) $data['como_lo_resuelvo']),
            'mision_estructura' => trim((string) ($data['mision_estructura'] ?? '')),
            'completado' => 0,
        ];

        if ($payload['mision_estructura'] === '') {
            $payload['mision_estructura'] = EmprendedorDefinicionStructureTemplates::mision($payload);
        }

        $payload['completado'] = collect($payload)->except(['mision_estructura'])->every(static fn ($v): bool => $v !== '') ? 1 : 0;
        $payload['user_auth_id'] = $user->id;

        DB::table('emprendedor_mision')->updateOrInsert(['user_auth_id' => $user->id], $payload);

        return DB::table('emprendedor_mision')->where('user_auth_id', $user->id)->first();
    }

    /** @param array<string, mixed> $data */
    public function saveVision(UserAuth $user, array $data): object
    {
        $payload = [
            'conversion_futura' => trim((string) $data['conversion_futura']),
            'lugar_mercado' => trim((string) $data['lugar_mercado']),
            'impacto_generado' => trim((string) $data['impacto_generado']),
            'vision_estructura' => trim((string) ($data['vision_estructura'] ?? '')),
            'completado' => 0,
        ];

        if ($payload['vision_estructura'] === '') {
            $payload['vision_estructura'] = EmprendedorDefinicionStructureTemplates::vision($payload);
        }

        $payload['completado'] = collect($payload)->except(['vision_estructura'])->every(static fn ($v): bool => $v !== '') ? 1 : 0;
        $payload['user_auth_id'] = $user->id;

        DB::table('emprendedor_vision')->updateOrInsert(['user_auth_id' => $user->id], $payload);

        return DB::table('emprendedor_vision')->where('user_auth_id', $user->id)->first();
    }

    /** @param array<string, mixed> $data */
    public function saveLanding(UserAuth $user, array $data): object
    {
        $payload = [
            'user_auth_id' => $user->id,
            'nombre_emprendimiento' => trim((string) $data['nombre_emprendimiento']),
            'fecha_inicio' => $data['fecha_inicio'],
            'descripcion' => trim((string) $data['descripcion']),
            'dominio_registrado' => ! empty($data['dominio_registrado']) ? 1 : 0,
            'hosting_propio' => ! empty($data['hosting_propio']) ? 1 : 0,
            'cantidad_colaboradores' => max(1, (int) ($data['cantidad_colaboradores'] ?? 1)),
            'nombre_fundador' => trim((string) $data['nombre_fundador']),
            'vende_productos' => ! empty($data['vende_productos']) ? 1 : 0,
            'vende_servicios' => ! empty($data['vende_servicios']) ? 1 : 0,
            'ya_factura' => ! empty($data['ya_factura']) ? 1 : 0,
            'espacio_fisico' => ! empty($data['espacio_fisico']) ? 1 : 0,
            'rubro_categoria_id' => $data['rubro_categoria_id'] ?? null,
            'rubro_subcategoria_id' => $data['rubro_subcategoria_id'] ?? null,
            'pais' => trim((string) ($data['pais'] ?? '')) ?: null,
            'provincia' => trim((string) ($data['provincia'] ?? '')) ?: null,
            'localidad' => trim((string) ($data['localidad'] ?? '')) ?: null,
            'calle' => trim((string) ($data['calle'] ?? '')) ?: null,
            'numero' => trim((string) ($data['numero'] ?? '')) ?: null,
            'telefono_contacto' => trim((string) $data['telefono_contacto']),
            'completado' => 0,
        ];

        if ($payload['nombre_emprendimiento'] === '' || $payload['telefono_contacto'] === '') {
            throw ValidationException::withMessages([
                'landing' => ['Completá los campos obligatorios del emprendimiento.'],
            ]);
        }

        $payload['completado'] = $this->isLandingComplete($payload) ? 1 : 0;

        DB::table('landing_page_request')->updateOrInsert(['user_auth_id' => $user->id], $payload);

        return DB::table('landing_page_request')->where('user_auth_id', $user->id)->first();
    }

    /** @param array<string, mixed> $data */
    private function buyerPersonaPayload(array $data): array
    {
        return [
            'cliente_ideal' => trim((string) ($data['cliente_ideal'] ?? '')),
            'edad_etapa_vida' => trim((string) ($data['edad_etapa_vida'] ?? '')),
            'ocupacion_realidad_diaria' => trim((string) ($data['ocupacion_realidad_diaria'] ?? '')),
            'problema_necesidad' => trim((string) ($data['problema_necesidad'] ?? '')),
            'preocupacion_frustracion' => trim((string) ($data['preocupacion_frustracion'] ?? '')),
            'objetivo_mejora' => trim((string) ($data['objetivo_mejora'] ?? '')),
            'motivacion_busqueda' => trim((string) ($data['motivacion_busqueda'] ?? '')),
            'freno_dudas' => trim((string) ($data['freno_dudas'] ?? '')),
            'criterio_eleccion' => trim((string) ($data['criterio_eleccion'] ?? '')),
            'busqueda_informacion' => trim((string) ($data['busqueda_informacion'] ?? '')),
            'decision_compra' => trim((string) ($data['decision_compra'] ?? '')),
            'motivo_eleccion' => trim((string) ($data['motivo_eleccion'] ?? '')),
            'buyer_persona_estructura' => trim((string) ($data['buyer_persona_estructura'] ?? '')),
        ];
    }

    /** @param array<string, mixed> $payload */
    private function isBuyerPersonaComplete(array $payload): bool
    {
        unset($payload['user_auth_id'], $payload['completado']);

        return collect($payload)->every(static fn ($value): bool => trim((string) $value) !== '');
    }

    /** @param array<string, mixed> $payload */
    private function isLandingComplete(array $payload): bool
    {
        $required = [
            'nombre_emprendimiento',
            'fecha_inicio',
            'descripcion',
            'nombre_fundador',
            'telefono_contacto',
            'rubro_categoria_id',
            'rubro_subcategoria_id',
        ];

        foreach ($required as $field) {
            $value = $payload[$field] ?? null;

            if ($value === null || trim((string) $value) === '') {
                return false;
            }
        }

        return true;
    }
}
