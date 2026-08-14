<?php

namespace App\Filament\Admin\Resources\WalletResource\Pages;

use App\Filament\Admin\Resources\WalletResource;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewWallet extends ViewRecord
{
    protected static string $resource = WalletResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Wallet overview')
                ->schema([
                    Infolists\Components\TextEntry::make('user.name')->label('User'),
                    Infolists\Components\TextEntry::make('user.email')->label('Email'),
                    Infolists\Components\TextEntry::make('currency.name')->label('Currency'),
                    Infolists\Components\TextEntry::make('currency.code')->label('Code')->badge(),
                    Infolists\Components\IconEntry::make('is_primary')->boolean(),
                ])
                ->columns(3),

            Infolists\Components\Section::make('Balances')
                ->schema([
                    Infolists\Components\TextEntry::make('available')->numeric(decimalPlaces: 8),
                    Infolists\Components\TextEntry::make('locked')->numeric(decimalPlaces: 8),
                    Infolists\Components\TextEntry::make('total')
                        ->state(fn ($record) => $record->totalBalance())
                        ->numeric(decimalPlaces: 8),
                ])
                ->columns(3),

            Infolists\Components\Section::make('Recent transactions')
                ->schema([
                    Infolists\Components\RepeatableEntry::make('transactions')
                        ->label('')
                        ->schema([
                            Infolists\Components\TextEntry::make('type')->badge(),
                            Infolists\Components\TextEntry::make('amount')->numeric(decimalPlaces: 8),
                            Infolists\Components\TextEntry::make('created_at')->dateTime('M j, Y g:i A'),
                        ])
                        ->columns(3),
                ])
                ->visible(fn ($record) => $record->transactions()->exists()),
        ]);
    }
}