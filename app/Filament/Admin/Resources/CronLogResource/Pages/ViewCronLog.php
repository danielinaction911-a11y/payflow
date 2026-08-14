<?php

namespace App\Filament\Admin\Resources\CronLogResource\Pages;

use App\Filament\Admin\Resources\CronLogResource;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Str;

class ViewCronLog extends ViewRecord
{
    protected static string $resource = CronLogResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Job overview')
                ->schema([
                    Infolists\Components\TextEntry::make('name')->weight('bold'),

                    Infolists\Components\TextEntry::make('status')
                        ->badge()
                        ->formatStateUsing(fn (string $state) => Str::headline($state))
                        ->color(fn (string $state) => match ($state) {
                            'success', 'completed' => 'success',
                            'running' => 'warning',
                            'failed' => 'danger',
                            default => 'gray',
                        }),

                    Infolists\Components\TextEntry::make('duration')
                        ->state(function ($record) {
                            $seconds = $record->durationInSeconds();

                            return $seconds === null ? 'Still running / unknown' : $seconds . ' seconds';
                        }),
                ])
                ->columns(3),

            Infolists\Components\Section::make('Results')
                ->schema([
                    Infolists\Components\TextEntry::make('processed')->numeric(),
                    Infolists\Components\TextEntry::make('completed')->numeric()->color('success'),
                    Infolists\Components\TextEntry::make('skipped')->numeric()->color('gray'),
                    Infolists\Components\TextEntry::make('failed')
                        ->numeric()
                        ->color(fn ($state) => $state > 0 ? 'danger' : 'gray'),
                ])
                ->columns(4),

            Infolists\Components\Section::make('Message')
                ->schema([
                    Infolists\Components\TextEntry::make('message')
                        ->placeholder('No message logged')
                        ->columnSpanFull(),
                ])
                ->visible(fn ($record) => filled($record->message)),

            Infolists\Components\Section::make('Timing')
                ->schema([
                    Infolists\Components\TextEntry::make('started_at')->dateTime('M j, Y g:i:s A'),
                    Infolists\Components\TextEntry::make('finished_at')
                        ->dateTime('M j, Y g:i:s A')
                        ->placeholder('Not finished / still running'),
                ])
                ->columns(2),
        ]);
    }
}