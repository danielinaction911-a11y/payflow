<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use App\Models\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class NotificationsRelationManager extends RelationManager
{
    protected static string $relationship = 'notifications';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => Str::headline($state))
                    ->color(fn ($state) => match ($state) {
                        Notification::info => 'primary',
                        Notification::success => 'success',
                        Notification::warning => 'warning',
                        Notification::error => 'danger',
                        default => 'secondary',
                    }),
                Tables\Columns\IconColumn::make('is_read')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('M j, g:i A')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([])
            ->bulkActions([]);
    }
}