<?php

namespace App\Filament\Admin\Resources\CurrencyResource\Pages;

use App\Filament\Admin\Resources\CurrencyResource;
use App\Models\Currency;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\File;

class EditCurrency extends EditRecord
{
    protected static string $resource = CurrencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->disabled(fn (Currency $record): bool =>
                    $record->wallets()->exists() 
                    || $record->baseTradingPairs()->exists()
                    || $record->quoteTradingPairs()->exists()
                )
                ->tooltip(fn (Currency $record): ?string => (
                    $record->wallets()->exists() 
                    || $record->baseTradingPairs()->exists()
                    || $record->quoteTradingPairs()->exists()
                ) ? 'Cannot delete this currency because it has related records.' : null),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $oldIcon = $this->record->getRawOriginal('icon');
        $newIcon = $data['icon'] ?? null;

        if ($oldIcon && $newIcon && $oldIcon !== $newIcon) {
            $path = public_path($oldIcon);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        return $data;
    }
}