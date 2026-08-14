<?php

namespace App\Filament\Admin\Resources\TradingPairResource\Pages;

use App\Filament\Admin\Resources\TradingPairResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTradingPairs extends ListRecords
{
    protected static string $resource = TradingPairResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
