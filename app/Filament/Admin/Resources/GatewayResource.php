<?php

namespace App\Filament\Admin\Resources;

use App\Models\Gateway;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Filament\Admin\Resources\GatewayResource\Pages;
use App\Filament\Admin\Resources\GatewayResource\GatewayPresets;

class GatewayResource extends Resource
{
    protected static ?string $model = Gateway::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Payments';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Deposit Gateways';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('preset')
                ->label('Quick start (optional)')
                ->options(GatewayPresets::options())
                ->default('custom')
                ->dehydrated(false)
                ->live()
                ->visible(fn(string $context) => $context === 'create')
                ->helperText('Pick a known gateway to prefill sensible defaults, then adjust as needed.')
                ->afterStateUpdated(function ($state, Forms\Set $set) {
                    $preset = GatewayPresets::get($state);

                    if (! $preset) {
                        return;
                    }

                    $set('name', $preset['name']);
                    $set('code', $preset['code']);
                    $set('type', $preset['type']);
                    $set('currency', $preset['currency']);
                    $set('min_amount', $preset['min_amount']);
                    $set('max_amount', $preset['max_amount']);
                    $set('percent_fee', $preset['percent_fee'] ?? 0);
                    $set('fixed_fee', 0);
                    $set('credentials', $preset['credentials']);
                    $set('payment_fields', $preset['payment_fields']);
                    $set('instructions_title', $preset['instructions_title']);
                    $set('instructions_steps', $preset['instructions_steps']);
                    $set('instructions_details', $preset['instructions_details']);
                })
                ->columnSpanFull(),

