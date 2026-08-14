<?php

namespace App\Filament\Admin\Resources\WithdrawGatewayResource\Pages;

use App\Filament\Admin\Resources\WithdrawGatewayResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWithdrawGateways extends ListRecords
{
    protected static string $resource = WithdrawGatewayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
