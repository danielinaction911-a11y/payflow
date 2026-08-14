<div class="rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Subject</p>
        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $subject }}</p>
    </div>

    <div class="bg-white dark:bg-gray-900 p-6">
        <div class="prose dark:prose-invert max-w-none">
            {!! $body !!}
        </div>
    </div>
</div>