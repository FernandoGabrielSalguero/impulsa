<?php

namespace App\Services\Emprendedor;

use App\Models\UserAuth;
use App\Services\ApiBlog\ApiBlogPostStorageService;
use App\Support\EmprendedorIntegrationAccess;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class EmprendedorBlogService
{
    public function __construct(
        private readonly EmprendedorIntegrationAccess $integrationAccess,
        private readonly ApiBlogPostStorageService $storageService,
    ) {}

    /** @return Collection<int, object> */
    public function list(UserAuth $user, ?string $q = null, ?string $status = null): Collection
    {
        $integration = $this->integrationAccess->requireIntegration($user);

        $query = DB::table('api_blog_posts')
            ->where('api_integration_id', $integration->id)
            ->orderBy('sort_order')
            ->orderByDesc('publication_date')
            ->orderByDesc('updated_at');

        $search = trim((string) $q);

        if (mb_strlen($search) >= 3) {
            $like = '%' . $search . '%';
            $query->where(function ($builder) use ($like): void {
                $builder
                    ->where('title', 'like', $like)
                    ->orWhere('slug', 'like', $like)
                    ->orWhere('category', 'like', $like);
            });
        }

        if ($status !== null && $status !== '' && $status !== '__all__') {
            $query->where('status', $status);
        }

        return $query->get();
    }

    public function find(UserAuth $user, int $postId): object
    {
        $integration = $this->integrationAccess->requireIntegration($user);

        $post = DB::table('api_blog_posts')
            ->where('api_integration_id', $integration->id)
            ->where('id', $postId)
            ->first();

        if ($post === null) {
            throw ValidationException::withMessages([
                'post' => ['El artículo no existe.'],
            ]);
        }

        return $post;
    }

    /** @return array{categories: list<string>, subcategories: list<string>} */
    public function taxonomy(UserAuth $user): array
    {
        $integration = $this->integrationAccess->requireIntegration($user);

        $categories = DB::table('api_blog_posts')
            ->where('api_integration_id', $integration->id)
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->map(static fn ($value): string => (string) $value)
            ->values()
            ->all();

        $subcategories = DB::table('api_blog_posts')
            ->where('api_integration_id', $integration->id)
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

    /** @param array<string, mixed> $data */
    public function create(UserAuth $user, array $data, array $files = []): object
    {
        $integration = $this->integrationAccess->requireIntegration($user);
        $sortOrder = max(1, (int) ($data['sort_order'] ?? 1));

        $this->shiftSortOrdersForInsert((int) $integration->id, $sortOrder);

        $payload = $this->normalizePayload($data, (int) $integration->id, $user->id);
        $payload['sort_order'] = $sortOrder;
        $payload = array_merge($payload, $this->storageService->resolveUploadedFiles($files, null, $data));
        $payload['created_at'] = now();

        $id = DB::table('api_blog_posts')->insertGetId($payload);

        return $this->find($user, (int) $id);
    }

    /** @param array<string, mixed> $data */
    public function update(UserAuth $user, int $postId, array $data, array $files = []): object
    {
        $existing = $this->find($user, $postId);
        $integrationId = (int) $existing->api_integration_id;
        $previousOrder = (int) $existing->sort_order;
        $newOrder = max(1, (int) ($data['sort_order'] ?? $previousOrder));

        if ($newOrder !== $previousOrder) {
            $this->shiftSortOrdersForUpdate($integrationId, $postId, $previousOrder, $newOrder);
        }

        $payload = $this->normalizePayload($data, $integrationId, $user->id, $existing);
        $payload['sort_order'] = $newOrder;
        $payload = array_merge(
            $payload,
            $this->storageService->resolveUploadedFiles($files, (array) $existing, $data),
        );

        DB::table('api_blog_posts')->where('id', $postId)->update($payload);

        return $this->find($user, $postId);
    }

    public function updateStatus(UserAuth $user, int $postId, string $status): object
    {
        $this->find($user, $postId);

        if (! in_array($status, ['active', 'inactive', 'draft'], true)) {
            throw ValidationException::withMessages([
                'status' => ['Estado inválido.'],
            ]);
        }

        DB::table('api_blog_posts')->where('id', $postId)->update(['status' => $status]);

        return $this->find($user, $postId);
    }

    public function resolveMediaFile(UserAuth $user, int $postId, string $mediaType): array
    {
        $post = $this->find($user, $postId);

        $column = match ($mediaType) {
            'cover' => 'cover_image_path',
            'attachment' => 'attachment_path',
            default => throw ValidationException::withMessages([
                'media' => ['Tipo de media inválido.'],
            ]),
        };

        $storedPath = trim((string) ($post->$column ?? ''));

        if ($storedPath === '') {
            throw ValidationException::withMessages([
                'media' => ['El archivo solicitado no existe.'],
            ]);
        }

        $absolutePath = $this->storageService->resolveAbsolutePath($storedPath);

        if ($absolutePath === null) {
            throw ValidationException::withMessages([
                'media' => ['El archivo solicitado no está disponible.'],
            ]);
        }

        return [
            'path' => $absolutePath,
            'mime' => mime_content_type($absolutePath) ?: 'application/octet-stream',
        ];
    }

    /** @param array<string, mixed> $data */
    private function normalizePayload(array $data, int $integrationId, int $userId, ?object $existing = null): array
    {
        $title = trim((string) $data['title']);
        $slug = trim((string) ($data['slug'] ?? ''));
        $slug = $slug !== '' ? Str::slug($slug) : Str::slug($title);

        if ($slug === '') {
            throw ValidationException::withMessages([
                'slug' => ['El slug del artículo no es válido.'],
            ]);
        }

        $slugQuery = DB::table('api_blog_posts')
            ->where('api_integration_id', $integrationId)
            ->where('slug', $slug);

        if ($existing !== null) {
            $slugQuery->where('id', '!=', $existing->id);
        }

        if ($slugQuery->exists()) {
            throw ValidationException::withMessages([
                'slug' => ['Ya existe un artículo con ese slug.'],
            ]);
        }

        return [
            'api_integration_id' => $integrationId,
            'title' => $title,
            'slug' => $slug,
            'subtitle' => trim((string) ($data['subtitle'] ?? '')) ?: null,
            'author' => trim((string) ($data['author'] ?? '')) ?: null,
            'bibliography' => trim((string) ($data['bibliography'] ?? '')) ?: null,
            'category' => trim((string) ($data['category'] ?? '')) ?: null,
            'subcategory' => trim((string) ($data['subcategory'] ?? '')) ?: null,
            'excerpt' => trim((string) ($data['excerpt'] ?? '')) ?: null,
            'description_html' => trim((string) ($data['description_html'] ?? '')),
            'publication_date' => $data['publication_date'] ?? now(),
            'status' => in_array($data['status'] ?? 'draft', ['active', 'inactive', 'draft'], true)
                ? $data['status']
                : 'draft',
            'created_by_user_id' => $userId,
            'updated_at' => now(),
        ];
    }

    private function shiftSortOrdersForInsert(int $integrationId, int $sortOrder): void
    {
        DB::table('api_blog_posts')
            ->where('api_integration_id', $integrationId)
            ->where('sort_order', '>=', $sortOrder)
            ->increment('sort_order');
    }

    private function shiftSortOrdersForUpdate(int $integrationId, int $postId, int $previousOrder, int $newOrder): void
    {
        if ($newOrder < $previousOrder) {
            DB::table('api_blog_posts')
                ->where('api_integration_id', $integrationId)
                ->where('id', '!=', $postId)
                ->where('sort_order', '>=', $newOrder)
                ->where('sort_order', '<', $previousOrder)
                ->increment('sort_order');

            return;
        }

        DB::table('api_blog_posts')
            ->where('api_integration_id', $integrationId)
            ->where('id', '!=', $postId)
            ->where('sort_order', '>', $previousOrder)
            ->where('sort_order', '<=', $newOrder)
            ->decrement('sort_order');
    }
}
