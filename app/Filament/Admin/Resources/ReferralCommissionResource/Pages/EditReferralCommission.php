<?php

namespace App\Filament\Admin\Resources\ReferralCommissionResource\Pages;

use App\Filament\Admin\Resources\ReferralCommissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReferralCommission extends EditRecord
{
    protected static string $resource = ReferralCommissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
