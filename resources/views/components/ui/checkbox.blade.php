@props([
    'label' => null,
    'name' => null,
    'error' => null,
])

<div>
    <label
        @if($name) for="{{ $name }}" @endif
        class="inline-flex items-center gap-3 cursor-pointer select-none"
    >
        <span class="relative flex h-5 w-5 shrink-0 items-center justify-center rounded-md border-2 transition
            border-slate-300 bg-slate-50
            dark:border-white/20 dark:bg-white/5">

            <input
                @if($name) id="{{ $name }}" name="{{ $name }}" @endif
                type="checkbox"
                {{ $attributes->merge([
                    'class' => 'peer absolute inset-0 h-full w-full cursor-pointer appearance-none rounded-md
                        border-2 border-transparent
                        checked:border-emerald-500 checked:bg-emerald-500
                        dark:checked:border-emerald-400 dark:checked:bg-emerald-400
                        transition'
                ]) }}
            />

            <svg class="pointer-events-none absolute h-3.5 w-3.5 text-white opacity-0 peer-checked:opacity-100 transition"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 6 9 17l-5-5"/>
            </svg>
        </span>

        @if($label)
            <span class="text-sm text-slate-600 dark:text-slate-300">{{ $label }}</span>
        @endif
    </label>

    @if($error)
        @error($error)
            <p class="mt-1.5 text-xs text-rose-500 dark:text-rose-400">{{ $message }}</p>
        @enderror
    @endif
</div>