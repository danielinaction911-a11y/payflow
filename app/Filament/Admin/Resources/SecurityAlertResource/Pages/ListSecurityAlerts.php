<?php

namespace App\Filament\Admin\Resources\SecurityAlertResource\Pages;

use App\Filament\Admin\Resources\SecurityAlertResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSecurityAlerts extends ListRecords
{
    protected static string $resource = SecurityAlertResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
