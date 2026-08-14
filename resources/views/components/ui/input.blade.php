@props([
    'label' => null,
    'name' => null,
    'type' => 'text',
    'error' => null,
])

<div>
    @if($label)
        <x-ui.label :for="$name" class="mt-6">{{ $label }}</x-ui.label>
    @endif

    <input
        @if($name) name="{{ $name }}" id="{{ $name }}" @endif
        type="{{ $type }}"
        {{ $attributes->merge([
            'class' => 'mt-2 w-full rounded-xl border px-3 py-3 text-sm outline-none transition
                border-slate-200 bg-slate-50 text-slate-900 placeholder:text-slate-400
                focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20
                dark:border-white/10 dark:bg-white/5 dark:text-white dark:placeholder:text-slate-500
                dark:focus:border-emerald-400 dark:focus:ring-emerald-400/20'
        ]) }}
    />

    @if($error)
        @error($error)
            <p class="mt-1.5 text-xs text-rose-500 dark:text-rose-400">{{ $message }}</p>
        @enderror
    @endif
</div>