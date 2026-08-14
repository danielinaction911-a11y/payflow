<?php

namespace App\Filament\Admin\Resources\KycResource\Pages;

use App\Filament\Admin\Resources\KycResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKyc extends CreateRecord
{
    protected static string $resource = KycResource::class;
}
