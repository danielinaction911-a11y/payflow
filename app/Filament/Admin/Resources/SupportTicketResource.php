<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SupportTicketResource\Pages;
use App\Filament\Admin\Resources\SupportTicketResource\RelationManagers;
use App\Models\Notification;
use App\Models\SupportTicket;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class SupportTicketResource extends Resource
{
    protected static ?string $model = SupportTicket::class;
    protected static ?string $navigationIcon = 'heroicon-o-lifebuoy';
    protected static ?string $navigationGroup = 'Support';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('subject')->required()->disabled(),

            Forms\Components\Select::make('priority')
                ->options(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'])
                ->required(),

            Forms\Components\Select::make('status')
                ->options([
                    'open' => 'Open',
                    'pending' => 'Pending',
                    'resolved' => 'Resolved',
                    'closed' => 'Closed',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(SupportTicket::query()->with('user')->latest())
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

                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'high' => 'danger',
                        'medium' => 'warning',
                        'low' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'open' => 'success',
                        'pending' => 'warning',
                        'resolved', 'closed' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\IconColumn::make('has_unread')
                    ->label('Unread')
                    ->getStateUsing(fn (SupportTicket $record) => $record->replies()->where('sender_type', 'user')->where('is_read', false)->exists())
                    ->boolean()
                    ->trueIcon('heroicon-s-envelope')
                    ->trueColor('danger')
                    ->falseIcon('heroicon-o-envelope-open')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last activity')
                    ->dateTime('M j, g:i A')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(['open' => 'Open', 'pending' => 'Pending', 'resolved' => 'Resolved', 'closed' => 'Closed']),

                Tables\Filters\SelectFilter::make('priority')
                    ->options(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High']),
            ])
            ->actions([
                Tables\Actions\Action::make('open')
                    ->label('Open')
                    ->icon('heroicon-m-chat-bubble-left-right')
                    ->url(fn (SupportTicket $record) => static::getUrl('view', ['record' => $record])),

                Tables\Actions\Action::make('resolve')
                    ->label('Mark resolved')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->visible(fn (SupportTicket $record) => ! in_array($record->status, ['resolved', 'closed']))
                    ->requiresConfirmation()
                    ->action(function (SupportTicket $record) {
                        $record->update(['status' => 'resolved']);

                        Notification::create([
                            'user_id' => $record->user_id,
                            'title' => 'Ticket resolved',
                            'body' => "Your ticket \"{$record->subject}\" has been marked as resolved.",
                            'type' => 'success',
                            'is_read' => false,
                        ]);
                    }),

                Tables\Actions\DeleteAction::make()
                    ->disabled(fn (SupportTicket $record): bool => $record->replies()->exists())
                    ->tooltip(fn (SupportTicket $record): ?string => $record->replies()->exists()
                        ? 'Cannot delete this ticket because it has replies.'
                        : null),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function (\Illuminate\Support\Collection $records) {
                            $blocked = $records->filter(fn (SupportTicket $record) => $record->replies()->exists());
                            $deletable = $records->reject(fn (SupportTicket $record) => $record->replies()->exists());

                            $deletable->each->delete();

                            if ($blocked->isNotEmpty() && $deletable->isNotEmpty()) {
                                FilamentNotification::make()
                                    ->warning()
                                    ->title('Some tickets were skipped')
                                    ->body($blocked->pluck('subject')->implode(', ') . ' could not be deleted because they have replies.')
                                    ->send();
                            } elseif ($blocked->isNotEmpty()) {
                                FilamentNotification::make()
                                    ->warning()
                                    ->title('No tickets deleted')
                                    ->body($blocked->pluck('subject')->implode(', ') . ' could not be deleted because they have replies.')
                                    ->send();
                            } elseif ($deletable->isNotEmpty()) {
                                FilamentNotification::make()
                                    ->success()
                                    ->title('Support tickets deleted')
                                    ->send();
                            }
                        }),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\RepliesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupportTickets::route('/'),
            'view' => Pages\ViewSupportTicket::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $count = SupportTicket::whereIn('status', ['open', 'pending'])->count();

        if ($count <= 0) {
            return null;
        }

        return $count > 90 ? '90+' : (string) $count;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }
}