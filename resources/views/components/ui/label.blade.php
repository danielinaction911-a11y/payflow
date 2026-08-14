@props(['for' => null])

<label
    @if($for) for="{{ $for }}" @endif
    {{ $attributes->merge(['class' => 'block text-xs font-medium text-slate-500 dark:text-slate-400']) }}
>
    {{ $slot }}
</label>