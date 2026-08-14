<?php

namespace App\Filament\Admin\Resources\InvestmentResource\Pages;

use App\Filament\Admin\Resources\InvestmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvestment extends EditRecord
{
    protected static string $resource = InvestmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
