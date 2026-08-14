<?php

namespace App\Filament\Admin\Resources\KycResource\Pages;

use App\Filament\Admin\Resources\KycResource;
use App\Models\Kyc;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKyc extends EditRecord
{
    protected static string $resource = KycResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->disabled(fn (Kyc $record): bool => $record->documents()->exists())
                ->tooltip(fn (Kyc $record): ?string => $record->documents()->exists()
                    ? 'Cannot delete this KYC type because there are existing documents.'
                    : null),
        ];
    }
}