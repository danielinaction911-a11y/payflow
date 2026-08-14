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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('address')->nullable();
            $table->string('avatar')->nullable()->default('images/user/user.png');
            $table->decimal('balance', 15, 2)->default(0);
            $table->decimal('profit_balance', 15, 2)->default(0);
            $table->enum('kyc_status', ['not_submitted', 'pending', 'approved', 'rejected'])->default('not_submitted');
            $table->enum('status', ['active', 'suspended', 'banned'])->default('active');

            $table->enum('deposit_status', ['enabled', 'disabled'])->default('enabled');
            $table->text('deposit_message')->default('Deposit is unavailable at the moment');
            $table->enum('transfer_status', ['enabled', 'disabled'])->default('enabled');
            $table->text('transfer_message')->default('Transfer is unavailable at the moment');
            $table->enum('withdrawal_status', ['enabled', 'disabled'])->default('enabled');
            $table->text('withdrawal_message')->default('Withdrawal is unavailable at the moment');
            $table->enum('investment_status', ['enabled', 'disabled'])->default('enabled');
            $table->text('investment_message')->default('Investment is unavailable at the moment');
            $table->enum('trading_status', ['enabled', 'disabled'])->default('enabled');
            $table->text('trading_message')->default('Trading is unavailable at the moment');
            $table->enum('withdrawal_fee_status', ['enabled', 'disabled'])->default('enabled');
            $table->decimal('withdrawal_fee', 10, 2)->default(45.00);
            $table->enum('withdrawal_fee_type', ['percentage', 'amount'])->default('percentage');

            $table->decimal('daily_transfer_limit', 15, 2)->default(10000.00);
            $table->decimal('daily_withdrawal_limit', 15, 2)->default(10000.00);
            $table->decimal('weekly_transfer_limit', 15, 2)->default(50000.00);
            $table->decimal('weekly_withdrawal_limit', 15, 2)->default(50000.00);
            $table->decimal('monthly_transfer_limit', 15, 2)->default(200000.00);
            $table->decimal('monthly_withdrawal_limit', 15, 2)->default(200000.00);

            $table->enum('default_theme', ['light', 'dark'])->default('dark');

            $table->string('referral_code')->unique();
            $table->foreignId('referred_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('transaction_pin')->nullable();
            $table->date('pin_update_at')->nullable();
            $table->boolean('biometric_enabled')->default(false);
            $table->timestamp('last_login_at')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
