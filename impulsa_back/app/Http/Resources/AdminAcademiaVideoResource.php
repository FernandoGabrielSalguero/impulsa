<?php

namespace App\Http\Resources;

use App\Models\AcademiaVideo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin AcademiaVideo */
class AdminAcademiaVideoResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->id,
            'title' => (string) $this->title,
            'subtitle' => $this->subtitle,
            'author' => $this->author,
            'author_instagram' => $this->author_instagram,
            'author_linkedin' => $this->author_linkedin,
            'category' => $this->category,
            'subcategory' => $this->subcategory,
            'description_html' => (string) $this->description_html,
            'youtube_url' => (string) $this->youtube_url,
            'youtube_video_id' => (string) $this->youtube_video_id,
            'thumbnail_url' => $this->thumbnail_url,
            'sort_order' => (int) $this->sort_order,
            'status' => (string) $this->status,
            'is_visible_to_clients' => (bool) $this->is_visible_to_clients,
            'attachments_count' => (int) ($this->attachments_count ?? $this->attachments?->count() ?? 0),
            'attachments' => AcademiaVideoAttachmentResource::collection($this->whenLoaded('attachments')),
            'created_by_user_id' => $this->created_by_user_id !== null ? (int) $this->created_by_user_id : null,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
