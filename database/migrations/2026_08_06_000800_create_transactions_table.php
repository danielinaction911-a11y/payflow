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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('wallet_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('type', ['deposit', 'withdrawal', 'trade', 'investment', 'profit', 'transfer_in', 'transfer_out', 'referral_earning', 'bonus', 'staking', 'referral_credit', 'refund', 'chargeback', 'fee', 'other']);
            $table->string('reference')->unique();
            $table->enum('direction', ['debit', 'credit']);
            $table->decimal('amount', 28, 18);
            $table->decimal('fee', 28, 18)->default(0);
            $table->string('currency', 10);
            $table->enum('status', ['pending', 'completed', 'failed', 'reversed']);
            $table->string('description')->nullable();
            $table->text('failed_reason')->nullable();
            $table->text('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
