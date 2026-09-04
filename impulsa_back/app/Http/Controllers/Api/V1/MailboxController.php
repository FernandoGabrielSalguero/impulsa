<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\HostingerMailException;
use App\Exceptions\MailboxNotConfiguredException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Mailbox\ListMailboxMessagesRequest;
use App\Http\Requests\Mailbox\SendMailboxMessageRequest;
use App\Services\Mailbox\UserMailboxService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class MailboxController extends Controller
{
    public function __construct(
        private readonly UserMailboxService $userMailboxService,
    ) {}

    public function show(): JsonResponse
    {
        return $this->handle(function () {
            return response()->json(
                $this->userMailboxService->status(request()->user()->loadMissing('mailbox')),
            );
        });
    }

    public function messages(ListMailboxMessagesRequest $request): JsonResponse
    {
        return $this->handle(function () use ($request) {
            $folder = (string) ($request->validated('folder') ?? 'inbox');
            $page = (int) ($request->validated('page') ?? 1);
            $perPage = (int) ($request->validated('per_page') ?? 20);

            return response()->json(
                $this->userMailboxService->listMessages(
                    $request->user()->loadMissing('mailbox'),
                    $folder,
                    $page,
                    $perPage,
                ),
            );
        });
    }

    public function message(ListMailboxMessagesRequest $request, int $uid): JsonResponse
    {
        return $this->handle(function () use ($request, $uid) {
            $folder = (string) ($request->validated('folder') ?? 'inbox');

            return response()->json(
                $this->userMailboxService->getMessage(
                    $request->user()->loadMissing('mailbox'),
                    $folder,
                    $uid,
                ),
            );
        });
    }

    public function attachment(ListMailboxMessagesRequest $request, int $uid, int $part): Response
    {
        return $this->handle(function () use ($request, $uid, $part) {
            $folder = (string) ($request->validated('folder') ?? 'inbox');
            $attachment = $this->userMailboxService->getAttachment(
                $request->user()->loadMissing('mailbox'),
                $folder,
                $uid,
                $part,
            );

            return response($attachment['contents'], 200, [
                'Content-Type' => $attachment['content_type'],
                'Content-Disposition' => 'attachment; filename="'.$this->safeFilename($attachment['filename']).'"',
            ]);
        });
    }

    public function send(SendMailboxMessageRequest $request): JsonResponse
    {
        return $this->handle(function () use ($request) {
            $this->userMailboxService->sendMessage(
                $request->user()->loadMissing(['mailbox', 'info']),
                $request->validated('to'),
                $request->validated('subject'),
                $request->validated('body'),
                $this->uploadedFiles($request->file('attachments')),
            );

            return response()->json([
                'message' => 'Correo enviado correctamente.',
            ]);
        });
    }

    private function handle(callable $callback): Response
    {
        try {
            return $callback();
        } catch (MailboxNotConfiguredException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 404);
        } catch (HostingerMailException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 502);
        }
    }

    /**
     * @return array<int, UploadedFile>
     */
    private function uploadedFiles(mixed $files): array
    {
        if ($files instanceof UploadedFile) {
            return [$files];
        }

        if (! is_array($files)) {
            return [];
        }

        return array_values(array_filter(
            $files,
            static fn (mixed $file): bool => $file instanceof UploadedFile,
        ));
    }

    private function safeFilename(string $filename): string
    {
        $clean = str_replace(['"', "\r", "\n"], '', $filename);

        return $clean !== '' ? $clean : 'adjunto';
    }
}
