<?php

namespace App\Filament\Admin\Resources;

use App\Models\LoginActivity;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Filament\Admin\Resources\LoginActivityResource\Pages;

class LoginActivityResource extends Resource
{
    protected static ?string $model = LoginActivity::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Compliance';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = 'Login Activity';

    public static function table(Table $table): Table
    {
        return $table
            ->query(LoginActivity::query()->with('user')->latest('logged_in_at'))
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
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP address')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('device')
                    ->searchable()
                    ->limit(30)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('device_type')
                    ->badge()
                    ->color(fn(?string $state) => match ($state) {
                        'mobile' => 'info',
                        'tablet' => 'warning',
                        'desktop' => 'success',
                        default => 'gray',
                    })
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('location')
                    ->searchable()
                    ->placeholder('Unknown')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('successful')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('logged_in_at')
                    ->label('Logged in at')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('successful')
                    ->label('Status')
                    ->trueLabel('Successful')
                    ->falseLabel('Failed'),

                Tables\Filters\SelectFilter::make('device_type')
                    ->options([
                        'desktop' => 'Desktop',
                        'mobile' => 'Mobile',
                        'tablet' => 'Tablet',
                    ]),

                Tables\Filters\Filter::make('logged_in_at')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')
                            ->native(false),
                        \Filament\Forms\Components\DatePicker::make('until')
                            ->native(false),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn($q, $date) => $q->whereDate('logged_in_at', '>=', $date))
                            ->when($data['until'], fn($q, $date) => $q->whereDate('logged_in_at', '<=', $date));
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
            ->defaultSort('logged_in_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLoginActivities::route('/'),
            'view' => Pages\ViewLoginActivity::route('/{record}'),
        ];
    }
}
