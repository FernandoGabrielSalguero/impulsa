<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademiaVideo extends Model
{
    protected $table = 'academia_videos';

    protected $fillable = [
        'title',
        'subtitle',
        'author',
        'author_instagram',
        'author_linkedin',
        'category',
        'subcategory',
        'description_html',
        'youtube_url',
        'youtube_video_id',
        'thumbnail_url',
        'sort_order',
        'status',
        'is_visible_to_clients',
        'created_by_user_id',
    ];

    protected $casts = [
        'is_visible_to_clients' => 'boolean',
        'sort_order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function attachments(): HasMany
    {
        return $this->hasMany(AcademiaVideoAttachment::class, 'academia_video_id')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'created_by_user_id');
    }
}
