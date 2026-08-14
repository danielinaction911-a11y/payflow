@props([
    'label' => null,
    'name' => null,
    'options' => null,
    'placeholder' => 'Select an option',
    'error' => null,
    'autodetect' => false,
])

<div
    @if($autodetect)
        x-data="{
            detecting: true,
            async init() {
                try {
                    const res = await fetch('https://ipapi.co/json/');
                    const data = await res.json();
                    const detected = data.country_name;

                    if (detected) {
                        const select = $refs.select;
                        const match = Array.from(select.options).find(
                            opt => opt.value.toLowerCase() === detected.toLowerCase()
                        );

                        if (match) {
                            select.value = match.value;
                            select.dispatchEvent(new Event('change'));
                        }
                    }
                } catch (e) {
                    console.warn('Country auto-detect failed', e);
                } finally {
                    this.detecting = false;
                }
            }
        }"
    @endif
>
    @if($label)
        <x-ui.label :for="$name" class="mt-6">
            {{ $label }}
            @if($autodetect)
                <span x-show="detecting" x-cloak class="ml-1 text-emerald-500">(detecting...)</span>
            @endif
        </x-ui.label>
    @endif

    <div class="relative mt-2">
        <select
            @if($autodetect) x-ref="select" @endif
            @if($name) name="{{ $name }}" id="{{ $name }}" @endif
            {{ $attributes->merge([
                'class' => 'w-full appearance-none rounded-xl border px-3 py-3 pr-9 text-sm outline-none transition
                    border-slate-200 bg-slate-50 text-slate-900
                    focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20
                    dark:border-white/10 dark:bg-white/5 dark:text-white
                    dark:focus:border-emerald-400 dark:focus:ring-emerald-400/20'
            ]) }}
        >
            @if($placeholder)
                <option value="" disabled selected>{{ $placeholder }}</option>
            @endif

            @if($options)
                @foreach($options as $value => $text)
                    <option value="{{ $value }}">{{ $text }}</option>
                @endforeach
            @else
                {{ $slot }}
            @endif
        </select>

        <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500"
            xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m6 9 6 6 6-6"/>
        </svg>
    </div>

    @if($error)
        @error($error)
            <p class="mt-1.5 text-xs text-rose-500 dark:text-rose-400">{{ $message }}</p>
        @enderror
    @endif
</div>