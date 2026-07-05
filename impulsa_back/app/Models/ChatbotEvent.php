<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatbotEvent extends Model
{
    public $timestamps = false;

    protected $table = 'chatbot_events';

    protected $fillable = [
        'chatbot_id',
        'api_integration_id',
        'event_type',
        'node_id',
        'option_id',
        'page_url',
        'metadata_json',
        'ip_hash',
        'user_agent',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function chatbot(): BelongsTo
    {
        return $this->belongsTo(Chatbot::class);
    }
}
