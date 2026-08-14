@props([
    'label' => null,
    'wireModel',   // e.g. "pin", "newPin", "newPinConfirmation" — the Livewire property name
    'error' => null,
    'length' => 4,
])

@php
    $error = $error ?? $wireModel;
@endphp

<div
    x-data="{
        digits: Array({{ $length }}).fill(''),
        init() {
            const current = ($wire.get('{{ $wireModel }}') || '').split('');
            this.digits = Array.from({ length: {{ $length }} }, (_, i) => current[i] ?? '');
        },
        commit() { 
            $wire.set('{{ $wireModel }}', this.digits.join(''), true);
        },
        onInput(i, e) {
            const clean = e.target.value.replace(/[^0-9]/g, '').slice(-1);
            this.digits[i] = clean;
            if (!clean) {
                this.clearFrom(i);
                return;
            }
            this.commit();
            if (i < {{ $length - 1 }}) {
                this.$nextTick(() => this.$refs['box' + (i + 1)].focus());
            }
        },
        onKeydown(i, e) {
            if (e.key === 'Backspace') {
                if (this.digits[i]) {
                    // let onInput handle clearing this box + everything after it
                    return;
                }
                if (i > 0) {
                    this.digits[i - 1] = '';
                    this.clearFrom(i - 1);
                    this.$nextTick(() => this.$refs['box' + (i - 1)].focus());
                }
                return;
            }
            if (e.key === 'ArrowLeft' && i > 0) this.$refs['box' + (i - 1)].focus();
            if (e.key === 'ArrowRight' && i < {{ $length - 1 }} && this.isUnlocked(i + 1)) {
                this.$refs['box' + (i + 1)].focus();
            }
        },
        onPaste(e) {
            e.preventDefault();
            const text = (e.clipboardData.getData('text') || '').replace(/[^0-9]/g, '').slice(0, {{ $length }});
            if (!text) return;
            this.digits = Array.from({ length: {{ $length }} }, (_, i) => text[i] ?? '');
            this.commit();
            const last = Math.min(text.length, {{ $length - 1 }});
            this.$nextTick(() => this.$refs['box' + last]?.focus());
        },
        isUnlocked(i) {
            return i === 0 || this.digits[i - 1] !== '';
        },
        clearFrom(i) {
            // clearing a digit invalidates everything typed after it
            for (let j = i + 1; j < {{ $length }}; j++) this.digits[j] = '';
            this.commit();
        },
    }"
    class="pin-field"
>
    @if($label)
        <label class="pin-field-label">{{ $label }}</label>
    @endif

    <div class="pin-boxes" x-on:paste="onPaste">
        @for ($i = 0; $i < $length; $i++)
            <input
                type="text"
                inputmode="numeric"
                pattern="[0-9]*"
                maxlength="1"
                autocomplete="{{ $i === 0 ? 'one-time-code' : 'off' }}"
                x-ref="box{{ $i }}"
                x-model="digits[{{ $i }}]"
                x-on:input="onInput({{ $i }}, $event)"
                x-on:keydown="onKeydown({{ $i }}, $event)"
                x-on:focus="$el.select()"
                :disabled="!isUnlocked({{ $i }})"
                class="pin-box"
                :class="{ 'filled': digits[{{ $i }}] !== '', 'locked': !isUnlocked({{ $i }}) }"
            />
        @endfor
    </div>

    @error($error)
        <p class="pin-field-error">{{ $message }}</p>
    @enderror
</div>