            Forms\Components\Section::make('Gateway details')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn(string $context, $state, Forms\Set $set) =>
                        $context === 'create' ? $set('code', Str::slug($state, '_')) : null),

                    Forms\Components\TextInput::make('code')
                        ->required()
                        ->maxLength(100)
                        ->unique(ignoreRecord: true)
                        ->helperText('Unique identifier, e.g. "paystack", "bank".'),

                    Forms\Components\Select::make('type')
                        ->options([
                            'auto' => 'Automatic (API-based)',
                            'manual' => 'Manual (admin approval)',
                        ])
                        ->required()
                        ->live(),

                    Forms\Components\Select::make('status')
                        ->options([1 => 'Active', 0 => 'Inactive'])
                        ->default(1)
                        ->required(),

                    Forms\Components\TextInput::make('currency')
                        ->required()
                        ->maxLength(10)
                        ->default('USD'),

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
                            $oldPath = $get('logo') ?? null;

                            $uploader = new class {
                                use \App\Traits\HandlesFileUploads;
                            };

                            return $uploader->uploadFile($file, 'images/gateway', $oldPath, $prefix);
                        })
                        ->imagePreviewHeight('80')
                        ->columnSpanFull(),
                ])
                ->columns(3),

            Forms\Components\Section::make('API credentials')
                ->description('Used for automatic gateways (e.g. API keys).')
                ->visible(fn(Forms\Get $get) => $get('type') === 'auto')
                ->schema([
                    Forms\Components\Repeater::make('credentials')
                        ->label('')
                        ->schema([
                            Forms\Components\TextInput::make('key')
                                ->required()
                                ->placeholder('e.g. public_key'),

                            Forms\Components\TextInput::make('value')
                                ->required()
                                ->password()
                                ->revealable()
                                ->placeholder('e.g. pk_test_xxx'),
                        ])
                        ->columns(2)
                        ->addActionLabel('Add credential')
                        ->defaultItems(0),
                ]),

            Forms\Components\Section::make('User-submitted fields')
                ->description('Fields the user must fill/upload for manual gateways.')
                ->visible(fn(Forms\Get $get) => $get('type') === 'manual')
                ->schema([
                    Forms\Components\Repeater::make('payment_fields')
                        ->label('')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->placeholder('e.g. transaction_id'),

                            Forms\Components\TextInput::make('label')
                                ->required()
                                ->placeholder('e.g. Transaction Reference'),

                            Forms\Components\Select::make('type')
                                ->options([
                                    'text' => 'Text',
                                    'textarea' => 'Textarea',
                                    'file' => 'File upload',
                                ])
                                ->default('text')
                                ->required(),

                            Forms\Components\Toggle::make('required')
                                ->default(true),
                        ])
                        ->columns(4)
                        ->itemLabel(fn(array $state) => $state['label'] ?? 'New field')
                        ->reorderable()
                        ->collapsible()
                        ->addActionLabel('Add field')
                        ->defaultItems(0),
                ]),

            Forms\Components\Section::make('Instructions')
                ->description('Shown to the user when paying with this gateway.')
                ->schema([
                    Forms\Components\TextInput::make('instructions_title')
                        ->label('Title')
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\Repeater::make('instructions_steps')
                        ->label('Steps')
                        ->schema([
                            Forms\Components\TextInput::make('step')
                                ->required()
                                ->placeholder('e.g. Upload proof of payment'),
                        ])
                        ->reorderable()
                        ->addActionLabel('Add step')
                        ->defaultItems(0)
                        ->columnSpanFull(),

                    Forms\Components\Repeater::make('instructions_details')
                        ->label('Details (key/value shown to user)')
                        ->schema([
                            Forms\Components\TextInput::make('key')
                                ->required()
                                ->placeholder('e.g. account_number'),

                            Forms\Components\TextInput::make('value')
                                ->required()
                                ->placeholder('e.g. 1234567890'),
                        ])
                        ->columns(2)
                        ->addActionLabel('Add detail')
                        ->defaultItems(0)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Gateway::query()->latest())
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label('')
                    ->getStateUsing(fn($record) => $record->logo
                        ? asset($record->logo)
                        : null)
                    ->defaultImageUrl(fn() => asset('images/default.png'))
                    ->square(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('code')
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn(string $state) => $state === 'auto' ? 'success' : 'info'),

                Tables\Columns\IconColumn::make('status')
                    ->boolean(),

                Tables\Columns\TextColumn::make('currency'),

                Tables\Columns\TextColumn::make('min_amount')
                    ->label('Min')
                    ->money(fn($record) => $record->currency, divideBy: 1),

                Tables\Columns\TextColumn::make('max_amount')
                    ->label('Max')
                    ->money(fn($record) => $record->currency, divideBy: 1),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(['auto' => 'Automatic', 'manual' => 'Manual']),

                Tables\Filters\TernaryFilter::make('status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListGateways::route('/'),
            'create' => Pages\CreateGateway::route('/create'),
            'edit' => Pages\EditGateway::route('/{record}/edit'),
        ];
    }

    public static function packInstructions(array $data): array
    {
        $data['credentials'] = collect($data['credentials'] ?? [])
            ->filter(fn($row) => filled($row['key'] ?? null))
            ->mapWithKeys(fn($row) => [$row['key'] => $row['value']])
            ->toArray() ?: null;

        $data['instructions'] = [
            'title' => $data['instructions_title'] ?? null,
            'steps' => collect($data['instructions_steps'] ?? [])
                ->pluck('step')
                ->filter()
                ->values()
                ->toArray(),
            'details' => collect($data['instructions_details'] ?? [])
                ->filter(fn($row) => filled($row['key'] ?? null))
                ->mapWithKeys(fn($row) => [$row['key'] => $row['value']])
                ->toArray(),
        ];

        unset($data['instructions_title'], $data['instructions_steps'], $data['instructions_details']);

        return $data;
    }

    public static function unpackInstructions(array $data): array
    {
        $instructions = $data['instructions'] ?? [];

        $data['instructions_title'] = $instructions['title'] ?? null;
        $data['instructions_steps'] = collect($instructions['steps'] ?? [])
            ->map(fn($step) => ['step' => $step])
            ->toArray();
        $data['instructions_details'] = collect($instructions['details'] ?? [])
            ->map(fn($value, $key) => ['key' => $key, 'value' => $value])
            ->values()
            ->toArray();

        $data['credentials'] = collect($data['credentials'] ?? [])
            ->map(fn($value, $key) => ['key' => $key, 'value' => $value])
            ->values()
            ->toArray();

        return $data;
    }
}
