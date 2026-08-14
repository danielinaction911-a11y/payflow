@props([
    'label' => null,
    'name' => null,
    'rows' => 4,
    'error' => null,
])

<div>
    @if($label)
        <x-ui.label :for="$name" class="mt-6">{{ $label }}</x-ui.label>
    @endif
 
    <textarea
        @if($name) name="{{ $name }}" id="{{ $name }}" @endif
        rows="{{ $rows }}"
        {{ $attributes->merge([
            'class' => 'textarea'
        ]) }}
    >{{ $slot }}</textarea>

    @if($error)
        @error($error)
            <p class="mt-1.5 text-xs text-rose-500 dark:text-rose-400">{{ $message }}</p>
        @enderror
    @endif
</div>