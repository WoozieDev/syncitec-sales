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
        Schema::create('shipping_zone_rules', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('shipping_zone_id')
                ->constrained('shipping_zones')
                ->cascadeOnDelete();

            // Address matching fields (manual input compatible)
            $table->string('country_code', 2); // e.g. PE
            $table->string('state')->nullable();   // departamento/estado
            $table->string('city')->nullable();    // provincia/ciudad
            $table->string('district')->nullable(); // distrito/comuna/etc
            $table->string('postal_code_prefix')->nullable(); // optional

            // Higher wins (district rule > city rule > state rule > country rule)
            $table->unsignedInteger('priority')->default(0);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index('shipping_zone_id');
            $table->index('country_code');
            $table->index(['country_code', 'state']);
            $table->index(['country_code', 'state', 'city']);
            $table->index(['country_code', 'state', 'city', 'district']);
            $table->index(['country_code', 'postal_code_prefix']);
            $table->index(['is_active', 'priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_zone_rules');
    }
};
