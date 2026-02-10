{{--
    Sidebar navigation item with icon support
    @include('shared.components.sidebar_item', [
        'route'  => 'admin.dashboard',
        'icon'   => '<svg>...</svg>',
        'label'  => __('ui.dashboard'),
        'active' => request()->routeIs('admin.dashboard'),  // optional, auto-detected
    ])
--}}
@php
    $isActive = $active ?? request()->routeIs($route);
    $href = route($route);
@endphp

<a href="{{ $href }}"
   class="sidebar-item {{ $isActive ? 'sidebar-item-active' : '' }}">
    <span class="sidebar-item-icon">
        {!! $icon !!}
    </span>
    <span class="sidebar-item-label">{{ $label }}</span>
</a>
