<?php

namespace App\Filament\Admin\Resources;

use App\Models\WithdrawGateway;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Filament\Admin\Resources\WithdrawGatewayResource\Pages;

class WithdrawGatewayResource extends Resource
{
    protected static ?string $model = WithdrawGateway::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-tray';
    protected static ?string $navigationGroup = 'Payments';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = 'Withdrawal Gateways';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Gateway details')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (string $context, $state, Forms\Set $set) =>
                            $context === 'create' ? $set('code', Str::slug($state, '_')) : null),

                    Forms\Components\TextInput::make('code')
                        ->required()
                        ->maxLength(100)
                        ->unique(ignoreRecord: true)
                        ->helperText('Unique identifier, e.g. "bank_transfer", "usdt_trc20".'),

                    Forms\Components\TextInput::make('currency')
                        ->required()
                        ->maxLength(20)
                        ->placeholder('e.g. USD, USDT, BTC'),

                    Forms\Components\Select::make('status')
                        ->options([1 => 'Active', 0 => 'Inactive'])
                        ->default(1)
                        ->required(),

                    Forms\Components\TextInput::make('min_amount')
                        ->numeric()
                        ->required()
                        ->default(0),

                    Forms\Components\TextInput::make('max_amount')
                        ->numeric()
                        ->required()
                        ->default(0),

                    Forms\Components\TextInput::make('fixed_fee')
                        ->numeric()
                        ->default(0),

                    Forms\Components\TextInput::make('percent_fee')
                        ->numeric()
                        ->default(0)
                        ->suffix('%'),

                    Forms\Components\FileUpload::make('logo')
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
                            $prefix = ($get('code') ?: Str::random(10)) . '_' . time();
                            $oldPath = $get('logo');

                            if (is_array($oldPath)) {
                                if (isset($oldPath['name']) && is_string($oldPath['name'])) {
                                    $oldPath = $oldPath['name'];
                                } elseif (isset($oldPath[0]) && is_array($oldPath[0]) && isset($oldPath[0]['name'])) {
                                    $oldPath = $oldPath[0]['name'];
                                } elseif (isset($oldPath[0]) && is_string($oldPath[0])) {
                                    $oldPath = $oldPath[0];
                                } else {
                                    $oldPath = null;
                                }
                            }

                            if ($oldPath instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                                $oldPath = null;
                            }

                            $uploader = new class {
                                use \App\Traits\HandlesFileUploads;
                            };

                            return $uploader->uploadFile($file, 'images/gateway', $oldPath, $prefix);
                        })
                        ->imagePreviewHeight('80')
                        ->columnSpanFull(),
                ])
                ->columns(3),

            Forms\Components\Section::make('Required fields')
                ->description('Fields the user must fill in when withdrawing via this gateway.')
                ->schema([
                    Forms\Components\Repeater::make('details')
                        ->label('')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->placeholder('e.g. wallet_address'),

                            Forms\Components\TextInput::make('label')
                                ->required()
                                ->placeholder('e.g. USDT Wallet Address'),

                            Forms\Components\Select::make('type')
                                ->options([
                                    'text' => 'Text',
                                    'textarea' => 'Textarea',
                                    'number' => 'Number',
                                    'select' => 'Select',
                                ])
                                ->default('text')
                                ->required(),

                            Forms\Components\Toggle::make('required')
                                ->default(true),
                        ])
                        ->columns(4)
                        ->itemLabel(fn (array $state) => $state['label'] ?? 'New field')
                        ->reorderable()
                        ->collapsible()
                        ->addActionLabel('Add field')
                        ->defaultItems(0),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(WithdrawGateway::query()->latest())
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label('')
                    ->getStateUsing(fn ($record) => $record->logo ? asset($record->logo) : null)
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name ?? 'G') . '&background=random&color=fff')
                    ->square(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('code')
                    ->searchable(),

                Tables\Columns\TextColumn::make('currency')
                    ->badge(),

                Tables\Columns\TextColumn::make('min_amount')
                    ->label('Min')
                    ->money(fn ($record) => $record->currency, divideBy: 1),

                Tables\Columns\TextColumn::make('max_amount')
                    ->label('Max')
                    ->money(fn ($record) => $record->currency, divideBy: 1),

                Tables\Columns\TextColumn::make('fixed_fee')
                    ->label('Fixed fee'),

                Tables\Columns\TextColumn::make('percent_fee')
                    ->label('% fee')
                    ->suffix('%'),

                Tables\Columns\IconColumn::make('status')
                    ->boolean(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('status'),

                Tables\Filters\SelectFilter::make('currency')
                    ->options(fn () => WithdrawGateway::query()->distinct()->pluck('currency', 'currency')->toArray()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make()
                    ->action(function (WithdrawGateway $record) {
                        if ($record->withdraws()->exists()) {
                            \Filament\Notifications\Notification::make()
                                ->danger()
                                ->title('Cannot delete this gateway')
                                ->body('This gateway has existing withdrawal records. Deactivate it instead.')
                                ->send();

                            return;
                        }

                        $record->delete();

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Gateway deleted')
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
            'index' => Pages\ListWithdrawGateways::route('/'),
            'create' => Pages\CreateWithdrawGateway::route('/create'),
            'edit' => Pages\EditWithdrawGateway::route('/{record}/edit'),
        ];
    }
}