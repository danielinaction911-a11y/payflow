<?php

namespace App\Filament\Admin\Resources;

use App\Models\Wallet;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Filament\Admin\Resources\WalletResource\Pages;

class WalletResource extends Resource
{
    protected static ?string $model = Wallet::class;
    protected static ?string $navigationIcon = 'heroicon-o-wallet';
    protected static ?string $navigationGroup = 'Payments';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Wallets';

    public static function table(Table $table): Table
    {
        return $table
            ->query(Wallet::query()->with(['user', 'currency'])->latest())
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

                Tables\Columns\TextColumn::make('currency.code')
                    ->label('Currency')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('available')
                    ->label('Available')
                    ->numeric(decimalPlaces: 8)
                    ->sortable(),

                Tables\Columns\TextColumn::make('locked')
                    ->label('Locked')
                    ->numeric(decimalPlaces: 8)
                    ->sortable(),

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->state(fn (Wallet $record) => $record->totalBalance())
                    ->numeric(decimalPlaces: 8),

                Tables\Columns\IconColumn::make('is_primary')
                    ->label('Primary')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('currency_id')
                    ->label('Currency')
                    ->relationship('currency', 'code')
                    ->searchable(),

                Tables\Filters\TernaryFilter::make('is_primary')
                    ->label('Primary wallet'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('adjust_balance')
                    ->label('Adjust balance')
                    ->icon('heroicon-m-banknotes')
                    ->color('warning')
                    ->form([
                        Forms\Components\Radio::make('direction')
                            ->label('Action')
                            ->options([
                                'credit' => 'Credit (add funds)',
                                'debit' => 'Debit (remove funds)',
                            ])
                            ->default('credit')
                            ->required()
                            ->live(),

                        Forms\Components\Select::make('balance_type')
                            ->label('Balance type')
                            ->options([
                                'available' => 'Available',
                                'locked' => 'Locked',
                            ])
                            ->default('available')
                            ->required(),

                        Forms\Components\TextInput::make('amount')
                            ->numeric()
                            ->required()
                            ->minValue(0.00000001)
                            ->step('0.00000001'),

                        Forms\Components\Textarea::make('reason')
                            ->required()
                            ->placeholder('e.g. Manual correction, refund, dispute resolution')
                            ->rows(3),
                    ])
                    ->action(function (Wallet $record, array $data) {
                        $field = $data['balance_type'];
                        $amount = (string) $data['amount'];

                        if ($data['direction'] === 'debit' && bccomp($record->{$field}, $amount, 18) < 0) {
                            Notification::make()
                                ->danger()
                                ->title('Insufficient balance')
                                ->body("Cannot debit {$amount}, wallet only has {$record->{$field}} in {$field}.")
                                ->send();

                            return;
                        }

                        DB::transaction(function () use ($record, $field, $amount, $data) {
                            $record->{$field} = $data['direction'] === 'credit'
                                ? bcadd($record->{$field}, $amount, 18)
                                : bcsub($record->{$field}, $amount, 18);

                            $record->save();

                            // If you have a Transaction model for audit logging, record it here, e.g.:
                            // $record->transactions()->create([
                            //     'type' => $data['direction'],
                            //     'amount' => $amount,
                            //     'reason' => $data['reason'],
                            //     'admin_id' => auth('admin')->id(),
                            // ]);
                        });

                        Notification::make()
                            ->success()
                            ->title('Balance adjusted')
                            ->send();
                    }),

                Tables\Actions\Action::make('set_primary')
                    ->label('Set as primary')
                    ->icon('heroicon-m-star')
                    ->color('gray')
                    ->visible(fn (Wallet $record) => ! $record->is_primary)
                    ->requiresConfirmation()
                    ->modalDescription('This will unset the current primary wallet for this user in this currency group.')
                    ->action(function (Wallet $record) {
                        DB::transaction(function () use ($record) {
                            Wallet::where('user_id', $record->user_id)
                                ->where('id', '!=', $record->id)
                                ->update(['is_primary' => false]);

                            $record->update(['is_primary' => true]);
                        });

                        Notification::make()
                            ->success()
                            ->title('Primary wallet updated')
                            ->send();
                    }),
                Tables\Actions\DeleteAction::make()
                    ->disabled(fn (Wallet $record): bool => $record->hasRelatedRecords())
                    ->tooltip(fn (Wallet $record): ?string => $record->hasRelatedRecords()
                        ? 'Cannot delete this wallet because it has transactions, is primary, or holds balance.'
                        : null),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function (\Illuminate\Support\Collection $records) {
                            $blocked = $records->filter(fn (Wallet $record) => $record->hasRelatedRecords());
                            $deletable = $records->reject(fn (Wallet $record) => $record->hasRelatedRecords());

                            $deletable->each->delete();

                            if ($blocked->isNotEmpty() && $deletable->isNotEmpty()) {
                                Notification::make()
                                    ->warning()
                                    ->title('Some wallets were skipped')
                                    ->body('Some wallets were skipped because they are primary, hold balance, or have transactions.')
                                    ->send();
                            } elseif ($blocked->isNotEmpty()) {
                                Notification::make()
                                    ->warning()
                                    ->title('No wallets deleted')
                                    ->body('Selected wallets were skipped because they are primary, hold balance, or have transactions.')
                                    ->send();
                            } elseif ($deletable->isNotEmpty()) {
                                Notification::make()
                                    ->success()
                                    ->title('Wallets deleted')
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
            'index' => Pages\ListWallets::route('/'),
            'view' => Pages\ViewWallet::route('/{record}'),
        ];
    }
}