<?php

namespace App\PublicEmbed\Services;

use App\Mail\PublicContactNotificationMail;
use App\Models\ApiIntegration;
use App\Models\UserAuth;
use App\Services\Mail\ImpulsaMailService;

class ContactSubmissionNotificationService
{
    public function __construct(
        private readonly ImpulsaMailService $mailService,
    ) {}

    /** @param array<string, mixed> $contactData */
    public function notifyAfterSubmit(ApiIntegration $integration, int $contactSubmissionId, array $contactData): void
    {
        $owner = $this->resolveVerifiedOwner($integration);

        if ($owner === null) {
            return;
        }

        $this->mailService->send(new PublicContactNotificationMail(
            user: $owner,
            integration: $integration,
            contactSubmissionId: $contactSubmissionId,
            contactData: $contactData,
        ));
    }

    private function resolveVerifiedOwner(ApiIntegration $integration): ?UserAuth
    {
        if ($integration->user_auth_id === null) {
            return null;
        }

        $owner = UserAuth::query()->find($integration->user_auth_id);

        if ($owner === null || $owner->email_verified_at === null) {
            return null;
        }

        return $owner;
    }
}
