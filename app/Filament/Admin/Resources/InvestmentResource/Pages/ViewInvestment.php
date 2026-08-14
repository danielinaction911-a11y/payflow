<?php

namespace App\Filament\Admin\Resources\InvestmentResource\Pages;

use App\Filament\Admin\Resources\InvestmentResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Str;

class ViewInvestment extends ViewRecord
{
    protected static string $resource = InvestmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('add_profit')
                ->label('Add profit')
                ->icon('heroicon-m-currency-dollar')
                ->color('success')
                ->visible(fn() => $this->record->status === \App\Enums\InvestmentStatus::Active)
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
                ->action(function (array $data) {
                    InvestmentResource::addProfit($this->record, $data);
                    $this->fillForm(); // refresh the infolist so new payout figures + profit log show immediately
                }),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Investment overview')
                ->schema([
                    Infolists\Components\TextEntry::make('user.name')->label('User'),
                    Infolists\Components\TextEntry::make('user.email')->label('Email'),
                    Infolists\Components\TextEntry::make('plan.name')->label('Plan'),
                    Infolists\Components\TextEntry::make('status')
                        ->badge()
                        ->formatStateUsing(fn($state) => Str::headline($state instanceof \App\Enums\InvestmentStatus ? $state->value : $state))
                        ->color(fn($state) => match ($state instanceof \App\Enums\InvestmentStatus ? $state->value : $state) {
                            'active' => 'success',
                            'completed' => 'info',
                            'cancelled' => 'danger',
                            default => 'gray',
                        }),
                ])
                ->columns(4),

            Infolists\Components\Section::make('Financials')
                ->schema([
                    Infolists\Components\TextEntry::make('amount_invested')->money('USD'),
                    Infolists\Components\TextEntry::make('roi_percentage')->suffix('%'),
                    Infolists\Components\TextEntry::make('expected_total_return')->money('USD'),
                    Infolists\Components\TextEntry::make('total_paid_out')->money('USD'),
                ])
                ->columns(4),

            Infolists\Components\Section::make('Timeline')
                ->schema([
                    Infolists\Components\TextEntry::make('starts_at')->dateTime('M j, Y g:i A'),
                    Infolists\Components\TextEntry::make('ends_at')->dateTime('M j, Y g:i A'),
                    Infolists\Components\TextEntry::make('last_profit_at')
                        ->dateTime('M j, Y g:i A')
                        ->placeholder('No payouts yet'),
                ])
                ->columns(3),

            Infolists\Components\Section::make('Profit history')
                ->schema([
                    Infolists\Components\RepeatableEntry::make('profitLogs')
                        ->label('')
                        ->schema([
                            Infolists\Components\TextEntry::make('amount')
                                ->label('Amount')
                                ->money('USD')
                                ->weight('bold')
                                ->color('success'),

                            Infolists\Components\TextEntry::make('status')
                                ->label('Status')
                                ->badge()
                                ->formatStateUsing(fn(string $state) => Str::headline($state))
                                ->color(fn(string $state) => match ($state) {
                                    'paid' => 'success',
                                    'pending' => 'warning',
                                    default => 'gray',
                                }),

                            Infolists\Components\TextEntry::make('paid_at')
                                ->label('Paid at')
                                ->dateTime('M j, Y g:i A'),

                            Infolists\Components\TextEntry::make('created_at')
                                ->label('Logged at')
                                ->dateTime('M j, Y g:i A')
                                ->color('gray'),
                        ])
                        ->columns([
                            'default' => 1,
                            'md' => 4,
                        ])
                        ->contained(false),
                ])
                ->visible(fn($record) => $record->profitLogs()->exists()),
        ]);
    }
}
