<?php

namespace App\Filament\Admin\Resources\ReferralCommissionResource\Pages;

use App\Filament\Admin\Resources\ReferralCommissionResource;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Str;

class ViewReferralCommission extends ViewRecord
{
    protected static string $resource = ReferralCommissionResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Commission overview')
                ->schema([
                    Infolists\Components\TextEntry::make('referral.referrer.name')->label('Referrer'),
                    Infolists\Components\TextEntry::make('referral.referred.name')->label('From referred user'),
                    Infolists\Components\TextEntry::make('referral.level')
                        ->label('Level')
                        ->badge()
                        ->formatStateUsing(fn ($state) => "Level {$state}"),
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
                ])
                ->columns(3),

            Infolists\Components\Section::make('Source transaction')
                ->schema([
                    Infolists\Components\TextEntry::make('sourceTransaction.reference')
                        ->label('Reference')
                        ->copyable()
                        ->placeholder('—'),
                    Infolists\Components\TextEntry::make('sourceTransaction.type')
                        ->label('Type')
                        ->placeholder('—'),
                    Infolists\Components\TextEntry::make('sourceTransaction.amount')
                        ->label('Transaction amount')
                        ->money('USD')
                        ->placeholder('—'),
                ])
                ->columns(3)
                ->visible(fn ($record) => $record->sourceTransaction),

            Infolists\Components\Section::make('Timestamps')
                ->schema([
                    Infolists\Components\TextEntry::make('created_at')->dateTime('M j, Y g:i A'),
                    Infolists\Components\TextEntry::make('updated_at')->dateTime('M j, Y g:i A'),
                ])
                ->columns(2),
        ]);
    }
}