{{--
    Dashboard stat card
    @include('shared.components.stat_card', [
        'icon'  => '<svg>...</svg>',
        'label' => 'Users',
        'value' => 42,
        'color' => 'primary',  // primary, info, warning, success, danger, accent
    ])
--}}
@php
    $color = $color ?? 'primary';
    $colorMap = [
        'primary' => ['bg' => 'var(--color-primary-light)', 'fg' => 'var(--color-primary)'],
        'info'    => ['bg' => 'var(--color-info-light)',    'fg' => 'var(--color-info)'],
        'warning' => ['bg' => 'var(--color-warning-light)', 'fg' => 'var(--color-warning)'],
        'success' => ['bg' => 'var(--color-success-light)', 'fg' => 'var(--color-success)'],
        'danger'  => ['bg' => 'var(--color-danger-light)',  'fg' => 'var(--color-danger)'],
        'accent'  => ['bg' => '#FFF7E6',                    'fg' => 'var(--color-accent-hover)'],
    ];
    $c = $colorMap[$color] ?? $colorMap['primary'];
@endphp

<div class="card p-5 flex items-center gap-4">
    <div class="flex items-center justify-center w-12 h-12 rounded-xl"
         style="background: {{ $c['bg'] }}; color: {{ $c['fg'] }};">
        {!! $icon !!}
    </div>
    <div>
        <p class="text-2xl font-bold" style="color: var(--text-primary);">{{ $value }}</p>
        <p class="text-sm" style="color: var(--text-secondary);">{{ $label }}</p>
    </div>
</div>
