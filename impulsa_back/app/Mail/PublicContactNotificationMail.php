<?php

namespace App\Mail;

use App\Enums\MailTemplate;
use App\Models\ApiIntegration;
use App\Models\UserAuth;
use App\Support\ImpulsaFrontendUrl;

class PublicContactNotificationMail extends ImpulsaMailable
{
    /** @param array<string, mixed> $contactData */
    public function __construct(
        private readonly UserAuth $user,
        private readonly ApiIntegration $integration,
        private readonly int $contactSubmissionId,
        private readonly array $contactData,
    ) {}

    public function mailTemplate(): MailTemplate
    {
        return MailTemplate::NotificacionContactoWebPublica;
    }

    public function recipientEmail(): string
    {
        return $this->user->correo;
    }

    public function userAuthId(): ?int
    {
        return $this->user->id;
    }

    public function subjectLine(): string
    {
        return 'Nuevo contacto en ' . $this->integration->project_name . ' — Impulsa';
    }

    public function htmlView(): string
    {
        return 'mail.public-contact-notification';
    }

    public function textView(): string
    {
        return 'mail.public-contact-notification-text';
    }

    public function viewData(): array
    {
        $description = trim((string) ($this->contactData['contact_description'] ?? ''));
        $excerpt = $description === ''
            ? '—'
            : (mb_strlen($description) > 200 ? mb_substr($description, 0, 200) . '…' : $description);

        return [
            'title' => $this->subjectLine(),
            'project_name' => $this->integration->project_name,
            'allowed_domain' => $this->integration->allowed_domain,
            'contact_nombre' => (string) ($this->contactData['contact_nombre'] ?? ''),
            'contact_email' => (string) ($this->contactData['contact_email'] ?? '') ?: '—',
            'contact_whatsapp' => (string) ($this->contactData['contact_whatsapp'] ?? '') ?: '—',
            'page' => (string) ($this->contactData['page'] ?? '/'),
            'message_excerpt' => $excerpt,
            'panel_url' => ImpulsaFrontendUrl::to('emprendedor/contactos?ver=' . $this->contactSubmissionId),
        ];
    }

    public function mailMeta(): array
    {
        return [
            'api_integration_id' => $this->integration->id,
            'contact_submission_id' => $this->contactSubmissionId,
        ];
    }
}
