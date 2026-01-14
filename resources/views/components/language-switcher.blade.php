@php
    $currentLocale = app()->getLocale();
    $locales = config('app.available_locales');
    $localeNames = config('app.locale_names');
    $currentPath = request()->path();

    // Remove locale prefix from current path if present
    $pathWithoutLocale = preg_replace('/^(nl|id)\/?/', '', $currentPath);

    // Build locale URLs
    $localeUrls = [];
    foreach ($locales as $locale) {
        if ($locale === 'en') {
            // English has no prefix
            $localeUrls[$locale] = url('/' . $pathWithoutLocale);
        } else {
            // Other languages have prefix
            $localeUrls[$locale] = url('/' . $locale . '/' . $pathWithoutLocale);
        }
    }
@endphp

<div class="relative inline-block text-left" x-data="{ open: false }">
    <button @click="open = !open" @click.away="open = false" type="button"
            class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white hover:text-[#fe742b] transition-colors duration-300">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
        </svg>
        <span class="uppercase">{{ $currentLocale }}</span>
        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 z-50 mt-2 w-48 origin-top-right bg-[#1a1f3a] rounded-lg shadow-lg border border-gray-700"
         style="display: none;">
        <div class="py-1">
            @foreach($locales as $locale)
                <a href="{{ $localeUrls[$locale] }}"
                   class="flex items-center px-4 py-2 text-sm hover:bg-[#fe742b] transition-colors {{ $locale === $currentLocale ? 'bg-[#fe742b] text-white font-semibold' : 'text-gray-300' }}">
                    <span class="mr-3 text-lg">
                        @if($locale === 'en')
                            🇬🇧
                        @elseif($locale === 'nl')
                            🇳🇱
                        @elseif($locale === 'id')
                            🇮🇩
                        @endif
                    </span>
                    {{ $localeNames[$locale] }}
                    @if($locale === $currentLocale)
                        <svg class="w-4 h-4 ml-auto" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>
