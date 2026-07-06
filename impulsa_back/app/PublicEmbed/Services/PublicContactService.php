<?php

namespace App\PublicEmbed\Services;

use App\Models\ApiIntegration;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PublicContactService
{
    public function __construct(
        private readonly ContactSubmissionNotificationService $notificationService,
    ) {}

    /** @param array<string, mixed> $data */
    public function submit(ApiIntegration $integration, array $data, string $defaultPage): int
    {
        $nombre = trim((string) ($data['contact_nombre'] ?? ''));
        $email = trim((string) ($data['contact_email'] ?? ''));
        $whatsapp = trim((string) ($data['contact_whatsapp'] ?? ''));
        $description = trim((string) ($data['contact_description'] ?? ''));
        $consultation = trim((string) ($data['contact_consultation'] ?? ''));
        $page = trim((string) ($data['page'] ?? $defaultPage));

        if ($nombre === '') {
            throw ValidationException::withMessages([
                'contact_nombre' => ['El nombre es obligatorio.'],
            ]);
        }

        if ($email === '' && $whatsapp === '') {
            throw ValidationException::withMessages([
                'contact_email' => ['Indicá un email o WhatsApp de contacto.'],
            ]);
        }

        $id = (int) DB::table('forms_clients_contact')->insertGetId([
            'page' => mb_substr($page !== '' ? $page : '/', 0, 150),
            'api_integration_id' => (int) $integration->id,
            'contact_nombre' => mb_substr($nombre, 0, 150),
            'contact_whatsapp' => $whatsapp !== '' ? mb_substr($whatsapp, 0, 50) : null,
            'contact_email' => $email !== '' ? mb_substr($email, 0, 150) : null,
            'contact_description' => $description !== '' ? $description : null,
            'contact_consultation' => $consultation !== '' ? mb_substr($consultation, 0, 1000) : null,
            'state' => 'recibido',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->notificationService->notifyAfterSubmit($integration, $id, [
            'contact_nombre' => $nombre,
            'contact_email' => $email,
            'contact_whatsapp' => $whatsapp,
            'contact_description' => $description,
            'contact_consultation' => $consultation,
            'page' => $page !== '' ? $page : '/',
        ]);

        return $id;
    }
}
