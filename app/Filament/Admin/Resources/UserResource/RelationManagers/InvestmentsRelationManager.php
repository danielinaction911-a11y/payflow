<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use App\Enums\InvestmentStatus;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class InvestmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'investments';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('plan.name')->label('Plan'),
                Tables\Columns\TextColumn::make('amount_invested')->formatStateUsing(fn ($state) => smart_money($state)),
                Tables\Columns\TextColumn::make('total_paid_out')->formatStateUsing(fn ($state) => smart_money($state)),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (InvestmentStatus $state) => Str::headline($state->value))
                    ->color(fn (InvestmentStatus $state) => match ($state) {
                        InvestmentStatus::Active => 'success',
                        InvestmentStatus::Completed => 'info',
                        InvestmentStatus::Cancelled => 'danger',
                    }),
                Tables\Columns\TextColumn::make('ends_at')->dateTime('M j, Y')->label('Matures'),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([])
            ->bulkActions([]);
    }
}