<?php

namespace App\Filament\Admin\Resources;

use App\Models\SecurityAlert;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\User;
use Illuminate\Support\Str;
use App\Filament\Admin\Resources\SecurityAlertResource\Pages;

class SecurityAlertResource extends Resource
{
    protected static ?string $model = SecurityAlert::class;
    protected static ?string $navigationIcon = 'heroicon-o-shield-exclamation';
    protected static ?string $navigationGroup = 'Compliance';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Security Alerts';

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::whereNull('resolved_at')->count();

        if ($count <= 0) {
            return null;
        }

        return $count > 90 ? '90+' : (string) $count;
    }

    /* form */
    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Alert details')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->searchable(['name', 'email'])
                            ->getSearchResultsUsing(
                                fn(string $search) => User::query()
                                    ->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%")
                                    ->limit(50)
                                    ->pluck('name', 'id')
                            )
                            ->getOptionLabelUsing(fn($value) => User::find($value)?->name)
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('type')
                            ->label('Type')
                            ->placeholder('e.g. login_failed, password_changed, suspicious_activity')
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(SecurityAlert::query()->with('user')->latest())
            ->columns([
                Tables\Columns\ImageColumn::make('user.avatar')
                    ->label('')
                    ->getStateUsing(fn($record) => $record->user?->avatar
                        ? asset($record->user->avatar)
                        : null)
                    ->defaultImageUrl(function ($record) {
                        $name = $record->user?->name ?: 'N';
                        $initials = Str::of($name)
                            ->trim()
                            ->explode(' ')
                            ->take(2)
                            ->map(fn($word) => Str::substr($word, 0, 1))
                            ->implode('');

                        return 'https://ui-avatars.com/api/?name=' . urlencode($initials ?: 'N') . '&background=e5e7eb&color=6b7280';
                    })
                    ->circular(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn(string $state) => Str::headline($state))
                    ->color(fn(string $state) => match (true) {
                        str_contains($state, 'login') => 'warning',
                        str_contains($state, 'password') => 'info',
                        str_contains($state, 'fraud'), str_contains($state, 'suspicious') => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->limit(60)
                    ->searchable(),

                Tables\Columns\IconColumn::make('resolved_at')
                    ->label('Resolved')
                    ->boolean()
                    ->getStateUsing(fn($record) => filled($record->resolved_at)),

                Tables\Columns\TextColumn::make('resolved_at')
                    ->label('Resolved at')
                    ->dateTime('M j, Y g:i A')
                    ->placeholder('—')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Triggered')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(fn() => SecurityAlert::query()
                        ->distinct()
                        ->pluck('type', 'type')
                        ->mapWithKeys(fn($type) => [$type => Str::headline($type)])
                        ->toArray()),

                Tables\Filters\TernaryFilter::make('resolved_at')
                    ->label('Status')
                    ->placeholder('All')
                    ->trueLabel('Resolved')
                    ->falseLabel('Unresolved')
                    ->queries(
                        true: fn($query) => $query->whereNotNull('resolved_at'),
                        false: fn($query) => $query->whereNull('resolved_at'),
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('resolve')
                    ->label('Mark resolved')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->visible(fn(SecurityAlert $record) => is_null($record->resolved_at))
                    ->requiresConfirmation()
                    ->modalDescription('Mark this security alert as resolved?')
                    ->action(function (SecurityAlert $record) {
                        $record->update(['resolved_at' => now()]);

                        Notification::make()
                            ->success()
                            ->title('Alert marked as resolved')
                            ->send();
                    }),

                Tables\Actions\Action::make('reopen')
                    ->label('Reopen')
                    ->icon('heroicon-m-arrow-uturn-left')
                    ->color('gray')
                    ->visible(fn(SecurityAlert $record) => filled($record->resolved_at))
                    ->requiresConfirmation()
                    ->modalDescription('Reopen this alert? It will be marked as unresolved again.')
                    ->action(function (SecurityAlert $record) {
                        $record->update(['resolved_at' => null]);

                        Notification::make()
                            ->success()
                            ->title('Alert reopened')
                            ->send();
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('resolve_bulk')
                        ->label('Mark resolved')
                        ->icon('heroicon-m-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (\Illuminate\Support\Collection $records) {
                            $records->each(
                                fn(SecurityAlert $record) =>
                                $record->update(['resolved_at' => now()])
                            );

                            Notification::make()
                                ->success()
                                ->title('Alerts resolved')
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSecurityAlerts::route('/'),
            'view' => Pages\ViewSecurityAlert::route('/{record}'),
        ];
    }
}
