<?php

namespace App\Services\Admin;

use App\Mail\NewUserClienteMail;
use App\Models\LandingPageRequest;
use App\Models\LandingPageRequestExternal;
use App\Models\Project;
use App\Models\UserAuth;
use App\Models\UserContacto;
use App\Models\UserInfo;
use App\Services\Mail\ImpulsaMailService;
use Illuminate\Support\Facades\DB;
use Throwable;

class WebRequestActionService
{
    public function __construct(
        private readonly WebRequestAdminService $webRequestAdminService,
        private readonly ImpulsaMailService $mailService,
    ) {}

    /**
     * @return array{ok: bool, message: string, email_sent?: bool, user?: array<string, mixed>}
     */
    public function createUserFromExternal(LandingPageRequestExternal $solicitud): array
    {
        $correo = strtolower(trim($solicitud->correo));

        if ($correo === '' || ! filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return [
                'ok' => false,
                'message' => 'La solicitud no tiene un correo válido.',
            ];
        }

        $existing = $this->webRequestAdminService->findUserByEmail($correo);

        if ($existing !== null) {
            return [
                'ok' => false,
                'message' => 'Ya existe un usuario registrado con ese correo.',
            ];
        }

        $password = rtrim(strtr(base64_encode(random_bytes(18)), '+/', '-_'), '=') . 'A1!';

        try {
            /** @var UserAuth $user */
            $user = DB::transaction(function () use ($solicitud, $correo, $password): UserAuth {
                $user = UserAuth::query()->create([
                    'correo' => $correo,
                    'password' => $password,
                    'rol' => 'impulsa_cliente',
                    'verification_token' => null,
                    'email_verified_at' => now(),
                    'usuario_tipo' => 'externo',
                ]);

                UserContacto::query()->create([
                    'user_auth_id' => $user->id,
                    'correo' => $correo,
                    'check_correo' => true,
                    'permison_correo' => true,
                    'whatsapp' => filled($solicitud->whatsapp) ? $solicitud->whatsapp : null,
                    'check_whatsapp' => filled($solicitud->whatsapp),
                    'permison_whatsapp' => true,
                ]);

                $nombre = trim($solicitud->nombre);

                if ($nombre !== '') {
                    UserInfo::query()->create([
                        'user_auth_id' => $user->id,
                        'nombre' => $nombre,
                        'apodo' => $nombre,
                    ]);
                }

                return $user->load('info');
            });

            $emailSent = $this->mailService->send(
                new NewUserClienteMail(
                    user: $user,
                    password: $password,
                    link: config('impulsa.frontend_url'),
                ),
            );

            return [
                'ok' => true,
                'message' => $emailSent
                    ? 'Usuario cliente creado y correo enviado correctamente.'
                    : 'Usuario cliente creado, pero falló el envío del correo.',
                'email_sent' => $emailSent,
                'user' => [
                    'id' => $user->id,
                    'correo' => $user->correo,
                    'rol' => $user->rol,
                ],
            ];
        } catch (Throwable $exception) {
            return [
                'ok' => false,
                'message' => 'No se pudo crear el usuario: ' . $exception->getMessage(),
            ];
        }
    }

    /**
     * @return array{ok: bool, message: string, project?: array<string, mixed>}
     */
    public function createProjectFromInternal(LandingPageRequest $solicitud, int $managerUserId): array
    {
        $clientUserId = (int) $solicitud->user_auth_id;

        if ($clientUserId <= 0) {
            return [
                'ok' => false,
                'message' => 'La solicitud no tiene un usuario registrado válido asociado.',
            ];
        }

        return $this->createProject(
            sourceType: 'landing_page_request',
            sourceId: (int) $solicitud->id,
            clientUserId: $clientUserId,
            managerUserId: $managerUserId,
            projectName: trim((string) $solicitud->nombre_emprendimiento) ?: 'Página web Impulsa Emprende',
            clientName: $this->resolveInternalRequesterName($solicitud) ?: 'Usuario registrado',
            clientEmail: strtolower(trim((string) ($solicitud->usuario_correo ?? $solicitud->userAuth?->correo ?? ''))),
            clientWhatsapp: filled($solicitud->telefono_contacto) ? $solicitud->telefono_contacto : null,
            summary: trim((string) $solicitud->descripcion),
            scopeSummary: $this->buildInternalScopeSummary($solicitud),
            updateMessage: 'El proyecto fue creado desde la solicitud de Impulsa Emprende y quedó visible para el cliente.',
        );
    }

