@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        {{-- Mobile: simple Previous / Next only --}}
        <div class="flex flex-1 justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="rounded-lg px-4 py-2 text-sm font-medium cursor-not-allowed opacity-40" style="border: 1px solid var(--line); background: var(--soft); color: var(--text)">
                    Previous
                </span>
            @else
                <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev"
                    class="rounded-lg px-4 py-2 text-sm font-medium transition hover:opacity-75"
                    style="border: 1px solid var(--line); background: var(--soft); color: var(--text)">
                    Previous
                </button>
            @endif

            @if ($paginator->hasMorePages())
                <button wire:click="nextPage" wire:loading.attr="disabled" rel="next"
                    class="rounded-lg px-4 py-2 text-sm font-medium transition hover:opacity-75"
                    style="border: 1px solid var(--line); background: var(--soft); color: var(--text)">
                    Next
                </button>
            @else
                <span class="rounded-lg px-4 py-2 text-sm font-medium cursor-not-allowed opacity-40" style="border: 1px solid var(--line); background: var(--soft); color: var(--text)">
                    Next
                </span>
            @endif
        </div>

        {{-- Desktop: full numbered pagination --}}
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <p class="text-sm" style="color: var(--muted)">
                Showing
                <span class="font-medium" style="color: var(--text)">{{ $paginator->firstItem() }}</span>
                to
                <span class="font-medium" style="color: var(--text)">{{ $paginator->lastItem() }}</span>
                of
                <span class="font-medium" style="color: var(--text)">{{ $paginator->total() }}</span>
                results
            </p>

            <div class="flex items-center gap-1">
                {{-- Previous --}}
                @if ($paginator->onFirstPage())
                    <span class="grid h-9 w-9 place-items-center rounded-lg cursor-not-allowed opacity-40" style="border: 1px solid var(--line); background: var(--soft); color: var(--muted)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"></path></svg>
                    </span>
                @else
                    <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev"
                        class="grid h-9 w-9 place-items-center rounded-lg transition hover:opacity-75"
                        style="border: 1px solid var(--line); background: var(--soft); color: var(--text)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"></path></svg>
                    </button>
                @endif

                {{-- Page numbers (Laravel's own UrlWindow already truncates with "..." for large sets) --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="grid h-9 w-9 place-items-center text-sm" style="color: var(--muted)">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="grid h-9 w-9 place-items-center rounded-lg text-sm font-semibold text-white" style="background: var(--green); box-shadow: 0 6px 14px rgba(16,185,129,.28)">
                                    {{ $page }}
                                </span>
                            @else
                                <button wire:click="gotoPage({{ $page }})" wire:loading.attr="disabled"
                                    class="grid h-9 w-9 place-items-center rounded-lg text-sm font-medium transition hover:opacity-75"
                                    style="border: 1px solid var(--line); background: var(--soft); color: var(--text)">
                                    {{ $page }}
                                </button>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($paginator->hasMorePages())
                    <button wire:click="nextPage" wire:loading.attr="disabled" rel="next"
                        class="grid h-9 w-9 place-items-center rounded-lg transition hover:opacity-75"
                        style="border: 1px solid var(--line); background: var(--soft); color: var(--text)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"></path></svg>
                    </button>
                @else
                    <span class="grid h-9 w-9 place-items-center rounded-lg cursor-not-allowed opacity-40" style="border: 1px solid var(--line); background: var(--soft); color: var(--muted)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"></path></svg>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif