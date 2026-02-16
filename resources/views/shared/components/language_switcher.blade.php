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
        class="lang-switcher-btn flex items-center gap-2 px-4 py-2.5 text-sm font-medium rounded-xl
               transition-all duration-300 group"
        style="background: var(--bg-card); border: 1px solid var(--border-color); color: var(--text-primary);"
    >
        <div class="flex items-center justify-center w-6 h-6 rounded-lg transition-all duration-300"
             style="background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary)); opacity: 0.9;">
            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                      d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
            </svg>
        </div>
        <span>{{ $locales[$currentLocale] ?? 'ENG' }}</span>
        <svg class="w-4 h-4 transition-transform duration-300" :class="open && 'rotate-180'" style="color: var(--text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
        class="absolute right-0 mt-2 w-36 rounded-2xl z-50 overflow-hidden"
        style="background: var(--bg-card); border: 1px solid var(--border-color); box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.2); backdrop-filter: blur(20px);"
    >
        <div class="py-1">
            @foreach($locales as $code => $label)
                <a
                    href="{{ route('locale.switch', $code) }}"
                    class="lang-option flex items-center gap-3 px-4 py-2.5 text-sm font-medium
                           transition-all duration-200 relative group"
                    style="color: {{ $currentLocale === $code ? 'var(--accent-primary)' : 'var(--text-primary)' }};"
                >
                    <div class="lang-indicator flex items-center justify-center w-7 h-7 rounded-lg transition-all duration-300"
                         style="{{ $currentLocale === $code ? 'background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));' : 'background: var(--bg-input);' }}">
                        <svg class="w-3.5 h-3.5 {{ $currentLocale === $code ? 'text-white' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                  d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                        </svg>
                    </div>
                    <span class="flex-1">{{ $label }}</span>
                    @if($currentLocale === $code)
                        <svg class="w-4 h-4" style="color: var(--accent-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>

<style>
    .lang-switcher-btn {
        box-shadow: 0 2px 8px -2px rgba(0, 0, 0, 0.08);
    }

    .lang-switcher-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 16px -4px rgba(0, 0, 0, 0.12);
    }

    .lang-switcher-btn:hover .lang-indicator {
        opacity: 1;
    }

    .lang-option:hover {
        background: var(--bg-hover);
    }

    .lang-option:hover .lang-indicator {
        background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
    }

    .lang-option:hover .lang-indicator svg {
        color: white;
    }
</style>
