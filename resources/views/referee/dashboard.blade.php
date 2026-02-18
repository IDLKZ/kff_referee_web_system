@extends('referee.layout')

@section('page-title', __('ui.dashboard'))

@section('content')
@php
    $locale = app()->getLocale();
    $user = auth()->user();
    $assignedCount = \App\Models\MatchJudge::where('judge_id', $user->id)->count();
    $tripsCount = \App\Models\Trip::where('judge_id', $user->id)->count();
    $unreadCount = \App\Models\Notification::where('user_id', $user->id)->whereNull('read_at')->count();
@endphp

    {{-- Welcome Card --}}
    <div class="card p-6 mb-6" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-hover)); color: var(--text-on-primary);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold mb-1">
                    {{ __('ui.greeting', ['name' => $user->first_name]) }}
                </h2>
                <p class="text-sm opacity-80">
                    {{ __('ui.welcome_dashboard') }}
                </p>
            </div>
            <div class="flex items-center gap-2 text-sm opacity-80">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        @include('shared.components.stat_card', [
            'label' => __('ui.assigned_matches'),
            'value' => $assignedCount,
            'color' => 'primary',
            'icon'  => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
        ])
        @include('shared.components.stat_card', [
            'label' => __('ui.active_trips'),
            'value' => $tripsCount,
            'color' => 'info',
            'icon'  => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>',
        ])
        @include('shared.components.stat_card', [
            'label' => __('ui.notifications'),
            'value' => $unreadCount,
            'color' => $unreadCount > 0 ? 'warning' : 'success',
            'icon'  => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>',
        ])
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- Quick Actions --}}
        <div class="card p-5">
            <h3 class="text-base font-semibold mb-4" style="color: var(--text-primary);">
                {{ __('ui.quick_actions') }}
            </h3>
            <div class="grid grid-cols-1 gap-3">
                <a href="{{ route('referee.referee-request') }}" class="flex items-center gap-3 p-3 rounded-lg transition-colors" style="background: var(--bg-hover);" onmouseover="this.style.background='var(--color-primary-light)'" onmouseout="this.style.background='var(--bg-hover)'">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg" style="background: var(--color-primary-light); color: var(--color-primary);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <span class="text-sm font-medium" style="color: var(--text-primary);">{{ __('ui.view_invitations') }}</span>
                        <p class="text-xs" style="color: var(--text-muted);">{{ __('ui.referee_request') }}</p>
                    </div>
                </a>
                <a href="{{ route('referee.referee-trips') }}" class="flex items-center gap-3 p-3 rounded-lg transition-colors" style="background: var(--bg-hover);" onmouseover="this.style.background='var(--color-info-light)'" onmouseout="this.style.background='var(--bg-hover)'">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg" style="background: var(--color-info-light); color: var(--color-info);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </div>
                    <div>
                        <span class="text-sm font-medium" style="color: var(--text-primary);">{{ __('ui.view_trips') }}</span>
                        <p class="text-xs" style="color: var(--text-muted);">{{ __('ui.my_trips') }}</p>
                    </div>
                </a>
                <a href="{{ route('referee.referee-protocol-management') }}" class="flex items-center gap-3 p-3 rounded-lg transition-colors" style="background: var(--bg-hover);" onmouseover="this.style.background='var(--color-warning-light)'" onmouseout="this.style.background='var(--bg-hover)'">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg" style="background: var(--color-warning-light); color: var(--color-warning);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <span class="text-sm font-medium" style="color: var(--text-primary);">{{ __('ui.view_reports') }}</span>
                        <p class="text-xs" style="color: var(--text-muted);">{{ __('ui.my_match_reports') }}</p>
                    </div>
                </a>
            </div>
        </div>

        {{-- Account Info --}}
        <div class="card p-5">
            <h3 class="text-base font-semibold mb-4" style="color: var(--text-primary);">
                {{ __('ui.account_info') }}
            </h3>
            <div class="space-y-0">
                <div class="flex justify-between items-center py-3" style="border-bottom: 1px solid var(--border-color);">
                    <span class="text-sm" style="color: var(--text-secondary);">{{ __('auth.full_name') }}</span>
                    <span class="text-sm font-semibold" style="color: var(--text-primary);">
                        {{ $user->last_name }} {{ $user->first_name }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-3" style="border-bottom: 1px solid var(--border-color);">
                    <span class="text-sm" style="color: var(--text-secondary);">{{ __('ui.role') }}</span>
                    <span class="badge badge-success">
                        {{ $user->role?->{'title_' . $locale} ?? '—' }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-3">
                    <span class="text-sm" style="color: var(--text-secondary);">{{ __('ui.email') }}</span>
                    <span class="text-sm font-medium" style="color: var(--text-primary);">
                        {{ $user->email ?? '—' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
@endsection
