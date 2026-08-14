<flux:button
    x-data
    x-on:click="$flux.dark = !$flux.dark"
    wire:click="toggleTheme"
    wire:loading.attr="disabled"
    variant="subtle"
    aria-label="Toggle dark mode"
    class="{{ $class }}">
    <span x-text="$flux.dark ? '☀️' : '🌙'"></span>
</flux:button>
