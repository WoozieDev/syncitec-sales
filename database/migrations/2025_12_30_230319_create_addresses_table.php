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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('label')->nullable(); // Casa/Trabajo
            $table->string('full_name');
            $table->string('phone');

            $table->string('country_code', 2)->default('PE'); // ISO-3166-1 alpha-2
            $table->string('state');   // departamento/estado/región
            $table->string('city');    // provincia/ciudad
            $table->string('district')->nullable(); // distrito/comuna/etc (opcional)

            $table->string('postal_code')->nullable();

            $table->string('line1'); // dirección principal
            $table->string('line2')->nullable();
            $table->string('reference')->nullable();

            $table->boolean('is_default')->default(false);

            // For future integrations (Google place_id, lat/lng, etc.)
            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->index('user_id');
            $table->index(['user_id', 'is_default']);
            $table->index('country_code');
            $table->index(['country_code', 'state']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
