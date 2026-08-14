<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('view')
                ->label('View activity')
                ->icon('heroicon-m-eye')
                ->url(fn () => static::getResource()::getUrl('view', ['record' => $this->record])),
            \Filament\Actions\DeleteAction::make()
                ->disabled(fn (User $record): bool => $record->hasRelatedRecords())
                ->tooltip(fn (User $record): ?string => $record->hasRelatedRecords() ? 'Cannot delete this user because related records exist.' : null),
        ];
    }
}
