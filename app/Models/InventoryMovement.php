<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class InventoryMovement extends Model
{
    /** @use HasFactory<\Database\Factories\InventoryMovementFactory> */
    use HasFactory;

    public const TYPE_IN = 'in';
    public const TYPE_OUT = 'out';

    // Common reasons (extend as needed)
    public const REASON_SALE = 'sale';
    public const REASON_ORDER = 'order';
    public const REASON_ADJUSTMENT = 'adjustment';
    public const REASON_RESTOCK = 'restock';

    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'reason',
        'reference_type',
        'reference_id',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'reference_id' => 'integer',
        'created_by' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function reference(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'reference_type', 'reference_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
