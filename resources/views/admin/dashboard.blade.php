@extends('admin.layout')

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
            'label' => __('ui.total_users'),
            'value' => \App\Models\User::count(),
            'color' => 'primary',
            'icon'  => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>',
        ])

        @include('shared.components.stat_card', [
            'label' => __('ui.total_roles'),
            'value' => \App\Models\Role::where('is_active', true)->count(),
            'color' => 'info',
            'icon'  => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>',
        ])

        @include('shared.components.stat_card', [
            'label' => __('ui.permissions'),
            'value' => \App\Models\Permission::count(),
            'color' => 'warning',
            'icon'  => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>',
        ])
    </div>

    {{-- System Info --}}
    <div class="card p-6">
        <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">
            {{ __('ui.system_info') }}
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex justify-between py-2" style="border-bottom: 1px solid var(--border-color);">
                <span class="text-sm" style="color: var(--text-secondary);">{{ __('ui.role') }}</span>
                <span class="text-sm font-medium" style="color: var(--text-primary);">
                    {{ auth()->user()->role?->{'title_' . app()->getLocale()} ?? '—' }}
                </span>
            </div>
            <div class="flex justify-between py-2" style="border-bottom: 1px solid var(--border-color);">
                <span class="text-sm" style="color: var(--text-secondary);">{{ __('ui.last_login') }}</span>
                <span class="text-sm font-medium" style="color: var(--text-primary);">{{ __('ui.just_now') }}</span>
            </div>
        </div>
    </div>
@endsection
