<?php

namespace App\Filament\Admin\Resources;

use App\Enums\TransaferStatus;
use App\Models\Transfer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Admin\Resources\TransferResource\Pages;

class TransferResource extends Resource
{
    protected static ?string $model = Transfer::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Transfer::query()->with(['sender', 'recipient'])->latest())
            ->columns([
                Tables\Columns\TextColumn::make('sender.name')
                    ->label('Sender')
                    ->formatStateUsing(fn ($state, $record) => $record->sender?->name ?? '@' . $record->sender?->username)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('recipient.name')
                    ->label('Recipient')
                    ->formatStateUsing(fn ($state, $record) => $record->recipient?->name ?? '@' . $record->recipient?->username)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('reference')
                    ->label('Reference')
                    ->copyable()
                    ->fontFamily('mono'),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->formatStateUsing(fn ($record) => money_format($record->amount))
                    ->weight('bold')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match ($state->value ?? $state) {
                        'completed' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'completed' => 'Completed',
                        'pending' => 'Pending',
                        'failed' => 'Failed',
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
            'index' => Pages\ListTransfers::route('/'),
            'view' => Pages\ViewTransfer::route('/{record}'),
        ];
    }
}
