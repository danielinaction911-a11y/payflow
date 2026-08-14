<?php

namespace App\Filament\Admin\Resources;

use App\Models\Currency;
use App\Traits\HandlesFileUploads;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Filament\Admin\Resources\CurrencyResource\Pages;
use Filament\Notifications\Notification;

class CurrencyResource extends Resource
{
    protected static ?string $model = Currency::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Payments';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Currencies';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Currency details')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('e.g. Bitcoin, US Dollar'),

                    Forms\Components\TextInput::make('code')
                        ->required()
                        ->maxLength(20)
                        ->unique(ignoreRecord: true)
                        ->placeholder('e.g. BTC, USD')
                        ->formatStateUsing(fn($state) => $state ? strtoupper($state) : $state)
                        ->dehydrateStateUsing(fn($state) => strtoupper($state)),

                    Forms\Components\TextInput::make('symbol')
                        ->required()
                        ->maxLength(10)
                        ->placeholder('e.g. ₿, $'),

                    Forms\Components\Select::make('type')
                        ->options([
                            'fiat' => 'Fiat',
                            'crypto' => 'Crypto',
                        ])
                        ->default('crypto')
                        ->required()
                        ->live(),

                    Forms\Components\TextInput::make('network')
                        ->maxLength(100)
                        ->placeholder('e.g. TRC20, ERC20, BEP20')
                        ->visible(fn(Forms\Get $get) => $get('type') === 'crypto'),

                    Forms\Components\TextInput::make('coingecko_id')
                        ->label('CoinGecko ID')
                        ->maxLength(100)
                        ->placeholder('e.g. bitcoin, tether')
                        ->helperText('Used to fetch live price data from CoinGecko.')
                        ->visible(fn(Forms\Get $get) => $get('type') === 'crypto'),

                    Forms\Components\FileUpload::make('icon')
                        ->label('Icon')
                        ->image()
                        ->fetchFileInformation(false)
                        ->getUploadedFileUsing(function ($component, $file, $storedFileNames) {
                            return [
                                'name' => basename($file),
                                'size' => 0,
                                'type' => null,
                                'url' => $file ? asset($file) : null,
                            ];
                        })
                        ->saveUploadedFileUsing(function ($file, Forms\Get $get) {
                            $prefix = Str::slug($get('code') ?: $get('name') ?: Str::random(8)) . '_' . time();
                            $iconState = $get('icon');
                            $oldPath = null;

                            if (is_string($iconState)) {
                                $oldPath = $iconState;
                            } elseif (is_array($iconState) && isset($iconState['url'])) {
                                $urlPath = parse_url($iconState['url'], PHP_URL_PATH) ?: '';
                                $oldPath = ltrim($urlPath, '/');
                            }

                            $uploader = new class {
                                use HandlesFileUploads;
                            };

                            return $uploader->uploadFile($file, 'images/currency', $oldPath, $prefix);
                        })
                        ->imagePreviewHeight('80')
                        ->columnSpanFull(),
                ])
                ->columns(3),

            Forms\Components\Section::make('Permissions')
                ->schema([
                    Forms\Components\Toggle::make('status')
                        ->label('Active')
                        ->default(true),

                    Forms\Components\Toggle::make('allow_deposit')
                        ->label('Allow deposit')
                        ->default(true),

                    Forms\Components\Toggle::make('allow_withdrawal')
                        ->label('Allow withdrawal')
                        ->default(true),
                ])
                ->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Currency::query()->latest())
            ->columns([
                Tables\Columns\ImageColumn::make('icon')
                    ->label('')
                    ->getStateUsing(fn($record) => $record->icon ? asset($record->icon) : null)
                    ->defaultImageUrl(fn($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->code ?? 'C') . '&background=random&color=fff')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('code')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('symbol'),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn(string $state) => $state === 'crypto' ? 'warning' : 'info'),

                Tables\Columns\TextColumn::make('network')
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Active')
                    ->boolean(),

                Tables\Columns\IconColumn::make('allow_deposit')
                    ->label('Deposit')
                    ->boolean(),

                Tables\Columns\IconColumn::make('allow_withdrawal')
                    ->label('Withdrawal')
                    ->boolean(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(['fiat' => 'Fiat', 'crypto' => 'Crypto']),

                Tables\Filters\TernaryFilter::make('status')
                    ->label('Active'),

                Tables\Filters\TernaryFilter::make('allow_deposit')
                    ->label('Deposit allowed'),

                Tables\Filters\TernaryFilter::make('allow_withdrawal')
                    ->label('Withdrawal allowed'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make()
                    ->action(function (Currency $record) {
                        if (static::hasRelatedRecords($record)) {
                            Notification::make()
                                ->danger()
                                ->title('Cannot delete this currency')
                                ->body(static::relatedRecordsMessage($record))
                                ->send();

                            return;
                        }

                        $record->delete();

                        Notification::make()
                            ->success()
                            ->title('Currency deleted')
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function (\Illuminate\Support\Collection $records) {
                            $blocked = $records->filter(fn(Currency $record) => static::hasRelatedRecords($record));
                            $deletable = $records->reject(fn(Currency $record) => static::hasRelatedRecords($record));

                            $deletable->each->delete();

                            if ($blocked->isNotEmpty()) {
                                Notification::make()
                                    ->warning()
                                    ->title('Some currencies were skipped')
                                    ->body($blocked->pluck('name')->implode(', ') . ' could not be deleted because they have related records.')
                                    ->send();
                            } elseif ($deletable->isNotEmpty()) {
                                Notification::make()
                                    ->success()
                                    ->title('Currencies deleted')
                                    ->send();
                            }
                        }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    protected static function hasRelatedRecords(Currency $currency): bool
    {
        return $currency->wallets()->exists()
            || $currency->deposits()->exists()
            || $currency->withdrawals()->exists()
            || $currency->baseTradingPairs()->exists()
            || $currency->quoteTradingPairs()->exists();
    }

    protected static function relatedRecordsMessage(Currency $currency): string
    {
        $parts = [];

        if ($currency->wallets()->exists()) {
            $parts[] = 'wallets';
        }
        if ($currency->deposits()->exists()) {
            $parts[] = 'deposits';
        }
        if ($currency->withdrawals()->exists()) {
            $parts[] = 'withdrawals';
        }
        if ($currency->baseTradingPairs()->exists() || $currency->quoteTradingPairs()->exists()) {
            $parts[] = 'trading pairs';
        }

        return 'This currency has existing ' . implode(', ', $parts) . '. Deactivate it instead of deleting.';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCurrencies::route('/'),
            'create' => Pages\CreateCurrency::route('/create'),
            'edit' => Pages\EditCurrency::route('/{record}/edit'),
        ];
    }
}
