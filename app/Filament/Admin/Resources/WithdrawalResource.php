<?php

namespace App\Filament\Admin\Resources;

use App\Enums\TransactionDirection;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Notification;
use App\Models\User;
use App\Models\Withdrawal;
use App\Models\Transaction;
use Filament\Notifications\Notification as FilamentNotification;
use App\Services\TransactionService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Filament\Admin\Resources\WithdrawalResource\Pages;

class WithdrawalResource extends Resource
{
    protected static ?string $model = Withdrawal::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-right';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?int $navigationSort = 2;

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
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                ])
                ->required(),

            Forms\Components\Textarea::make('rejection_reason')
                ->visible(fn (Forms\Get $get) => $get('status') === 'rejected')
                ->required(fn (Forms\Get $get) => $get('status') === 'rejected'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Withdrawal::query()->with('user')->latest())
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
                Tables\Columns\TextColumn::make('user.name')->label('User')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('transaction_id')->label('Reference')->copyable()->fontFamily('mono'),
                Tables\Columns\TextColumn::make('method')->badge(),
                Tables\Columns\TextColumn::make('amount')
                    ->formatStateUsing(fn ($record) => smart_money($record->amount, $record->currency))
                    ->weight('bold')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match ($state->value ?? $state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')->label('Requested')->dateTime('M j, Y g:i A')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected']),
                Tables\Filters\SelectFilter::make('method')
                    ->options(fn () => Withdrawal::query()->distinct()->pluck('method', 'method')->toArray()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->visible(fn (Withdrawal $record) => $record->status->value === 'pending')
                    ->requiresConfirmation()
                    ->modalDescription('Mark this withdrawal as approved and processed? Funds were already reserved at request time.')
                    ->action(fn (Withdrawal $record) => static::approveWithdrawal($record)),

                Tables\Actions\Action::make('reject')
                    ->label('Reject & refund')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->visible(fn (Withdrawal $record) => $record->status->value === 'pending')
                    ->form([
                        Forms\Components\Textarea::make('rejection_reason')->label('Reason for rejection')->required(),
                    ])
                    ->action(fn (Withdrawal $record, array $data) => static::rejectWithdrawal($record, $data['rejection_reason'])),
                Tables\Actions\DeleteAction::make()
                    ->disabled(fn (Withdrawal $record): bool => $record->transaction_id !== null || Transaction::where('metadata->withdrawal_id', $record->id)->exists())
                    ->tooltip(fn (Withdrawal $record): ?string => ($record->transaction_id !== null || Transaction::where('metadata->withdrawal_id', $record->id)->exists())
                        ? 'Cannot delete this withdrawal because a related transaction exists.'
                        : null),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function (\Illuminate\Support\Collection $records) {
                            $blocked = $records->filter(fn (Withdrawal $record) => $record->transaction_id !== null || Transaction::where('metadata->withdrawal_id', $record->id)->exists());
                            $deletable = $records->reject(fn (Withdrawal $record) => $record->transaction_id !== null || Transaction::where('metadata->withdrawal_id', $record->id)->exists());

                            $deletable->each->delete();

                            if ($blocked->isNotEmpty() && $deletable->isNotEmpty()) {
                                FilamentNotification::make()
                                    ->warning()
                                    ->title('Some withdrawals were skipped')
                                    ->body($blocked->pluck('transaction_id')->map(fn($id, $k) => $id ?? ('#' . $blocked->pluck('id')[$k]))->implode(', ') . ' could not be deleted because related transactions exist.')
                                    ->send();
                            } elseif ($blocked->isNotEmpty()) {
                                FilamentNotification::make()
                                    ->warning()
                                    ->title('No withdrawals deleted')
                                    ->body($blocked->pluck('transaction_id')->map(fn($id, $k) => $id ?? ('#' . $blocked->pluck('id')[$k]))->implode(', ') . ' could not be deleted because related transactions exist.')
                                    ->send();
                            } elseif ($deletable->isNotEmpty()) {
                                FilamentNotification::make()
                                    ->success()
                                    ->title('Withdrawals deleted')
                                    ->send();
                            }
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function approveWithdrawal(Withdrawal $withdrawal): void
    {
        DB::transaction(function () use ($withdrawal) {
            $withdrawal->update(['status' => 'approved']);

            // If there is an existing Transaction linked to this withdrawal, update it.
            $tx = \App\Models\Transaction::where('metadata->withdrawal_id', $withdrawal->id)->first();

            if ($tx) {
                $tx->update([
                    'status' => TransactionStatus::Completed,
                ]);
            } else {
                app(TransactionService::class)->create([
                    'user_id' => $withdrawal->user_id,
                    'amount' => (float) $withdrawal->amount,
                    'currency' => $withdrawal->currency,
                    'type' => TransactionType::Withdrawal,
                    'direction' => TransactionDirection::Debit,
                    'status' => TransactionStatus::Completed,
                    'description' => "Withdrawal approved via {$withdrawal->method}",
                    'metadata' => ['withdrawal_id' => $withdrawal->id],
                ]);
            }

            Notification::create([
                'user_id' => $withdrawal->user_id,
                'title' => 'Withdrawal approved',
                'body' => 'Your withdrawal of ' . smart_money($withdrawal->amount, $withdrawal->currency) . ' has been processed.',
                'type' => 'success',
                'is_read' => false,
            ]);
        });
    }

    public static function rejectWithdrawal(Withdrawal $withdrawal, string $reason): void
    {
        DB::transaction(function () use ($withdrawal, $reason) {
            // Funds were already deducted when the user requested this
            // withdrawal, so rejecting it must refund the exact same
            // balance source (main vs profit) they were debited from.
            $balanceSource = $withdrawal->metadata['balance_source'] ?? 'balance';

            $user = User::where('id', $withdrawal->user_id)->lockForUpdate()->first();
            $user->increment($balanceSource, (float) $withdrawal->amount);

            $withdrawal->update([
                'status' => 'rejected',
                'rejection_reason' => $reason,
            ]);

            // Mark any existing withdrawal transaction as failed
            $tx = Transaction::where('metadata->withdrawal_id', $withdrawal->id)->first();
            if ($tx) {
                $tx->update([
                    'status' => TransactionStatus::Failed,
                    'failed_reason' => $reason,
                ]);
            }

            // Create refund transaction
            app(TransactionService::class)->create([
                'user_id' => $user->id,
                'amount' => (float) $withdrawal->amount,
                'currency' => $withdrawal->currency,
                'type' => TransactionType::Refund,
                'direction' => TransactionDirection::Credit,
                'status' => TransactionStatus::Completed,
                'description' => 'Withdrawal rejected — funds refunded',
                'metadata' => ['withdrawal_id' => $withdrawal->id],
            ]);

            Notification::create([
                'user_id' => $user->id,
                'title' => 'Withdrawal rejected',
                'body' => 'Your withdrawal of ' . smart_money($withdrawal->amount, $withdrawal->currency) . " was rejected and refunded to your balance. Reason: {$reason}",
                'type' => 'error',
                'is_read' => false,
            ]);
        });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWithdrawals::route('/'),
            'view' => Pages\ViewWithdrawal::route('/{record}'),
        ];
    }
}