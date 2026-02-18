@extends('kff.layout')

@section('page-title', __('ui.dashboard'))

@section('content')
@php
    $locale = app()->getLocale();
    $user = auth()->user();
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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @include('shared.components.stat_card', [
            'label' => __('ui.active_matches'),
            'value' => \App\Models\MatchModel::where('is_active', true)->where('is_finished', false)->count(),
            'color' => 'primary',
            'icon'  => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
        ])
        @include('shared.components.stat_card', [
            'label' => __('ui.total_tournaments'),
            'value' => \App\Models\Tournament::count(),
            'color' => 'warning',
            'icon'  => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>',
        ])
        @include('shared.components.stat_card', [
            'label' => __('ui.total_clubs'),
            'value' => \App\Models\Club::count(),
            'color' => 'info',
            'icon'  => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
        ])
        @include('shared.components.stat_card', [
            'label' => __('ui.judges'),
            'value' => \App\Models\User::whereHas('role', fn($q) => $q->where('group', \App\Constants\RoleConstants::REFEREE_GROUP))->count(),
            'color' => 'success',
            'icon'  => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>',
        ])
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- Quick Actions --}}
        <div class="card p-5">
            <h3 class="text-base font-semibold mb-4" style="color: var(--text-primary);">
                {{ __('ui.quick_actions') }}
            </h3>
            <div class="grid grid-cols-1 gap-3">
                <a href="{{ route('kff.referee-approval') }}" class="flex items-center gap-3 p-3 rounded-lg transition-colors" style="background: var(--bg-hover);" onmouseover="this.style.background='var(--color-primary-light)'" onmouseout="this.style.background='var(--bg-hover)'">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg" style="background: var(--color-primary-light); color: var(--color-primary);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <span class="text-sm font-medium" style="color: var(--text-primary);">{{ __('ui.view_approvals') }}</span>
                        <p class="text-xs" style="color: var(--text-muted);">{{ __('ui.referee_approval') }}</p>
                    </div>
                </a>
                <a href="{{ route('kff.kff-trips') }}" class="flex items-center gap-3 p-3 rounded-lg transition-colors" style="background: var(--bg-hover);" onmouseover="this.style.background='var(--color-info-light)'" onmouseout="this.style.background='var(--bg-hover)'">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg" style="background: var(--color-info-light); color: var(--color-info);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </div>
                    <div>
                        <span class="text-sm font-medium" style="color: var(--text-primary);">{{ __('ui.view_trips') }}</span>
                        <p class="text-xs" style="color: var(--text-muted);">{{ __('ui.trips') }}</p>
                    </div>
                </a>
                <a href="{{ route('kff.protocol-review') }}" class="flex items-center gap-3 p-3 rounded-lg transition-colors" style="background: var(--bg-hover);" onmouseover="this.style.background='var(--color-warning-light)'" onmouseout="this.style.background='var(--bg-hover)'">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg" style="background: var(--color-warning-light); color: var(--color-warning);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <span class="text-sm font-medium" style="color: var(--text-primary);">{{ __('ui.view_reports') }}</span>
                        <p class="text-xs" style="color: var(--text-muted);">{{ __('ui.protocol_review') }}</p>
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
