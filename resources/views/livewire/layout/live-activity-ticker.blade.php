<div class="ticker-tape" style="--ticker-duration: {{ $durationSeconds }}s">
    <div class="ticker-track"> 
        @for ($pass = 0; $pass < 2; $pass++)
            @foreach($items as $i=> $item)
            <div class="ticker-item" wire:key="ticker-{{ $pass }}-{{ $i }}" aria-hidden="{{ $pass === 1 ? 'true' : 'false' }}">
                <span class="ticker-dot {{ $item['dot'] }}"></span>
                <span>{{ $item['text'] }}</span>
            </div>
            @endforeach
        @endfor
    </div>
</div>