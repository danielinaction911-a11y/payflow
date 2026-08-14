<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class LoginActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'loginActivities';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('ip_address')
            ->columns([
                Tables\Columns\TextColumn::make('device'),
                Tables\Columns\TextColumn::make('ip_address'),
                Tables\Columns\TextColumn::make('location')->placeholder('Unknown'),
                Tables\Columns\IconColumn::make('successful')->boolean(),
                Tables\Columns\TextColumn::make('logged_in_at')->dateTime('M j, g:i A')->sortable(),
            ])
            ->defaultSort('logged_in_at', 'desc')
            ->headerActions([])
            ->bulkActions([]);
    }
}