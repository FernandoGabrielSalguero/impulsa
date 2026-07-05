<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chatbot extends Model
{
    protected $table = 'chatbots';

    protected $fillable = [
        'api_integration_id',
        'name',
        'avatar_url',
        'whatsapp',
        'initial_message',
        'status',
        'disabled_by_admin',
    ];

    protected function casts(): array
    {
        return [
            'disabled_by_admin' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function integration(): BelongsTo
    {
        return $this->belongsTo(ApiIntegration::class, 'api_integration_id');
    }

    public function nodes(): HasMany
    {
        return $this->hasMany(ChatbotNode::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(ChatbotEvent::class);
    }
}
