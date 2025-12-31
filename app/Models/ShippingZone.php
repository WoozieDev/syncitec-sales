<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingZone extends Model
{
    /** @use HasFactory<\Database\Factories\ShippingZoneFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'price_amount',
        'currency',
        'is_active',
    ];

    protected $casts = [
        'price_amount' => 'integer',
        'is_active' => 'boolean',
    ];

    // Step 3 will add rules()
    public function rules(): HasMany
    {
        return $this->hasMany(ShippingZoneRule::class);
    }

    public function getPriceFormattedAttribute(): string
    {
        $amount = $this->price_amount / 100;

        return 'S/ ' . number_format($amount, 2, '.', ',');
    }
}
