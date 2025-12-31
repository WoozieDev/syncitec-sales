<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;

    // Providers
    public const PROVIDER_MERCADOPAGO = 'mercadopago';
    public const PROVIDER_OFFLINE = 'offline';

    // Methods
    public const METHOD_MERCADOPAGO = 'mercadopago';
    public const METHOD_CASH_ON_DELIVERY = 'cash_on_delivery';
    public const METHOD_YAPE = 'yape';
    public const METHOD_BANK_TRANSFER = 'bank_transfer';

    // Statuses
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_UNDER_REVIEW = 'under_review';

    protected $fillable = [
        'payable_type',
        'payable_id',
        'provider',
        'method',
        'status',
        'amount',
        'currency',
        'provider_payment_id',
        'provider_reference',
        'provider_payload',
        'proof_path',
        'proof_submitted_at',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
    ];

    protected $casts = [
        'amount' => 'integer',
        'provider_payload' => 'array',
        'proof_submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'reviewed_by' => 'integer',
    ];

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
