@php $locale = app()->getLocale(); @endphp
@section('page-title', __('ui.referee_approve_detail'))

<div>
    {{-- ── Back + Title ──────────────────────────────────── --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('kff.referee-approval') }}" class="flex items-center gap-1 text-sm link">
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
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ $match->city->{'title_' . $locale} }}
                </span>
            @endif
            @if($match->stadium)
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    {{ $match->stadium->{'title_' . $locale} }}
                </span>
            @endif
            @if($match->start_at)
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ $match->start_at->format('d.m.Y H:i') }}
                </span>
            @endif
        </div>
    </div>

    {{-- ── MATCH_CREATED_WAITING_REFEREES ────────────────── --}}
    @if($operationValue === \App\Constants\OperationConstants::MATCH_CREATED_WAITING_REFEREES)
        <div class="card p-5 mb-6">
            <h3 class="text-lg font-semibold mb-3" style="color: var(--text-primary);">
                {{ __('crud.judge_requirements_section') }}
            </h3>

            @if($match->judge_requirements->isEmpty())
                <p class="text-sm mb-4" style="color: var(--color-danger);">
                    {{ __('crud.no_judge_requirements_error') }}
                </p>
            @else
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach($match->judge_requirements as $req)
                        <span class="badge badge-secondary">
                            {{ $req->judge_type->{'title_' . $locale} ?? '—' }}: {{ $req->qty }}
                            @if($req->is_required)
                                <span style="color: var(--color-danger);">*</span>
                            @endif
                        </span>
                    @endforeach
                </div>

                <button wire:click="confirmMoveToAssignment" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                    {{ __('crud.move_to_assignment') }}
                </button>
            @endif
        </div>
    @endif

    {{-- ── REFEREE_ASSIGNMENT / REFEREE_REASSIGNMENT ─────── --}}
    @if(in_array($operationValue, [
        \App\Constants\OperationConstants::REFEREE_ASSIGNMENT,
        \App\Constants\OperationConstants::REFEREE_REASSIGNMENT,
    ]))
        {{-- Requirements with slots --}}
        <div class="space-y-4 mb-6">
            @foreach($slotInfo as $typeId => $info)
                @php
                    $req = $info['requirement'];
                    $judgesForType = $match->match_judges->where('type_id', $typeId);
                    $showInvite = $info['available'] > 0;
                    // In REASSIGNMENT, only show invite for types that need reassignment
                    if ($operationValue === \App\Constants\OperationConstants::REFEREE_REASSIGNMENT) {
                        $showInvite = $info['available'] > 0 && $info['rejectedByHead'] > 0;
                    }
                @endphp
                <div class="card p-5">
                    {{-- Type header --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                        <div>
                            <h4 class="text-base font-semibold" style="color: var(--text-primary);">
                                {{ $req->judge_type->{'title_' . $locale} ?? '—' }}
                            </h4>
                            <div class="flex items-center gap-3 mt-1 text-sm">
                                <span style="color: var(--text-secondary);">
                                    {{ __('crud.qty') }}: {{ $req->qty }}
                                </span>
                                <span style="color: {{ $info['isMet'] ? 'var(--color-success)' : 'var(--color-warning)' }};">
                                    {{ __('crud.accepted_count') }}: {{ $info['accepted'] }}/{{ $req->qty }}
                                </span>
                                @if($req->is_required)
                                    <span class="badge badge-danger" style="font-size: 0.6875rem;">{{ __('crud.is_required') }}</span>
                                @else
                                    <span class="badge badge-secondary" style="font-size: 0.6875rem;">{{ __('crud.optional') }}</span>
                                @endif
                            </div>
                        </div>

                        @if($showInvite)
                            <button wire:click="openInviteModal({{ $typeId }})" class="btn-primary text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                                {{ __('crud.invite_judge') }} ({{ $info['available'] }})
                            </button>
                        @endif
                    </div>

                    {{-- Progress bar --}}
                    <div class="w-full rounded-full h-2 mb-4" style="background: var(--bg-body);">
                        @php
                            $pct = $req->qty > 0 ? min(100, round($info['accepted'] / $req->qty * 100)) : 0;
                            $barColor = $info['isMet'] ? 'var(--color-success)' : 'var(--color-warning)';
                        @endphp
                        <div class="h-2 rounded-full transition-all" style="width: {{ $pct }}%; background: {{ $barColor }};"></div>
                    </div>

                    {{-- Judges for this type --}}
                    @if($judgesForType->count())
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($judgesForType as $mj)
                                <div class="rounded-lg p-3" style="background: var(--bg-body); border: 1px solid var(--border-color);">
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <div>
                                            <div class="font-semibold text-sm" style="color: var(--text-primary);">
                                                {{ $mj->user->last_name ?? '' }} {{ $mj->user->first_name ?? '' }}
                                            </div>
                                            <div class="text-xs" style="color: var(--text-muted);">
                                                {{ $mj->user->email ?? '' }}
                                            </div>
                                        </div>

                                        {{-- Remove button --}}
                                        @if(in_array($operationValue, [
                                            \App\Constants\OperationConstants::REFEREE_ASSIGNMENT,
                                            \App\Constants\OperationConstants::REFEREE_REASSIGNMENT,
                                        ]))
                                            <button wire:click="confirmRemoveJudge({{ $mj->id }})"
                                                    class="btn-icon btn-icon-delete flex-shrink-0"
                                                    title="{{ __('crud.remove') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>

                                    {{-- Request comment --}}
                                    @if($mj->request_comment)
                                        <div class="text-xs mb-2" style="color: var(--text-secondary);">
                                            <span class="font-medium">{{ __('crud.request_comment') }}:</span>
                                            {{ $mj->request_comment }}
                                        </div>
                                    @endif

                                    {{-- Judge response --}}
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        <span class="text-xs" style="color: var(--text-muted);">{{ __('crud.judge_response') }}:</span>
                                        @if($mj->judge_response == 1)
                                            <span class="badge badge-success">{{ __('crud.judge_response_accepted') }}</span>
                                        @elseif($mj->judge_response == -1)
                                            <span class="badge badge-danger">{{ __('crud.judge_response_declined') }}</span>
                                        @else
                                            <span class="badge badge-warning">{{ __('crud.judge_response_pending') }}</span>
                                        @endif
                                    </div>

                                    {{-- Judge comment --}}
                                    @if($mj->judge_comment)
                                        <div class="text-xs mb-1" style="color: var(--text-secondary);">
                                            <span class="font-medium">{{ __('crud.judge_comment') }}:</span>
                                            {{ $mj->judge_comment }}
                                        </div>
                                    @endif

                                    {{-- Final status (show if not default) --}}
                                    @if($mj->final_status != 0)
                                        <div class="flex flex-wrap items-center gap-2 mt-1">
                                            <span class="text-xs" style="color: var(--text-muted);">{{ __('crud.final_status') }}:</span>
                                            @if($mj->final_status == 1)
                                                <span class="badge badge-success">{{ __('crud.final_status_approved') }}</span>
                                            @elseif($mj->final_status == -1)
                                                <span class="badge badge-danger">{{ __('crud.final_status_rejected') }}</span>
                                            @endif
                                        </div>
                                        @if($mj->final_comment)
                                            <div class="text-xs mt-1" style="color: var(--text-secondary);">
                                                <span class="font-medium">{{ __('crud.final_comment') }}:</span>
                                                {{ $mj->final_comment }}
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm" style="color: var(--text-muted);">{{ __('crud.no_judges_assigned') }}</p>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Submit for review button --}}
        <div class="card p-5 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h4 class="font-semibold" style="color: var(--text-primary);">{{ __('crud.submit_for_review') }}</h4>
                    <p class="text-sm mt-1" style="color: var(--text-secondary);">
                        {{ __('crud.submit_for_review_hint') }}
                    </p>
                </div>
                <button
                    wire:click="confirmSubmitForReview"
                    class="btn-primary"
                    @if(!$canSubmitForReview) disabled @endif
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('crud.submit_for_review') }}
                </button>
            </div>
        </div>
    @endif

    {{-- ── REFEREE_TEAM_APPROVAL ─────────────────────────── --}}
    @if($operationValue === \App\Constants\OperationConstants::REFEREE_TEAM_APPROVAL)
        <div class="card p-5 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: var(--color-warning-light);">
                    <svg class="w-5 h-5" style="color: var(--color-warning);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold" style="color: var(--text-primary);">{{ __('crud.waiting_for_head_approval') }}</h3>
                    <p class="text-sm" style="color: var(--text-secondary);">{{ __('crud.waiting_for_head_approval_hint') }}</p>
                </div>
            </div>

            {{-- Show judges read-only --}}
            @foreach($slotInfo as $typeId => $info)
                @php $judgesForType = $match->match_judges->where('type_id', $typeId); @endphp
                <div class="mb-4">
                    <h4 class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                        {{ $info['requirement']->judge_type->{'title_' . $locale} ?? '—' }}
                        <span class="font-normal">({{ $judgesForType->count() }}/{{ $info['requirement']->qty }})</span>
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($judgesForType as $mj)
                            <div class="rounded-lg p-3" style="background: var(--bg-body); border: 1px solid var(--border-color);">
                                <div class="font-semibold text-sm mb-1" style="color: var(--text-primary);">
                                    {{ $mj->user->last_name ?? '' }} {{ $mj->user->first_name ?? '' }}
                                </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    @if($mj->judge_response == 1)
                                        <span class="badge badge-success">{{ __('crud.judge_response_accepted') }}</span>
                                    @elseif($mj->judge_response == -1)
                                        <span class="badge badge-danger">{{ __('crud.judge_response_declined') }}</span>
                                    @else
                                        <span class="badge badge-warning">{{ __('crud.judge_response_pending') }}</span>
                                    @endif
                                    @if($mj->final_status == 1)
                                        <span class="badge badge-success">{{ __('crud.final_status_approved') }}</span>
                                    @elseif($mj->final_status == -1)
                                        <span class="badge badge-danger">{{ __('crud.final_status_rejected') }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- ── Invite Judge Modal ────────────────────────────── --}}
    <x-modal wire:model="showInviteModal" maxWidth="lg">
        <x-slot name="title">{{ __('crud.invite_judge') }}</x-slot>

        <div class="space-y-4">
            {{-- Type display --}}
            @if($inviteTypeId)
                @php
                    $inviteType = $match->judge_requirements->firstWhere('judge_type_id', $inviteTypeId);
                @endphp
                @if($inviteType)
                    <div>
                        <label class="form-label">{{ __('crud.judge_type') }}</label>
                        <div class="form-input" style="background: var(--bg-input-disabled); cursor: default;">
                            {{ $inviteType->judge_type->{'title_' . $locale} ?? '—' }}
                        </div>
                    </div>
                @endif
            @endif

            {{-- Search --}}
            <div>
                <label class="form-label">{{ __('crud.search_referee') }}</label>
                <div class="relative">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="judgeSearch"
                        class="form-input pl-9"
                        placeholder="{{ __('crud.search_referee_placeholder') }}"
                    />
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2" style="color: var(--text-muted);"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                @if(strlen($judgeSearch) > 0 && strlen($judgeSearch) < 2)
                    <p class="text-xs mt-1" style="color: var(--text-muted);">{{ __('crud.type_to_search') }}</p>
                @endif
            </div>

            {{-- Results --}}
            @if($searchResults->count())
                <div class="max-h-64 overflow-y-auto rounded-lg" style="border: 1px solid var(--border-color);">
                    @foreach($searchResults as $referee)
                        <div class="flex items-center justify-between p-3 transition-colors"
                             style="border-bottom: 1px solid var(--border-color);"
                             onmouseover="this.style.backgroundColor='var(--bg-hover)'"
                             onmouseout="this.style.backgroundColor='transparent'">
                            <div>
                                <div class="text-sm font-semibold" style="color: var(--text-primary);">
                                    {{ $referee->last_name }} {{ $referee->first_name }}
                                    @if($referee->patronymic) {{ $referee->patronymic }} @endif
                                </div>
                                <div class="text-xs" style="color: var(--text-muted);">
                                    {{ $referee->email }} &middot; {{ $referee->phone }}
                                </div>
                            </div>
                            <button wire:click="inviteJudge({{ $referee->id }})" class="btn-primary text-xs py-1 px-3">
                                {{ __('crud.invite') }}
                            </button>
                        </div>
                    @endforeach
                </div>
            @elseif(strlen($judgeSearch) >= 2)
                <p class="text-sm text-center py-4" style="color: var(--text-muted);">{{ __('crud.no_results') }}</p>
            @endif

            {{-- Request comment --}}
            <div>
                <label class="form-label">{{ __('crud.request_comment') }}</label>
                <textarea
                    wire:model="requestComment"
                    class="form-input"
                    rows="2"
                    placeholder="{{ __('crud.request_comment_placeholder') }}"
                ></textarea>
            </div>
        </div>

        <x-slot name="footer">
            <button wire:click="closeInviteModal" class="btn-secondary">{{ __('crud.cancel') }}</button>
        </x-slot>
    </x-modal>

    {{-- ── Remove Judge Confirmation Modal ───────────────── --}}
    <x-modal wire:model="showRemoveModal" maxWidth="sm">
        <x-slot name="title">{{ __('crud.remove_judge_title') }}</x-slot>

        <p style="color: var(--text-secondary);">
            {{ __('crud.remove_judge_confirm', ['name' => $removingJudgeName]) }}
        </p>

        <x-slot name="footer">
            <button wire:click="$set('showRemoveModal', false)" class="btn-secondary">{{ __('crud.cancel') }}</button>
            <button wire:click="removeJudge" class="btn-danger">{{ __('crud.yes_delete') }}</button>
        </x-slot>
    </x-modal>

    {{-- ── Confirm Transition Modal ──────────────────────── --}}
    <x-modal wire:model="showConfirmTransition" maxWidth="sm">
        <x-slot name="title">{{ __('crud.confirm_action') }}</x-slot>

        <p style="color: var(--text-secondary);">
            @if($transitionTarget === 'assignment')
                {{ __('crud.move_to_assignment_confirm') }}
            @elseif($transitionTarget === 'review')
                {{ __('crud.submit_for_review_confirm') }}
            @endif
        </p>

        <x-slot name="footer">
            <button wire:click="cancelTransition" class="btn-secondary">{{ __('crud.cancel') }}</button>
            <button wire:click="executeTransition" class="btn-primary">{{ __('crud.yes') }}</button>
        </x-slot>
    </x-modal>
</div>
