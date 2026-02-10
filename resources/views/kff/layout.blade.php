@extends('shared.layouts.base')

@section('sidebar')
    {{-- Brand --}}
    <div class="sidebar-brand">
        <div class="sidebar-brand-logo">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
            </svg>
        </div>
        <div>
            <div class="sidebar-brand-text">{{ __('ui.kff_panel') }}</div>
            <div class="sidebar-brand-sub">KFF System</div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">
        @include('kff.sidebar.menu')
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
    <div class="flex items-center gap-4">
        <h1 class="text-lg font-semibold" style="color: var(--text-primary);">@yield('page-title')</h1>
    </div>
    <div class="flex items-center gap-4">
        @include('shared.top_bar.user_menu')
    </div>
@endsection
