<?php

namespace App\Filament\Admin\Resources;

use App\Enums\DepositStatus;
use App\Enums\TransactionDirection;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Deposit;
use App\Models\Notification;
use App\Models\User;
use App\Services\TransactionService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Filament\Admin\Resources\DepositResource\Pages;

class DepositResource extends Resource
{
    protected static ?string $model = Deposit::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-left';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', 'pending')->count();

        if ($count <= 0) {
            return null;
        }

        return $count > 90 ? '90+' : (string) $count;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'confirmed' => 'Confirmed',
                    'rejected' => 'Rejected',
                ])
                ->required(),

            Forms\Components\Textarea::make('rejection_reason')
                ->visible(fn(Forms\Get $get) => $get('status') === 'rejected')
                ->required(fn(Forms\Get $get) => $get('status') === 'rejected'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Deposit::query()->with('user')->latest())
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

                Tables\Columns\TextColumn::make('transaction_id')
                    ->label('Reference')
                    ->copyable()
                    ->fontFamily('mono'),

                Tables\Columns\TextColumn::make('method')
                    ->badge(),

                Tables\Columns\TextColumn::make('amount')
                    ->formatStateUsing(fn($record) => smart_money($record->amount, $record->currency))
                    ->weight('bold')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn($state) => match ($state->value ?? $state) {
                        'confirmed' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'rejected' => 'Rejected',
                    ]),

                Tables\Filters\SelectFilter::make('method')
                    ->options(fn() => Deposit::query()->distinct()->pluck('method', 'method')->toArray()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->visible(fn(Deposit $record) => $record->status->value === 'pending')
                    ->requiresConfirmation()
                    ->modalDescription(fn(Deposit $record) => 'Approve this deposit and credit ' . smart_money($record->amount, $record->currency) . ' to the user\'s balance?')
                    ->action(fn(Deposit $record) => static::approveDeposit($record)),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->visible(fn(Deposit $record) => $record->status->value === 'pending')
                    ->form([
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Reason for rejection')
                            ->required(),
                    ])
                    ->action(fn(Deposit $record, array $data) => static::rejectDeposit($record, $data['rejection_reason'])),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function approveDeposit(Deposit $deposit): void
    {
        DB::transaction(function () use ($deposit) {
            $user = User::where('id', $deposit->user_id)->lockForUpdate()->first();

            $isFirstDeposit = Deposit::where('user_id', $user->id)
                ->where('status', 'confirmed')
                ->where('id', '!=', $deposit->id)
                ->doesntExist();

            $user->increment('balance', (float) $deposit->amount);

            $deposit->update(['status' => 'confirmed']);

            app(TransactionService::class)->create([
                'user_id' => $user->id,
                'amount' => (float) $deposit->amount,
                'currency' => $deposit->currency,
                'type' => TransactionType::Deposit,
                'direction' => TransactionDirection::Credit,
                'status' => TransactionStatus::Completed,
                'description' => "Deposit approved via {$deposit->method}",
                'metadata' => ['deposit_id' => $deposit->id],
            ]);

            // If a Transaction record already exists for this deposit (linked by reference), update it.
            if ($deposit->transaction) {
                $deposit->transaction->update([
                    'status' => TransactionStatus::Completed,
                ]);
            } else {
                app(TransactionService::class)->create([
                    'user_id' => $user->id,
                    'amount' => (float) $deposit->amount,
                    'currency' => $deposit->currency,
                    'type' => TransactionType::Deposit,
                    'direction' => TransactionDirection::Credit,
                    'status' => TransactionStatus::Completed,
                    'description' => "Deposit approved via {$deposit->method}",
                    'metadata' => ['deposit_id' => $deposit->id],
                ]);
            }

            Notification::create([
                'user_id' => $user->id,
                'title' => 'Deposit approved',
                'body' => 'Your deposit of ' . smart_money($deposit->amount, $deposit->currency) . ' has been approved and credited to your balance.',
                'type' => 'success',
                'is_read' => false,
            ]);

            // First-deposit bonus
            if ($isFirstDeposit && setting('first_deposit_bonus_enabled', false)) {
                $bonusType = setting('first_deposit_bonus_type', 'fixed');
                $bonusAmount = (float) setting('first_deposit_bonus_amount', 0);

                $bonus = $bonusType === 'percentage'
                    ? round((float) $deposit->amount * $bonusAmount / 100, 2)
                    : $bonusAmount;

                if ($bonus > 0) {
                    $user->increment('balance', $bonus);

                    app(TransactionService::class)->create([
                        'user_id' => $user->id,
                        'amount' => $bonus,
                        'currency' => $deposit->currency,
                        'type' => TransactionType::Bonus,
                        'direction' => TransactionDirection::Credit,
                        'status' => TransactionStatus::Completed,
                        'description' => 'First deposit bonus',
                        'metadata' => ['deposit_id' => $deposit->id],
                    ]);

                    Notification::create([
                        'user_id' => $user->id,
                        'title' => 'Welcome bonus credited',
                        'body' => 'You received a ' . smart_money($bonus, $deposit->currency) . ' bonus for your first deposit!',
                        'type' => 'success',
                        'is_read' => false,
                    ]);
                }
            }
        });
    }

    public static function rejectDeposit(Deposit $deposit, string $reason): void
    {
        $deposit->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);

        // If a Transaction record exists for this deposit, mark it as failed
        if ($deposit->transaction) {
            $deposit->transaction->update([
                'status' => TransactionStatus::Failed,
                'failed_reason' => $reason,
            ]);
        }

        Notification::create([
            'user_id' => $deposit->user_id,
            'title' => 'Deposit rejected',
            'body' => 'Your deposit of ' . smart_money($deposit->amount, $deposit->currency) . " was rejected. Reason: {$reason}",
            'type' => 'error',
            'is_read' => false,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDeposits::route('/'),
            'view' => Pages\ViewDeposit::route('/{record}'),
        ];
    }
}
