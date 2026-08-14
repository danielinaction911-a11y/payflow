<?php

namespace App\Filament\Admin\Resources\TradeResource\Pages;

use App\Enums\OrderType;
use App\Enums\TradeDirection;
use App\Enums\TradeStatus;
use App\Filament\Admin\Resources\TradeResource;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewTrade extends ViewRecord
{
    protected static string $resource = TradeResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Trade overview')
                ->schema([
                    Infolists\Components\TextEntry::make('user.name')->label('User'),
                    Infolists\Components\TextEntry::make('user.email')->label('Email'),
                    Infolists\Components\TextEntry::make('tradingPair.symbol')->label('Pair'),
                    Infolists\Components\TextEntry::make('side')
                        ->badge()
                        ->formatStateUsing(fn (TradeDirection $state) => $state->label())
                        ->color(fn (TradeDirection $state) => $state === TradeDirection::Buy ? 'success' : 'danger'),
                ])
                ->columns(4),

            Infolists\Components\Section::make('Order details')
                ->schema([
                    Infolists\Components\TextEntry::make('order_type')
                        ->badge()
                        ->formatStateUsing(fn (OrderType $state) => $state->label()),

                    Infolists\Components\TextEntry::make('status')
                        ->badge()
                        ->formatStateUsing(fn (TradeStatus $state) => $state->label())
                        ->color(fn (TradeStatus $state) => $state->color()),

                    Infolists\Components\TextEntry::make('amount')->numeric(decimalPlaces: 8),
                    Infolists\Components\TextEntry::make('price')->numeric(decimalPlaces: 8),
                    Infolists\Components\TextEntry::make('total')->money('USD'),
                    Infolists\Components\TextEntry::make('expires_at')
                        ->dateTime('M j, Y g:i A')
                        ->placeholder('No expiry'),
                ])
                ->columns(3),

            Infolists\Components\Section::make('Timestamps')
                ->schema([
                    Infolists\Components\TextEntry::make('created_at')->label('Placed at')->dateTime('M j, Y g:i A'),
                    Infolists\Components\TextEntry::make('updated_at')->label('Last updated')->dateTime('M j, Y g:i A'),
                ])
                ->columns(2),
        ]);
    }
}