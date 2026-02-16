{{-- Theme Toggle (Light/Dark) --}}
<button
    x-data="{
        dark: document.documentElement.classList.contains('theme-dark') || (!document.documentElement.classList.contains('theme-light') && localStorage.getItem('theme') !== 'light'),
        toggle() {
            this.dark = !this.dark;
            localStorage.setItem('theme', this.dark ? 'dark' : 'light');
            document.documentElement.classList.toggle('theme-dark', this.dark);
            document.documentElement.classList.toggle('theme-light', !this.dark);
        }
    }"
    @click="toggle()"
    class="theme-toggle-btn relative flex items-center justify-center w-10 h-10 rounded-xl transition-all duration-300 hover:scale-105 active:scale-95"
    :title="dark ? '{{ __('ui.light') }}' : '{{ __('ui.dark') }}'"
    style="background: var(--bg-card); border: 1px solid var(--border-color);"
>
    {{-- Animated icon container --}}
    <div class="relative w-5 h-5 overflow-hidden">
        {{-- Sun icon (shown in dark mode) --}}
        <svg x-show="dark" x-transition:enter="transition-transform duration-300 ease-out" x-transition:enter-start="-translate-y-full opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition-transform duration-300 ease-in" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="-translate-y-full opacity-0" class="absolute inset-0 w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        {{-- Moon icon (shown in light mode) --}}
        <svg x-show="!dark" x-transition:enter="transition-transform duration-300 ease-out" x-transition:enter-start="translate-y-full opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition-transform duration-300 ease-in" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-full opacity-0" class="absolute inset-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--accent-secondary);">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
        </svg>
    </div>

    {{-- Glow effect --}}
    <div class="absolute inset-0 rounded-xl opacity-0 hover:opacity-100 transition-opacity duration-300" x-show="dark" style="background: radial-gradient(circle at center, rgba(251, 191, 36, 0.15), transparent);"></div>
    <div class="absolute inset-0 rounded-xl opacity-0 hover:opacity-100 transition-opacity duration-300" x-show="!dark" style="background: radial-gradient(circle at center, rgba(139, 92, 246, 0.15), transparent);"></div>
</button>

<style>
    .theme-toggle-btn {
        box-shadow: 0 2px 8px -2px rgba(0, 0, 0, 0.1);
    }

    .theme-toggle-btn:hover {
        box-shadow: 0 4px 12px -4px rgba(0, 0, 0, 0.15);
    }
</style>
