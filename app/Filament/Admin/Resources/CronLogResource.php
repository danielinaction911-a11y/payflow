<?php

namespace App\Filament\Admin\Resources;

use App\Models\CronLog;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Filament\Admin\Resources\CronLogResource\Pages;

class CronLogResource extends Resource
{
    protected static ?string $model = CronLog::class;
    protected static ?string $navigationIcon = 'heroicon-o-command-line';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationLabel = 'Cron Logs';

    public static function getNavigationBadge(): ?string
    {
        $failedCount = static::getModel()::where('status', 'failed')
            ->where('created_at', '>=', now()->subDay())
            ->count();

        return $failedCount > 0 ? (string) $failedCount : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(CronLog::query()->latest())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Str::headline($state))
                    ->color(fn (string $state) => match ($state) {
                        'success', 'completed' => 'success',
                        'running' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('processed')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('completed')
                    ->numeric()
                    ->color('success')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('skipped')
                    ->numeric()
                    ->color('gray')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('failed')
                    ->numeric()
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'gray')
                    ->weight(fn ($state) => $state > 0 ? 'bold' : 'normal')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('duration')
                    ->label('Duration')
                    ->state(function (CronLog $record) {
                        $seconds = $record->durationInSeconds();

                        return $seconds === null ? '—' : $seconds . 's';
                    }),

                Tables\Columns\TextColumn::make('started_at')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('finished_at')
                    ->dateTime('M j, Y g:i A')
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(fn () => CronLog::query()
                        ->distinct()
                        ->pluck('status', 'status')
                        ->mapWithKeys(fn ($status) => [$status => Str::headline($status)])
                        ->toArray()),

                Tables\Filters\SelectFilter::make('name')
                    ->label('Job')
                    ->options(fn () => CronLog::query()->distinct()->pluck('name', 'name')->toArray()),

                Tables\Filters\Filter::make('has_failures')
                    ->label('Has failures')
                    ->query(fn ($query) => $query->where('failed', '>', 0))
                    ->toggle(),

                Tables\Filters\Filter::make('started_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')->native(false),
                        Forms\Components\DatePicker::make('until')->native(false),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('started_at', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('started_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s'); // auto-refresh so recent cron runs show up without manual reload
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCronLogs::route('/'),
            'view' => Pages\ViewCronLog::route('/{record}'),
        ];
    }
}