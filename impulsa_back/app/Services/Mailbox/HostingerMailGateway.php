<?php

namespace App\Services\Mailbox;

use App\Models\UserMailbox;
use Illuminate\Http\UploadedFile;

interface HostingerMailGateway
{
    public function testConnection(string $email, string $password): void;

    /**
     * @return array{data: array<int, array<string, mixed>>, meta: array<string, int>}
     */
    public function listMessages(UserMailbox $mailbox, string $folder, int $page, int $perPage): array;

    /**
     * @return array<string, mixed>
     */
    public function getMessage(UserMailbox $mailbox, string $folder, int $uid): array;

    /**
     * @return array{filename: string, content_type: string, contents: string}
     */
    public function getAttachment(UserMailbox $mailbox, string $folder, int $uid, int $part): array;

    /**
     * @param  array<int, UploadedFile>  $attachments
     */
    public function sendMessage(
        UserMailbox $mailbox,
        string $to,
        string $subject,
        string $body,
        array $attachments = [],
    ): void;
}
