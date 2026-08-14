<div>
    <section class="mb-7 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[.16em] text-emerald-600 dark:text-emerald-500">{{ setting('site_name', 'App') }} legal</p>
            <h1 class="mt-1.5 text-2xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-3xl">
                {{ $this->activePolicy->title ?? 'Legal center' }}
            </h1>
            <p class="mt-1.5 max-w-xl text-sm text-slate-500 dark:text-slate-400">Clear terms designed to help you understand your account and our services.</p>
        </div>
    </section>

    <div class="grid gap-6 lg:grid-cols-[230px_minmax(0,1fr)]">
        {{-- Sidebar nav --}}
        <aside class="h-fit rounded-2xl border p-3 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90 lg:sticky lg:top-24">
            <nav class="space-y-1">
                @foreach($this->policies as $policy)
                <button
                    wire:click="selectPolicy('{{ $policy->slug }}')"
                    class="policy-nav-btn {{ $this->activePolicy->slug === $policy->slug ? 'is-active' : '' }}">
                    {{ $policy->title }}
                </button>
                @endforeach
            </nav>
        </aside>

        {{-- Content --}}
        <article
            wire:key="policy-{{ $this->activePolicy->id ?? 'none' }}"
            class="rounded-2xl border p-6 sm:p-8 border-slate-200 bg-white dark:border-white/[.08] dark:bg-[#111a2d]/90">
            @if($this->activePolicy)
            <div class="flex flex-wrap items-center justify-between gap-2">
                <small class="text-slate-500 dark:text-slate-400">
                    Last updated: {{ $this->activePolicy->effective_date?->format('F j, Y') ?? $this->activePolicy->updated_at->format('F j, Y') }}
                </small>
                @if($this->activePolicy->version)
                <span class="rounded-md bg-slate-100 px-2 py-1 text-[10px] font-semibold text-slate-500 dark:bg-white/[.06] dark:text-slate-400">
                    v{{ $this->activePolicy->version }}
                </span>
                @endif
            </div>

            <h2 class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">{{ $this->activePolicy->title }}</h2>

            <div class="legal-content mt-6 space-y-6 text-sm leading-7 text-slate-600 dark:text-slate-400">
                {!! $this->activePolicy->content !!}
            </div>
            @else
            <div class="flex flex-col items-center py-14 text-center">
                <span class="grid h-12 w-12 place-items-center rounded-2xl border border-slate-200 bg-slate-50 dark:border-white/[.07] dark:bg-white/[.035]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400">
                        <path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z"></path>
                        <path d="M14 2v5a1 1 0 0 0 1 1h5"></path>
                    </svg>
                </span>
                <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">No legal documents are available right now.</p>
            </div>
            @endif
        </article>
    </div>
</div>