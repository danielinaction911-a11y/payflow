<?php

namespace App\Filament\Admin\Resources\SupportTicketResource\Pages;

use App\Filament\Admin\Resources\SupportTicketResource;
use App\Enums\SupportTicketStatus;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Models\Notification;
use App\Traits\HandlesFileUploads;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Resources\Pages\ViewRecord;

class ViewSupportTicket extends ViewRecord
{
    use HandlesFileUploads;

    protected static string $resource = SupportTicketResource::class;

    public function mount(int|string $record): void
    {
        parent::mount($record);

        // Mark customer replies read the moment admin opens the ticket.
        TicketReply::where('support_ticket_id', $this->record->id)
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true]);

    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('reply')
                ->label('Reply')
                ->icon('heroicon-m-arrow-uturn-left')
                ->visible(fn (SupportTicket $record): bool => $record->status !== SupportTicketStatus::Closed->value)
                ->form([
                    Forms\Components\Textarea::make('message')
                        ->label('Reply')
                        ->rows(3)
                        ->required(fn (Forms\Get $get) => ! $get('attachment')),

                    Forms\Components\FileUpload::make('attachment')
                        ->label('Attachment (optional)')
                        ->image()
                        ->maxSize(2048)
                        ->saveUploadedFileUsing(function ($file) {
                            return $this->uploadFile($file, 'images/tickets', null, 'reply_admin');
                        }),
                ])
                ->action(function (array $data) {
                    $ticket = $this->record;

                    $reply = TicketReply::create([
                        'support_ticket_id' => $ticket->id,
                        'sender_type' => 'admin',
                        'sender_id' => auth('admin')->id(),
                        'message' => $data['message'] ?? '',
                        'attachment_path' => $data['attachment'] ?? null,
                        'is_read' => true,
                    ]);

                    // Keep existing code's 'pending' status string for compatibility
                    $ticket->update(['status' => 'pending']);

                    Notification::create([
                        'user_id' => $ticket->user_id,
                        'title' => 'New reply on your ticket',
                        'body' => 'Support replied to "' . $ticket->subject . '".',
                        'type' => 'info',
                        'is_read' => false,
                    ]);

                    FilamentNotification::make()->success()->title('Reply sent')->send();
                    $this->fillForm();
                }),
            Actions\DeleteAction::make()
                ->disabled(fn (SupportTicket $record): bool => $record->replies()->exists())
                ->tooltip(fn (SupportTicket $record): ?string => $record->replies()->exists()
                    ? 'Cannot delete this ticket because it has replies.'
                    : null),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Ticket overview')->schema([
                Grid::make(4)->schema([
                    TextEntry::make('user.name')->label('User'),
                    TextEntry::make('user.email')->label('Email'),
                    TextEntry::make('priority')->badge(),
                    TextEntry::make('status')->badge(),
                    TextEntry::make('subject')->columnSpan(2),
                    TextEntry::make('created_at')->label('Opened')->dateTime(),
                ]),
            ]),
        ]);
    }
}