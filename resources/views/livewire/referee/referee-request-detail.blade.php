@php $locale = app()->getLocale(); @endphp
@section('page-title', __('ui.referee_request_detail'))

<div>
    {{-- ── Back + Title ──────────────────────────────────── --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('referee.referee-request') }}" class="flex items-center gap-1 text-sm link">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            {{ __('crud.back_to_list') }}
        </a>
    </div>

    {{-- ── Match Info Card ───────────────────────────────── --}}
    <div class="card p-5 mb-6">
        {{-- Badges --}}
        <div class="flex flex-wrap items-center gap-2 mb-3">
            @if($match->tournament)
                <span class="badge badge-info">{{ $match->tournament->{'title_' . $locale} }}</span>
            @endif
            @if($match->season)
                <span class="badge badge-purple">{{ $match->season->{'title_' . $locale} }}</span>
            @endif
            @if($match->round)
                <span class="badge badge-secondary">{{ __('crud.round') }}: {{ $match->round }}</span>
            @endif
            @if($match->operation)
                <span class="badge badge-warning">{{ $match->operation->{'title_' . $locale} }}</span>
            @endif
        </div>

        {{-- Clubs --}}
        <h2 class="text-xl font-bold mb-3" style="color: var(--text-primary);">
            {{ $match->ownerClub->{'short_name_' . $locale} ?? '—' }}
            <span class="font-normal" style="color: var(--text-muted);">{{ __('crud.vs') }}</span>
            {{ $match->guestClub->{'short_name_' . $locale} ?? '—' }}
        </h2>

        {{-- Meta --}}
        <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-sm" style="color: var(--text-secondary);">
            @if($match->city)
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ $match->city->{'title_' . $locale} }}
                </span>
            @endif
            @if($match->stadium)
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h-1m-3-4h6m-7-4H3m2 0h5"/></svg>
                    {{ $match->stadium->{'title_' . $locale} }}
                </span>
            @endif
            @if($match->start_at)
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12z"/>
                    </svg>
                    {{ $match->start_at->format('d.m.Y H:i') }}
                </span>
            @endif
        </div>
    </div>

    {{-- ── Judge Requirements ───────────────────────────────── --}}
    @if($match->judge_requirements->isNotEmpty())
        <div class="card p-5 mb-6">
            <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">
                {{ __('crud.judge_requirements_section') }}
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($match->judge_requirements as $req)
                    <div class="rounded-lg p-4" style="background: var(--bg-body); border: 1px solid var(--border-color);">
                        <div class="font-semibold text-sm mb-1" style="color: var(--text-primary);">
                            {{ $req->judge_type->{'title_' . $locale} ?? '—' }}
                        </div>
                        <div class="flex items-center gap-3 text-sm" style="color: var(--text-secondary);">
                            <span>
                                {{ __('crud.qty') }}: {{ $req->qty }}
                            </span>
                            @if($req->is_required)
                                <span class="badge badge-danger" style="font-size: 0.6875rem;">*</span>
                            @else
                                <span class="badge badge-secondary" style="font-size: 0.6875rem;">{{ __('crud.optional') }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ── My Assignment ───────────────────────────────── --}}
    @if($myJudgeAssignments->isNotEmpty())
        <div class="card p-5 mb-6">
            <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">
                {{ __('ui.my_assignment') }}
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($myJudgeAssignments as $assignment)
                    <div class="rounded-lg p-4" style="background: var(--bg-body); border: 1px solid var(--border-color);">
                        {{-- Judge type --}}
                        <div class="text-xs font-medium mb-2" style="color: var(--text-muted);">
                            {{ __('crud.judge_type') }}: {{ $assignment->judge_type->{'title_' . $locale} ?? '—' }}
                        </div>

                        {{-- Response status --}}
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="text-xs" style="color: var(--text-muted);">
                                {{ __('crud.judge_response') }}:
                            </span>
                            @if($assignment->judge_response == 1)
                                <span class="badge badge-success">{{ __('crud.accepted') }}</span>
                            @elseif($assignment->judge_response == -1)
                                <span class="badge badge-danger">{{ __('crud.declined') }}</span>
                            @else
                                <span class="badge badge-warning">{{ __('ui.waiting_for_response') }}</span>
                            @endif

                            {{-- Final status (only if judge responded) --}}
                            @if($assignment->judge_response != 0 && $assignment->final_status != 0)
                                <span class="text-xs" style="color: var(--text-muted);">
                                    | {{ __('crud.final_status') }}:
                                </span>
                                @if($assignment->final_status == 1)
                                    <span class="badge badge-success">{{ __('crud.final_status_approved') }}</span>
                                @elseif($assignment->final_status == -1)
                                    <span class="badge badge-danger">{{ __('crud.final_status_rejected') }}</span>
                                @endif
                            @endif
                        </div>

                        {{-- Request comment (from department) --}}
                        @if($assignment->request_comment)
                            <div class="text-xs mb-2" style="color: var(--text-secondary);">
                                <span class="font-medium">{{ __('crud.request_comment') }}:</span>
                                {{ $assignment->request_comment }}
                            </div>
                        @endif

                        {{-- My comment (if any) --}}
                        @if($assignment->judge_comment)
                            <div class="text-xs mb-2" style="color: var(--text-secondary);">
                                <span class="font-medium">{{ __('crud.my_comment') }}:</span>
                                {{ $assignment->judge_comment }}
                            </div>
                        @endif

                        {{-- Action buttons --}}
                        @if($assignment->judge_response == 0)
                            <div class="flex gap-2">
                                <button wire:click="acceptInvitation" class="btn-primary text-sm flex-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ __('crud.accept_invitation') }}
                                </button>
                                <button wire:click="declineInvitation" class="btn-danger text-sm flex-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    {{ __('crud.decline_invitation') }}
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @else
        {{-- No assignment message --}}
        <div class="card p-5 mb-6">
            <p class="text-center" style="color: var(--text-muted);">
                {{ __('ui.no_assignment_yet') }}
            </p>
        </div>
    @endif

    {{-- ── Response Confirmation Modal ───────────────────────────────── --}}
    @if(auth()->user()->role->value === \App\Constants\RoleConstants::SOCCER_REFEREE)
        @script
            window.addEventListener('referee-accept', function(e) {
                if (e.detail.confirm) {
                    @this.acceptInvitation()
                }
            })

            window.addEventListener('referee-decline', function(e) {
                if (e.detail.confirm) {
                    @this.declineInvitation()
                }
            })
        @endscript
    @endif
</div>
