<?php

namespace App\Filament\Admin\Resources;

use App\Enums\AdminStatus;
use App\Models\Admin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Filament\Admin\Resources\AdminResource\Pages;

class AdminResource extends Resource
{
    protected static ?string $model = Admin::class;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationGroup = 'Access Control';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Admins';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Admin details')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),

                    Forms\Components\Select::make('status')
                        ->options(collect(AdminStatus::cases())
                            ->mapWithKeys(fn ($case) => [$case->value => Str::headline($case->name)])
                            ->toArray())
                        ->default(AdminStatus::Active->value)
                        ->required(),

                    Forms\Components\TextInput::make('password')
                        ->password()
                        ->revealable()
                        ->required(fn (string $context) => $context === 'create')
                        ->minLength(8)
                        ->dehydrated(fn ($state) => filled($state))
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->helperText(fn (string $context) => $context === 'edit'
                            ? 'Leave blank to keep the current password.'
                            : null),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Admin::query()->latest())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (AdminStatus $state) => match ($state) {
                        AdminStatus::Active => 'success',
                        default => 'danger',
                    })
                    ->formatStateUsing(fn (AdminStatus $state) => Str::headline($state->name)),

                Tables\Columns\TextColumn::make('last_login_at')
                    ->label('Last login')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->placeholder('Never'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(AdminStatus::cases())
                        ->mapWithKeys(fn ($case) => [$case->value => Str::headline($case->name)])
                        ->toArray()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->disabled(fn (Admin $record): bool => auth('admin')->check() && auth('admin')->id() === $record->id)
                    ->tooltip(fn (Admin $record): ?string => (auth('admin')->check() && auth('admin')->id() === $record->id)
                        ? 'You cannot delete the currently logged-in admin.'
                        : null),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function (\Illuminate\Support\Collection $records) {
                            $blocked = $records->filter(fn (Admin $record) => auth('admin')->check() && auth('admin')->id() === $record->id);
                            $deletable = $records->reject(fn (Admin $record) => auth('admin')->check() && auth('admin')->id() === $record->id);

                            $deletable->each->delete();

                            if ($blocked->isNotEmpty() && $deletable->isNotEmpty()) {
                                \Filament\Notifications\Notification::make()
                                    ->warning()
                                    ->title('Some admins were skipped')
                                    ->body('The currently signed-in admin was skipped and not deleted.')
                                    ->send();
                            } elseif ($blocked->isNotEmpty()) {
                                \Filament\Notifications\Notification::make()
                                    ->warning()
                                    ->title('No admins deleted')
                                    ->body('The currently signed-in admin was skipped and not deleted.')
                                    ->send();
                            } elseif ($deletable->isNotEmpty()) {
                                \Filament\Notifications\Notification::make()
                                    ->success()
                                    ->title('Admins deleted')
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
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
}