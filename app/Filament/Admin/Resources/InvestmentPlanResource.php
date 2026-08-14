<?php

namespace App\Filament\Admin\Resources;

use App\Enums\RoiType;
use App\Models\InvestmentPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Filament\Admin\Resources\InvestmentPlanResource\Pages;

class InvestmentPlanResource extends Resource
{
    protected static ?string $model = InvestmentPlan::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Investments';
    protected static ?int $navigationSort = 0;
    protected static ?string $navigationLabel = 'Investment Plans';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Plan details')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (string $context, $state, Forms\Set $set) =>
                            $context === 'create' ? $set('slug', Str::slug($state)) : null),

                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->helperText('Auto-generated from name, but you can override it.'),

                    Forms\Components\Select::make('status')
                        ->options([
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                        ])
                        ->default('active')
                        ->required(),

                    Forms\Components\Textarea::make('description')
                        ->required()
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->columns(3),

            Forms\Components\Section::make('Investment range & returns')
                ->schema([
                    Forms\Components\TextInput::make('min_amount')
                        ->label('Minimum amount')
                        ->numeric()
                        ->required()
                        ->prefix('$'),

                    Forms\Components\TextInput::make('max_amount')
                        ->label('Maximum amount')
                        ->numeric()
                        ->required()
                        ->prefix('$')
                        ->gt('min_amount')
                        ->validationMessages([
                            'gt' => 'The maximum amount must be greater than the minimum amount.',
                        ]),

                    Forms\Components\TextInput::make('roi_percentage')
                        ->label('ROI percentage')
                        ->numeric()
                        ->required()
                        ->suffix('%'),

                    Forms\Components\Select::make('roi_type')
                        ->options(collect(RoiType::cases())
                            ->mapWithKeys(fn ($case) => [$case->value => Str::headline($case->name)])
                            ->toArray())
                        ->required(),

                    Forms\Components\TextInput::make('duration_days')
                        ->label('Duration (days)')
                        ->numeric()
                        ->required()
                        ->suffix('days'),
                ])
                ->columns(3),

            Forms\Components\Section::make('Options')
                ->schema([
                    Forms\Components\Toggle::make('is_popular')
                        ->label('Mark as popular')
                        ->helperText('Highlights this plan as a recommended/featured option.'),

                    Forms\Components\Toggle::make('capital_back')
                        ->label('Capital returned at end of plan')
                        ->default(true),
                ])
                ->columns(2),

            Forms\Components\Section::make('Features')
                ->description('Listed as bullet points on the plan card.')
                ->schema([
                    Forms\Components\TagsInput::make('features')
                        ->label('')
                        ->placeholder('Type a feature and press Enter')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(InvestmentPlan::query()->latest())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('min_amount')
                    ->label('Min')
                    ->money('USD'),

                Tables\Columns\TextColumn::make('max_amount')
                    ->label('Max')
                    ->money('USD'),

                Tables\Columns\TextColumn::make('roi_percentage')
                    ->label('ROI')
                    ->suffix('%')
                    ->sortable(),

                Tables\Columns\TextColumn::make('roi_type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => Str::headline($state->value ?? $state)),

                Tables\Columns\TextColumn::make('duration_days')
                    ->label('Duration')
                    ->formatStateUsing(fn ($state) => $state . ' days')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_popular')
                    ->label('Popular')
                    ->boolean(),

                Tables\Columns\IconColumn::make('capital_back')
                    ->label('Capital back')
                    ->boolean(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => $state === 'active' ? 'success' : 'gray'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roi_type')
                    ->options(collect(RoiType::cases())
                        ->mapWithKeys(fn ($case) => [$case->value => Str::headline($case->name)])
                        ->toArray()),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),

                Tables\Filters\TernaryFilter::make('is_popular')
                    ->label('Popular'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->disabled(fn (InvestmentPlan $record): bool => $record->investments()->exists())
                    ->tooltip(fn (InvestmentPlan $record): ?string => $record->investments()->exists()
                        ? 'Cannot delete this plan because it has existing user investments.'
                        : null),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function (\Illuminate\Support\Collection $records) {
                            $blocked = $records->filter(fn (InvestmentPlan $record) => $record->investments()->exists());
                            $deletable = $records->reject(fn (InvestmentPlan $record) => $record->investments()->exists());

                            $deletable->each->delete();

                            if ($blocked->isNotEmpty() && $deletable->isNotEmpty()) {
                                Notification::make()
                                    ->warning()
                                    ->title('Some plans were skipped')
                                    ->body($blocked->pluck('name')->implode(', ') . ' could not be deleted because they have existing investments.')
                                    ->send();
                            } elseif ($blocked->isNotEmpty()) {
                                Notification::make()
                                    ->warning()
                                    ->title('No plans deleted')
                                    ->body($blocked->pluck('name')->implode(', ') . ' could not be deleted because they have existing investments.')
                                    ->send();
                            } elseif ($deletable->isNotEmpty()) {
                                Notification::make()
                                    ->success()
                                    ->title('Investment plans deleted')
                                    ->send();
                            }
                        }),
                ]),
            ])
            ->defaultSort('min_amount');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvestmentPlans::route('/'),
            'create' => Pages\CreateInvestmentPlan::route('/create'),
            'edit' => Pages\EditInvestmentPlan::route('/{record}/edit'),
        ];
    }
}