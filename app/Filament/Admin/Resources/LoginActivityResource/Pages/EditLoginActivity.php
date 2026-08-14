<?php

namespace App\Filament\Admin\Resources\LoginActivityResource\Pages;

use App\Filament\Admin\Resources\LoginActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLoginActivity extends EditRecord
{
    protected static string $resource = LoginActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
