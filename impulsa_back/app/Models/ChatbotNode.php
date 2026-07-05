<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatbotNode extends Model
{
    protected $table = 'chatbot_nodes';

    protected $fillable = [
        'chatbot_id',
        'title',
        'body',
        'sort_order',
        'is_start',
        'status',
    ];

    protected $casts = [
            'sort_order' => 'integer',
            'is_start' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];

    public function chatbot(): BelongsTo
    {
        return $this->belongsTo(Chatbot::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(ChatbotNodeOption::class, 'node_id');
    }
}
