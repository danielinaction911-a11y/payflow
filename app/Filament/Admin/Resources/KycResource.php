<?php

namespace App\Filament\Admin\Resources;

use App\Models\Kyc;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Admin\Resources\KycResource\Pages;

class KycResource extends Resource
{
    protected static ?string $model = Kyc::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Compliance';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'KYC Types';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Document type')
                ->schema([
                    Forms\Components\TextInput::make('type')
                        ->label('Type')
                        ->placeholder('e.g. drivers_license, passport, national_id')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Select::make('status')
                        ->options([
                            'enabled' => 'Enabled',
                            'disabled' => 'Disabled',
                        ])
                        ->default('enabled')
                        ->required(),
                ])
                ->columns(2),

            Forms\Components\Section::make('Required fields')
                ->description('Define the fields a user must fill out or upload for this document type.')
                ->schema([
                    Forms\Components\Repeater::make('required_fields')
                        ->label('')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Field name')
                                ->placeholder('e.g. full_name')
                                ->required()
                                ->helperText('Used internally as the field key.')
                                ->columnSpan(1),

                            Forms\Components\TextInput::make('label')
                                ->label('Display label')
                                ->placeholder('e.g. Full Legal Name')
                                ->required()
                                ->columnSpan(1),

                            Forms\Components\Select::make('type')
                                ->label('Field type')
                                ->options([
                                    'text' => 'Text',
                                    'number' => 'Number',
                                    'date' => 'Date',
                                    'select' => 'Select',
                                    'file' => 'File upload',
                                ])
                                ->default('text')
                                ->required()
                                ->columnSpan(1),

                            Forms\Components\Toggle::make('required')
                                ->label('Required')
                                ->default(true)
                                ->columnSpan(1),
                        ])
                        ->columns(4)
                        ->itemLabel(fn(array $state) => $state['label'] ?? $state['name'] ?? 'New field')
                        ->reorderable()
                        ->collapsible()
                        ->addActionLabel('Add field')
                        ->defaultItems(1),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Kyc::query()->latest())
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'enabled' => 'success',
                        'disabled' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('required_fields')
                    ->label('Fields')
                    ->formatStateUsing(function ($state) {
                        if (is_string($state)) {
                            $decoded = json_decode($state, true);
                            $state = is_array($decoded) ? $decoded : $state;
                        }

                        return is_iterable($state) ? count($state) . ' field(s)' : '0 field(s)';
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'enabled' => 'Enabled',
                        'disabled' => 'Disabled',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->disabled(fn (Kyc $record): bool => $record->documents()->exists())
                    ->tooltip(fn (Kyc $record): ?string => $record->documents()->exists()
                        ? 'Cannot delete this KYC type because there are existing documents.'
                        : null),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function (\Illuminate\Support\Collection $records) {
                            $blocked = $records->filter(fn (Kyc $record) => $record->documents()->exists());
                            $deletable = $records->reject(fn (Kyc $record) => $record->documents()->exists());

                            $deletable->each->delete();

                            if ($blocked->isNotEmpty() && $deletable->isNotEmpty()) {
                                Notification::make()
                                    ->warning()
                                    ->title('Some KYC types were skipped')
                                    ->body($blocked->pluck('type')->implode(', ') . ' could not be deleted because they have existing documents.')
                                    ->send();
                            } elseif ($blocked->isNotEmpty()) {
                                Notification::make()
                                    ->warning()
                                    ->title('No KYC types deleted')
                                    ->body($blocked->pluck('type')->implode(', ') . ' could not be deleted because they have existing documents.')
                                    ->send();
                            } elseif ($deletable->isNotEmpty()) {
                                Notification::make()
                                    ->success()
                                    ->title('KYC types deleted')
                                    ->send();
                            }
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKycs::route('/'),
            'create' => Pages\CreateKyc::route('/create'),
            'edit' => Pages\EditKyc::route('/{record}/edit'),
        ];
    }
}
