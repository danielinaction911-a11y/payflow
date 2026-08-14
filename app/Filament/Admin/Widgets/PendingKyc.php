<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\KycStatus;
use App\Models\KycDocument;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingKyc extends BaseWidget
{
    protected static ?string $heading = 'Pending KYC submissions';
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                KycDocument::query()
                    ->with(['user', 'kyc'])
                    ->where('status', KycStatus::Pending)
                    ->latest()
                    ->limit(8)
            )
            ->columns([ 
                Tables\Columns\TextColumn::make('user.name')->label('User')->searchable(),
                Tables\Columns\TextColumn::make('kyc.type')->label('Document type'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('M j, Y')->label('Submitted'),
            ])
            ->actions([
                Tables\Actions\Action::make('review')
                    ->label('Review')
                    ->url(fn ($record) => route('filament.admin.resources.kyc-documents.view', $record))
                    ->icon('heroicon-m-eye'),
            ]) 
            ->paginated(false);
    }
}