<?php

namespace App\Filament\Admin\Resources\DepositResource\Pages;

use App\Filament\Admin\Resources\DepositResource;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewDeposit extends ViewRecord
{
    protected static string $resource = DepositResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Deposit details')->schema([
                Grid::make(3)->schema([
                    TextEntry::make('user.name')->label('User'),
                    TextEntry::make('amount')->formatStateUsing(fn ($record) => smart_money($record->amount, $record->currency)),
                    TextEntry::make('status')->badge(),
                    TextEntry::make('method'),
                    TextEntry::make('transaction_id')->label('Reference')->copyable(),
                    TextEntry::make('created_at')->dateTime(),
                ]),
            ]),

            Section::make('Submitted proof / details')
                ->schema(function ($record) {
                    $metadata = $record->metadata ?? [];
                    $entries = [];

                    foreach ($metadata as $key => $value) {
                        if (is_string($value) && (str_ends_with($value, '.jpg') || str_ends_with($value, '.jpeg') || str_ends_with($value, '.png'))) {
                            $entries[] = ImageEntry::make("metadata.{$key}")
                                ->label(ucwords(str_replace('_', ' ', $key)))
                                ->getStateUsing(fn () => asset($value))
                                ->height(220);
                        } elseif (is_string($value) && str_ends_with($value, '.pdf')) {
                            $entries[] = TextEntry::make("metadata.{$key}")
                                ->label(ucwords(str_replace('_', ' ', $key)))
                                ->getStateUsing(fn () => $value)
                                ->url(fn () => asset($value))
                                ->openUrlInNewTab();
                        } else {
                            $entries[] = TextEntry::make("metadata.{$key}")
                                ->label(ucwords(str_replace('_', ' ', $key)))
                                ->getStateUsing(fn () => $value);
                        }
                    }

                    return $entries ?: [TextEntry::make('none')->label('')->getStateUsing(fn () => 'No additional details submitted.')];
                }),

            Section::make('Rejection')
                ->visible(fn ($record) => $record->status->value === 'rejected')
                ->schema([
                    TextEntry::make('rejection_reason')->label('Reason'),
                ]),
        ]);
    }
}