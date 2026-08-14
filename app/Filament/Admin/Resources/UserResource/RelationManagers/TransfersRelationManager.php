<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TransfersRelationManager extends RelationManager
{
    protected static string $relationship = 'sentTransfers';
    protected static ?string $title = 'Transfers sent';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('reference')
            ->columns([
                Tables\Columns\TextColumn::make('recipient.name')->label('To'),
                Tables\Columns\TextColumn::make('amount')->formatStateUsing(fn ($state) => smart_money($state)),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('M j, g:i A')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([])
            ->bulkActions([]);
    }
}