<?php

namespace App\Livewire\Notifications;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class NotificationCenter extends Component
{
    use WithPagination;

    public string $filter = 'all'; // all | unread

    #[Computed]
    public function notifications()
    {
        $query = Notification::where('user_id', Auth::id())->latest();

        if ($this->filter === 'unread') {
            $query->where('is_read', false);
        }

        return $query->paginate(15);
    }

    #[Computed]
    public function grouped()
    {
        return $this->notifications->getCollection()->groupBy(function ($notification) {
            if ($notification->created_at->isToday()) {
                return 'Today';
            }

            if ($notification->created_at->isYesterday()) {
                return 'Yesterday';
            }

            return 'Earlier';
        });
    }

    #[Computed]
    public function unreadCount()
    {
        return Notification::where('user_id', Auth::id())->where('is_read', false)->count();
    }

    public function setFilter(string $filter): void
    {
        $this->filter = in_array($filter, ['all', 'unread']) ? $filter : 'all';
        $this->resetPage();
    }

    public function markAsRead(int $id): void
    {
        Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->update(['is_read' => true]);

        unset($this->notifications, $this->grouped, $this->unreadCount);
        $this->dispatch(
            'notify',
            type: 'success',
            title: 'Notification marked as read',
            message: "The notification has been marked as read."
        );
    }

    public function markAllRead(): void
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        unset($this->notifications, $this->grouped, $this->unreadCount);
        $this->dispatch(
            'notify',
            type: 'success',
            title: 'All notifications marked as read',
            message: "All notifications have been marked as read."
        );
    }

    public function delete(int $id): void
    {
        Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        unset($this->notifications, $this->grouped, $this->unreadCount);
        $this->dispatch(
            'notify',
            type: 'success',
            title: 'Notification deleted',
            message: "The notification has been deleted."
        );
    }

    public function render()
    {
        return view('livewire.notifications.notification-center')->layout('components.layouts.app', [
            'title' => 'Notification Center',
        ]);
    }
}