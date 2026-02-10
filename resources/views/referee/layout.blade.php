@extends('shared.layouts.base')

@section('sidebar')
    {{-- Brand --}}
    <div class="sidebar-brand">
        <div class="sidebar-brand-logo">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14.4 6L14 4H5v17h2v-7h5.6l.4 2h7V6h-5.6z"/>
            </svg>
        </div>
        <div>
            <div class="sidebar-brand-text">{{ __('ui.referee_panel') }}</div>
            <div class="sidebar-brand-sub">KFF System</div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">
        @include('referee.sidebar.menu')
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
