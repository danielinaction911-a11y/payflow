<?php

namespace App\Filament\Admin\Resources\KycDocumentResource\Pages;

use App\Filament\Admin\Resources\KycDocumentResource;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewKycDocument extends ViewRecord
{
    protected static string $resource = KycDocumentResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Submission overview')->schema([
                Grid::make(3)->schema([
                    TextEntry::make('user.name')->label('User'),
                    TextEntry::make('user.email')->label('Email'),
                    TextEntry::make('kyc.type')->label('Document type'),
                    TextEntry::make('status')->badge(),
                    TextEntry::make('created_at')->dateTime(),
                ]),
            ]),

            Section::make('Submitted information')
                ->schema(function ($record) {
                    $fields = $record->required_fields ?? [];
                    $entries = [];

                    foreach ($fields as $key => $value) {
                        if (! is_string($value)) {
                            continue;
                        }

                        $isImage = preg_match('/\.(jpg|jpeg|png)$/i', $value);
                        $isPdf = preg_match('/\.pdf$/i', $value);

                        if ($isImage) {
                            $entries[] = ImageEntry::make("required_fields.{$key}")
                                ->label(ucwords(str_replace('_', ' ', $key)))
                                ->getStateUsing(fn () => asset($value))
                                ->height(260);
                        } elseif ($isPdf) {
                            $entries[] = TextEntry::make("required_fields.{$key}")
                                ->label(ucwords(str_replace('_', ' ', $key)))
                                ->getStateUsing(fn () => 'View PDF document')
                                ->url(fn () => asset($value))
                                ->openUrlInNewTab()
                                ->icon('heroicon-m-document');
                        } else {
                            $entries[] = TextEntry::make("required_fields.{$key}")
                                ->label(ucwords(str_replace('_', ' ', $key)))
                                ->getStateUsing(fn () => $value)
                                ->copyable();
                        }
                    }

                    return $entries ?: [
                        TextEntry::make('none')->label('')->getStateUsing(fn () => 'No information submitted.'),
                    ];
                }),

            Section::make('Rejection details')
                ->visible(fn ($record) => $record->status->value === 'rejected')
                ->schema([
                    TextEntry::make('rejection_reason')->label('Reason'),
                ]),
        ]);
    }
}