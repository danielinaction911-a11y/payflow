<?php

namespace App\Filament\Admin\Resources\TransactionResource\Pages;

use App\Filament\Admin\Resources\TransactionResource;
use App\Models\Transaction;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->disabled(fn (Transaction $record): bool => $record->hasRelatedReference())
                ->tooltip(fn (Transaction $record): ?string => $record->hasRelatedReference()
                    ? 'Cannot delete this transaction because it is linked to another record.'
                    : null),
        ];
    }
}
