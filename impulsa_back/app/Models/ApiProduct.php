<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiProduct extends Model
{
    protected $table = 'api_products';

    protected $fillable = [
        'api_integration_id',
        'title',
        'slug',
        'sku',
        'short_description',
        'description_html',
        'main_image_path',
        'thumbnail_path',
        'attachment_path',
        'category',
        'subcategory',
        'price',
        'compare_at_price',
        'currency',
        'stock_quantity',
        'availability',
        'status',
        'featured',
        'sort_order',
        'metadata_json',
        'created_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_at_price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'featured' => 'boolean',
            'sort_order' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function integration(): BelongsTo
    {
        return $this->belongsTo(ApiIntegration::class, 'api_integration_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(UserAuth::class, 'created_by_user_id');
    }
}
