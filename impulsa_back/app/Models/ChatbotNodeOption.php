<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatbotNodeOption extends Model
{
    protected $table = 'chatbot_node_options';

    protected $fillable = [
        'node_id',
        'target_node_id',
        'label',
        'action_type',
        'sort_order',
    ];

    protected $casts = [
            'sort_order' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];

    public function node(): BelongsTo
    {
        return $this->belongsTo(ChatbotNode::class, 'node_id');
    }

    public function targetNode(): BelongsTo
    {
        return $this->belongsTo(ChatbotNode::class, 'target_node_id');
    }
}
