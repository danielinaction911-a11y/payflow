<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Enums\TransactionDirection;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Filament\Admin\Resources\UserResource;
use App\Models\User;
use App\Models\Notification;
use App\Services\TransactionService;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()
                ->disabled(fn (User $record): bool => $record->hasRelatedRecords())
                ->tooltip(fn (User $record): ?string => $record->hasRelatedRecords() ? 'Cannot delete this user because related records exist.' : null),

            Actions\Action::make('adjustBalance')
                ->label('Add / subtract balance')
                ->icon('heroicon-m-banknotes')
                ->color('success')
                ->form([
                    Forms\Components\Radio::make('direction')
                        ->options(['credit' => 'Add funds', 'debit' => 'Subtract funds'])
                        ->default('credit')
                        ->inline()
                        ->required(),

                    Forms\Components\TextInput::make('amount')
                        ->numeric()
                        ->minValue(0.01)
                        ->required()
                        ->prefix('$'),

                    Forms\Components\Textarea::make('description')
                        ->required()
                        ->placeholder('Reason for this adjustment (visible in transaction history)'),
                ])
                ->action(function (array $data) {
                    if ($data['direction'] === 'debit' && (float) $this->record->balance < (float) $data['amount']) {
                        \Filament\Notifications\Notification::make()
                            ->title('Insufficient balance')
                            ->danger()
                            ->body('This user does not have enough balance to debit the requested amount.')
                            ->send();

                        return;
                    }

                    $success = static::adjustBalance($this->record, 'balance', $data['direction'], (float) $data['amount'], $data['description']);

                    if (! $success) {
                        \Filament\Notifications\Notification::make()
                            ->title('Insufficient balance')
                            ->danger()
                            ->body('This user does not have enough balance to complete the debit.')
                            ->send();

                        return;
                    }

                    $this->record->refresh();
                    \Filament\Notifications\Notification::make()
                        ->title('Balance updated successfully')
                        ->success()
                        ->send();
                }),

            Actions\Action::make('adjustProfit')
                ->label('Add / subtract profit')
                ->icon('heroicon-m-chart-bar')
                ->color('info')
                ->form([
                    Forms\Components\Radio::make('direction')
                        ->options(['credit' => 'Add profit', 'debit' => 'Subtract profit'])
                        ->default('credit')
                        ->inline()
                        ->required(),

                    Forms\Components\TextInput::make('amount')
                        ->numeric()
                        ->minValue(0.01)
                        ->required()
                        ->prefix('$'),

                    Forms\Components\Textarea::make('description')
                        ->required()
                        ->placeholder('Reason for this adjustment'),
                ])
                ->action(function (array $data) {
                    if ($data['direction'] === 'debit' && (float) $this->record->profit_balance < (float) $data['amount']) {
                        \Filament\Notifications\Notification::make()
                            ->title('Insufficient profit balance')
                            ->danger()
                            ->body('This user does not have enough profit balance to debit the requested amount.')
                            ->send();

                        return;
                    }

                    $success = static::adjustBalance($this->record, 'profit_balance', $data['direction'], (float) $data['amount'], $data['description']);

                    if (! $success) {
                        \Filament\Notifications\Notification::make()
                            ->title('Insufficient profit balance')
                            ->danger()
                            ->body('This user does not have enough profit balance to complete the debit.')
                            ->send();

                        return;
                    }

                    $this->record->refresh();
                    \Filament\Notifications\Notification::make()
                        ->title('Profit balance updated successfully')
                        ->success()
                        ->send();
                }),

            Actions\Action::make('sendNotification')
                ->label('Send notification')
                ->icon('heroicon-m-bell-alert')
                ->color('gray')
                ->form([
                    Forms\Components\TextInput::make('title')->required(),
                    Forms\Components\Textarea::make('body')->required(),
                    Forms\Components\Select::make('type')
                        ->options(['info' => 'Info', 'success' => 'Success', 'warning' => 'Warning', 'error' => 'Error'])
                        ->default('info')
                        ->required(),
                ])
                ->action(function (array $data) {
                    Notification::create([
                        'user_id' => $this->record->id,
                        'title' => $data['title'],
                        'body' => $data['body'],
                        'type' => $data['type'],
                        'is_read' => false,
                    ]);
                    \Filament\Notifications\Notification::make()
                        ->title('Notification sent successfully')
                        ->success()
                        ->send();
                }),
        ];
    }

    public static function adjustBalance($user, string $column, string $direction, float $amount, string $description): bool
    {
        return DB::transaction(function () use ($user, $column, $direction, $amount, $description) {
            $locked = \App\Models\User::where('id', $user->id)->lockForUpdate()->first();

            if ($direction === 'debit') {
                if ((float) $locked->{$column} < $amount) {
                    return false;
                }

                $amountApplied = $amount;
                $locked->decrement($column, $amountApplied);
            } else {
                $amountApplied = $amount;
                $locked->increment($column, $amountApplied);
            }

            app(TransactionService::class)->create([
                'user_id' => $locked->id,
                'amount' => $amountApplied,
                'currency' => 'USD',
                'type' => $column === 'profit_balance' ? TransactionType::Profit : TransactionType::Deposit,
                'direction' => $direction === 'credit' ? TransactionDirection::Credit : TransactionDirection::Debit,
                'status' => TransactionStatus::Completed,
                'description' => $description,
                'metadata' => ['adjusted_by_admin' => true, 'column' => $column],
            ]);

            Notification::create([
                'user_id' => $locked->id,
                'title' => $direction === 'credit' ? 'Balance credited' : 'Balance adjusted',
                'body' => ($direction === 'credit' ? 'Your account was credited ' : 'Your account was debited ') . smart_money($amountApplied) . ". {$description}",
                'type' => $direction === 'credit' ? 'success' : 'info',
                'is_read' => false,
            ]);

            return true;
        });
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make()->schema([
                Grid::make(4)->schema([ 

                    ImageEntry::make('avatar')
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
                        ->circular()
                        ->columnSpan(1),


                    Grid::make(1)->columnSpan(3)->schema([
                        TextEntry::make('name')->size('lg')->weight('bold'),
                        TextEntry::make('email'),
                        TextEntry::make('username')->prefix('@'),
                    ]),
                ]),
            ]),

            Section::make('Balances')->schema([
                Grid::make(2)->schema([
                    TextEntry::make('balance')->formatStateUsing(fn($state) => smart_money($state))->size('lg')->weight('bold')->color('success'),
                    TextEntry::make('profit_balance')->label('Profit balance')->formatStateUsing(fn($state) => smart_money($state))->size('lg')->weight('bold')->color('info'),
                ]),
            ]),

            Section::make('Status')->schema([
                Grid::make(3)->schema([
                    TextEntry::make('status')
                        ->badge()
                        ->color(fn (string $state) => match ($state) {
                            'active' => 'success',
                            'suspended' => 'warning',
                            'banned' => 'danger',
                            default => 'gray',
                        }),
                    TextEntry::make('kyc_status')
                        ->badge()
                        ->color(fn (string $state) => match ($state) {
                            'approved' => 'success',
                            'pending' => 'warning',
                            'rejected' => 'danger',
                            default => 'gray',
                        }),
                    TextEntry::make('last_login_at')->dateTime()->placeholder('Never'),
                ]),
            ]),
        ]);
    }
}
