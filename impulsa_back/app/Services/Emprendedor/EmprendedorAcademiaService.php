<?php

namespace App\Services\Emprendedor;

use App\Models\AcademiaVideo;
use App\Services\Academia\AcademiaAttachmentStorageService;
use App\Services\Admin\AcademiaAdminService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class EmprendedorAcademiaService
{
    public function __construct(
        private readonly AcademiaAdminService $academiaAdminService,
        private readonly AcademiaAttachmentStorageService $storageService,
    ) {}

    /** @return array{categories: list<string>, subcategories: list<string>} */
    public function taxonomy(): array
    {
        return $this->academiaAdminService->taxonomy();
    }

    public function list(?string $q, ?string $category, ?string $subcategory, int $perPage = 20, int $page = 1): LengthAwarePaginator
    {
        $query = AcademiaVideo::query()
            ->with('attachments')
            ->where('status', 'active')
            ->where('is_visible_to_clients', true)
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
                    ->orWhere('description_html', 'like', $like);
            });
        }

        $categoryFilter = trim((string) $category);

        if ($categoryFilter !== '' && $categoryFilter !== '__all__') {
            $query->where('category', $categoryFilter);
        }

        $subcategoryFilter = trim((string) $subcategory);

        if ($subcategoryFilter !== '' && $subcategoryFilter !== '__all__') {
            $query->where('subcategory', $subcategoryFilter);
        }

        return $query->paginate($perPage, ['*'], 'page', $page)->withQueryString();
    }

    public function find(int $videoId): AcademiaVideo
    {
        $video = AcademiaVideo::query()
            ->with('attachments')
            ->where('status', 'active')
            ->where('is_visible_to_clients', true)
            ->find($videoId);

        if ($video === null) {
            throw ValidationException::withMessages([
                'video' => ['El video no está disponible.'],
            ]);
        }

        return $video;
    }

    public function resolveAttachmentPath(int $videoId, int $attachmentId): string
    {
        $video = $this->find($videoId);

        return $this->academiaAdminService->resolveAttachmentPath($video, $attachmentId);
    }
}
