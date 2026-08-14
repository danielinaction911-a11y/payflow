<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\DepositStatus;
use App\Enums\InvestmentStatus;
use App\Models\Deposit;
use App\Models\Investment;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\Withdrawal;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalUsers = User::count();
        $newUsersToday = User::whereDate('created_at', today())->count();

        $totalBalance = User::sum('balance') + User::sum('profit_balance');

        $pendingDeposits = Deposit::where('status', DepositStatus::Pending)->count();
        $pendingDepositAmount = Deposit::where('status', DepositStatus::Pending)->sum('amount');

        $pendingWithdrawals = Withdrawal::where('status', 'pending')->count();
        $pendingWithdrawalAmount = Withdrawal::where('status', 'pending')->sum('amount');

        $activeInvestments = Investment::where('status', InvestmentStatus::Active)->count();
        $totalInvested = Investment::sum('amount_invested');

        $openTickets = SupportTicket::whereIn('status', ['open', 'pending'])->count();

        return [
            Stat::make('Total users', number_format($totalUsers))
                ->description("+{$newUsersToday} today")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Platform balance', money_format($totalBalance))
                ->description('Main + profit balances combined')
                ->color('primary'),

            Stat::make('Pending deposits', $pendingDeposits)
                ->description(money_format($pendingDepositAmount) . ' awaiting approval')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingDeposits > 0 ? 'warning' : 'success'),

            Stat::make('Pending withdrawals', $pendingWithdrawals)
                ->description(money_format($pendingWithdrawalAmount) . ' awaiting approval')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingWithdrawals > 0 ? 'warning' : 'success'),

            Stat::make('Active investments', $activeInvestments)
                ->description(money_format($totalInvested) . ' total invested')
                ->color('info'),

            Stat::make('Open support tickets', $openTickets)
                ->description($openTickets > 0 ? 'Needs attention' : 'All caught up')
                ->descriptionIcon($openTickets > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                ->color($openTickets > 0 ? 'danger' : 'success'),
        ];
    }
}