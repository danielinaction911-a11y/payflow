<?php

namespace App\Filament\Admin\Resources\WithdrawalResource\Pages;

use App\Filament\Admin\Resources\WithdrawalResource;
use App\Models\Withdrawal;
use App\Models\Transaction;
use Filament\Actions;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewWithdrawal extends ViewRecord
{
    protected static string $resource = WithdrawalResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Withdrawal details')->schema([
                Grid::make(3)->schema([
                    TextEntry::make('user.name')->label('User'),
                    TextEntry::make('amount')->formatStateUsing(fn ($record) => smart_money($record->amount, $record->currency)),
                    TextEntry::make('status')->badge(),
                    TextEntry::make('method'),
                    TextEntry::make('transaction_id')->label('Reference')->copyable(),
                    TextEntry::make('created_at')->dateTime(),
                ]),
            ]),

            Section::make('Destination details submitted by user')
                ->schema(function ($record) {
                    $metadata = collect($record->metadata ?? [])->except('balance_source');

                    return $metadata->map(
                        fn ($value, $key) => TextEntry::make("metadata.{$key}")
                            ->label(ucwords(str_replace('_', ' ', $key)))
                            ->getStateUsing(fn () => $value)
                            ->copyable()
                    )->values()->toArray() ?: [TextEntry::make('none')->label('')->getStateUsing(fn () => 'No details submitted.')];
                }),

            Section::make('Rejection')
                ->visible(fn ($record) => $record->status->value === 'rejected')
                ->schema([
                    TextEntry::make('rejection_reason')->label('Reason'),
                ]),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->disabled(fn (Withdrawal $record): bool => $record->transaction_id !== null || Transaction::where('metadata->withdrawal_id', $record->id)->exists())
                ->tooltip(fn (Withdrawal $record): ?string => ($record->transaction_id !== null || Transaction::where('metadata->withdrawal_id', $record->id)->exists())
                    ? 'Cannot delete this withdrawal because a related transaction exists.'
                    : null),
        ];
    }
}