<?php

namespace App\Filament\Admin\Resources\GatewayResource\Pages;

use App\Filament\Admin\Resources\GatewayResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\File;

class EditGateway extends EditRecord
{
    protected static string $resource = GatewayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return GatewayResource::unpackInstructions($data);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $oldLogo = $this->record->getRawOriginal('logo');
        $newLogo = $data['logo'] ?? null;

        if ($oldLogo && $newLogo && $oldLogo !== $newLogo) {
            $path = public_path($oldLogo);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        return GatewayResource::packInstructions($data);
    }
}