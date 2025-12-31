<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    // Statuses
    public const STATUS_PENDING = 'pending';
    public const STATUS_PAYMENT_PENDING = 'payment_pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_READY_FOR_PICKUP = 'ready_for_pickup';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    // Payment statuses
    public const PAYMENT_STATUS_UNPAID = 'unpaid';
    public const PAYMENT_STATUS_PENDING_VERIFICATION = 'pending_verification';
    public const PAYMENT_STATUS_PAID = 'paid';
    public const PAYMENT_STATUS_REJECTED = 'rejected';

    // Payment methods
    public const PAYMENT_METHOD_MERCADOPAGO = 'mercadopago';
    public const PAYMENT_METHOD_CASH_ON_DELIVERY = 'cash_on_delivery';
    public const PAYMENT_METHOD_YAPE = 'yape';
    public const PAYMENT_METHOD_BANK_TRANSFER = 'bank_transfer';

    protected $fillable = [
        'order_number',
        'user_id',
        'guest_email',
        'status',
        'payment_method',
        'payment_status',
        'shipping_zone_id',
        'shipping_amount',
        'shipping_address_snapshot',
        'discount_amount',
        'subtotal_amount',
        'total_amount',
        'currency',
    ];

    protected $casts = [
        'shipping_amount' => 'integer',
        'discount_amount' => 'integer',
        'subtotal_amount' => 'integer',
        'total_amount' => 'integer',
        'shipping_address_snapshot' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shippingZone(): BelongsTo
    {
        return $this->belongsTo(ShippingZone::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isGuest(): bool
    {
        return $this->user_id === null;
    }
}
