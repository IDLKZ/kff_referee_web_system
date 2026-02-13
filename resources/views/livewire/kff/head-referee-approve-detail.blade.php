@php $locale = app()->getLocale(); @endphp
@section('page-title', __('ui.head_referee_approve_detail'))

<div>
    {{-- ── Back ───────────────────────────────────────────── --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('kff.head-referee-approval') }}" class="flex items-center gap-1 text-sm link">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            {{ __('crud.back_to_list') }}
        </a>
    </div>

    {{-- ── Match Info Card ────────────────────────────────── --}}
    <div class="card p-5 mb-6">
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

        <h2 class="text-xl font-bold mb-3" style="color: var(--text-primary);">
            {{ $match->ownerClub->{'short_name_' . $locale} ?? '—' }}
            <span class="font-normal" style="color: var(--text-muted);">{{ __('crud.vs') }}</span>
            {{ $match->guestClub->{'short_name_' . $locale} ?? '—' }}
        </h2>

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

    {{-- ── Judge Sections by Type ─────────────────────────── --}}
    @if($operationValue === \App\Constants\OperationConstants::REFEREE_TEAM_APPROVAL)
        <div class="space-y-4 mb-6">
            @foreach($slotInfo as $typeId => $info)
                @php
                    $req = $info['requirement'];
                    // Only show judges who accepted (judge_response == 1)
                    $judgesForType = $match->match_judges->where('type_id', $typeId)->filter(fn ($mj) => $mj->judge_response == 1);
                    $pct = $req->qty > 0 ? min(100, round($info['approved'] / $req->qty * 100)) : 0;
                    $isMet = $req->is_required ? ($info['approved'] >= $req->qty) : ($info['rejected'] == 0);
                    $barColor = $isMet ? 'var(--color-success)' : 'var(--color-warning)';
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
                                <span style="color: {{ $isMet ? 'var(--color-success)' : 'var(--color-warning)' }};">
                                    {{ __('crud.final_status_approved') }}: {{ $info['approved'] }}/{{ $req->qty }}
                                </span>
                                @if($req->is_required)
                                    <span class="badge badge-danger" style="font-size: 0.6875rem;">{{ __('crud.is_required') }}</span>
                                @else
                                    <span class="badge badge-secondary" style="font-size: 0.6875rem;">{{ __('crud.optional') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Progress bar --}}
                    <div class="w-full rounded-full h-2 mb-4" style="background: var(--bg-body);">
                        <div class="h-2 rounded-full transition-all" style="width: {{ $pct }}%; background: {{ $barColor }};"></div>
                    </div>

                    {{-- Judge cards --}}
                    @if($judgesForType->count())
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($judgesForType as $mj)
                                <div class="rounded-lg p-3" style="background: var(--bg-body); border: 1px solid var(--border-color);">
                                    {{-- Name + email --}}
                                    <div class="font-semibold text-sm" style="color: var(--text-primary);">
                                        {{ $mj->user->last_name ?? '' }} {{ $mj->user->first_name ?? '' }}
                                    </div>
                                    <div class="text-xs mb-1" style="color: var(--text-muted);">
                                        {{ $mj->user->username ?? '' }}
                                    </div>
                                    <div class="text-xs mb-1" style="color: var(--text-muted);">
                                        {{ $mj->user->email ?? '' }}
                                        @if($mj->user->phone)
                                            &middot; {{ $mj->user->phone }}
                                        @endif
                                    </div>

                                    {{-- Judge comment --}}
                                    @if($mj->judge_comment)
                                        <div class="text-xs mb-2" style="color: var(--text-secondary);">
                                            <span class="font-medium">{{ __('crud.judge_comment') }}:</span>
                                            {{ $mj->judge_comment }}
                                        </div>
                                    @endif

                                    {{-- Final status --}}
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        <span class="text-xs" style="color: var(--text-muted);">{{ __('crud.final_status') }}:</span>
                                        @if($mj->final_status == 1)
                                            <span class="badge badge-success">{{ __('crud.final_status_approved') }}</span>
                                        @elseif($mj->final_status == -1)
                                            <span class="badge badge-danger">{{ __('crud.final_status_rejected') }}</span>
                                        @else
                                            <span class="badge badge-warning">{{ __('crud.final_status_pending') }}</span>
                                        @endif
                                    </div>

                                    {{-- Final comment --}}
                                    @if($mj->final_comment)
                                        <div class="text-xs mb-2" style="color: var(--text-secondary);">
                                            <span class="font-medium">{{ __('crud.final_comment') }}:</span>
                                            {{ $mj->final_comment }}
                                        </div>
                                    @endif

                                    {{-- Action buttons — always available while at referee_team_approval --}}
                                    <div class="flex items-center gap-2 mt-3 pt-2" style="border-top: 1px solid var(--border-color);">
                                        <button
                                            wire:click="openJudgeModal({{ $mj->id }}, 'approve')"
                                            class="{{ $mj->final_status == 1 ? 'btn-secondary' : 'btn-primary' }} text-xs py-1 px-3"
                                        >
                                            {{ __('crud.approve_judge') }}
                                        </button>
                                        <button
                                            wire:click="openJudgeModal({{ $mj->id }}, 'reject')"
                                            class="{{ $mj->final_status == -1 ? 'btn-secondary' : 'btn-danger' }} text-xs py-1 px-3"
                                        >
                                            {{ __('crud.reject_judge') }}
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm" style="color: var(--text-muted);">{{ __('crud.no_judges_assigned') }}</p>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- ── Brigade Actions ────────────────────────────── --}}
        @if($brigadeState['showButtons'])
            <div class="card p-5 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h4 class="font-semibold" style="color: var(--text-primary);">{{ __('ui.head_referee_approve_detail') }}</h4>
                        <p class="text-sm mt-1" style="color: var(--text-secondary);">
                            @if($brigadeState['hasRequiredRejected'])
                                {{ __('crud.brigade_not_ready_error') }}
                            @else
                                {{ __('crud.waiting_for_head_approval_hint') }}
                            @endif
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        @if($brigadeState['canApprove'])
                            <button wire:click="openBrigadeModal('approve')" class="btn-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ __('crud.approve_brigade') }}
                            </button>
                        @endif
                        @if($brigadeState['canReassign'])
                            <button wire:click="openBrigadeModal('reject')" class="btn-danger">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ __('crud.reject_brigade') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    @else
        {{-- Not at approval stage — read-only --}}
        <div class="card p-5 mb-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background: var(--color-info-light, var(--bg-body));">
                    <svg class="w-5 h-5" style="color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-semibold" style="color: var(--text-primary);">{{ $match->operation->{'title_' . $locale} ?? '—' }}</h3>
            </div>

            @foreach($slotInfo as $typeId => $info)
                @php $judgesForType = $match->match_judges->where('type_id', $typeId)->filter(fn ($mj) => $mj->judge_response == 1); @endphp
                <div class="mt-4">
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
                                    @if($mj->final_status == 1)
                                        <span class="badge badge-success">{{ __('crud.final_status_approved') }}</span>
                                    @elseif($mj->final_status == -1)
                                        <span class="badge badge-danger">{{ __('crud.final_status_rejected') }}</span>
                                    @else
                                        <span class="badge badge-warning">{{ __('crud.final_status_pending') }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- ── Judge Approve/Reject Modal ──────────────────────── --}}
    <x-modal wire:model="showJudgeModal" maxWidth="md">
        <x-slot name="title">
            @if($actionType === 'approve')
                {{ __('crud.approve_judge') }}
            @else
                {{ __('crud.reject_judge') }}
            @endif
        </x-slot>

        <div class="space-y-4">
            <p style="color: var(--text-secondary);">
                @if($actionType === 'approve')
                    {{ __('crud.approve_judge_confirm') }}
                @else
                    {{ __('crud.reject_judge_confirm') }}
                @endif
            </p>

            @if($actionJudgeName)
                <div class="font-semibold" style="color: var(--text-primary);">
                    {{ $actionJudgeName }}
                </div>
            @endif

            <div>
                <label class="form-label">{{ __('crud.final_comment') }}</label>
                <textarea
                    wire:model="finalComment"
                    class="form-input"
                    rows="3"
                    placeholder="{{ __('crud.final_comment_placeholder') }}"
                ></textarea>
            </div>
        </div>

        <x-slot name="footer">
            <button wire:click="closeJudgeModal" class="btn-secondary">{{ __('crud.cancel') }}</button>
            @if($actionType === 'approve')
                <button wire:click="executeJudgeAction" class="btn-primary">{{ __('crud.approve_judge') }}</button>
            @else
                <button wire:click="executeJudgeAction" class="btn-danger">{{ __('crud.reject_judge') }}</button>
            @endif
        </x-slot>
    </x-modal>

    {{-- ── Brigade Approve/Reject Modal ────────────────────── --}}
    <x-modal wire:model="showBrigadeModal" maxWidth="md">
        <x-slot name="title">
            @if($brigadeActionType === 'approve')
                {{ __('crud.approve_brigade') }}
            @else
                {{ __('crud.reject_brigade') }}
            @endif
        </x-slot>

        <div class="space-y-4">
            <p style="color: var(--text-secondary);">
                @if($brigadeActionType === 'approve')
                    {{ __('crud.approve_brigade_confirm') }}
                @else
                    {{ __('crud.reject_brigade_confirm') }}
                @endif
            </p>

            <div>
                <label class="form-label">{{ __('crud.final_comment') }}</label>
                <textarea
                    wire:model="brigadeComment"
                    class="form-input"
                    rows="3"
                    placeholder="{{ __('crud.final_comment_placeholder') }}"
                ></textarea>
            </div>
        </div>

        <x-slot name="footer">
            <button wire:click="closeBrigadeModal" class="btn-secondary">{{ __('crud.cancel') }}</button>
            @if($brigadeActionType === 'approve')
                <button wire:click="executeBrigadeAction" class="btn-primary">{{ __('crud.approve_brigade') }}</button>
            @else
                <button wire:click="executeBrigadeAction" class="btn-danger">{{ __('crud.reject_brigade') }}</button>
            @endif
        </x-slot>
    </x-modal>
</div>
