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
            'bgGradient' => 'from-indigo-500/10 to-purple-500/10',
            'borderColor' => 'from-indigo-500 to-purple-500',
        ],
        [
            'label' => __('ui.total_roles'),
            'value' => \App\Models\Role::where('is_active', true)->count(),
            'change' => '+5%',
            'color' => 'success',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>',
            'bgGradient' => 'from-emerald-500/10 to-teal-500/10',
            'borderColor' => 'from-emerald-500 to-teal-500',
        ],
        [
            'label' => __('ui.permissions'),
            'value' => \App\Models\Permission::count(),
            'change' => '+8%',
            'color' => 'info',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>',
            'bgGradient' => 'from-cyan-500/10 to-blue-500/10',
            'borderColor' => 'from-cyan-500 to-blue-500',
        ],
        [
            'label' => __('ui.total_matches'),
            'value' => \App\Models\MatchModel::count(),
            'change' => '+15%',
            'color' => 'warning',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            'bgGradient' => 'from-amber-500/10 to-orange-500/10',
            'borderColor' => 'from-amber-500 to-orange-500',
        ],
    ];
@endphp

<style>
    .dashboard-welcome {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 32px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .dashboard-welcome::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
        animation: pulse 4s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        padding: 24px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        inset: 0;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        border-color: rgba(255, 255, 255, 0.15);
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.3);
    }

    .stat-card:hover::before {
        opacity: 0.05;
    }

    .stat-icon-wrapper {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .stat-icon-wrapper::after {
        content: '';
        position: absolute;
        inset: -2px;
        border-radius: 16px;
        padding: 2px;
        background: linear-gradient(135deg, var(--color-primary), var(--color-accent));
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .stat-card:hover .stat-icon-wrapper::after {
        opacity: 1;
    }

    .change-badge {
        font-size: 12px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
    }

    .change-positive {
        background: rgba(52, 211, 153, 0.15);
        color: #34d399;
    }

    .info-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        padding: 28px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.5);
    }

    .info-value {
        font-size: 15px;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.9);
    }
</style>

    {{-- Welcome Card --}}
    <div class="dashboard-welcome mb-8">
        <div class="relative z-10">
            <h2 class="text-3xl font-bold mb-2">
                {{ __('ui.greeting', ['name' => auth()->user()->first_name]) }} 👋
            </h2>
            <p class="text-white/80">
                {{ __('ui.welcome_dashboard') }}
            </p>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @foreach($stats as $stat)
        <div class="stat-card">
            <div class="flex items-start justify-between mb-4">
                <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, {{ $stat['bgGradient'] }});">
                    {!! $stat['icon'] !!}
                </div>
                <span class="change-badge change-positive">
                    {{ $stat['change'] }}
                </span>
            </div>
            <div>
                <p class="text-3xl font-bold mb-1" style="color: var(--text-primary);">
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
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- User Info --}}
        <div class="info-card">
            <h3 class="text-lg font-semibold mb-6" style="color: var(--text-primary);">
                {{ __('ui.account_info') }}
            </h3>
            <div class="space-y-0">
                <div class="info-item">
                    <span class="info-label">{{ __('auth.full_name') }}</span>
                    <span class="info-value">
                        {{ auth()->user()->last_name }} {{ auth()->user()->first_name }}
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ __('ui.role') }}</span>
                    <span class="info-value">
                        {{ auth()->user()->role?->{'title_' . $locale} ?? '—' }}
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ __('ui.email') }}</span>
                    <span class="info-value">
                        {{ auth()->user()->email ?? '—' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- System Info --}}
        <div class="info-card">
            <h3 class="text-lg font-semibold mb-6" style="color: var(--text-primary);">
                {{ __('ui.system_info') }}
            </h3>
            <div class="space-y-0">
                <div class="info-item">
                    <span class="info-label">{{ __('ui.php_version') }}</span>
                    <span class="info-value">{{ PHP_VERSION }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ __('ui.laravel_version') }}</span>
                    <span class="info-value">{{ app()->version() }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ __('ui.last_login') }}</span>
                    <span class="info-value">{{ __('ui.just_now') }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
