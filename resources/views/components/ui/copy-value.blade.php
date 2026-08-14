@props(['value', 'label' => null])

<div
    x-data="{ copied: false, copy() { navigator.clipboard.writeText(@js($value)); this.copied = true; setTimeout(() => this.copied = false, 1800) } }"
    class="copy-field"
>
    <div class="copy-field-info">
        @if($label)
            <small>{{ $label }}</small>
        @endif
        <code>{{ $value }}</code>
    </div>

    <button type="button" @click="copy()" class="copy-field-button">
        <span x-show="!copied">Copy</span>
        <span x-show="copied" x-cloak class="copy-field-copied">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"></path></svg>
            Copied
        </span>
    </button>
</div>