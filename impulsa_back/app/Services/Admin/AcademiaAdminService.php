<?php

namespace App\Services\Admin;

use App\Models\AcademiaVideo;
use App\Models\AcademiaVideoAttachment;
use App\Services\Academia\AcademiaAttachmentStorageService;
use App\Support\AcademiaLabels;
use App\Support\YoutubeUrlParser;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AcademiaAdminService
{
    public function __construct(
        private readonly AcademiaAttachmentStorageService $storageService,
    ) {}

    /** @return array<string, int> */
    public function summary(): array
    {
        $row = DB::table('academia_videos')
            ->selectRaw("
                COUNT(*) AS total,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) AS active,
                SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) AS draft,
                SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) AS inactive
            ")
            ->first();

        return [
            'total' => (int) ($row->total ?? 0),
            'active' => (int) ($row->active ?? 0),
            'draft' => (int) ($row->draft ?? 0),
            'inactive' => (int) ($row->inactive ?? 0),
        ];
    }

    /** @return array{categories: list<string>, subcategories: list<string>} */
    public function taxonomy(): array
    {
        $categories = DB::table('academia_videos')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->map(static fn ($value): string => (string) $value)
            ->values()
            ->all();

        $subcategories = DB::table('academia_videos')
            ->whereNotNull('subcategory')
            ->where('subcategory', '!=', '')
            ->distinct()
            ->orderBy('subcategory')
            ->pluck('subcategory')
            ->map(static fn ($value): string => (string) $value)
            ->values()
            ->all();

        return [
            'categories' => $categories,
            'subcategories' => $subcategories,
        ];
    }

    public function list(?string $q, ?string $status, ?string $category, int $perPage = 20, int $page = 1): LengthAwarePaginator
    {
        $query = AcademiaVideo::query()
            ->withCount('attachments')
            ->orderBy('sort_order')
            ->orderByDesc('updated_at');

        $search = trim((string) $q);

        if (mb_strlen($search) >= 2) {
            $like = '%' . $search . '%';
            $query->where(function ($builder) use ($like): void {
                $builder
                    ->where('title', 'like', $like)
                    ->orWhere('subtitle', 'like', $like)
                    ->orWhere('author', 'like', $like)
                    ->orWhere('category', 'like', $like)
                    ->orWhere('subcategory', 'like', $like);
            });
        }

        $statusFilter = trim((string) $status);

        if ($statusFilter !== '' && $statusFilter !== '__all__') {
            $query->where('status', $statusFilter);
        }

        $categoryFilter = trim((string) $category);

        if ($categoryFilter !== '' && $categoryFilter !== '__all__') {
            $query->where('category', $categoryFilter);
        }

        return $query->paginate($perPage, ['*'], 'page', $page)->withQueryString();
    }

    public function find(int $videoId): AcademiaVideo
    {
        $video = AcademiaVideo::query()
            ->with('attachments')
            ->find($videoId);

        if ($video === null) {
            throw ValidationException::withMessages([
                'video' => ['El video de Academia no existe.'],
            ]);
        }

        return $video;
    }

    /** @param array<string, mixed> $data */
    public function create(array $data, int $userId, array $files = []): AcademiaVideo
    {
        return DB::transaction(function () use ($data, $userId, $files): AcademiaVideo {
            $youtube = $this->resolveYoutubeData((string) ($data['youtube_url'] ?? ''));

            $video = AcademiaVideo::query()->create([
                ...$this->videoAttributes($data),
                ...$youtube,
                'created_by_user_id' => $userId,
            ]);

            $this->syncAttachments($video, $files, $data);

            return $this->find((int) $video->id);
        });
    }

    /** @param array<string, mixed> $data */
    public function update(AcademiaVideo $video, array $data, array $files = []): AcademiaVideo
    {
        return DB::transaction(function () use ($video, $data, $files): AcademiaVideo {
            $youtube = $this->resolveYoutubeData((string) ($data['youtube_url'] ?? $video->youtube_url));

            $video->fill([
                ...$this->videoAttributes($data),
                ...$youtube,
            ]);
            $video->save();

            $this->syncAttachments($video, $files, $data);

            return $this->find((int) $video->id);
        });
    }

    public function updateStatus(AcademiaVideo $video, string $status): AcademiaVideo
    {
        if (! in_array($status, AcademiaLabels::statuses(), true)) {
            throw ValidationException::withMessages([
                'status' => ['El estado seleccionado no es válido.'],
            ]);
        }

        $video->status = $status;
        $video->save();

        return $this->find((int) $video->id);
    }

    public function delete(AcademiaVideo $video): void
    {
        DB::transaction(function () use ($video): void {
            foreach ($video->attachments as $attachment) {
                $this->storageService->deleteStoredPath($attachment->file_path);
            }

            $video->delete();
        });
    }

    public function resolveAttachmentPath(AcademiaVideo $video, int $attachmentId): string
    {
        $attachment = $video->attachments()->where('id', $attachmentId)->first();

        if ($attachment === null) {
            throw ValidationException::withMessages([
                'attachment' => ['El adjunto no existe.'],
            ]);
        }

        $absolutePath = $this->storageService->resolveAbsolutePath($attachment->file_path);

        if ($absolutePath === null || ! is_file($absolutePath)) {
            throw ValidationException::withMessages([
                'attachment' => ['El archivo adjunto no está disponible.'],
            ]);
        }

        return $absolutePath;
    }

    /** @param array<string, mixed> $data */
    private function videoAttributes(array $data): array
    {
        return [
            'title' => trim((string) ($data['title'] ?? '')),
            'subtitle' => $this->nullableString($data['subtitle'] ?? null),
            'author' => $this->nullableString($data['author'] ?? null),
            'author_instagram' => $this->nullableString($data['author_instagram'] ?? null),
            'author_linkedin' => $this->nullableString($data['author_linkedin'] ?? null),
            'category' => $this->nullableString($data['category'] ?? null),
            'subcategory' => $this->nullableString($data['subcategory'] ?? null),
            'description_html' => trim((string) ($data['description_html'] ?? '')),
            'sort_order' => max(1, (int) ($data['sort_order'] ?? 1)),
            'status' => (string) ($data['status'] ?? 'draft'),
            'is_visible_to_clients' => filter_var($data['is_visible_to_clients'] ?? false, FILTER_VALIDATE_BOOL),
        ];
    }

    /** @return array{youtube_url: string, youtube_video_id: string, thumbnail_url: string} */
    private function resolveYoutubeData(string $url): array
    {
        $parsed = YoutubeUrlParser::parse($url);

        if ($parsed === null) {
            throw ValidationException::withMessages([
                'youtube_url' => ['La URL de YouTube no es válida.'],
            ]);
        }

        return [
            'youtube_url' => trim($url),
            'youtube_video_id' => $parsed['video_id'],
            'thumbnail_url' => $parsed['thumbnail_url'],
        ];
    }

    /** @param array<string, mixed> $data */
    private function syncAttachments(AcademiaVideo $video, array $files, array $data): void
    {
        $removeIds = $data['remove_attachment_ids'] ?? [];

        if (is_string($removeIds)) {
            $decoded = json_decode($removeIds, true);
            $removeIds = is_array($decoded) ? $decoded : array_filter(array_map('intval', explode(',', $removeIds)));
        }

        if (is_array($removeIds)) {
            foreach ($removeIds as $attachmentId) {
                $attachmentId = (int) $attachmentId;

                if ($attachmentId <= 0) {
                    continue;
                }

                $attachment = AcademiaVideoAttachment::query()
                    ->where('academia_video_id', $video->id)
                    ->where('id', $attachmentId)
                    ->first();

                if ($attachment !== null) {
                    $this->storageService->deleteStoredPath($attachment->file_path);
                    $attachment->delete();
                }
            }
        }

        $storedFiles = $this->storageService->storeUploadedFiles($files, $video->attachments()->count() + 1);

        foreach ($storedFiles as $storedFile) {
            AcademiaVideoAttachment::query()->create([
                'academia_video_id' => $video->id,
                'label' => $storedFile['label'],
                'file_path' => $storedFile['file_path'],
                'sort_order' => $storedFile['sort_order'],
            ]);
        }
    }

    private function nullableString(mixed $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}
