<div>
    @if($this->selectedTicket)
    {{-- TICKET DETAIL / CHAT VIEW --}}
    @php $ticket = $this->selectedTicket; @endphp

    <button wire:click="closeDetails" class="mb-4 flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m15 18-6-6 6-6"></path>
        </svg>
        Back to support
    </button>

    <div class="rounded-2xl border border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
        {{-- Header --}}
        <div class="flex items-center justify-between border-b p-5 border-slate-200 dark:border-white/[.08]">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">{{ $ticket->subject }}</h2>
                    <span class="rounded-md px-2 py-0.5 text-[10px] font-semibold
                            {{ match($ticket->status) {
                                'open' => 'bg-emerald-500/12 text-emerald-600 dark:text-emerald-400',
                                'pending' => 'bg-amber-500/12 text-amber-600 dark:text-amber-400',
                                'resolved', 'closed' => 'bg-slate-500/12 text-slate-600 dark:text-slate-400',
                            } }}">
                        {{ ucfirst($ticket->status) }}
                    </span>
                </div>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                    Ticket #{{ $ticket->id }} · {{ ucfirst($ticket->priority) }} priority · Opened {{ time_ago($ticket->created_at) }}
                </p>
            </div>
        </div>

        {{-- Messages --}}
        <div class="custom-scroll max-h-[480px] space-y-4 overflow-y-auto p-5">
            @foreach($this->replies as $reply)
            <div class="flex gap-3 {{ $reply->sender_type === 'admin' ? '' : 'flex-row-reverse' }}">
                <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full text-xs font-bold text-white
                            {{ $reply->sender_type === 'admin' ? 'bg-sky-500' : 'bg-emerald-500' }}">
                    {{ $reply->sender_type === 'admin' ? 'S' : strtoupper(substr($reply->sender->name ?? 'U', 0, 1)) }}
                </span>

                <div class="max-w-[75%] {{ $reply->sender_type === 'admin' ? 'items-start' : 'items-end' }} flex flex-col">
                    <div class="rounded-2xl px-4 py-2.5 text-sm
                                {{ $reply->sender_type === 'admin'
                                    ? 'rounded-tl-sm bg-slate-100 text-slate-800 dark:bg-white/[.06] dark:text-slate-100'
                                    : 'rounded-tr-sm bg-emerald-500 text-white' }}">
                        @if($reply->message)
                        <p class="whitespace-pre-wrap">{{ $reply->message }}</p>
                        @endif

                        @if($reply->attachment_path)
                        <a href="{{ asset($reply->attachment_path) }}" target="_blank" class="mt-2 flex items-center gap-1.5 text-xs underline opacity-90">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l8.57-8.57A4 4 0 1 1 18 8.84l-8.59 8.57a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
                            </svg>
                            View attachment
                        </a>
                        @endif
                    </div>
                    <span class="mt-1 text-[10px] text-slate-400 dark:text-slate-500">{{ $reply->created_at->format('M j, g:i A') }}</span>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Reply box --}}
        @if(! in_array($ticket->status, ['resolved', 'closed']))
        <div class="border-t p-4 border-slate-200 dark:border-white/[.08]">
            <div class="flex items-end gap-2">
                <textarea
                    wire:model="replyMessage" 
                    placeholder="Type your reply..."
                    class="w-full resize-none textarea"></textarea>

                <label class="grid h-11 w-11 shrink-0 cursor-pointer place-items-center rounded-xl border border-slate-200 bg-slate-50 text-slate-400 hover:bg-slate-100 dark:border-white/10 dark:bg-white/5 dark:hover:bg-white/10">
                    <input type="file" wire:model="replyAttachment" class="hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l8.57-8.57A4 4 0 1 1 18 8.84l-8.59 8.57a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
                    </svg>
                </label>

                <button
                    wire:click="sendReply"
                    wire:loading.attr="disabled"
                    wire:target="sendReply"
                    class="grid h-11 w-11 shrink-0 place-items-center rounded-xl !bg-emerald-500 !text-white hover:!bg-emerald-400 disabled:cursor-not-allowed disabled:opacity-70">
                    <svg wire:loading.remove wire:target="sendReply" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z"></path>
                        <path d="m21.854 2.147-10.94 10.939"></path>
                    </svg>
                    <svg wire:loading wire:target="sendReply" class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </button>
            </div>

            @if($replyAttachment)
            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Attached: {{ $replyAttachment->getClientOriginalName() }}</p>
            @endif

            @error('replyMessage') <p class="mt-2 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>
        @else
        <div class="border-t p-4 text-center text-xs text-slate-500 border-slate-200 dark:border-white/[.08] dark:text-slate-400">
            This ticket has been {{ $ticket->status }}. Create a new ticket if you need further assistance.
        </div> 
        @endif
    </div>

    @else
    {{-- MAIN SUPPORT PAGE --}}
    <section class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[.16em] text-emerald-600 dark:text-emerald-500">We're here to help</p>
            <h1 class="mt-1.5 text-2xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-3xl">Help & support</h1>
            <p class="mt-1.5 max-w-xl text-sm text-slate-500 dark:text-slate-400">Find answers, open a ticket, or contact our support team.</p>
        </div>
        <button wire:click="openCreateModal" class="primary-button w-fit">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"></path>
                <path d="M12 5v14"></path>
            </svg>
            Create ticket
        </button>
    </section>

    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_360px]">
        <div class="space-y-6">
            {{-- FAQ --}}
            <section class="rounded-2xl border p-5 sm:p-6 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
                <h2 class="font-semibold text-slate-900 dark:text-white">Frequently asked questions</h2>

                <div class="mt-4 divide-y divide-slate-100 dark:divide-white/[.08]" x-data="{ open: null }">
                    @foreach($this->faqs as $faq)
                    <div>
                        <button
                            @click="open = open === {{ $faq->id }} ? null : {{ $faq->id }}"
                            class="flex w-full justify-between gap-4 py-4 text-left text-sm font-medium text-slate-900 dark:text-white">
                            {{ $faq->question }}
                            <svg
                                xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="shrink-0 text-slate-400 transition-transform"
                                :class="open === {{ $faq->id }} ? 'rotate-180' : ''">
                                <path d="m6 9 6 6 6-6"></path>
                            </svg>
                        </button>
                        <div x-show="open === {{ $faq->id }}" x-collapse x-cloak class="pb-4 text-sm leading-relaxed text-slate-500 dark:text-slate-400">
                            {{ $faq->answer }}
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($this->faqLimit < $this->totalFaqCount)
                    <button wire:click="loadMoreFaqs" wire:loading.attr="disabled" wire:target="loadMoreFaqs" class="mt-4 flex w-full items-center justify-center gap-1.5 rounded-xl border py-2.5 text-xs font-semibold text-slate-600 border-slate-200 hover:bg-slate-50 dark:border-white/[.08] dark:text-slate-300 dark:hover:bg-white/[.04]">
                        <span wire:loading.remove wire:target="loadMoreFaqs">Show more questions</span>
                        <span wire:loading wire:target="loadMoreFaqs">Loading...</span>
                    </button>
                    @endif
            </section>

            {{-- Ticket list --}}
            <section class="rounded-2xl border border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
                <div class="border-b p-5 border-slate-200 dark:border-white/[.08]">
                    <h2 class="font-semibold text-slate-900 dark:text-white">Your tickets</h2>
                </div>

                @forelse($this->tickets as $ticket)
                <button wire:click="view({{ $ticket->id }})" class="flex w-full items-center gap-3 border-b p-4 sm:p-5 text-left transition last:border-b-0 border-slate-100 hover:bg-slate-50 dark:border-white/[.06] dark:hover:bg-white/[.03]">
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-sky-500/12 text-sky-500">
                        <x-app-icon name="life-buoy" class="h-4 w-4" />
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <b class="truncate text-sm text-slate-900 dark:text-white">{{ $ticket->subject }}</b>
                            @if($ticket->latestReply && $ticket->latestReply->sender_type === 'admin' && ! $ticket->latestReply->is_read)
                            <span class="h-2 w-2 shrink-0 rounded-full bg-rose-500"></span>
                            @endif
                        </div>
                        <small class="text-xs text-slate-500 dark:text-slate-400">
                            {{ ucfirst($ticket->priority) }} priority · {{ time_ago($ticket->updated_at) }}
                        </small>
                    </div>
                    <span class="shrink-0 rounded-md px-2 py-1 text-[10px] font-semibold
                                {{ match($ticket->status) {
                                    'open' => 'bg-emerald-500/12 text-emerald-600 dark:text-emerald-400',
                                    'pending' => 'bg-amber-500/12 text-amber-600 dark:text-amber-400',
                                    'resolved', 'closed' => 'bg-slate-500/12 text-slate-600 dark:text-slate-400',
                                } }}">
                        {{ ucfirst($ticket->status) }}
                    </span>
                </button>
                @empty
                <div class="flex flex-col items-center p-10 text-center">
                    <span class="grid h-12 w-12 place-items-center rounded-2xl border border-slate-200 bg-slate-50 dark:border-white/[.07] dark:bg-white/[.035]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400">
                            <path d="M12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83z"></path>
                            <path d="M2 12a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 12"></path>
                            <path d="M2 17a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 17"></path>
                        </svg>
                    </span>
                    <b class="mt-3 text-sm text-slate-900 dark:text-white">No tickets yet</b>
                    <p class="text-sm text-slate-500 dark:text-slate-400">You haven't opened any tickets yet.</p>
                </div>
                @endforelse
            </section>
        </div>

        {{-- Sidebar --}}
        <section class="h-max rounded-2xl border p-5 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            <span class="grid h-11 w-11 place-items-center rounded-2xl bg-sky-500/12 text-sky-500">
                <x-app-icon name="life-buoy" class="h-5 w-5" />
            </span>
            <h2 class="mt-4 font-semibold text-slate-900 dark:text-white">Talk to a specialist</h2>
            <p class="mt-2 text-sm leading-relaxed text-slate-500 dark:text-slate-400">Our account support team is available Monday through Friday.</p>
            <button wire:click="openCreateModal" class="mt-5 flex w-full btn btn-outline-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z"></path>
                    <path d="m21.854 2.147-10.94 10.939"></path>
                </svg>
                Start live chat
            </button>
        </section>
    </div>
    @endif

    {{-- Create Ticket Modal --}}
    <div
        x-show="$wire.showCreateModal"
        x-cloak
        class="fixed inset-0 z-50 grid place-items-center bg-slate-900/60 p-4 backdrop-blur-sm"
        x-transition.opacity
        @click.self="$wire.closeCreateModal()"
        @keydown.escape.window="$wire.closeCreateModal()">
        <div x-show="$wire.showCreateModal" x-transition class="w-full max-w-lg rounded-2xl border p-6 shadow-2xl border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Create support ticket</h3>
                <button wire:click="closeCreateModal" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 dark:hover:bg-white/[.06]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="mt-5 space-y-4">
                <x-ui.input label="Subject" wire:model="subject" name="subject" placeholder="Briefly describe your issue" error="subject" />

                <x-ui.select label="Priority" wire:model="priority" name="priority" :placeholder="null" error="priority">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </x-ui.select>

                <x-ui.textarea label="Message" wire:model="message" name="message" rows="4" placeholder="Explain your issue in detail..." error="message" />

                <div>
                    <x-ui.label>Attachment (optional)</x-ui.label>
                    <input type="file" wire:model="attachment" class="mt-2 w-full rounded-xl border px-3 py-2.5 text-sm border-slate-200 bg-slate-50 dark:border-white/10 dark:bg-white/5 dark:text-white">
                    <div wire:loading wire:target="attachment" class="mt-1 text-xs text-slate-400">Uploading...</div>
                    @error('attachment') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <button
                wire:click="createTicket"
                wire:loading.attr="disabled"
                wire:target="createTicket"
                class="primary-button mt-5 w-full justify-center disabled:cursor-not-allowed disabled:opacity-50">
                <span wire:loading.remove wire:target="createTicket">Submit ticket</span>
                <span wire:loading wire:target="createTicket">Submitting...</span>
            </button>
        </div>
    </div>
</div>