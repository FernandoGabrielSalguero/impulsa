<?php

namespace App\Services\Admin;

use App\Services\PublicApi\PublicMediaUrlBuilder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class AdminBlogAdminService
{
    public function __construct(
        private readonly PublicMediaUrlBuilder $mediaUrlBuilder,
    ) {}
    /** @return array{total: int, active: int, draft: int, inactive: int} */
    public function summary(): array
    {
        $row = DB::table('api_blog_posts')
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

    public function list(
        ?string $q = null,
        ?string $status = null,
        int $perPage = 20,
        int $page = 1,
    ): LengthAwarePaginator {
        $query = DB::table('api_blog_posts as p')
            ->join('api_integrations as ai', 'ai.id', '=', 'p.api_integration_id')
            ->leftJoin('user_auth as ua', 'ua.id', '=', 'ai.user_auth_id')
            ->leftJoin('user_info as ui', 'ui.user_auth_id', '=', 'ua.id')
            ->select([
                'p.id',
                'p.api_integration_id',
                'p.title',
                'p.slug',
                'p.subtitle',
                'p.author',
                'p.category',
                'p.subcategory',
                'p.excerpt',
                'p.description_html',
                'p.publication_date',
                'p.status',
                'p.sort_order',
                'p.cover_image_path',
                'p.attachment_path',
                'p.created_at',
                'p.updated_at',
                'ai.project_name',
                'ai.allowed_domain',
                'ai.status as integration_status',
                'ua.correo as owner_email',
                'ui.nombre as owner_nombre',
                'ui.apellido as owner_apellido',
            ])
            ->orderByDesc('p.publication_date')
            ->orderByDesc('p.updated_at');

        $search = trim((string) $q);

        if (mb_strlen($search) >= 3) {
            $like = '%' . $search . '%';
            $query->where(function ($builder) use ($like): void {
                $builder
                    ->where('p.title', 'like', $like)
                    ->orWhere('p.slug', 'like', $like)
                    ->orWhere('p.category', 'like', $like)
                    ->orWhere('ai.project_name', 'like', $like)
                    ->orWhere('ua.correo', 'like', $like);
            });
        }

        if ($status !== null && $status !== '' && $status !== '__all__') {
            $query->where('p.status', $status);
        }

        return $query->paginate(max(1, min($perPage, 100)), ['*'], 'page', max(1, $page))
            ->through(fn (object $post): array => $this->mapPost($post));
    }

    /** @return array<string, mixed> */
    public function find(int $postId): array
    {
        $post = DB::table('api_blog_posts as p')
            ->join('api_integrations as ai', 'ai.id', '=', 'p.api_integration_id')
            ->leftJoin('user_auth as ua', 'ua.id', '=', 'ai.user_auth_id')
            ->leftJoin('user_info as ui', 'ui.user_auth_id', '=', 'ua.id')
            ->where('p.id', $postId)
            ->select([
                'p.*',
                'ai.project_name',
                'ai.allowed_domain',
                'ua.correo as owner_email',
                'ui.nombre as owner_nombre',
                'ui.apellido as owner_apellido',
            ])
            ->first();

        if ($post === null) {
            abort(404, 'Artículo no encontrado.');
        }

        return $this->mapPost($post);
    }

    /** @return array<string, mixed> */
    private function mapPost(object $post): array
    {
        return [
            'id' => (int) $post->id,
            'api_integration_id' => (int) $post->api_integration_id,
            'title' => (string) $post->title,
            'slug' => (string) $post->slug,
            'subtitle' => $post->subtitle,
            'author' => $post->author,
            'category' => $post->category,
            'subcategory' => $post->subcategory,
            'excerpt' => $post->excerpt,
            'description_html' => (string) $post->description_html,
            'publication_date' => $post->publication_date,
            'status' => (string) $post->status,
            'sort_order' => (int) $post->sort_order,
            'cover_image_path' => $post->cover_image_path,
            'attachment_path' => $post->attachment_path,
            'cover_image_url' => $this->mediaUrlBuilder->url($post->cover_image_path ?? null),
            'attachment_url' => $this->mediaUrlBuilder->url($post->attachment_path ?? null),
            'created_at' => $post->created_at,
            'updated_at' => $post->updated_at,
            'project_name' => (string) ($post->project_name ?? ''),
            'allowed_domain' => (string) ($post->allowed_domain ?? ''),
            'integration_status' => (string) ($post->integration_status ?? ''),
            'owner_email' => $post->owner_email ?? null,
            'owner_nombre' => $post->owner_nombre ?? null,
            'owner_apellido' => $post->owner_apellido ?? null,
        ];
    }
}
