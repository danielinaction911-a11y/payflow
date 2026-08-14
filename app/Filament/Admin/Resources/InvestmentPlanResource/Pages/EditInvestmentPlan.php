<?php

namespace App\Filament\Admin\Resources\InvestmentPlanResource\Pages;

use App\Filament\Admin\Resources\InvestmentPlanResource;
use App\Models\InvestmentPlan;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvestmentPlan extends EditRecord
{
    protected static string $resource = InvestmentPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->disabled(fn (InvestmentPlan $record): bool => $record->investments()->exists())
                ->tooltip(fn (InvestmentPlan $record): ?string => $record->investments()->exists()
                    ? 'Cannot delete this plan because it has existing user investments.'
                    : null),
        ];
    }
}
