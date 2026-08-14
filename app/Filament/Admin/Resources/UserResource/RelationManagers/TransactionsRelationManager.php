<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use App\Enums\TransactionDirection;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('reference')
            ->columns([
                Tables\Columns\TextColumn::make('reference')->copyable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (TransactionType $state) => Str::headline($state->value))
                    ->color(fn (TransactionType $state) => match ($state) {
                        TransactionType::Deposit, TransactionType::Bonus, TransactionType::ReferralCredit, TransactionType::Refund => 'success',
                        TransactionType::Withdrawal, TransactionType::Chargeback, TransactionType::Fee => 'danger',
                        TransactionType::Trade, TransactionType::Exchange => 'info',
                        TransactionType::Investment, TransactionType::Staking => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('amount')
                    ->formatStateUsing(fn ($record) => ($record->direction->value === 'credit' ? '+' : '-') . smart_money($record->amount, $record->currency))
                    ->color(fn ($record) => $record->direction->value === 'credit' ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (TransactionStatus $state) => Str::headline($state->value))
                    ->color(fn (TransactionStatus $state) => match ($state) {
                        TransactionStatus::Completed => 'success',
                        TransactionStatus::Pending => 'warning',
                        TransactionStatus::Failed => 'danger',
                        TransactionStatus::Reversed => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('created_at')->dateTime('M j, g:i A')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([])
            ->actions([Tables\Actions\ViewAction::make()->url(fn ($record) => route('filament.admin.resources.transactions.view', $record))])
            ->bulkActions([]);
    }
}