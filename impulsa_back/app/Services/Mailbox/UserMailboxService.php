<?php

namespace App\Services\Mailbox;

use App\Exceptions\MailboxNotConfiguredException;
use App\Models\UserAuth;
use App\Models\UserMailbox;
use Illuminate\Http\UploadedFile;

class UserMailboxService
{
    public function __construct(
        private readonly HostingerMailGateway $mailGateway,
    ) {}

    /**
     * @return array{configured: bool, email: string, enabled: bool}
     */
    public function status(UserAuth $user): array
    {
        $mailbox = $this->requireMailbox($user);

        return [
            'configured' => true,
            'email' => $mailbox->email,
            'enabled' => true,
        ];
    }

    /**
     * @return array{data: array<int, array<string, mixed>>, meta: array<string, int>}
     */
    public function listMessages(UserAuth $user, string $folder, int $page, int $perPage): array
    {
        return $this->mailGateway->listMessages(
            $this->requireMailbox($user),
            $folder,
            $page,
            $perPage,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function getMessage(UserAuth $user, string $folder, int $uid): array
    {
        return $this->mailGateway->getMessage($this->requireMailbox($user), $folder, $uid);
    }

    /**
     * @return array{filename: string, content_type: string, contents: string}
     */
    public function getAttachment(UserAuth $user, string $folder, int $uid, int $part): array
    {
        return $this->mailGateway->getAttachment($this->requireMailbox($user), $folder, $uid, $part);
    }

    /**
     * @param  array<int, UploadedFile>  $attachments
     */
    public function sendMessage(
        UserAuth $user,
        string $to,
        string $subject,
        string $body,
        array $attachments = [],
    ): void {
        $mailbox = $this->requireMailbox($user);
        $mailbox->loadMissing('userAuth.info');

        $this->mailGateway->sendMessage($mailbox, $to, $subject, $body, $attachments);
    }

    public function requireMailbox(UserAuth $user): UserMailbox
    {
        $mailbox = $user->mailbox;

        if ($mailbox === null || ! $mailbox->enabled) {
            throw new MailboxNotConfiguredException();
        }

        return $mailbox;
    }
}
