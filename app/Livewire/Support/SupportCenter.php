<?php

namespace App\Livewire\Support;

use App\Models\Faq;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Traits\HandlesFileUploads;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
class SupportCenter extends Component
{
    use WithFileUploads, HandlesFileUploads;

    #[Url]
    public ?int $viewing = null;

    public int $faqLimit = 5;

    public bool $showCreateModal = false;
    public string $subject = '';
    public string $priority = 'medium';
    public string $message = '';
    public $attachment = null;

    public string $replyMessage = '';
    public $replyAttachment = null;

    #[Computed]
    public function faqs()
    {
        return Faq::orderBy('id')->limit($this->faqLimit)->get();
    }

    #[Computed]
    public function totalFaqCount()
    {
        return Faq::count();
    }

    #[Computed]
    public function tickets()
    {
        return SupportTicket::with('latestReply')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
    }

    #[Computed]
    public function selectedTicket()
    {
        return $this->viewing
            ? SupportTicket::where('user_id', Auth::id())->find($this->viewing)
            : null;
    }

    #[Computed]
    public function replies()
    {
        if (! $this->selectedTicket) {
            return collect();
        }

        return TicketReply::where('support_ticket_id', $this->selectedTicket->id)
            ->oldest()
            ->get();
    }

    public function loadMoreFaqs(): void
    {
        $this->faqLimit += 5;
    }

    public function openCreateModal(): void
    {
        $this->reset(['subject', 'priority', 'message', 'attachment']);
        $this->priority = 'medium';
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
    }

    public function createTicket(): void
    {
        $this->validate([
            'subject' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high',
            'message' => 'required|string|max:2000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $ticket = SupportTicket::create([
            'user_id' => Auth::id(),
            'subject' => $this->subject,
            'priority' => $this->priority,
            'status' => 'open',
        ]);

        $attachmentPath = null;

        if ($this->attachment) {
            $attachmentPath = $this->uploadFile(
                $this->attachment,
                'images/tickets',
                null,
                'ticket_' . $ticket->id
            );
        }

        TicketReply::create([
            'support_ticket_id' => $ticket->id,
            'sender_type' => 'user',
            'sender_id' => Auth::id(),
            'message' => $this->message,
            'attachment_path' => $attachmentPath,
            'is_read' => false,
        ]);

        $this->showCreateModal = false;
        unset($this->tickets);
        $this->dispatch(
            'notify',
            type: 'success',
            title: 'Ticket created',
            message: "Your ticket has been created."
        );

        $this->viewing = $ticket->id;
    }

    public function view(int $ticketId): void
    {
        $this->viewing = $ticketId;
        $this->replyMessage = '';
        $this->replyAttachment = null;

        // Mark admin replies as read when the user opens the ticket
        TicketReply::where('support_ticket_id', $ticketId)
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    public function closeDetails(): void
    {
        $this->viewing = null;
        unset($this->tickets);
    }

    public function sendReply(): void
    {
        $this->validate([
            'replyMessage' => 'required_without:replyAttachment|nullable|string|max:2000',
            'replyAttachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $ticket = $this->selectedTicket;

        if (! $ticket) {
            return;
        }

        if (in_array($ticket->status, ['resolved', 'closed'])) {
            $this->addError('replyMessage', 'This ticket is closed. Please open a new ticket if you need further help.');
            return;
        }

        $attachmentPath = null;

        if ($this->replyAttachment) {
            $attachmentPath = $this->uploadFile(
                $this->replyAttachment,
                'images/tickets',
                null,
                'reply_' . $ticket->id
            );
        }

        TicketReply::create([
            'support_ticket_id' => $ticket->id,
            'sender_type' => 'user',
            'sender_id' => Auth::id(),
            'message' => $this->replyMessage ?: '',
            'attachment_path' => $attachmentPath,
            'is_read' => false,
        ]);

        $ticket->update(['status' => 'pending']);

        $this->replyMessage = '';
        $this->replyAttachment = null;

        unset($this->replies, $this->tickets, $this->selectedTicket);
        $this->dispatch(
            'notify',
            type: 'success',
            title: 'Reply sent',
            message: "Your reply has been sent."
        );
    }

    public function render()
    {
        return view('livewire.support.support-center')->layout('components.layouts.app', [
            'title' => 'Support Center',
        ]);
    }
}
