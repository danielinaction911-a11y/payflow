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
        Schema::create('trading_pairs', function (Blueprint $table) {
            $table->id();
            $table->string('symbol')->unique(); // BTC/USD
            $table->foreignId('base_currency_id')->constrained('currencies');
            $table->foreignId('quote_currency_id')->constrained('currencies');
            $table->decimal('current_price', 24, 8);
            $table->decimal('change_24h_percent', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trading_pairs');
    }
};
