<?php

namespace App\Filament\Admin\Resources;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Admin\Resources\UserResource\Pages;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Filament\Notifications\Notification as FilamentNotification;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Users';
    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Profile')->schema([ 
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('name')->required(),
                    Forms\Components\TextInput::make('username')
                        ->required()
                        ->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('phone')
                        ->tel()
                        ->unique(ignoreRecord: true),
                    Forms\Components\Select::make('country')
                        ->label('Country')
                        ->options(fn () => collect(getCountries())->pluck('name', 'name')->toArray())
                        ->searchable()
                        ->placeholder('Select country'),
                    Forms\Components\TextInput::make('state'),
                    Forms\Components\TextInput::make('city'),
                    Forms\Components\TextInput::make('address'),
                ]),
            ]),

            Forms\Components\Section::make('Security')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('transaction_pin')
                        ->label('Transaction PIN')
                        ->password()
                        ->revealable()
                        ->placeholder('Enter a new transaction PIN (4 digits)')
                        ->numeric()
                        ->inputMode('numeric')
                        ->minLength(4)
                        ->maxLength(4)
                        ->afterStateHydrated(fn ($component) => $component->state(null))
                        ->dehydrated(fn ($state) => filled($state))
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->helperText(fn (string $context) => $context === 'edit'
                            ? 'Leave blank to keep the current transaction PIN.'
                            : null),

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
                ]),
            ]),

            Forms\Components\Section::make('Account status')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Select::make('status')
                        ->options(['active' => 'Active', 'suspended' => 'Suspended', 'banned' => 'Banned'])
                        ->required(),

                    Forms\Components\Select::make('kyc_status')
                        ->options([
                            'not_submitted' => 'Not Submitted',
                            'pending' => 'Pending',
                            'approved' => 'Approved',
                            'rejected' => 'Rejected',
                        ])
                        ->required(),
                ]),
            ]),

            Forms\Components\Section::make('Feature restrictions')->schema([
                Forms\Components\Grid::make(2)->schema(
                    collect(['deposit', 'transfer', 'withdrawal', 'investment', 'trading'])
                        ->flatMap(fn($key) => [
                            Forms\Components\Select::make("{$key}_status")
                                ->label(ucfirst($key) . ' access')
                                ->options(['enabled' => 'Enabled', 'disabled' => 'Disabled'])
                                ->live()
                                ->required(),

                            Forms\Components\TextInput::make("{$key}_message")
                                ->label(ucfirst($key) . ' restriction message')
                                ->visible(fn(Forms\Get $get) => $get("{$key}_status") === 'disabled')
                                ->placeholder('Shown to the user when this action is disabled'),
                        ])->toArray()
                ),
            ]),

            Forms\Components\Section::make('Withdrawal fee')->schema([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\Select::make('withdrawal_fee_status')
                        ->options(['enabled' => 'Enabled', 'disabled' => 'Disabled'])
                        ->live()
                        ->required(),

                    Forms\Components\TextInput::make('withdrawal_fee')
                        ->numeric()
                        ->visible(fn(Forms\Get $get) => $get('withdrawal_fee_status') === 'enabled'),

                    Forms\Components\Select::make('withdrawal_fee_type')
                        ->options(['percentage' => 'Percentage', 'amount' => 'Fixed amount'])
                        ->visible(fn(Forms\Get $get) => $get('withdrawal_fee_status') === 'enabled'),
                ]),
            ]),

            Forms\Components\Section::make('Transaction limits')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Fieldset::make('Transfer limits')->schema([
                        Forms\Components\TextInput::make('daily_transfer_limit')->numeric()->prefix('$'),
                        Forms\Components\TextInput::make('weekly_transfer_limit')->numeric()->prefix('$'),
                        Forms\Components\TextInput::make('monthly_transfer_limit')->numeric()->prefix('$'),
                    ]),

                    Forms\Components\Fieldset::make('Withdrawal limits')->schema([
                        Forms\Components\TextInput::make('daily_withdrawal_limit')->numeric()->prefix('$'),
                        Forms\Components\TextInput::make('weekly_withdrawal_limit')->numeric()->prefix('$'),
                        Forms\Components\TextInput::make('monthly_withdrawal_limit')->numeric()->prefix('$'),
                    ]),
                ]),
            ]),

            Forms\Components\Section::make('Referral')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('referral_code')->disabled(),
                    Forms\Components\Select::make('referred_by')
                        ->relationship('referredBy', 'name')
                        ->searchable()
                        ->disabled(),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([ 
                Tables\Columns\ImageColumn::make('avatar')
                    ->label('')
                    ->getStateUsing(fn($record) => $record->avatar
                        ? asset($record->avatar)
                        : null)
                    ->defaultImageUrl(function ($record) {
                        $name = $record->name ?: 'N';
                        $initials = Str::of($name)
                            ->trim()
                            ->explode(' ')
                            ->take(2)
                            ->map(fn($word) => Str::substr($word, 0, 1))
                            ->implode('');

                        return 'https://ui-avatars.com/api/?name=' . urlencode($initials ?: 'N') . '&background=e5e7eb&color=6b7280';
                    })
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->description(fn(User $record) => $record->email),

                Tables\Columns\TextColumn::make('username')
                    ->searchable(),

                Tables\Columns\TextColumn::make('balance')
                    ->formatStateUsing(fn($state) => smart_money($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('profit_balance')
                    ->label('Profit')
                    ->formatStateUsing(fn($state) => smart_money($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('kyc_status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'active' => 'success',
                        'suspended' => 'warning',
                        'banned' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Verified')
                    ->boolean(),

                Tables\Columns\TextColumn::make('last_login_at')
                    ->dateTime('M j, Y')
                    ->placeholder('Never')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(['active' => 'Active', 'suspended' => 'Suspended', 'banned' => 'Banned']),

                Tables\Filters\SelectFilter::make('kyc_status')
                    ->options([
                        'not_submitted' => 'Not Submitted',
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->disabled(fn (User $record): bool => $record->hasRelatedRecords())
                    ->tooltip(fn (User $record): ?string => $record->hasRelatedRecords()
                        ? ('Cannot delete this user — ' . $record->deletionBlockReasonLabel() . '.')
                        : null),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function (\Illuminate\Support\Collection $records) {
                            $blocked = $records->filter(fn (User $record) => $record->hasRelatedRecords());
                            $deletable = $records->reject(fn (User $record) => $record->hasRelatedRecords());

                            $deletable->each->delete();

                            if ($blocked->isNotEmpty() && $deletable->isNotEmpty()) {
                                $summary = $blocked->map(fn(User $u) => $u->email . ' (' . $u->deletionBlockReasonLabel() . ')')->implode(', ');
                                FilamentNotification::make()
                                    ->warning()
                                    ->title('Some users were skipped')
                                    ->body($summary . ' could not be deleted.')
                                    ->send();
                            } elseif ($blocked->isNotEmpty()) {
                                $summary = $blocked->map(fn(User $u) => $u->email . ' (' . $u->deletionBlockReasonLabel() . ')')->implode(', ');
                                FilamentNotification::make()
                                    ->warning()
                                    ->title('No users deleted')
                                    ->body($summary . ' could not be deleted.')
                                    ->send();
                            } elseif ($deletable->isNotEmpty()) {
                                FilamentNotification::make()
                                    ->success()
                                    ->title('Users deleted')
                                    ->send();
                            }
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

     public static function getRelations(): array
    {
        return [
            \App\Filament\Admin\Resources\UserResource\RelationManagers\TransactionsRelationManager::class,
            \App\Filament\Admin\Resources\UserResource\RelationManagers\TradesRelationManager::class,
            \App\Filament\Admin\Resources\UserResource\RelationManagers\DepositsRelationManager::class,
            \App\Filament\Admin\Resources\UserResource\RelationManagers\WithdrawalsRelationManager::class,
            \App\Filament\Admin\Resources\UserResource\RelationManagers\TransfersRelationManager::class,
            \App\Filament\Admin\Resources\UserResource\RelationManagers\InvestmentsRelationManager::class,
            \App\Filament\Admin\Resources\UserResource\RelationManagers\SecurityAlertsRelationManager::class,
            \App\Filament\Admin\Resources\UserResource\RelationManagers\SupportTicketsRelationManager::class,
            \App\Filament\Admin\Resources\UserResource\RelationManagers\NotificationsRelationManager::class,
            \App\Filament\Admin\Resources\UserResource\RelationManagers\LoginActivitiesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }
}
