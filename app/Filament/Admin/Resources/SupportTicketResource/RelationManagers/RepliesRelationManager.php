<?php

namespace App\Filament\Admin\Resources\SupportTicketResource\RelationManagers;

use App\Models\Notification;
use App\Traits\HandlesFileUploads;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class RepliesRelationManager extends RelationManager
{
    use HandlesFileUploads;

    protected static string $relationship = 'replies';
    protected static ?string $title = 'Conversation';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Textarea::make('message')
                ->label('Reply')
                ->rows(3)
                ->required(fn (Forms\Get $get) => ! $get('attachment')),

            Forms\Components\FileUpload::make('attachment')
                ->label('Attachment (optional)')
                ->image()
                ->maxSize(2048)
                // Store the actual uploaded file straight to public/images/tickets
                // using the same trait/location the customer-side form uses, so
                // both admin- and user-uploaded attachments resolve identically
                // via asset($path) with no disk/symlink mismatch.
                ->saveUploadedFileUsing(function ($file) {
                    return $this->uploadFile($file, 'images/tickets', null, 'reply_admin');
                }),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('message')
            ->columns([
                Tables\Columns\TextColumn::make('sender_type')
                    ->badge()
                    ->color(fn (string $state) => $state === 'admin' ? 'success' : 'gray')
                    ->formatStateUsing(fn (string $state) => $state === 'admin' ? 'Support' : 'Customer'),

                Tables\Columns\TextColumn::make('message')
                    ->wrap()
                    ->limit(120),

                Tables\Columns\ImageColumn::make('attachment_path')
                    ->label('Attachment')
                    ->getStateUsing(fn ($record) => $record->attachment_path ? asset($record->attachment_path) : null)
                    ->visibility('visible')
                    ->height(60)
                    ->width(60)
                    ->extraImgAttributes(['class' => 'rounded-lg object-cover'])
                    ->url(fn ($record) => $record->attachment_path ? asset($record->attachment_path) : null)
                    ->openUrlInNewTab(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Sent')
                    ->dateTime('M j, g:i A'),
            ])
            ->defaultSort('created_at', 'asc')
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Send reply')
                    ->icon('heroicon-m-paper-airplane')
                    ->using(function (array $data, RelationManager $livewire) {
                        $ticket = $livewire->getOwnerRecord();

                        $reply = $ticket->replies()->create([
                            'sender_type' => 'admin',
                            'sender_id' => auth('admin')->id(),
                            'message' => $data['message'] ?? '',
                            'attachment_path' => $data['attachment'] ?? null,
                            'is_read' => true,
                        ]);

                        $ticket->update(['status' => 'pending']);

                        Notification::create([
                            'user_id' => $ticket->user_id,
                            'title' => 'New reply on your ticket',
                            'body' => 'Support replied to "' . $ticket->subject . '".',
                            'type' => 'info',
                            'is_read' => false,
                        ]);

                        return $reply;
                    }),
            ])
            ->actions([]) // no edit/delete on individual messages — keep the thread immutable
            ->paginated(false);
    }
}