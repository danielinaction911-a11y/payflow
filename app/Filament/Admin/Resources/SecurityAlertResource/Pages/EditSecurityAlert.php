<?php

namespace App\Filament\Admin\Resources\SecurityAlertResource\Pages;

use App\Filament\Admin\Resources\SecurityAlertResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSecurityAlert extends EditRecord
{
    protected static string $resource = SecurityAlertResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
