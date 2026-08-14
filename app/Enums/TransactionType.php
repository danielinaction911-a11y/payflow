<?php

namespace App\Enums;

enum TransactionType: string
{
    case Deposit = 'deposit';
    case Withdrawal = 'withdrawal';
    case Trade = 'trade';
    case Investment = 'investment';
    case Profit = 'profit';
    case TransferIn = 'transfer_in';
    case Exchange = 'exchange';
    case TransferOut = 'transfer_out';
    case Bonus = 'bonus';
    case Staking = 'staking';
    case ReferralCredit = 'referral_credit';
    case Refund = 'refund';
    case Chargeback = 'chargeback';
    case Fee = 'fee';
    case Other = 'other';

    public function label(): string
    {
        return ucwords(str_replace(['_', '-'], ' ', $this->value));
    }

    /** Whether this type adds to or subtracts from wallet balance */
    public function isCredit(): bool
    {
        return match ($this) {
            self::Deposit, self::Profit, self::Bonus, self::Staking,
            self::ReferralCredit, self::Refund, self::TransferIn => true,
            self::Withdrawal, self::Trade, self::Investment, self::TransferOut,
            self::Chargeback, self::Fee => false,
            self::Other => true, // adjust based on how "Other" should behave
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Deposit => 'badge-success text-success',
            self::Withdrawal => 'badge-danger text-danger',
            self::Trade => 'badge-info text-info',
            self::Investment => 'badge-info text-info',
            self::Profit => 'badge-info text-info',
            self::TransferIn, self::TransferOut => 'badge-info text-info',
            self::Bonus => 'badge-success text-success',
            self::Staking => 'badge-info text-info',
            self::ReferralCredit => 'badge-info text-info',
            self::Refund => 'badge-info text-info',
            self::Chargeback => 'badge-danger text-danger',
            self::Fee => 'badge-danger text-danger',
            self::Other => 'badge-info text-info',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Deposit => 'fa-solid fa-arrow-down',
            self::Withdrawal => 'fa-solid fa-arrow-up',
            self::Trade => 'fa-solid fa-exchange-alt',
            self::Investment => 'fa-solid fa-chart-line',
            self::Profit => 'fa-solid fa-coins',
            self::TransferIn, self::TransferOut, self::Exchange => 'fa-solid fa-exchange-alt',
            self::Bonus => 'fa-solid fa-gift',
            self::Staking => 'fa-solid fa-coins',
            self::ReferralCredit => 'fa-solid fa-users',
            self::Refund => 'fa-solid fa-undo',
            self::Chargeback => 'fa-solid fa-ban',
            self::Fee => 'fa-solid fa-money-bill-wave',
            self::Other => 'fa-solid fa-ellipsis-h',
        };
    }
}
