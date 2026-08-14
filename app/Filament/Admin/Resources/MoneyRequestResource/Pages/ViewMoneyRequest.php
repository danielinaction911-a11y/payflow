<?php

namespace App\Filament\Admin\Resources\MoneyRequestResource\Pages;

use App\Filament\Admin\Resources\MoneyRequestResource;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewMoneyRequest extends ViewRecord
{
    protected static string $resource = MoneyRequestResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Request details')->schema([
                Grid::make(2)->schema([
                    TextEntry::make('requester.name')->label('Requester'),
                    TextEntry::make('recipient.name')->label('Requested From'),
                    TextEntry::make('amount')->formatStateUsing(fn ($record) => money_format($record->amount)),
                    TextEntry::make('status')
                        ->badge()
                        ->color(fn ($state) => match ($state->value ?? $state) {
                            'pending' => 'warning',
                            'accepted' => 'success',
                            'declined' => 'danger',
                            'expired' => 'secondary',
                            default => 'gray',
                        }),
                    TextEntry::make('expires_at')->label('Expires')->dateTime(),
                    TextEntry::make('created_at')->label('Created')->dateTime(),
                ]),
            ]),
            Section::make('Message')->schema([
                TextEntry::make('message')->placeholder('No message provided.'),
            ]),
        ]);
    }
}
