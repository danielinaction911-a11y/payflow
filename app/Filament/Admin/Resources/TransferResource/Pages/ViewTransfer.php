<?php

namespace App\Filament\Admin\Resources\TransferResource\Pages;

use App\Filament\Admin\Resources\TransferResource;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewTransfer extends ViewRecord
{
    protected static string $resource = TransferResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Transfer details')->schema([
                Grid::make(2)->schema([
                    TextEntry::make('sender.name')
                        ->label('Sender')
                        ->formatStateUsing(fn ($state, $record) => $record->sender?->name ?? '@' . $record->sender?->username),

                    TextEntry::make('recipient.name')
                        ->label('Recipient')
                        ->formatStateUsing(fn ($state, $record) => $record->recipient?->name ?? '@' . $record->recipient?->username),

                    TextEntry::make('amount')
                        ->formatStateUsing(fn ($record) => money_format($record->amount)),

                    TextEntry::make('status')
                        ->badge()
                        ->color(fn ($state) => match ($state->value ?? $state) {
                            'completed' => 'success',
                            'pending' => 'warning',
                            'failed' => 'danger',
                            default => 'gray',
                        }),

                    TextEntry::make('reference')->label('Reference')->copyable(),

                    TextEntry::make('created_at')->dateTime(),
                ]),
            ]),

            Section::make('Note')
                ->schema([
                    TextEntry::make('description')
                        ->label('Note')
                        ->placeholder('No note provided.'),
                ]),
        ]);
    }
}
