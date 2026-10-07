@php
    $currentLocale = app()->getLocale();
@endphp

<div class="flex items-center gap-1 px-1.5 py-1 text-xs font-semibold rounded-lg bg-gray-100 dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 mx-2 shadow-xs" role="group" aria-label="{{ __('filament.language_switcher.switch_language') }}">
    <a href="{{ route('locale.switch', 'id') }}" 
       title="{{ __('filament.language_switcher.id') }}"
       class="flex items-center gap-1 px-2 py-0.5 rounded transition-all {{ $currentLocale === 'id' ? 'bg-white dark:bg-gray-700 text-amber-600 dark:text-amber-400 shadow-xs font-bold' : 'text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200' }}">
        <span aria-hidden="true">🇮🇩</span>
        <span>ID</span>
    </a>
    <span class="text-gray-300 dark:text-gray-600 text-[10px]">|</span>
    <a href="{{ route('locale.switch', 'en') }}" 
       title="{{ __('filament.language_switcher.en') }}"
       class="flex items-center gap-1 px-2 py-0.5 rounded transition-all {{ $currentLocale === 'en' ? 'bg-white dark:bg-gray-700 text-amber-600 dark:text-amber-400 shadow-xs font-bold' : 'text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200' }}">
        <span aria-hidden="true">🇬🇧</span>
        <span>EN</span>
    </a>
</div>
