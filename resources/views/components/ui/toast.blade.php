{{--
    Global toast notification listener.

    Include this once in your main layout (see components/layouts/app.blade.php)
    and it's available everywhere — any Livewire component can trigger it from
    PHP with:

        $this->dispatch('notify', type: 'success', title: 'Saved', message: 'Your changes were saved.');

    `type` accepts 'success' (default) or 'error'. `title` and `message` are plain
    strings — this component only renders them via x-text, so no HTML/markdown.
--}}
<div
    x-data="{ show: false, type: 'success', title: '', message: '', timer: null }"
    x-on:notify.window="
        type = $event.detail.type || 'success';
        title = $event.detail.title || '';
        message = $event.detail.message || '';
        show = true;
        clearTimeout(timer);
        timer = setTimeout(() => show = false, 4000);
    "
    class="toast"
    :class="{ show: show, error: type === 'error' }"
    x-cloak
>
    <span>
        <svg x-show="type !== 'error'" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 6 9 17l-5-5"></path>
        </svg>
        <svg x-show="type === 'error'" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6 6 18"></path>
            <path d="m6 6 12 12"></path>
        </svg>
    </span>
    <div>
        <b x-text="title"></b>
        <small x-text="message"></small>
    </div>
</div>