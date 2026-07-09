<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademiaVideoAttachment extends Model
{
    public $timestamps = false;

    protected $table = 'academia_video_attachments';

    protected $fillable = [
        'academia_video_id',
        'label',
        'file_path',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'created_at' => 'datetime',
    ];

    public function video(): BelongsTo
    {
        return $this->belongsTo(AcademiaVideo::class, 'academia_video_id');
    }
}
