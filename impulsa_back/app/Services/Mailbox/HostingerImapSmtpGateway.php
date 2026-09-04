<?php

namespace App\Services\Mailbox;

use App\Exceptions\HostingerMailException;
use App\Models\UserMailbox;
use Illuminate\Http\UploadedFile;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Throwable;
use Webklex\PHPIMAP\Client;
use Webklex\PHPIMAP\ClientManager;
use Webklex\PHPIMAP\Exceptions\ConnectionFailedException;
use Webklex\PHPIMAP\Folder;
use Webklex\PHPIMAP\Message;
use Webklex\PHPIMAP\Support\FolderCollection;

class HostingerImapSmtpGateway implements HostingerMailGateway
{
    private const SENT_FOLDER_CANDIDATES = [
        'Sent',
        'INBOX.Sent',
        'Sent Mail',
        'Sent Items',
        'Enviados',
        'INBOX.Enviados',
    ];

    public function testConnection(string $email, string $password): void
    {
        $this->assertImapExtension();

        $client = $this->makeImapClient($email, $password);

        try {
            $client->connect();
        } catch (Throwable $exception) {
            throw new HostingerMailException($this->connectionErrorMessage($exception, 'IMAP'));
        } finally {
            $this->safeDisconnect($client);
        }

        $transport = $this->makeSmtpTransport($email, $password);

        try {
            $transport->start();
            $transport->stop();
        } catch (TransportExceptionInterface $exception) {
            throw new HostingerMailException($this->connectionErrorMessage($exception, 'SMTP'));
        }
    }

