<?php

namespace App\Filament\Admin\Resources\SecurityAlertResource\Pages;

use App\Filament\Admin\Resources\SecurityAlertResource;
use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Str;

class ViewSecurityAlert extends ViewRecord
{
    protected static string $resource = SecurityAlertResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Alert details')
                ->schema([
                    Infolists\Components\TextEntry::make('user.name')->label('User'),
                    Infolists\Components\TextEntry::make('user.email')->label('Email'),
                    Infolists\Components\TextEntry::make('type')
                        ->badge()
                        ->formatStateUsing(fn (string $state) => Str::headline($state)),
                    Infolists\Components\TextEntry::make('description')
                        ->columnSpanFull(),
                ])
                ->columns(3),

            Infolists\Components\Section::make('Status')
                ->schema([
                    Infolists\Components\IconEntry::make('resolved_at')
                        ->label('Resolved')
                        ->boolean()
                        ->getStateUsing(fn ($record) => filled($record->resolved_at)),

                    Infolists\Components\TextEntry::make('resolved_at')
                        ->label('Resolved at')
                        ->dateTime('M j, Y g:i A')
                        ->placeholder('Not yet resolved'),

                    Infolists\Components\TextEntry::make('created_at')
                        ->label('Triggered at')
                        ->dateTime('M j, Y g:i A'),
                ])
                ->columns(3),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('resolve')
                ->label('Mark resolved')
                ->icon('heroicon-m-check-circle')
                ->color('success')
                ->visible(fn () => is_null($this->record->resolved_at))
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['resolved_at' => now()]);
                    $this->fillForm();
                }),

            Actions\DeleteAction::make(),
        ];
    }
}