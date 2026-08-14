<?php

namespace App\Filament\Admin\Resources;

use App\Models\ReferralCommission;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Filament\Admin\Resources\ReferralCommissionResource\Pages;

class ReferralCommissionResource extends Resource
{
    protected static ?string $model = ReferralCommission::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Referrals';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Commissions';

    public static function table(Table $table): Table
    {
        return $table
            ->query(ReferralCommission::query()->with(['referral.referrer', 'referral.referred'])->latest())
            ->columns([
                Tables\Columns\TextColumn::make('referral.referrer.name')
                    ->label('Referrer')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('referral.referred.name')
                    ->label('From referred user')
                    ->searchable(),

                Tables\Columns\TextColumn::make('referral.level')
                    ->label('Level')
                    ->badge()
                    ->formatStateUsing(fn ($state) => "Level {$state}"),

                Tables\Columns\TextColumn::make('amount')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Str::headline($state))
                    ->color(fn (string $state) => match ($state) {
                        'paid', 'completed' => 'success',
                        'pending' => 'warning',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('sourceTransaction.reference')
                    ->label('Source transaction')
                    ->searchable()
                    ->copyable()
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(fn () => ReferralCommission::query()
                        ->distinct()
                        ->pluck('status', 'status')
                        ->mapWithKeys(fn ($status) => [$status => Str::headline($status)])
                        ->toArray()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('mark_paid')
                    ->label('Mark paid')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->visible(fn (ReferralCommission $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalDescription('Mark this commission as paid? Make sure the referrer\'s wallet has actually been credited before confirming.')
                    ->action(function (ReferralCommission $record) {
                        $record->update(['status' => 'paid']);

                        Notification::make()
                            ->success()
                            ->title('Commission marked as paid')
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('mark_paid_bulk')
                        ->label('Mark paid')
                        ->icon('heroicon-m-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (\Illuminate\Support\Collection $records) {
                            $records->each(fn (ReferralCommission $record) =>
                                $record->status === 'pending' ? $record->update(['status' => 'paid']) : null
                            );

                            Notification::make()
                                ->success()
                                ->title('Commissions updated')
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReferralCommissions::route('/'),
            'view' => Pages\ViewReferralCommission::route('/{record}'),
        ];
    }
}