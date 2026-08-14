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
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('symbol', 20)->nullable();
            $table->string('code', 20);
            $table->string('network')->nullable();
            $table->boolean('allow_deposit')->default(true);
            $table->boolean('allow_withdrawal')->default(true);
            $table->enum('type', ['fiat', 'crypto']);
            $table->string('coingecko_id')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
