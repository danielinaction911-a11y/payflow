<?php

namespace App\Filament\Admin\Resources\CronLogResource\Pages;

use App\Filament\Admin\Resources\CronLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCronLogs extends ListRecords
{
    protected static string $resource = CronLogResource::class; 
}
