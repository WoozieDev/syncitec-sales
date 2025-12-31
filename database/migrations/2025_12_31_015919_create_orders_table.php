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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            $table->string('order_number')->unique();

            // Customer (nullable for guest checkout)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('guest_email')->nullable();

            // Order lifecycle
            $table->string('status', 30)->default('pending');

            // Payment
            $table->string('payment_method', 30); // mercadopago | cash_on_delivery | yape | bank_transfer
            $table->string('payment_status', 30)->default('unpaid'); // unpaid | pending_verification | paid | rejected

            // Shipping snapshot
            $table->foreignId('shipping_zone_id')
                ->nullable()
                ->constrained('shipping_zones')
                ->nullOnDelete();

            $table->unsignedInteger('shipping_amount')->default(0);

            // JSON snapshot of address at time of order
            $table->json('shipping_address_snapshot');

            // Coupon snapshot (we’ll add coupon_id later in coupons phase)
            $table->unsignedInteger('discount_amount')->default(0);

            // Totals (money in cents)
            $table->unsignedInteger('subtotal_amount')->default(0);
            $table->unsignedInteger('total_amount')->default(0);

            $table->string('currency', 3)->default('PEN');

            $table->timestamps();

            $table->index('user_id');
            $table->index('guest_email');
            $table->index('status');
            $table->index('payment_status');
            $table->index('payment_method');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
