@extends('shared.layouts.base')

@section('sidebar')
    {{-- Brand --}}
    <div class="sidebar-brand">
        <div class="sidebar-brand-logo">
            <img src="{{asset("logo_kff.png")}}">
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
