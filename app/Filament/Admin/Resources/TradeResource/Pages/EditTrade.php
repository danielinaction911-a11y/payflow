<?php

namespace App\Filament\Admin\Resources\TradeResource\Pages;

use App\Filament\Admin\Resources\TradeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrade extends EditRecord
{
    protected static string $resource = TradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