    public function listMessages(UserMailbox $mailbox, string $folder, int $page, int $perPage): array
    {
        $client = $this->connectMailbox($mailbox);

        try {
            $imapFolder = $this->resolveFolder($client, $folder);
            $query = $imapFolder->messages()->all()->leaveUnread()->setFetchOrder('desc');
            $total = (int) $imapFolder->messages()->all()->leaveUnread()->count();
            $messages = $query->limit($perPage, max(1, $page))->get();

            $data = [];

            foreach ($messages as $message) {
                $data[] = $this->serializeListMessage($message);
            }

            return [
                'data' => $data,
                'meta' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'last_page' => max(1, (int) ceil($total / max(1, $perPage))),
                ],
            ];
        } catch (HostingerMailException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            throw new HostingerMailException($this->connectionErrorMessage($exception, 'IMAP'));
        } finally {
            $this->safeDisconnect($client);
        }
    }

    public function getMessage(UserMailbox $mailbox, string $folder, int $uid): array
    {
        $client = $this->connectMailbox($mailbox);

        try {
            $message = $this->findMessage($client, $folder, $uid);
            $message->setFlag('Seen');

            return $this->serializeDetailMessage($message);
        } catch (HostingerMailException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            throw new HostingerMailException($this->connectionErrorMessage($exception, 'IMAP'));
        } finally {
            $this->safeDisconnect($client);
        }
    }

    public function getAttachment(UserMailbox $mailbox, string $folder, int $uid, int $part): array
    {
        $client = $this->connectMailbox($mailbox);

        try {
            $message = $this->findMessage($client, $folder, $uid);
            $attachments = $message->getAttachments()->values();
            $attachment = $attachments[$part] ?? null;

            if ($attachment === null) {
                throw new HostingerMailException('No encontramos ese adjunto.');
            }

            return [
                'filename' => (string) ($attachment->getName() ?: 'adjunto'),
                'content_type' => (string) ($attachment->getMimeType() ?: 'application/octet-stream'),
                'contents' => (string) $attachment->getContent(),
            ];
        } catch (HostingerMailException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            throw new HostingerMailException($this->connectionErrorMessage($exception, 'IMAP'));
        } finally {
            $this->safeDisconnect($client);
        }
    }

    public function sendMessage(
        UserMailbox $mailbox,
        string $to,
        string $subject,
        string $body,
        array $attachments = [],
    ): void {
        $this->assertAttachmentBudget($attachments);

        $fromName = trim((string) ($mailbox->userAuth?->info?->nombre ?? ''));
        $emailMessage = (new Email())
            ->from(new Address($mailbox->email, $fromName !== '' ? $fromName : $mailbox->email))
            ->to($to)
            ->subject($subject)
            ->html($body)
            ->text(trim(html_entity_decode(strip_tags($body), ENT_QUOTES | ENT_HTML5, 'UTF-8')));

        foreach ($attachments as $file) {
            $emailMessage->attachFromPath(
                $file->getRealPath(),
                $file->getClientOriginalName() ?: $file->getFilename(),
                $file->getMimeType() ?: 'application/octet-stream',
            );
        }

        $transport = $this->makeSmtpTransport($mailbox->email, $mailbox->getPlainPassword());

        try {
            (new Mailer($transport))->send($emailMessage);
        } catch (TransportExceptionInterface $exception) {
            throw new HostingerMailException($this->connectionErrorMessage($exception, 'SMTP'));
        }

        $this->appendToSent($mailbox, $emailMessage->toString());
    }

    private function appendToSent(UserMailbox $mailbox, string $rawMessage): void
    {
        $this->assertImapExtension();

        $client = $this->makeImapClient($mailbox->email, $mailbox->getPlainPassword());

        try {
            $client->connect();
            $folder = $this->resolveSentFolder($client);

            if ($folder === null) {
                return;
            }

            $folder->appendMessage($rawMessage, ['\\Seen']);
        } catch (Throwable) {
            // El envío SMTP ya se completó; no fallar si Hostinger no expone Enviados.
        } finally {
            $this->safeDisconnect($client);
        }
    }

    private function connectMailbox(UserMailbox $mailbox): Client
    {
        $this->assertImapExtension();

        $client = $this->makeImapClient($mailbox->email, $mailbox->getPlainPassword());

        try {
            $client->connect();
        } catch (Throwable $exception) {
            throw new HostingerMailException($this->connectionErrorMessage($exception, 'IMAP'));
        }

        return $client;
    }

    private function findMessage(Client $client, string $folder, int $uid): Message
    {
        $imapFolder = $this->resolveFolder($client, $folder);
        $message = $imapFolder->messages()->getMessageByUid((string) $uid);

        if (! $message instanceof Message) {
            throw new HostingerMailException('No encontramos ese correo.');
        }

        return $message;
    }

    private function resolveFolder(Client $client, string $folder): Folder
    {
        if ($folder === 'sent') {
            $sent = $this->resolveSentFolder($client);

            if ($sent === null) {
                throw new HostingerMailException('No encontramos la carpeta de enviados en Hostinger.');
            }

            return $sent;
        }

        try {
            $inbox = $client->getFolderByPath('INBOX', false, true)
                ?? $client->getFolderByName('INBOX', true);
        } catch (Throwable $exception) {
            throw new HostingerMailException($this->connectionErrorMessage($exception, 'IMAP'));
        }

        if (! $inbox instanceof Folder) {
            throw new HostingerMailException('No encontramos la bandeja de entrada.');
        }

        return $inbox;
    }

    private function resolveSentFolder(Client $client): ?Folder
    {
        foreach (self::SENT_FOLDER_CANDIDATES as $path) {
            try {
                $folder = $client->getFolderByPath($path, false, true)
                    ?? $client->getFolderByName($path, true);
            } catch (Throwable) {
                $folder = null;
            }

            if ($folder instanceof Folder) {
                return $folder;
            }
        }

        try {
            $folders = $client->getFolders(false);
        } catch (Throwable) {
            return null;
        }

        return $this->findSentFolderRecursive($folders);
    }

    private function findSentFolderRecursive(FolderCollection $folders): ?Folder
    {
        foreach ($folders as $folder) {
            if (! $folder instanceof Folder) {
                continue;
            }

            $name = strtolower((string) ($folder->name ?? $folder->path ?? ''));

            if (in_array($name, ['sent', 'sent mail', 'sent items', 'enviados'], true)) {
                return $folder;
            }

            if ($folder->hasChildren() && $folder->children instanceof FolderCollection) {
                $nested = $this->findSentFolderRecursive($folder->children);

                if ($nested instanceof Folder) {
                    return $nested;
                }
            }
        }

        return null;
    }

    private function serializeListMessage(Message $message): array
    {
        $from = $this->firstAddress($message->getFrom());
        $to = $this->formatAddressList($message->getTo());
        $previewSource = (string) ($message->getTextBody() ?: strip_tags((string) $message->getHTMLBody()));

        return [
            'uid' => (int) $message->getUid(),
            'from' => $from['full'],
            'from_email' => $from['email'],
            'from_name' => $from['name'],
            'to' => $to,
            'subject' => trim((string) $message->getSubject()) ?: '(Sin asunto)',
            'date' => $this->formatDate($message),
            'seen' => (bool) $message->getSeen(),
            'preview' => $this->preview($previewSource),
        ];
    }

    private function serializeDetailMessage(Message $message): array
    {
        $from = $this->firstAddress($message->getFrom());
        $attachments = [];

        foreach ($message->getAttachments()->values() as $index => $attachment) {
            $attachments[] = [
                'id' => (int) $index,
                'filename' => (string) ($attachment->getName() ?: 'adjunto'),
                'size' => (int) $attachment->getSize(),
                'content_type' => (string) ($attachment->getMimeType() ?: 'application/octet-stream'),
            ];
        }

        return [
            'uid' => (int) $message->getUid(),
            'from' => $from['full'],
            'from_email' => $from['email'],
            'from_name' => $from['name'],
            'to' => $this->formatAddressList($message->getTo()),
            'cc' => $this->formatAddressList($message->getCc()),
            'subject' => trim((string) $message->getSubject()) ?: '(Sin asunto)',
            'date' => $this->formatDate($message),
            'seen' => true,
            'body_html' => (string) ($message->getHTMLBody() ?: ''),
            'body_text' => (string) ($message->getTextBody() ?: ''),
            'attachments' => $attachments,
        ];
    }

    /**
     * @return array{email: string, name: string, full: string}
     */
    private function firstAddress(mixed $attribute): array
    {
        $items = $this->addressItems($attribute);
        $first = $items[0] ?? null;

        if ($first === null) {
            return ['email' => '', 'name' => '', 'full' => ''];
        }

        $email = is_object($first) ? (string) ($first->mail ?? '') : '';
        $name = is_object($first) ? (string) ($first->personal ?? '') : '';
        $full = trim($name !== '' && $email !== '' ? "{$name} <{$email}>" : ($email !== '' ? $email : $name));

        return ['email' => $email, 'name' => $name, 'full' => $full];
    }

    private function formatAddressList(mixed $attribute): string
    {
        $parts = [];

        foreach ($this->addressItems($attribute) as $item) {
            $email = is_object($item) ? (string) ($item->mail ?? '') : '';
            $name = is_object($item) ? (string) ($item->personal ?? '') : '';
            $full = trim($name !== '' && $email !== '' ? "{$name} <{$email}>" : ($email !== '' ? $email : $name));

            if ($full !== '') {
                $parts[] = $full;
            }
        }

        return implode(', ', $parts);
    }

    /**
     * @return array<int, mixed>
     */
    private function addressItems(mixed $attribute): array
    {
        if ($attribute === null) {
            return [];
        }

        if (is_array($attribute)) {
            return array_values($attribute);
        }

        if (is_object($attribute) && method_exists($attribute, 'toArray')) {
            return array_values($attribute->toArray());
        }

        return [$attribute];
    }

    private function formatDate(Message $message): ?string
    {
        $date = $message->getDate();

        if ($date === null) {
            return null;
        }

        if (is_object($date) && method_exists($date, 'toDate')) {
            $carbon = $date->toDate();

            return $carbon ? $carbon->toIso8601String() : null;
        }

        if (is_object($date) && method_exists($date, 'first')) {
            $first = $date->first();

            if ($first instanceof \DateTimeInterface) {
                return $first->format(\DateTimeInterface::ATOM);
            }

            if (is_string($first) && $first !== '') {
                return date(\DateTimeInterface::ATOM, strtotime($first) ?: time());
            }
        }

        if ($date instanceof \DateTimeInterface) {
            return $date->format(\DateTimeInterface::ATOM);
        }

        return null;
    }

    private function preview(string $text): string
    {
        $normalized = trim(preg_replace('/\s+/', ' ', $text) ?? $text);

        if (mb_strlen($normalized) <= 140) {
            return $normalized;
        }

        return mb_substr($normalized, 0, 137).'...';
    }

    /**
     * @param  array<int, UploadedFile>  $attachments
     */
    private function assertAttachmentBudget(array $attachments): void
    {
        $maxBytes = (int) config('impulsa.hostinger_mail.max_attachment_bytes', 10 * 1024 * 1024);
        $total = 0;

        foreach ($attachments as $file) {
            $total += (int) $file->getSize();
        }

        if ($total > $maxBytes) {
            throw new HostingerMailException('Los adjuntos superan el límite de 10 MB.');
        }
    }

    private function makeImapClient(string $email, string $password): Client
    {
        $manager = new ClientManager();

        return $manager->make([
            'host' => (string) config('impulsa.hostinger_mail.imap_host'),
            'port' => (int) config('impulsa.hostinger_mail.imap_port'),
            'encryption' => (string) config('impulsa.hostinger_mail.imap_encryption'),
            'validate_cert' => true,
            'username' => $email,
            'password' => $password,
            'protocol' => 'imap',
            'timeout' => 20,
        ]);
    }

    private function makeSmtpTransport(string $email, string $password): EsmtpTransport
    {
        $port = (int) config('impulsa.hostinger_mail.smtp_port');
        $implicitTls = $port === 465;
        $transport = new EsmtpTransport(
            (string) config('impulsa.hostinger_mail.smtp_host'),
            $port,
            $implicitTls,
        );
        $transport->setUsername($email);
        $transport->setPassword($password);

        return $transport;
    }

    private function safeDisconnect(Client $client): void
    {
        try {
            $client->disconnect();
        } catch (Throwable) {
        }
    }

    private function assertImapExtension(): void
    {
        if (! extension_loaded('imap')) {
            throw new HostingerMailException(
                'El servidor no tiene habilitada la extensión IMAP de PHP. Activala en hPanel para leer el correo.',
            );
        }
    }

    private function connectionErrorMessage(Throwable $exception, string $protocol): string
    {
        if ($exception instanceof ConnectionFailedException || $exception instanceof TransportExceptionInterface) {
            return "No pudimos autenticar el correo por {$protocol}. Revisá usuario y contraseña de Hostinger.";
        }

        $message = trim($exception->getMessage());

        if ($message !== '' && ! str_contains(strtolower($message), 'password')) {
            return "No pudimos conectar el correo por {$protocol}.";
        }

        return "No pudimos autenticar el correo por {$protocol}. Revisá usuario y contraseña de Hostinger.";
    }
}
