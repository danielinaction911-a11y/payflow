<div
    x-data="{ show: true }"
    x-init="setTimeout(() => show = false, 1800)"
    x-show="show"
    x-transition:leave="transition ease-in duration-500"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="splash"
    id="splash">
    <div class="splash-glow"></div>
    <div class="brand-mark splash-mark">{{ substr(setting('site_title', config('app.name')), 0, 1) }}</div>
    <h1>{{ setting('site_title', config('app.name')) }}</h1>
    <p>Your money, moving at the speed of now</p>
    <div class="loading-dots"><i></i><i></i><i></i></div>
</div>