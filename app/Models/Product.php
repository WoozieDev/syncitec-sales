<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'brand_id',
        'sku',
        'name',
        'slug',
        'description',
        'price_amount',
        'currency',
        'stock_on_hand',
        'is_active',
    ];

    protected $casts = [
        'price_amount' => 'integer',
        'stock_on_hand' => 'integer',
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class)->latest();
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function getPriceFormattedAttribute(): string
    {
        $amount = $this->price_amount / 100;

        // MVP simple formatting; later we can add IntlNumberFormatter
        return 'S/ ' . number_format($amount, 2, '.', ',');
    }

    public function getStockAvailableAttribute(): int
    {
        // In MVP we only have stock_on_hand, but we leave hook for reserved later
        return max(0, (int) $this->stock_on_hand);
    }
}
