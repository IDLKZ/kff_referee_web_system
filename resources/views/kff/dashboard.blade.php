@extends('kff.layout')

@section('page-title', __('ui.dashboard'))

@section('content')
    {{-- Greeting --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold" style="color: var(--text-primary);">
            {{ __('ui.greeting', ['name' => auth()->user()->first_name]) }}
        </h2>
        <p class="mt-1 text-sm" style="color: var(--text-secondary);">
            {{ __('ui.welcome_dashboard') }}
        </p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        @include('shared.components.stat_card', [
            'label' => __('ui.active_matches'),
            'value' => \App\Models\MatchModel::where('is_active', true)->where('is_finished', false)->count(),
            'color' => 'primary',
            'icon'  => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
        ])

        @include('shared.components.stat_card', [
            'label' => __('ui.total_tournaments'),
            'value' => \App\Models\Tournament::count(),
            'color' => 'accent',
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


@endsection
