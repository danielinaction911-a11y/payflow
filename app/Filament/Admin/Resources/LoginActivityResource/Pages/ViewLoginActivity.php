<?php

namespace App\Filament\Admin\Resources\LoginActivityResource\Pages;

use App\Filament\Admin\Resources\LoginActivityResource;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewLoginActivity extends ViewRecord
{
    protected static string $resource = LoginActivityResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Login details')
                ->schema([
                    Infolists\Components\TextEntry::make('user.name')->label('User'),
                    Infolists\Components\TextEntry::make('user.email')->label('Email'),
                    Infolists\Components\IconEntry::make('successful')
                        ->boolean()
                        ->trueColor('success')
                        ->falseColor('danger'),
                    Infolists\Components\TextEntry::make('logged_in_at')
                        ->dateTime('M j, Y g:i A'),
                ])
                ->columns(2),

            Infolists\Components\Section::make('Device & location')
                ->schema([
                    Infolists\Components\TextEntry::make('ip_address')->copyable(),
                    Infolists\Components\TextEntry::make('device')->placeholder('—'),
                    Infolists\Components\TextEntry::make('device_type')
                        ->badge()
                        ->placeholder('—'),
                    Infolists\Components\TextEntry::make('location')->placeholder('Unknown'),
                ])
                ->columns(2),
        ]);
    }
}