    /**
     * @return array{ok: bool, message: string, project?: array<string, mixed>}
     */
    public function createProjectFromExternal(LandingPageRequestExternal $solicitud, int $managerUserId): array
    {
        $client = $this->webRequestAdminService->findUserByEmail($solicitud->correo);

        if ($client === null) {
            return [
                'ok' => false,
                'message' => 'Antes de crear el proyecto tenés que generar o asociar el usuario cliente.',
            ];
        }

        if ($client->rol !== 'impulsa_cliente') {
            return [
                'ok' => false,
                'message' => 'El correo ya existe, pero no corresponde al rol impulsa_cliente.',
            ];
        }

        return $this->createProject(
            sourceType: 'landing_page_requests_external',
            sourceId: (int) $solicitud->id,
            clientUserId: (int) $client->id,
            managerUserId: $managerUserId,
            projectName: trim((string) $solicitud->nombre_proyecto) ?: 'Página web Impulsa',
            clientName: trim((string) $solicitud->nombre) ?: 'Cliente externo',
            clientEmail: strtolower(trim((string) $solicitud->correo)),
            clientWhatsapp: filled($solicitud->whatsapp) ? $solicitud->whatsapp : null,
            summary: trim((string) $solicitud->q3_objetivo),
            scopeSummary: $this->buildExternalScopeSummary($solicitud),
            updateMessage: 'El proyecto fue creado desde la solicitud externa y quedó visible para el cliente.',
        );
    }

    /**
     * @return array{ok: bool, message: string, project?: array<string, mixed>}
     */
    private function createProject(
        string $sourceType,
        int $sourceId,
        int $clientUserId,
        int $managerUserId,
        string $projectName,
        string $clientName,
        string $clientEmail,
        ?string $clientWhatsapp,
        string $summary,
        string $scopeSummary,
        string $updateMessage,
    ): array {
        if ($sourceId <= 0) {
            return [
                'ok' => false,
                'message' => 'Solicitud inválida.',
            ];
        }

        $existing = $this->webRequestAdminService->findProjectBySource($sourceType, $sourceId);

        if ($existing !== null) {
            return [
                'ok' => false,
                'message' => 'Ya existe un proyecto creado para esta solicitud.',
                'project' => ['id' => $existing->id],
            ];
        }

        try {
            $projectId = DB::transaction(function () use (
                $sourceType,
                $sourceId,
                $clientUserId,
                $managerUserId,
                $projectName,
                $clientName,
                $clientEmail,
                $clientWhatsapp,
                $summary,
                $scopeSummary,
                $updateMessage,
            ): int {
                $project = Project::query()->create([
                    'source_type' => $sourceType,
                    'source_id' => $sourceId,
                    'project_name' => $projectName,
                    'project_type' => 'website',
                    'client_user_id' => $clientUserId,
                    'manager_user_id' => $managerUserId,
                    'client_name' => $clientName,
                    'client_email' => $clientEmail,
                    'client_whatsapp' => $clientWhatsapp,
                    'summary' => $summary,
                    'scope_summary' => $scopeSummary,
                    'status' => 'planned',
                    'priority' => 'medium',
                    'progress_percent' => 0,
                    'client_visible' => true,
                ]);

                $this->seedProjectStructure((int) $project->id, $managerUserId, $updateMessage);

                return (int) $project->id;
            });

            return [
                'ok' => true,
                'message' => 'Proyecto creado correctamente.',
                'project' => ['id' => $projectId],
            ];
        } catch (Throwable $exception) {
            return [
                'ok' => false,
                'message' => 'No se pudo crear el proyecto: ' . $exception->getMessage(),
            ];
        }
    }

