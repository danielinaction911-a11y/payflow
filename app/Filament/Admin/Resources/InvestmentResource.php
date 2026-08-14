<?php

namespace App\Filament\Admin\Resources;

use App\Enums\InvestmentStatus;
use App\Models\Investment;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Filament\Admin\Resources\InvestmentResource\Pages;
use Illuminate\Support\Facades\DB;

class InvestmentResource extends Resource
{
    protected static ?string $model = Investment::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';
    protected static ?string $navigationGroup = 'Investments';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Investments';

    public static function table(Table $table): Table
    {
        return $table
            ->query(Investment::query()->with(['user', 'plan'])->latest())
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

                Tables\Columns\TextColumn::make('plan.name')
                    ->label('Plan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('amount_invested')
                    ->label('Invested')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('roi_percentage')
                    ->label('ROI')
                    ->suffix('%'),

                Tables\Columns\TextColumn::make('expected_total_return')
                    ->label('Expected return')
                    ->money('USD'),

                Tables\Columns\TextColumn::make('total_paid_out')
                    ->label('Paid out')
                    ->money('USD')
                    ->color(fn($record) => $record->total_paid_out >= $record->expected_total_return ? 'success' : 'gray'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn($state) => Str::headline($state->value ?? $state))
                    ->color(fn($state) => match ($state->value ?? $state) {
                        'active' => 'success',
                        'completed' => 'info',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('starts_at')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('ends_at')
                    ->dateTime('M j, Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('last_profit_at')
                    ->label('Last profit')
                    ->dateTime('M j, Y g:i A')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(InvestmentStatus::cases())
                        ->mapWithKeys(fn($case) => [$case->value => Str::headline($case->name)])
                        ->toArray()),

                Tables\Filters\SelectFilter::make('investment_plan_id')
                    ->label('Plan')
                    ->relationship('plan', 'name'),

                Tables\Filters\Filter::make('ends_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')->native(false),
                        Forms\Components\DatePicker::make('until')->native(false),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn($q, $date) => $q->whereDate('ends_at', '>=', $date))
                            ->when($data['until'], fn($q, $date) => $q->whereDate('ends_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('add_profit')
                    ->label('Add profit')
                    ->icon('heroicon-m-currency-dollar')
                    ->color('success')
                    ->visible(fn(Investment $record) => $record->status === InvestmentStatus::Active)
                    ->form([
                        Forms\Components\TextInput::make('amount')
                            ->numeric()
                            ->required()
                            ->minValue(0.01)
                            ->prefix('$'),

                        Forms\Components\Select::make('status')
                            ->options([
                                'paid' => 'Paid',
                                'pending' => 'Pending',
                            ])
                            ->default('paid')
                            ->required(),

                        Forms\Components\DateTimePicker::make('paid_at')
                            ->label('Paid at')
                            ->default(now())
                            ->native(false)
                            ->required(),
                    ])
                    ->action(fn(Investment $record, array $data) => static::addProfit($record, $data)),
                Tables\Actions\Action::make('mark_completed')
                    ->label('Mark completed')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->visible(fn(Investment $record) => $record->status === InvestmentStatus::Active)
                    ->requiresConfirmation()
                    ->modalDescription('Mark this investment as completed? This should only be done once the full cycle has ended and payouts are settled.')
                    ->action(function (Investment $record) {
                        $record->update(['status' => InvestmentStatus::Completed]);

                        Notification::make()
                            ->success()
                            ->title('Investment marked as completed')
                            ->send();
                    }),

                Tables\Actions\Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->visible(fn(Investment $record) => $record->status === InvestmentStatus::Active)
                    ->requiresConfirmation()
                    ->modalDescription('Cancel this investment? This does not automatically refund the user — handle any refund separately.')
                    ->form([
                        Forms\Components\Textarea::make('reason')
                            ->label('Cancellation reason')
                            ->required(),
                    ])
                    ->action(function (Investment $record, array $data) {
                        $record->update(['status' => InvestmentStatus::Cancelled]);

                        Notification::make()
                            ->success()
                            ->title('Investment cancelled')
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function addProfit(Investment $investment, array $data): void
    {
        DB::transaction(function () use ($investment, $data) {
            $amount = (string) $data['amount'];

            // 1. create the profit log entry
            \App\Models\ProfitLog::create([
                'investment_id' => $investment->id,
                'user_id' => $investment->user_id,
                'amount' => $amount,
                'status' => $data['status'],
                'paid_at' => $data['paid_at'],
            ]);

            // 2. update the investment's payout tracking
            $investment->update([
                'total_paid_out' => bcadd($investment->total_paid_out, $amount, 2),
                'last_profit_at' => $data['paid_at'],
            ]);

            // 3. credit the user's profit balance
            $investment->user->increment('profit_balance', $amount);

            // 4. notify the user
            \App\Models\Notification::create([
                'user_id' => $investment->user_id,
                'title' => 'Profit added',
                'body' => 'A profit of ' . smart_money($amount, 'USD') . ' has been added to your investment in ' . ($investment->plan->name ?? 'your plan') . '.',
                'type' => 'success',
                'is_read' => false,
            ]);
        });

        Notification::make()
            ->success()
            ->title('Profit added successfully')
            ->send();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvestments::route('/'),
            'view' => Pages\ViewInvestment::route('/{record}'),
        ];
    }
}
