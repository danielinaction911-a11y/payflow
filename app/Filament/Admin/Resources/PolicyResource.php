<?php

namespace App\Filament\Admin\Resources;

use App\Models\Policy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Filament\Admin\Resources\PolicyResource\Pages;

class PolicyResource extends Resource
{
    protected static ?string $model = Policy::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationLabel = 'Policies';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Policy details')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $context, $state, Forms\Set $set) {
                            // only auto-generate slug on create, don't overwrite existing slugs on edit
                            if ($context === 'create') {
                                $set('slug', Str::slug($state));
                            }
                        }),

                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->helperText('Auto-generated from title, but you can override it.'),

                    Forms\Components\Select::make('type')
                        ->options([
                            'terms' => 'Terms & Conditions',
                            'privacy' => 'Privacy Policy',
                            'refund' => 'Refund Policy',
                            'aml' => 'AML Policy',
                            'cookie' => 'Cookie Policy',
                            'other' => 'Other',
                        ])
                        ->searchable()
                        ->required(),

                    Forms\Components\TextInput::make('version')
                        ->maxLength(50)
                        ->placeholder('e.g. 1.0, 2024-v2'),

                    Forms\Components\DateTimePicker::make('effective_date')
                        ->label('Effective date')
                        ->native(false),

                    Forms\Components\TextInput::make('sort_order')
                        ->numeric()
                        ->default(0),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true)
                        ->helperText('Only active policies are visible to users.'),
                ])
                ->columns(2),

            Forms\Components\Section::make('Content')
                ->schema([
                    Forms\Components\RichEditor::make('content')
                        ->label('')
                        ->required()
                        ->fileAttachmentsDirectory('policy-attachments')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Policy::query()->latest())
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Str::headline($state))
                    ->searchable(),

                Tables\Columns\TextColumn::make('version')
                    ->placeholder('—'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),

                Tables\Columns\TextColumn::make('effective_date')
                    ->label('Effective')
                    ->date('M j, Y')
                    ->sortable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'terms' => 'Terms & Conditions',
                        'privacy' => 'Privacy Policy',
                        'refund' => 'Refund Policy',
                        'aml' => 'AML Policy',
                        'cookie' => 'Cookie Policy',
                        'other' => 'Other',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
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
            ->reorderable('sort_order')
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPolicies::route('/'),
            'create' => Pages\CreatePolicy::route('/create'),
            'edit' => Pages\EditPolicy::route('/{record}/edit'),
        ];
    }
}