{{-- KFF sidebar menu --}}


@include('shared.components.sidebar_section', ['title' => __('ui.operations')])

@include('shared.components.sidebar_item', [
    'route' => 'kff.referee-approval',
    'label' => __('ui.referee_approval'),
    'active' => request()->routeIs('kff.referee-approval') || request()->routeIs('kff.referee-approve-detail'),
    'icon'  => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
])
