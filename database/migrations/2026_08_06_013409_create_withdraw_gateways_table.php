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
        Schema::create('withdraw_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('logo')->nullable();
            $table->json('details')->nullable();
            $table->decimal('min_amount', 12, 2)->default(0);
            $table->decimal('max_amount', 12, 2)->default(0);
            $table->decimal('fixed_fee', 12, 2)->default(0);
            $table->decimal('percent_fee', 5, 2)->default(0);
            $table->string('currency')->default('USD');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdraw_gateways');
    }
};
