<?php

namespace App\Filament\Admin\Resources;

use App\Enums\TransactionDirection;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Filament\Admin\Resources\TransactionResource\Pages;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationLabel = 'Transactions';

    public static function table(Table $table): Table
    {
        return $table
            ->query(Transaction::query()->with(['user', 'wallet'])->latest())
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
                Tables\Columns\TextColumn::make('reference')
                    ->searchable()
                    ->copyable()
                    ->limit(20),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn(TransactionType $state) => Str::headline($state->value))
                    ->color(fn(TransactionType $state) => match ($state) {
                        TransactionType::Deposit, TransactionType::Bonus, TransactionType::ReferralCredit, TransactionType::Refund => 'success',
                        TransactionType::Withdrawal, TransactionType::Chargeback, TransactionType::Fee => 'danger',
                        TransactionType::Trade, TransactionType::Exchange => 'info',
                        TransactionType::Investment, TransactionType::Staking => 'warning',
                        TransactionType::Profit => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('direction')
                    ->badge()
                    ->formatStateUsing(fn(TransactionDirection $state) => Str::headline($state->value))
                    ->color(fn(TransactionDirection $state) => $state === TransactionDirection::Credit ? 'success' : 'danger')
                    ->icon(fn(TransactionDirection $state) => $state === TransactionDirection::Credit
                        ? 'heroicon-m-arrow-down-left'
                        : 'heroicon-m-arrow-up-right'),

                Tables\Columns\TextColumn::make('amount')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->color(fn(Transaction $record) => $record->direction === TransactionDirection::Credit ? 'success' : 'danger')
                    ->formatStateUsing(fn($state, Transaction $record) => ($record->direction === TransactionDirection::Credit ? '+' : '-') . number_format($state, 2)),

                Tables\Columns\TextColumn::make('fee')
                    ->numeric(decimalPlaces: 2)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('currency')
                    ->badge(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn(TransactionStatus $state) => Str::headline($state->value))
                    ->color(fn(TransactionStatus $state) => match ($state) {
                        TransactionStatus::Completed => 'success',
                        TransactionStatus::Pending => 'warning',
                        TransactionStatus::Failed => 'danger',
                        TransactionStatus::Reversed => 'gray',
                    }),

                Tables\Columns\TextColumn::make('description')
                    ->limit(30)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(collect(TransactionType::cases())
                        ->mapWithKeys(fn($case) => [$case->value => Str::headline($case->value)])
                        ->toArray()),

                Tables\Filters\SelectFilter::make('direction')
                    ->options(collect(TransactionDirection::cases())
                        ->mapWithKeys(fn($case) => [$case->value => Str::headline($case->value)])
                        ->toArray()),

                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(TransactionStatus::cases())
                        ->mapWithKeys(fn($case) => [$case->value => Str::headline($case->value)])
                        ->toArray()),

                Tables\Filters\SelectFilter::make('currency')
                    ->options(fn() => Transaction::query()->distinct()->pluck('currency', 'currency')->toArray()),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')->native(false),
                        Forms\Components\DatePicker::make('until')->native(false),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'], fn($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),

                Tables\Filters\Filter::make('amount_range')
                    ->form([
                        Forms\Components\TextInput::make('min')->numeric()->prefix('$'),
                        Forms\Components\TextInput::make('max')->numeric()->prefix('$'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['min'], fn($q, $amount) => $q->where('amount', '>=', $amount))
                            ->when($data['max'], fn($q, $amount) => $q->where('amount', '<=', $amount));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->disabled(fn (Transaction $record): bool => $record->hasRelatedReference())
                    ->tooltip(fn (Transaction $record): ?string => $record->hasRelatedReference()
                        ? 'Cannot delete this transaction because it is linked to another record.'
                        : null),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'view' => Pages\ViewTransaction::route('/{record}'),
        ];
    }
}
