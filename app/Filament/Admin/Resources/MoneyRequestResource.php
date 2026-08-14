<?php

namespace App\Filament\Admin\Resources;

use App\Enums\TransaferStatus;
use App\Filament\Admin\Resources\MoneyRequestResource\Pages;
use App\Models\MoneyRequest;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MoneyRequestResource extends Resource
{
    protected static ?string $model = MoneyRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-hand-raised';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?int $navigationSort = 4;

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(MoneyRequest::query()->with(['requester', 'recipient'])->latest())
            ->columns([
                Tables\Columns\TextColumn::make('requester.name')
                    ->label('Requester')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('recipient.name')
                    ->label('Requested From')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount')
                    ->formatStateUsing(fn ($record) => money_format($record->amount))
                    ->sortable(),

                Tables\Columns\TextColumn::make('message')
                    ->limit(40)
                    ->wrap(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match ($state->value ?? $state) {
                        'pending' => 'warning',
                        'accepted' => 'success',
                        'declined' => 'danger',
                        'expired' => 'secondary',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime('M j, Y g:i A'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'declined' => 'Declined',
                        'expired' => 'Expired',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMoneyRequests::route('/'),
            'view' => Pages\ViewMoneyRequest::route('/{record}'),
        ];
    }
}
