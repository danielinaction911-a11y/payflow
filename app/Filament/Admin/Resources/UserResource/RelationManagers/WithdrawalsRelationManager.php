<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class WithdrawalsRelationManager extends RelationManager
{
    protected static string $relationship = 'withdrawals';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('transaction_id')
            ->columns([
                Tables\Columns\TextColumn::make('method')->badge(),
                Tables\Columns\TextColumn::make('amount')->formatStateUsing(fn ($record) => smart_money($record->amount, $record->currency)),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn ($state) => match ($state->value ?? $state) {
                    'approved' => 'success', 'pending' => 'warning', 'rejected' => 'danger', default => 'gray',
                }),
                Tables\Columns\TextColumn::make('created_at')->dateTime('M j, g:i A')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([])
            ->actions([Tables\Actions\ViewAction::make()->url(fn ($record) => route('filament.admin.resources.withdrawals.view', $record))])
            ->bulkActions([]);
    }
}