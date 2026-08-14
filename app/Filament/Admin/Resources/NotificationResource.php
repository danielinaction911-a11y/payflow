<?php

namespace App\Filament\Admin\Resources;

use App\Models\Notification;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Filament\Admin\Resources\NotificationResource\Pages;

class NotificationResource extends Resource
{
    protected static ?string $model = Notification::class;
    protected static ?string $navigationIcon = 'heroicon-o-bell';
    protected static ?string $navigationGroup = 'Communication';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationLabel = 'Notifications';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Send notification')
                ->schema([
                    Forms\Components\Select::make('user_id')
                        ->label('User')
                        ->relationship('user', 'name')
                        ->searchable(['name', 'email'])
                        ->getSearchResultsUsing(
                            fn (string $search) => User::query()
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->limit(50)
                                ->pluck('name', 'id')
                        )
                        ->getOptionLabelUsing(fn ($value) => User::find($value)?->name)
                        ->preload()
                        ->required(),

                    Forms\Components\Select::make('type')
                        ->options([
                            Notification::info => 'Info',
                            Notification::success => 'Success',
                            Notification::warning => 'Warning',
                            Notification::error => 'Error',
                        ])
                        ->default(Notification::info)
                        ->required(),

                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('body')
                        ->required()
                        ->rows(4)
                        ->columnSpanFull(),

                    Forms\Components\FileUpload::make('image')
                        ->label('Image (optional)')
                        ->image()
                        ->disk('notification_images')
                        ->directory('')
                        ->visibility('public')
                        ->saveUploadedFileUsing(function ($file) {
                            $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
                            $file->storeAs('', $filename, ['disk' => 'notification_images']);

                            return $filename;
                        })
                        ->imagePreviewHeight('150')
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Notification::query()->with('user')->latest())
            ->columns([
                Tables\Columns\ImageColumn::make('user.avatar')
                    ->label('')
                    ->getStateUsing(fn ($record) => $record->user?->avatar
                        ? asset($record->user->avatar)
                        : null)
                    ->defaultImageUrl(function ($record) {
                        $name = $record->user?->name ?: 'N';
                        $initials = Str::of($name)
                            ->trim()
                            ->explode(' ')
                            ->take(2)
                            ->map(fn ($word) => Str::substr($word, 0, 1))
                            ->implode('');

                        return 'https://ui-avatars.com/api/?name=' . urlencode($initials ?: 'N') . '&background=e5e7eb&color=6b7280';
                    })
                    ->circular(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        Notification::success => 'success',
                        Notification::warning => 'warning',
                        Notification::error => 'danger',
                        default => 'info',
                    }),

                Tables\Columns\IconColumn::make('is_read')
                    ->label('Read')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Sent')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        Notification::info => 'Info',
                        Notification::success => 'Success',
                        Notification::warning => 'Warning',
                        Notification::error => 'Error',
                    ]),

                Tables\Filters\TernaryFilter::make('is_read')
                    ->label('Read'),
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
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotifications::route('/'),
            'create' => Pages\CreateNotification::route('/create'),
            'view' => Pages\ViewNotification::route('/{record}'),
        ];
    }
}