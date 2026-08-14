@props([
    'label' => null,
    'name' => null,
    'error' => null,
])

<div x-data="{ show: false }">
    @if($label)
        <x-ui.label :for="$name" class="mt-4">{{ $label }}</x-ui.label>
    @endif

    <div class="mt-2 flex items-center rounded-xl border px-3 transition
        border-slate-200 bg-slate-50 focus-within:border-emerald-500 focus-within:ring-2 focus-within:ring-emerald-500/20
        dark:border-white/10 dark:bg-white/5 dark:focus-within:border-emerald-400 dark:focus-within:ring-emerald-400/20">

        <input
            @if($name) name="{{ $name }}" id="{{ $name }}" @endif
            :type="show ? 'text' : 'password'"
            {{ $attributes->merge([
                'class' => 'w-full bg-transparent py-3 text-sm outline-none
                    text-slate-900 placeholder:text-slate-400
                    dark:text-white dark:placeholder:text-slate-500'
            ]) }}
        />

        <button
            type="button"
            @click="show = !show"
            class="shrink-0 text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300 transition"
        >
            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                <circle cx="12" cy="12" r="3"></circle>
            </svg>
            <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                <line x1="2" x2="22" y1="2" y2="22"></line>
            </svg>
        </button>
    </div>

    @if($error)
        @error($error)
            <p class="mt-1.5 text-xs text-rose-500 dark:text-rose-400">{{ $message }}</p>
        @enderror
    @endif
</div>