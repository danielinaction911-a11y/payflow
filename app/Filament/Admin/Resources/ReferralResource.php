<?php

namespace App\Filament\Admin\Resources;

use App\Models\Referral;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Admin\Resources\ReferralResource\Pages;

class ReferralResource extends Resource
{
    protected static ?string $model = Referral::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Referrals';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Referrals';

    public static function table(Table $table): Table
    {
        return $table
            ->query(Referral::query()->with(['referrer', 'referred'])->latest())
            ->columns([
                Tables\Columns\TextColumn::make('referrer.name')
                    ->label('Referrer')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('referred.name')
                    ->label('Referred user')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('level')
                    ->badge()
                    ->formatStateUsing(fn ($state) => "Level {$state}")
                    ->color(fn ($state) => match ((int) $state) {
                        1 => 'success',
                        2 => 'info',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('commissions_count')
                    ->label('Commissions')
                    ->counts('commissions')
                    ->badge(),

                Tables\Columns\TextColumn::make('commissions_sum_amount')
                    ->label('Total earned')
                    ->state(fn (Referral $record) => $record->commissions()->sum('amount'))
                    ->money('USD'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Referred on')
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('level')
                    ->options([
                        1 => 'Level 1 (Direct)',
                        2 => 'Level 2 (Indirect)',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReferrals::route('/'),
            'view' => Pages\ViewReferral::route('/{record}'),
        ];
    }
}