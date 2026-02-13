{{-- Referee sidebar menu --}}

@include('shared.components.sidebar_section', ['title' => __('ui.operations')])

@include('shared.components.sidebar_item', [
    'route' => 'referee.referee-request',
    'label' => __('ui.referee_request'),
    'active' => request()->routeIs('referee.referee-request') || request()->routeIs('referee.referee-request-detail'),
    'icon'  => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h16a1 1 0 011-1V9a1 1 0 00-2-2H6a1 1 0 00-2 2v10m14 0v-8a1 1 0 011 1h2a1 1 0 011 1v6a1 1 0 01-1h-2a1 1 0 00-1-1z"/></svg>',
])

@include('shared.components.sidebar_item', [
    'route' => 'referee.dashboard',
    'label' => __('ui.dashboard'),
    'active' => request()->routeIs('referee.dashboard'),
    'icon'  => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1h-2z"/></svg>',
])

