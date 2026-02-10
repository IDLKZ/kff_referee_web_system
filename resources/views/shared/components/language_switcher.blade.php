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
        class="flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-md
               transition-colors duration-150"
        style="color: var(--text-secondary); background: var(--bg-hover);"
    >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
        </svg>
        <span>{{ $locales[$currentLocale] ?? 'ENG' }}</span>
        <svg class="w-3 h-3 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-1 w-28 rounded-md shadow-lg z-50"
        style="background: var(--bg-card); border: 1px solid var(--border-color);"
    >
        @foreach($locales as $code => $label)
            <a
                href="{{ route('locale.switch', $code) }}"
                class="block px-4 py-2 text-sm transition-colors duration-150
                       {{ $currentLocale === $code ? 'font-semibold' : '' }}"
                style="color: var(--text-primary);"
                onmouseover="this.style.backgroundColor='var(--bg-hover)'"
                onmouseout="this.style.backgroundColor='transparent'"
            >
                {{ $label }}
            </a>
        @endforeach
    </div>
</div>
