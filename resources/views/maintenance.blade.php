<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ setting('site_title', 'App') }} — Under Maintenance</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen grid place-items-center px-4 bg-slate-50 text-slate-900 dark:bg-[#090f1e] dark:text-slate-100">
    <div class="max-w-md text-center">
        <span class="mx-auto grid h-16 w-16 place-items-center rounded-2xl bg-emerald-500/12 text-emerald-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
        </span>

        <h1 class="mt-6 text-2xl font-semibold text-slate-900 dark:text-white">We'll be right back</h1>
        <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">
            {{ setting('site_title', 'Our platform') }} is currently undergoing scheduled maintenance. Please check back shortly.
        </p>

        @if(setting('contact_email'))
            <p class="mt-6 text-xs text-slate-400 dark:text-slate-500">
                Need urgent help? Contact us at
                <a href="mailto:{{ setting('contact_email') }}" class="font-semibold text-emerald-600 dark:text-emerald-500">{{ setting('contact_email') }}</a>
            </p>
        @endif
    </div>
</body>
</html>