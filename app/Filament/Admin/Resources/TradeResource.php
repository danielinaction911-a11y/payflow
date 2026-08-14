<?php

namespace App\Filament\Admin\Resources;

use App\Enums\OrderType;
use App\Enums\TradeDirection;
use App\Enums\TradeStatus;
use App\Models\Trade;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Admin\Resources\TradeResource\Pages;

class TradeResource extends Resource
{
    protected static ?string $model = Trade::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrows-up-down';
    protected static ?string $navigationGroup = 'Trading';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Trades';

    public static function table(Table $table): Table
    {
        return $table
            ->query(Trade::query()->with(['user', 'tradingPair'])->latest())
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tradingPair.symbol')
                    ->label('Pair')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('side')
                    ->badge()
                    ->formatStateUsing(fn (TradeDirection $state) => $state->label())
                    ->color(fn (TradeDirection $state) => $state === TradeDirection::Buy ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('order_type')
                    ->badge()
                    ->formatStateUsing(fn (OrderType $state) => $state->label())
                    ->color('gray'),

                Tables\Columns\TextColumn::make('amount')
                    ->numeric(decimalPlaces: 8)
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->numeric(decimalPlaces: 8)
                    ->sortable(),

                Tables\Columns\TextColumn::make('total')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (TradeStatus $state) => $state->label())
                    ->color(fn (TradeStatus $state) => $state->color()),

                Tables\Columns\TextColumn::make('expires_at')
                    ->dateTime('M j, Y g:i A')
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Placed')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('side')
                    ->options(collect(TradeDirection::cases())
                        ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
                        ->toArray()),

                Tables\Filters\SelectFilter::make('order_type')
                    ->options(collect(OrderType::cases())
                        ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
                        ->toArray()),

                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(TradeStatus::cases())
                        ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
                        ->toArray()),

                Tables\Filters\SelectFilter::make('trading_pair_id')
                    ->label('Pair')
                    ->relationship('tradingPair', 'symbol'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->visible(fn (Trade $record) => $record->status === TradeStatus::Open)
                    ->requiresConfirmation()
                    ->modalDescription('Cancel this open trade? This does not automatically reverse any funds held for it — verify wallet balances separately.')
                    ->action(function (Trade $record) {
                        $record->update(['status' => TradeStatus::Cancelled]);

                        Notification::make()
                            ->success()
                            ->title('Trade cancelled')
                            ->send();
                    }),

                Tables\Actions\Action::make('expire')
                    ->label('Mark expired')
                    ->icon('heroicon-m-clock')
                    ->color('gray')
                    ->visible(fn (Trade $record) => $record->status === TradeStatus::Open)
                    ->requiresConfirmation()
                    ->action(function (Trade $record) {
                        $record->update(['status' => TradeStatus::Expired]);

                        Notification::make()
                            ->success()
                            ->title('Trade marked as expired')
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrades::route('/'),
            'view' => Pages\ViewTrade::route('/{record}'),
        ];
    }
}