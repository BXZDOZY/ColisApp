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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number')->unique();
            $table->string('sender_name');
            $table->string('sender_phone')->nullable();
            $table->string('receiver_name');
            $table->string('receiver_phone')->nullable();
            $table->text('receiver_address');
            $table->decimal('weight', 8, 2);
            $table->string('type'); // e.g., Standard, Enveloppe, Fragile, Express
            $table->text('description')->nullable();
            $table->string('status')->default('pending'); // pending, in_transit, delivered, cancelled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
