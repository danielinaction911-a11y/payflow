<?php

namespace App\Filament\Admin\Resources\WithdrawGatewayResource\Pages;

use App\Filament\Admin\Resources\WithdrawGatewayResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\File;

class EditWithdrawGateway extends EditRecord
{
    protected static string $resource = WithdrawGatewayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
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

        return $data;
    }
}