    private function seedProjectStructure(int $projectId, int $managerUserId, string $updateMessage): void
    {
        $phases = [
            ['Relevamiento y alcance', 'Revisión de objetivos, contenido, referencias y criterios de éxito.', 1],
            ['Diseño y contenidos', 'Definición visual, estructura de secciones y textos principales.', 2],
            ['Desarrollo y publicación', 'Construcción, pruebas, ajustes finales y puesta online.', 3],
        ];

        foreach ($phases as [$title, $description, $order]) {
            DB::table('project_phases')->insert([
                'project_id' => $projectId,
                'title' => $title,
                'description' => $description,
                'duration_days' => null,
                'phase_order' => $order,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $deliverables = [
            ['Documento de alcance', 'Resumen inicial de objetivos, secciones y materiales necesarios.', 'document'],
            ['Propuesta visual', 'Base visual y criterio de marca para la página web.', 'design'],
            ['Página web publicada', 'Entrega de la página construida y publicada.', 'deployment'],
        ];

        foreach ($deliverables as [$title, $description, $type]) {
            DB::table('project_deliverables')->insert([
                'project_id' => $projectId,
                'phase_id' => null,
                'title' => $title,
                'description' => $description,
                'deliverable_type' => $type,
                'status' => 'pending',
                'client_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('project_updates')->insert([
            'project_id' => $projectId,
            'phase_id' => null,
            'created_by' => $managerUserId,
            'title' => 'Proyecto creado',
            'message' => $updateMessage,
            'progress_delta' => null,
            'visible_to_client' => true,
            'created_at' => now(),
        ]);
    }

    private function resolveInternalRequesterName(LandingPageRequest $solicitud): string
    {
        $fullName = trim((string) ($solicitud->usuario_nombre ?? '') . ' ' . (string) ($solicitud->usuario_apellido ?? ''));

        if ($fullName !== '') {
            return $fullName;
        }

        return trim((string) ($solicitud->usuario_apodo ?? ''));
    }

    private function buildExternalScopeSummary(LandingPageRequestExternal $solicitud): string
    {
        return trim(implode("\n\n", array_filter([
            'Actividad: ' . trim((string) $solicitud->q2_actividad),
            'Público: ' . trim((string) $solicitud->q4_publico),
            'Acción principal: ' . trim((string) $solicitud->q5_accion_principal),
            'Secciones: ' . trim((string) $solicitud->q8_secciones),
            'Dominio y hosting: ' . trim((string) $solicitud->q16_dominio_hosting),
            'Requerimientos adicionales: ' . trim((string) $solicitud->q18_requerimientos_adicionales),
        ], fn (string $line): bool => trim(substr($line, (int) strpos($line, ':') + 1)) !== '')));
    }

    private function buildInternalScopeSummary(LandingPageRequest $solicitud): string
    {
        return trim(implode("\n\n", array_filter([
            'Descripción: ' . trim((string) $solicitud->descripcion),
            'Rubro: ' . trim((string) ($solicitud->rubro_categoria ?? '')),
            'Subrubro: ' . trim((string) ($solicitud->rubro_subcategoria ?? '')),
            'Fecha de inicio: ' . ($solicitud->fecha_inicio?->format('Y-m-d') ?? ''),
            'Cantidad de colaboradores: ' . (string) $solicitud->cantidad_colaboradores,
            'Dominio registrado: ' . ($solicitud->dominio_registrado ? 'Sí' : 'No'),
            'Hosting propio: ' . ($solicitud->hosting_propio ? 'Sí' : 'No'),
        ], fn (string $line): bool => trim(substr($line, (int) strpos($line, ':') + 1)) !== '')));
    }
}
