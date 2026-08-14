<?php

namespace App\Filament\Admin\Resources\NotificationResource\Pages;

use App\Filament\Admin\Resources\NotificationResource;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewNotification extends ViewRecord
{
    protected static string $resource = NotificationResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Notification details')->schema([
                ImageEntry::make('image')
                    ->label('')
                    ->getStateUsing(fn ($record) => $record->image
                        ? asset('images/notification/' . $record->image)
                        : null)
                    ->visible(fn ($record) => filled($record->image))
                    ->columnSpanFull(),

                Grid::make(2)->schema([
                    TextEntry::make('user.name')->label('User'),
                    TextEntry::make('title'),
                    TextEntry::make('body')->columnSpanFull(),
                    TextEntry::make('type')->badge(),
                    TextEntry::make('is_read')
                        ->label('Read')
                        ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No'),
                    TextEntry::make('created_at')->dateTime('M j, Y g:i A'),
                ]),
            ]),
        ]);
    }
}