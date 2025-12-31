<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    /** @use HasFactory<\Database\Factories\CouponFactory> */
    use HasFactory;

    public const TYPE_PERCENT = 'percent';
    public const TYPE_FIXED = 'fixed';

    protected $fillable = [
        'code',
        'type',
        'value_amount',
        'value_percent',
        'min_order_amount',
        'starts_at',
        'ends_at',
        'max_redemptions',
        'max_redemptions_per_user',
        'is_active',
    ];

    protected $casts = [
        'value_amount' => 'integer',
        'value_percent' => 'integer',
        'min_order_amount' => 'integer',
        'max_redemptions' => 'integer',
        'max_redemptions_per_user' => 'integer',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function redemptions(): HasMany
    {
        return $this->hasMany(CouponRedemption::class);
    }
}
