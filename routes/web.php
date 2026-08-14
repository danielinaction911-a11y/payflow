<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\Dashboard;
use App\Livewire\Deposit\DepositForm;
use App\Livewire\Deposit\DepositHistory;
use App\Livewire\Withdraw\WithdrawForm;
use App\Livewire\Withdraw\WithdrawalHistory;
use App\Livewire\Transfer\TransferForm;
use App\Livewire\Transfer\ReceiveMoney;
use App\Livewire\Transfer\TransferHistory;
use App\Livewire\Trade\TradeForm;
use App\Livewire\Wallet\WalletIndex;
use App\Livewire\Wallet\WalletSwap;
use App\Livewire\Investment\InvestmentPlans;
use App\Livewire\Investment\InvestmentHistory;
use App\Livewire\Support\SupportCenter;
use App\Livewire\Notifications\NotificationCenter;
use App\Livewire\Legal\LegalCenter;
use App\Livewire\Security\SecurityCenter;
use App\Livewire\Settings\ProfileSettings;
use App\Livewire\Transactions\TransactionHistory;
use App\Livewire\Referral\ReferralProgram;
use App\Livewire\Requests\RequestMoney;
use App\Livewire\Analytics\AnalyticsDashboard;

Route::get('/', Dashboard::class)
    ->middleware(['auth', 'verified', 'active.verified'])
    ->name('home');

Route::get('dashboard', Dashboard::class)
    ->middleware(['auth', 'verified', 'active.verified'])
    ->name('dashboard');

Route::middleware(['auth', 'active.verified'])->group(function () {
    Route::get('/deposit', DepositForm::class)->name('deposit.index');
    Route::get('/deposit/history', DepositHistory::class)->name('deposit.history');

    Route::get('/withdraw', WithdrawForm::class)->name('withdraw.index');
    Route::get('/withdraw/history', WithdrawalHistory::class)->name('withdraw.history');

    Route::get('/transfer', TransferForm::class)->name('transfer.index');
    Route::get('/transfer/history', TransferHistory::class)->name('transfer.history');

    Route::get('/receive', ReceiveMoney::class)->name('receive.index');
    Route::get('/requests', RequestMoney::class)->name('requests.index');

    Route::get('/trade', TradeForm::class)->name('trade.index');

    Route::get('/wallet', WalletIndex::class)->name('wallet.index');
    Route::get('/wallet/{walletId?}/{mode?}', WalletSwap::class)
        ->where('mode', 'deposit|withdraw')
        ->name('wallet.swap');

    Route::get('/investments', InvestmentPlans::class)->name('investments.index');
    Route::get('/investments/history', InvestmentHistory::class)->name('investments.history');

    Route::get('/support', SupportCenter::class)->name('support.index');
    Route::get('/notifications', NotificationCenter::class)->name('notifications.index');

    Route::get('/legal/{slug?}', LegalCenter::class)->name('legal.index');

    Route::get('/security', SecurityCenter::class)->name('security.index');

    Route::get('/profile', ProfileSettings::class)->name('profile.index');
    Route::get('/transactions', TransactionHistory::class)->name('transactions.index');

    Route::get('/referrals', ReferralProgram::class)->name('referrals.index');

    Route::get('/analytics', AnalyticsDashboard::class)->name('analytics.index'); 

    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__ . '/auth.php';
