@extends('referee.layout')

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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
        @include('shared.components.stat_card', [
            'label' => __('ui.assigned_matches'),
            'value' => \App\Models\MatchJudge::where('judge_id', auth()->id())->count(),
            'color' => 'primary',
            'icon'  => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
        ])

        @include('shared.components.stat_card', [
            'label' => __('ui.active_trips'),
            'value' => \App\Models\Trip::where('judge_id', auth()->id())->count(),
            'color' => 'info',
            'icon'  => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>',
        ])

        @include('shared.components.stat_card', [
            'label' => __('ui.notifications'),
            'value' => \App\Models\Notification::where('user_id', auth()->id())->whereNull('read_at')->count(),
            'color' => 'warning',
            'icon'  => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>',
        ])
    </div>

@endsection
