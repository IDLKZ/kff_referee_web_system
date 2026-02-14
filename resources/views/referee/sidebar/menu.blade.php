{{-- Referee sidebar menu --}}

@include('shared.components.sidebar_section', ['title' => __('ui.operations')])

@include('shared.components.sidebar_item', [
    'route' => 'referee.referee-protocol-management',
    'label' => __('ui.my_match_reports'),
    'active' => request()->routeIs('referee.referee-protocol-management'),
    'icon'  => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
])

@include('shared.components.sidebar_item', [
    'route' => 'referee.referee-request',
    'label' => __('ui.referee_request'),
    'active' => request()->routeIs('referee.referee-request') || request()->routeIs('referee.referee-request-detail'),
    'icon'  => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h16a1 1 0 011-1V9a1 1 0 00-2-2H6a1 1 0 00-2 2v10m14 0v-8a1 1 0 011 1h2a1 1 0 011 1v6a1 1 0 01-1h-2a1 1 0 00-1-1z"/></svg>',
])

@include('shared.components.sidebar_item', [
    'route' => 'referee.referee-trips',
    'label' => __('ui.my_trips'),
    'active' => request()->routeIs('referee.referee-trips'),
    'icon'  => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>',
])

@include('shared.components.sidebar_item', [
    'route' => 'referee.dashboard',
    'label' => __('ui.dashboard'),
    'active' => request()->routeIs('referee.dashboard'),
    'icon'  => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1h-2z"/></svg>',
])

