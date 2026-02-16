{{-- KFF sidebar menu --}}


@include('shared.components.sidebar_section', ['title' => __('ui.operations')])

@if(auth()->user()->role->value == \App\Constants\RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE)
    @include('shared.components.sidebar_item', [
    'route' => 'kff.referee-approval',
    'label' => __('ui.referee_approval'),
    'active' => request()->routeIs('kff.referee-approval') || request()->routeIs('kff.referee-approve-detail'),
    'icon'  => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
])
@endif

@if(auth()->user()->role->value == \App\Constants\RoleConstants::REFEREEING_DEPARTMENT_HEAD)
@include('shared.components.sidebar_item', [
    'route' => 'kff.head-referee-approval',
    'label' => __('ui.head_referee_approval'),
    'active' => request()->routeIs('kff.head-referee-approval') || request()->routeIs('kff.head-referee-approve-detail'),
    'icon'  => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>',
])
@endif
@if(auth()->user()->role->value == \App\Constants\RoleConstants::REFEREEING_DEPARTMENT_LOGISTICIAN)
@include('shared.components.sidebar_item', [
    'route' => 'kff.kff-trips',
    'label' => __('crud.trips'),
    'active' => request()->routeIs('kff.kff-trips'),
    'icon'  => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>',
])
@endif
@if(auth()->user()->role->value == \App\Constants\RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE)
@include('shared.components.sidebar_section', ['title' => __('ui.review')])
@include('shared.components.sidebar_item', [
    'route' => 'kff.protocol-review',
    'label' => __('ui.protocol_review'),
    'active' => request()->routeIs('kff.protocol-review') || request()->routeIs('kff.protocol-report-detail'),
    'icon'  => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
])
@endif

