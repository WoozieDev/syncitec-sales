<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            
            // Polymorphic: Order or Sale
            $table->string('payable_type');
            $table->unsignedBigInteger('payable_id');

            // Provider and method
            $table->string('provider', 30); // mercadopago | offline
            $table->string('method', 30);   // mercadopago | cash_on_delivery | yape | bank_transfer

            // pending | approved | rejected | cancelled | under_review
            $table->string('status', 30)->default('pending');

            // Money in cents
            $table->unsignedInteger('amount');
            $table->string('currency', 3)->default('PEN');

            // Mercado Pago fields
            $table->string('provider_payment_id')->nullable();
            $table->string('provider_reference')->nullable(); // preference_id, merchant_order, etc.
            $table->json('provider_payload')->nullable();

            // Offline proof fields (Yape/Transfer)
            $table->string('proof_path')->nullable();
            $table->dateTime('proof_submitted_at')->nullable();

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->dateTime('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();

            $table->timestamps();

            $table->index(['payable_type', 'payable_id']);
            $table->index(['provider', 'status']);
            $table->index('provider_payment_id');
            $table->index('method');
            $table->index('created_at');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
