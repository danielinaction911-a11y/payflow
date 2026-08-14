<?php

namespace App\Filament\Admin\Resources\TransactionResource\Pages;

use App\Enums\TransactionDirection;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Filament\Admin\Resources\TransactionResource;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Str;

class ViewTransaction extends ViewRecord
{
    protected static string $resource = TransactionResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Transaction overview')
                ->schema([
                    Infolists\Components\TextEntry::make('reference')->copyable(),
                    Infolists\Components\TextEntry::make('user.name')->label('User'),
                    Infolists\Components\TextEntry::make('user.email')->label('Email'),
                    Infolists\Components\TextEntry::make('wallet.currency.code')->label('Wallet currency'),

                    Infolists\Components\TextEntry::make('type')
                        ->badge()
                        ->formatStateUsing(fn (TransactionType $state) => Str::headline($state->value)),

                    Infolists\Components\TextEntry::make('direction')
                        ->badge()
                        ->formatStateUsing(fn (TransactionDirection $state) => Str::headline($state->value))
                        ->color(fn (TransactionDirection $state) => $state === TransactionDirection::Credit ? 'success' : 'danger'),

                    Infolists\Components\TextEntry::make('status')
                        ->badge()
                        ->formatStateUsing(fn (TransactionStatus $state) => Str::headline($state->value))
                        ->color(fn (TransactionStatus $state) => match ($state) {
                            TransactionStatus::Completed => 'success',
                            TransactionStatus::Pending => 'warning',
                            TransactionStatus::Failed => 'danger',
                            TransactionStatus::Reversed => 'gray',
                        }),

                    Infolists\Components\TextEntry::make('currency')->badge(),
                ])
                ->columns(4),

            Infolists\Components\Section::make('Amounts')
                ->schema([
                    Infolists\Components\TextEntry::make('amount')->numeric(decimalPlaces: 2),
                    Infolists\Components\TextEntry::make('fee')->numeric(decimalPlaces: 2),
                ])
                ->columns(2),

            Infolists\Components\Section::make('Details')
                ->schema([
                    Infolists\Components\TextEntry::make('description')
                        ->placeholder('—')
                        ->columnSpanFull(),

                    Infolists\Components\TextEntry::make('failed_reason')
                        ->label('Failure reason')
                        ->placeholder('—')
                        ->color('danger')
                        ->visible(fn ($record) => filled($record->failed_reason))
                        ->columnSpanFull(),
                ]),

            Infolists\Components\Section::make('Metadata')
                ->schema([
                    Infolists\Components\KeyValueEntry::make('metadata')
                        ->label('')
                        ->columnSpanFull(),
                ])
                ->visible(fn ($record) => filled($record->metadata)),

            Infolists\Components\Section::make('Timestamps')
                ->schema([
                    Infolists\Components\TextEntry::make('created_at')->dateTime('M j, Y g:i A'),
                    Infolists\Components\TextEntry::make('updated_at')->dateTime('M j, Y g:i A'),
                ])
                ->columns(2),
        ]);
    }
}