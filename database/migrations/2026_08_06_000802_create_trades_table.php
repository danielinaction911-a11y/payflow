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
        Schema::create('trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('trading_pair_id')->constrained();
            $table->enum('side', ['buy', 'sell']);
            $table->enum('order_type', ['market', 'limit', 'stop_loss', 'take_profit']);
            $table->decimal('amount', 24, 8);
            $table->decimal('price', 24, 8);
            $table->decimal('total', 24, 8);
            $table->enum('status', ['open', 'filled', 'hit_target', 'hit_stop', 'expired', 'cancelled']);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};
