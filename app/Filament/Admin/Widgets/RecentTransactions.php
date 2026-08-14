<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Transaction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Str;

class RecentTransactions extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::query()->with('user')->latest()->limit(10)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('user.avatar')
                    ->label('')
                    ->getStateUsing(fn($record) => $record->user?->avatar
                        ? asset($record->user->avatar)
                        : null)
                    ->defaultImageUrl(function ($record) {
                        $name = $record->user?->name ?: 'N';
                        $initials = Str::of($name)
                            ->trim()
                            ->explode(' ')
                            ->take(2)
                            ->map(fn($word) => Str::substr($word, 0, 1))
                            ->implode('');

                        return 'https://ui-avatars.com/api/?name=' . urlencode($initials ?: 'N') . '&background=e5e7eb&color=6b7280';
                    })
                    ->circular(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state->label()),

                Tables\Columns\TextColumn::make('reference')
                    ->copyable()
                    ->fontFamily('mono'),

                Tables\Columns\TextColumn::make('amount')
                    ->formatStateUsing(fn ($record) => ($record->direction->value === 'credit' ? '+' : '-') . smart_money($record->amount, $record->currency))
                    ->color(fn ($record) => $record->direction->value === 'credit' ? 'success' : 'danger')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match ($state->value) {
                        'completed' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        'reversed' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M j, g:i A')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}