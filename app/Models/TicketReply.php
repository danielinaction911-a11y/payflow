<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketReply extends Model
{
    protected $fillable = [
        'support_ticket_id',
        'sender_type',
        'sender_id',
        'message',
        'attachment_path',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // Convenience: whether this reply was sent by an admin
    public function getIsFromAdminAttribute(): bool
    {
        return $this->sender_type === 'admin';
    }

    // Friendly sender label for UI
    public function getSenderLabelAttribute(): string
    {
        if ($this->sender_type === 'admin') {
            return 'Support';
        }

        if ($this->sender && method_exists($this->sender, 'name')) {
            return $this->sender->name;
        }

        return 'Customer';
    }

    // Return a fully qualified URL for the attachment if present
    public function getAttachmentUrlAttribute(): ?string
    {
        return $this->attachment_path ? asset($this->attachment_path) : null;
    }

    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class, 'support_ticket_id');
    }

    public function sender()
    {
        return $this->sender_type === 'admin'
            ? $this->belongsTo(\App\Models\Admin::class, 'sender_id')
            : $this->belongsTo(\App\Models\User::class, 'sender_id');
    }
}
