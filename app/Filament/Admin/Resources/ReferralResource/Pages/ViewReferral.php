<?php

namespace App\Filament\Admin\Resources\ReferralResource\Pages;

use App\Filament\Admin\Resources\ReferralResource;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Str;

class ViewReferral extends ViewRecord
{
    protected static string $resource = ReferralResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Referral overview')
                ->schema([
                    Infolists\Components\TextEntry::make('referrer.name')->label('Referrer'),
                    Infolists\Components\TextEntry::make('referrer.email')->label('Referrer email'),
                    Infolists\Components\TextEntry::make('referred.name')->label('Referred user'),
                    Infolists\Components\TextEntry::make('referred.email')->label('Referred email'),
                    Infolists\Components\TextEntry::make('level')
                        ->badge()
                        ->formatStateUsing(fn ($state) => "Level {$state}"),
                    Infolists\Components\TextEntry::make('created_at')->dateTime('M j, Y g:i A'),
                ])
                ->columns(3),

            Infolists\Components\Section::make('Commission history')
                ->schema([
                    Infolists\Components\RepeatableEntry::make('commissions')
                        ->label('')
                        ->schema([
                            Infolists\Components\TextEntry::make('amount')->money('USD'),

                            Infolists\Components\TextEntry::make('status')
                                ->badge()
                                ->formatStateUsing(fn (string $state) => Str::headline($state))
                                ->color(fn (string $state) => match ($state) {
                                    'paid', 'completed' => 'success',
                                    'pending' => 'warning',
                                    'cancelled' => 'danger',
                                    default => 'gray',
                                }),

                            Infolists\Components\TextEntry::make('created_at')
                                ->dateTime('M j, Y g:i A'),
                        ])
                        ->columns(3),
                ])
                ->visible(fn ($record) => $record->commissions()->exists()),
        ]);
    }
}