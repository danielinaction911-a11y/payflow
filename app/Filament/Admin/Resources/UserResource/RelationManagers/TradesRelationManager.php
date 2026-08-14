<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use App\Enums\TradeDirection;
use App\Enums\TradeStatus;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TradesRelationManager extends RelationManager
{
    protected static string $relationship = 'trades';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('tradingPair.symbol')->label('Pair'),
                Tables\Columns\TextColumn::make('side')
                    ->badge()
                    ->formatStateUsing(fn (TradeDirection $state) => $state->label())
                    ->color(fn (TradeDirection $state) => $state === TradeDirection::Buy ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('amount')->formatStateUsing(fn ($state) => smart_amount($state)),
                Tables\Columns\TextColumn::make('total')->formatStateUsing(fn ($state) => smart_money($state)),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (TradeStatus $state) => $state->label())
                    ->color(fn (TradeStatus $state) => $state->color()),
                Tables\Columns\TextColumn::make('created_at')->dateTime('M j, g:i A')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([])
            ->bulkActions([]);
    }
}