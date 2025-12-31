<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingZoneRule extends Model
{
    /** @use HasFactory<\Database\Factories\ShippingZoneRuleFactory> */
    use HasFactory;

    protected $fillable = [
        'shipping_zone_id',
        'country_code',
        'state',
        'city',
        'district',
        'postal_code_prefix',
        'priority',
        'is_active',
    ];

    protected $casts = [
        'priority' => 'integer',
        'is_active' => 'boolean',
    ];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(ShippingZone::class, 'shipping_zone_id');
    }
}
