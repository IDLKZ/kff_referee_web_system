{{-- Language Switcher --}}
@php
    $currentLocale = app()->getLocale();
    $locales = [
        'ru' => 'РУС',
        'kk' => 'ҚАЗ',
        'en' => 'ENG',
    ];
@endphp

<div class="relative" x-data="{ open: false }">
    <button
        @click="open = !open"
        @click.outside="open = false"
        class="lang-switcher-btn flex items-center gap-2 px-3 py-1.5 text-sm font-medium rounded-lg
               transition-all duration-300 hover:scale-105 active:scale-95"
        style="background: var(--bg-hover); color: var(--text-secondary);"
    >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
        </svg>
        <span class="font-semibold">{{ $locales[$currentLocale] ?? 'ENG' }}</span>
        <svg class="w-3 h-3 transition-transform duration-300" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
        class="absolute right-0 mt-2 w-32 rounded-xl z-50 overflow-hidden"
        style="background: var(--bg-card); border: 1px solid var(--border-color); box-shadow: var(--shadow-lg);"
    >
        <div class="py-1">
            @foreach($locales as $code => $label)
            <a
                href="{{ route('locale.switch', $code) }}"
                class="lang-option block px-4 py-2 text-sm font-medium transition-all duration-150 relative"
                style="color: {{ $currentLocale === $code ? 'var(--color-primary)' : 'var(--text-primary)' }};"
            >
                {{ $label }}
                @if($currentLocale === $code)
                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                @endif
            </a>
            @endforeach
        </div>
    </div>
</div>

<style>
    .lang-switcher-btn:hover {
        background: var(--border-color);
        color: var(--text-primary);
    }

    .lang-option:hover {
        background: var(--bg-hover);
    }
</style>
