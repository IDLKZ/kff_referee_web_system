@extends('shared.layouts.base')

@section('sidebar')
    {{-- Brand --}}
    <div class="sidebar-brand">
        <div class="sidebar-brand-logo">
            <img src="{{asset("logo_kff.png")}}">
        </div>
        <div>
            <div class="sidebar-brand-text">{{ __('ui.admin_panel') }}</div>
            <div class="sidebar-brand-sub">KFF System</div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">
        @include('admin.sidebar.menu')
    </nav>

    {{-- User footer --}}
    @auth
        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    {{ mb_substr(auth()->user()->first_name, 0, 1) }}{{ mb_substr(auth()->user()->last_name, 0, 1) }}
                </div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ auth()->user()->last_name }} {{ auth()->user()->first_name }}</div>
                    <div class="sidebar-user-role">{{ auth()->user()->role?->{'title_' . app()->getLocale()} ?? '' }}</div>
                </div>
            </div>
        </div>
    @endauth
@endsection

@section('topbar')
    {{-- Mobile Menu Toggle --}}
    <button id="sidebar-toggle" class="lg:hidden p-2 rounded-lg transition-colors"
            style="color: var(--text-secondary); background: var(--bg-hover);">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    <h1 class="text-base md:text-lg font-semibold truncate" style="color: var(--text-primary);">
        @yield('page-title')
    </h1>

    {{-- Theme & Language (desktop) --}}
    <div class="hidden md:flex items-center gap-3">
        @include('shared.components.language_switcher')
        @include('shared.components.theme_toggle')
    </div>

    {{-- User Menu --}}
    <div class="flex items-center gap-3">
        @auth
            <div class="hidden sm:flex items-center gap-2">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold"
                     style="background: var(--color-primary); color: var(--text-on-primary);">
                    {{ mb_substr(auth()->user()->first_name, 0, 1) }}{{ mb_substr(auth()->user()->last_name, 0, 1) }}
                </div>
                <span class="text-sm font-medium hidden md:block truncate" style="color: var(--text-secondary);">
                    {{ auth()->user()->last_name }} {{ auth()->user()->first_name }}
                </span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="btn-icon btn-icon-delete"
                        :title="{{ __('auth.logout') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        @endauth
    </div>

    {{-- Theme & Language (mobile dropdown) --}}
    <div class="md:hidden relative" x-data="{ open: false }">
        <button @click="open = !open" class="btn-icon">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
            </svg>
        </button>
        <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="absolute right-0 mt-2 w-40 rounded-xl shadow-lg z-50 py-2"
             style="background: var(--bg-card); border: 1px solid var(--border-color);">
            <div class="px-4 py-2">
                <div class="flex items-center gap-3 py-1">
                    <div class="relative">
                        @include('shared.components.theme_toggle')
                    </div>
                    <span class="text-sm" style="color: var(--text-secondary);">{{ __('ui.theme') }}</span>
                </div>
            </div>
            <div class="px-4 py-2 border-t" style="border-color: var(--border-color);">
                <div class="flex items-center gap-3 py-1">
                    <div class="relative">
                        @include('shared.components.language_switcher')
                    </div>
                    <span class="text-sm" style="color: var(--text-secondary);">{{ __('ui.language') }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Mobile sidebar toggle
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.querySelector('aside');
        let sidebarOpen = false;

        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function() {
                sidebarOpen = !sidebarOpen;
                if (sidebarOpen) {
                    sidebar.style.position = 'fixed';
                    sidebar.style.left = '0';
                    sidebar.style.top = '0';
                    sidebar.style.bottom = '0';
                    sidebar.style.zIndex = '50';
                    sidebar.style.transform = 'translateX(0)';
                } else {
                    sidebar.style.transform = 'translateX(-100%)';
                    setTimeout(() => {
                        sidebar.style.position = '';
                        sidebar.style.left = '';
                        sidebar.style.top = '';
                        sidebar.style.bottom = '';
                        sidebar.style.zIndex = '';
                        sidebar.style.transform = '';
                    }, 300);
                }
            });

            // Close sidebar on outside click
            document.addEventListener('click', function(e) {
                if (sidebarOpen && !sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebarToggle.click();
                }
            });
        }
    });
</script>
@endpush
