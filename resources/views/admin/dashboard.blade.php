@extends('admin.layout')

@section('page-title', __('ui.dashboard'))

@section('content')
@php
    $locale = app()->getLocale();
    $stats = [
        [
            'label' => __('ui.total_users'),
            'value' => \App\Models\User::count(),
            'change' => '+12%',
            'color' => 'primary',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
        ],
        [
            'label' => __('ui.total_roles'),
            'value' => \App\Models\Role::where('is_active', true)->count(),
            'change' => '+5%',
            'color' => 'success',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>',
        ],
        [
            'label' => __('ui.permissions'),
            'value' => \App\Models\Permission::count(),
            'change' => '+8%',
            'color' => 'info',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>',
        ],
        [
            'label' => __('ui.total_matches'),
            'value' => \App\Models\MatchModel::count(),
            'change' => '+15%',
            'color' => 'warning',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        ],
    ];
@endphp

<style>
    /* Welcome Card */
    .welcome-card {
        background: var(--color-primary);
        border-radius: var(--radius-xl);
        padding: 2rem;
        position: relative;
        overflow: hidden;
        color: var(--text-on-primary);
    }

    .welcome-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 80%;
        height: 200%;
        background: radial-gradient(ellipse at center, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 8s ease-in-out infinite;
    }

    .welcome-card::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 60%;
        height: 150%;
        background: radial-gradient(ellipse at center, rgba(255,255,255,0.08) 0%, transparent 60%);
        animation: pulse 6s ease-in-out infinite reverse;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1) translate(0, 0); opacity: 0.5; }
        50% { transform: scale(1.05) translate(5px, 5px); opacity: 0.8; }
    }

    /* Enhanced Stat Card */
    .stat-card-enhanced {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        transition: all var(--transition-normal);
    }

    .stat-card-enhanced:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: var(--color-primary);
    }

    .stat-icon-enhanced {
        width: 3.5rem;
        height: 3.5rem;
        border-radius: var(--radius-lg);
        position: relative;
        transition: all var(--transition-normal);
    }

    .stat-icon-enhanced > div {
        width: 100%;
        height: 100%;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all var(--transition-normal);
    }

    .stat-card-enhanced:hover .stat-icon-enhanced > div {
        box-shadow: 0 0 0 2px var(--color-primary);
    }

    .change-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.625rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .change-indicator--positive {
        background: var(--color-success-light);
        color: var(--color-success);
    }

    .theme-dark .change-indicator--positive {
        background: rgba(22, 163, 74, 0.15);
    }

    /* Info Card */
    .info-card-enhanced {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: 1.5rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid var(--border-color);
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-secondary);
    }

    .info-value {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    /* Grid adjustments */
    @media (max-width: 768px) {
        .welcome-card {
            padding: 1.5rem;
        }
        .stat-card-enhanced {
            padding: 1.25rem;
        }
        .info-card-enhanced {
            padding: 1.25rem;
        }
    }
</style>

    {{-- Welcome Card --}}
    <div class="welcome-card mb-6">
        <div class="relative z-10">
            <h2 class="text-2xl md:text-3xl font-bold mb-2">
                {{ __('ui.greeting', ['name' => auth()->user()->first_name]) }} 👋
            </h2>
            <p class="text-sm md:text-base opacity-90">
                {{ __('ui.welcome_dashboard') }}
            </p>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6">
        @foreach($stats as $stat)
        <div class="stat-card-enhanced">
            <div class="flex items-start justify-between mb-4">
                <div class="stat-icon-enhanced">
                    @if($stat['color'] === 'primary')
                        <div class="absolute inset-0 rounded-xl" style="background: var(--color-primary-light); color: var(--color-primary);">
                            {!! $stat['icon'] !!}
                        </div>
                    @elseif($stat['color'] === 'success')
                        <div class="absolute inset-0 rounded-xl" style="background: var(--color-success-light); color: var(--color-success);">
                            {!! $stat['icon'] !!}
                        </div>
                    @elseif($stat['color'] === 'info')
                        <div class="absolute inset-0 rounded-xl" style="background: var(--color-info-light); color: var(--color-info);">
                            {!! $stat['icon'] !!}
                        </div>
                    @elseif($stat['color'] === 'warning')
                        <div class="absolute inset-0 rounded-xl" style="background: var(--color-warning-light); color: var(--color-warning);">
                            {!! $stat['icon'] !!}
                        </div>
                    @else
                        <div class="absolute inset-0 rounded-xl" style="background: var(--color-primary-light); color: var(--color-primary);">
                            {!! $stat['icon'] !!}
                        </div>
                    @endif
                </div>
                <span class="change-indicator change-indicator--positive">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    {{ $stat['change'] }}
                </span>
            </div>
            <div>
                <p class="text-2xl md:text-3xl font-bold mb-1" style="color: var(--text-primary);">
                    {{ number_format($stat['value']) }}
                </p>
                <p class="text-sm" style="color: var(--text-secondary);">
                    {{ $stat['label'] }}
                </p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Info Cards --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
        {{-- User Info --}}
        <div class="info-card-enhanced">
            <h3 class="text-lg font-semibold mb-5" style="color: var(--text-primary);">
                {{ __('ui.account_info') }}
            </h3>
            <div class="space-y-0">
                <div class="info-row">
                    <span class="info-label">{{ __('auth.full_name') }}</span>
                    <span class="info-value">
                        {{ auth()->user()->last_name }} {{ auth()->user()->first_name }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('ui.role') }}</span>
                    <span class="info-value">
                        {{ auth()->user()->role?->{'title_' . $locale} ?? '—' }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('ui.email') }}</span>
                    <span class="info-value text-sm">
                        {{ auth()->user()->email ?? '—' }}
                    </span>
                </div>
            </div>
        </div>


    </div>
@endsection
