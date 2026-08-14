<?php

namespace App\Filament\Admin\Resources;

use App\Models\Currency;
use App\Models\TradingPair;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Admin\Resources\TradingPairResource\Pages;

class TradingPairResource extends Resource
{
    protected static ?string $model = TradingPair::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';
    protected static ?string $navigationGroup = 'Trading';
    protected static ?int $navigationSort = 0;
    protected static ?string $navigationLabel = 'Trading Pairs';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Pair details')
                ->schema([
                    Forms\Components\Select::make('base_currency_id')
                        ->label('Base currency')
                        ->relationship('baseCurrency', 'code')
                        ->searchable(['code', 'name'])
                        ->preload()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                            static::syncSymbol($set, $get);
                        }),

                    Forms\Components\Select::make('quote_currency_id')
                        ->label('Quote currency')
                        ->relationship('quoteCurrency', 'code')
                        ->searchable(['code', 'name'])
                        ->preload()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                            static::syncSymbol($set, $get);
                        })
                        ->different('base_currency_id')
                        ->validationMessages([
                            'different' => 'Quote currency must be different from the base currency.',
                        ]),

                    Forms\Components\TextInput::make('symbol')
                        ->required()
                        ->maxLength(50)
                        ->unique(ignoreRecord: true)
                        ->helperText('Auto-generated from base + quote currency codes, but you can override it.'),

                    Forms\Components\TextInput::make('current_price')
                        ->numeric()
                        ->required()
                        ->step('0.00000001')
                        ->prefix('$'),

                    Forms\Components\TextInput::make('change_24h_percent')
                        ->label('24h change (%)')
                        ->numeric()
                        ->required()
                        ->step('0.01')
                        ->suffix('%')
                        ->helperText('Use a negative number for a price decrease, e.g. -1.15'),
                ])
                ->columns(2),
        ]);
    }

    protected static function syncSymbol(Forms\Set $set, Forms\Get $get): void
    {
        $base = Currency::find($get('base_currency_id'));
        $quote = Currency::find($get('quote_currency_id'));

        if ($base && $quote) {
            $set('symbol', $base->code . $quote->code);
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(TradingPair::query()->with(['baseCurrency', 'quoteCurrency'])->latest())
            ->columns([
                Tables\Columns\TextColumn::make('symbol')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('baseCurrency.code')
                    ->label('Base'),

                Tables\Columns\TextColumn::make('quoteCurrency.code')
                    ->label('Quote'),

                Tables\Columns\TextColumn::make('current_price')
                    ->label('Price')
                    ->numeric(decimalPlaces: 8)
                    ->sortable(),

                Tables\Columns\TextColumn::make('change_24h_percent')
                    ->label('24h change')
                    ->suffix('%')
                    ->color(fn ($state) => $state >= 0 ? 'success' : 'danger')
                    ->icon(fn ($state) => $state >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('base_currency_id')
                    ->label('Base currency')
                    ->relationship('baseCurrency', 'code'),

                Tables\Filters\SelectFilter::make('quote_currency_id')
                    ->label('Quote currency')
                    ->relationship('quoteCurrency', 'code'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make()
                    ->disabled(fn (TradingPair $record): bool => $record->trades()->exists())
                    ->tooltip(fn (TradingPair $record): ?string => $record->trades()->exists()
                        ? 'Cannot delete this trading pair because it has existing trades.'
                        : null),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function (\Illuminate\Support\Collection $records) {
                            $blocked = $records->filter(fn (TradingPair $record) => $record->trades()->exists());
                            $deletable = $records->reject(fn (TradingPair $record) => $record->trades()->exists());

                            $deletable->each->delete();

                            if ($blocked->isNotEmpty() && $deletable->isNotEmpty()) {
                                Notification::make()
                                    ->warning()
                                    ->title('Some pairs were skipped')
                                    ->body($blocked->pluck('symbol')->implode(', ') . ' could not be deleted because they have existing trades.')
                                    ->send();
                            } elseif ($blocked->isNotEmpty()) {
                                Notification::make()
                                    ->warning()
                                    ->title('No pairs deleted')
                                    ->body($blocked->pluck('symbol')->implode(', ') . ' could not be deleted because they have existing trades.')
                                    ->send();
                            } elseif ($deletable->isNotEmpty()) {
                                Notification::make()
                                    ->success()
                                    ->title('Trading pairs deleted')
                                    ->send();
                            }
                        }),
                ]),
            ])
            ->defaultSort('symbol');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTradingPairs::route('/'),
            'create' => Pages\CreateTradingPair::route('/create'),
            'edit' => Pages\EditTradingPair::route('/{record}/edit'),
        ];
    }
}