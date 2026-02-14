@php $locale = app()->getLocale(); @endphp
@section('page-title', __('ui.referee_request_detail'))

<div>
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('referee.referee-request') }}" class="link flex items-center gap-2 font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            {{ __('crud.back_to_list') }}
        </a>
    </div>

    {{-- Match Header — Club vs Club --}}
    <div class="card p-6 mb-6">
        <div class="flex flex-col items-center text-center mb-6">
            <div class="flex items-center gap-4 mb-3">
                <div class="text-right">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-1"
                         style="background: var(--color-primary-light);">
                        <span class="text-sm font-bold" style="color: var(--color-primary);">
                            {{ mb_substr($match->ownerClub->{'short_name_' . $locale} ?? '?', 0, 3) }}
                        </span>
                    </div>
                    <p class="font-semibold text-sm" style="color: var(--text-primary);">
                        {{ $match->ownerClub->{'title_' . $locale} ?? '—' }}
                    </p>
                </div>
                <div class="px-4">
                    <span class="text-2xl font-bold tracking-wide" style="color: var(--text-muted);">vs</span>
                </div>
                <div class="text-left">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-1"
                         style="background: var(--color-info-light);">
                        <span class="text-sm font-bold" style="color: var(--color-info);">
                            {{ mb_substr($match->guestClub->{'short_name_' . $locale} ?? '?', 0, 3) }}
                        </span>
                    </div>
                    <p class="font-semibold text-sm" style="color: var(--text-primary);">
                        {{ $match->guestClub->{'title_' . $locale} ?? '—' }}
                    </p>
                </div>
            </div>

            {{-- Operation Badge --}}
            @if($match->operation)
                <span class="badge badge-warning mt-1">
                    {{ $match->operation->{'title_' . $locale} ?? '' }}
                </span>
            @endif
        </div>

        {{-- Info Grid with Icons --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            @if($match->start_at)
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <div>
                        <span style="color: var(--text-muted);">{{ __('crud.start_at') }}</span>
                        <span class="font-medium block" style="color: var(--text-primary);">
                            {{ $match->start_at->format('d.m.Y H:i') }}
                        </span>
                    </div>
                </div>
            @endif
            @if($match->city)
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <div>
                        <span style="color: var(--text-muted);">{{ __('crud.city') }}</span>
                        <span class="font-medium block" style="color: var(--text-primary);">
                            {{ $match->city->{'title_' . $locale} }}
                        </span>
                    </div>
                </div>
            @endif
            @if($match->tournament)
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                    <div>
                        <span style="color: var(--text-muted);">{{ __('crud.tournament') }}</span>
                        <span class="font-medium block" style="color: var(--text-primary);">
                            {{ $match->tournament->{'title_' . $locale} }}
                        </span>
                    </div>
                </div>
            @endif
            @if($match->stadium)
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <div>
                        <span style="color: var(--text-muted);">{{ __('crud.stadium') }}</span>
                        <span class="font-medium block" style="color: var(--text-primary);">
                            {{ $match->stadium->{'title_' . $locale} }}
                        </span>
                    </div>
                </div>
            @endif
            @if($match->season)
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <span style="color: var(--text-muted);">{{ __('crud.season') }}</span>
                        <span class="font-medium block" style="color: var(--text-primary);">
                            {{ $match->season->{'title_' . $locale} }}
                        </span>
                    </div>
                </div>
            @endif
            @if($match->round)
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                    </svg>
                    <div>
                        <span style="color: var(--text-muted);">{{ __('crud.round') }}</span>
                        <span class="font-medium block" style="color: var(--text-primary);">
                            {{ $match->round }}
                        </span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Judge Requirements — Horizontal Stat Cards --}}
    @if($match->judge_requirements->isNotEmpty())
        <div class="mb-6">
            <h2 class="text-sm font-semibold mb-3 uppercase tracking-wide" style="color: var(--text-muted);">
                {{ __('crud.judge_requirements_section') }}
            </h2>
            <div class="flex flex-wrap gap-3">
                @foreach($match->judge_requirements as $req)
                    <div class="card px-4 py-3 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center"
                             style="background: {{ $req->is_required ? 'var(--color-primary-light)' : 'var(--bg-hover)' }};">
                            <svg class="w-4 h-4" style="color: {{ $req->is_required ? 'var(--color-primary)' : 'var(--text-muted)' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs" style="color: var(--text-muted);">
                                {{ $req->judge_type->{'title_' . $locale} ?? '—' }}
                                @if($req->is_required)
                                    <span style="color: var(--color-danger);">*</span>
                                @endif
                            </p>
                            <p class="text-lg font-bold" style="color: var(--text-primary);">{{ $req->qty }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- My Assignment --}}
    <div class="mb-6">
        <h2 class="text-sm font-semibold mb-4 uppercase tracking-wide" style="color: var(--text-muted);">
            {{ __('ui.my_assignment') }}
        </h2>

        @if($myJudgeAssignments->isNotEmpty())
            <div class="space-y-4">
                @foreach($myJudgeAssignments as $assignment)
                    @php
                        if ($assignment->judge_response == 1) {
                            $borderColor = 'var(--color-success)';
                        } elseif ($assignment->judge_response == -1) {
                            $borderColor = 'var(--color-danger)';
                        } else {
                            $borderColor = 'var(--color-warning)';
                        }
                    @endphp

                    <div class="card overflow-hidden" style="border-left: 4px solid {{ $borderColor }};">
                        {{-- Assignment Header --}}
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-5"
                             style="border-bottom: 1px solid var(--border-color);">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-full flex items-center justify-center text-sm font-bold shrink-0"
                                     style="background: var(--color-primary-light); color: var(--color-primary);">
                                    {{ mb_substr(auth()->user()->last_name ?? '', 0, 1) }}{{ mb_substr(auth()->user()->first_name ?? '', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold" style="color: var(--text-primary);">
                                        {{ $assignment->judge_type->{'title_' . $locale} ?? '—' }}
                                    </p>
                                    <p class="text-xs" style="color: var(--text-muted);">
                                        {{ auth()->user()->last_name }} {{ auth()->user()->first_name }}
                                    </p>
                                </div>
                            </div>

                            {{-- Status Badges --}}
                            <div class="flex items-center gap-2 shrink-0">
                                @if($assignment->judge_response == 1)
                                    <span class="badge badge-success">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        {{ __('crud.accepted') }}
                                    </span>
                                @elseif($assignment->judge_response == -1)
                                    <span class="badge badge-danger">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        {{ __('crud.declined') }}
                                    </span>
                                @else
                                    <span class="badge badge-warning">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ __('ui.waiting_for_response') }}
                                    </span>
                                @endif

                                @if($assignment->judge_response != 0 && $assignment->final_status != 0)
                                    @if($assignment->final_status == 1)
                                        <span class="badge badge-success">{{ __('crud.final_status_approved') }}</span>
                                    @elseif($assignment->final_status == -1)
                                        <span class="badge badge-danger">{{ __('crud.final_status_rejected') }}</span>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="p-5 space-y-4">
                            {{-- Comments --}}
                            @if($assignment->request_comment || $assignment->judge_comment)
                                <div class="space-y-3">
                                    @if($assignment->request_comment)
                                        <div class="flex items-start gap-3 p-3 rounded-lg" style="background: var(--color-info-light);">
                                            <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                            </svg>
                                            <div class="text-sm min-w-0">
                                                <p class="font-medium text-xs mb-0.5" style="color: var(--color-info);">{{ __('crud.request_comment') }}</p>
                                                <p style="color: var(--text-primary);">{{ $assignment->request_comment }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    @if($assignment->judge_comment)
                                        <div class="flex items-start gap-3 p-3 rounded-lg" style="background: var(--bg-hover);">
                                            <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                            </svg>
                                            <div class="text-sm min-w-0">
                                                <p class="font-medium text-xs mb-0.5" style="color: var(--text-muted);">{{ __('crud.my_comment') }}</p>
                                                <p style="color: var(--text-primary);">{{ $assignment->judge_comment }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            {{-- Action buttons --}}
                            @if($assignment->judge_response == 0)
                                <div class="flex gap-3 pt-2" style="border-top: 1px solid var(--border-color);">
                                    <button wire:click="openResponseModal('accept')"
                                            class="btn-primary text-sm flex-1 justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('crud.accept_invitation') }}
                                    </button>
                                    <button wire:click="openResponseModal('decline')"
                                            class="btn-danger text-sm flex-1"
                                            style="display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        {{ __('crud.decline_invitation') }}
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="card p-12 text-center">
                <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4"
                     style="background: var(--bg-hover);">
                    <svg class="w-8 h-8" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <p class="font-medium" style="color: var(--text-secondary);">{{ __('ui.no_assignment_yet') }}</p>
            </div>
        @endif
    </div>

    {{-- Response Modal --}}
    @if($showResponseModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);">
            <div class="w-full max-w-md rounded-xl shadow-2xl overflow-hidden"
                 style="background: var(--bg-card);"
                 @click.outside="$wire.closeResponseModal()"
                 @keydown.escape.window="$wire.closeResponseModal()">
                {{-- Modal Header --}}
                <div class="p-5" style="border-bottom: 1px solid var(--border-color);">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                             style="background: {{ $responseAction === 'accept' ? 'var(--color-success-light)' : 'var(--color-danger-light)' }};">
                            @if($responseAction === 'accept')
                                <svg class="w-5 h-5" style="color: var(--color-success);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5" style="color: var(--color-danger);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            @endif
                        </div>
                        <h3 class="text-lg font-semibold" style="color: var(--text-primary);">
                            @if($responseAction === 'accept')
                                {{ __('crud.confirm_accept_invitation') }}
                            @else
                                {{ __('crud.confirm_decline_invitation') }}
                            @endif
                        </h3>
                    </div>
                </div>

                {{-- Modal Body --}}
                <div class="p-5">
                    <label class="form-label">{{ __('crud.judge_comment') }}</label>
                    <textarea
                        wire:model="responseComment"
                        rows="3"
                        class="form-input w-full"
                        placeholder="{{ __('crud.response_comment_placeholder') }}"
                    ></textarea>
                </div>

                {{-- Modal Footer --}}
                <div class="flex gap-3 justify-end p-5" style="background: var(--bg-hover); border-top: 1px solid var(--border-color);">
                    <button wire:click="closeResponseModal" class="btn-secondary text-sm">
                        {{ __('crud.cancel') }}
                    </button>
                    @if($responseAction === 'accept')
                        <button wire:click="submitResponse" class="btn-primary text-sm" wire:loading.attr="disabled">
                            <span wire:loading wire:target="submitResponse">
                                <svg class="w-4 h-4 animate-spin inline mr-1" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            </span>
                            <svg class="w-4 h-4" wire:loading.remove wire:target="submitResponse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ __('crud.accept_invitation') }}
                        </button>
                    @else
                        <button wire:click="submitResponse" class="btn-danger text-sm" wire:loading.attr="disabled"
                                style="display: inline-flex; align-items: center; gap: 0.5rem;">
                            <span wire:loading wire:target="submitResponse">
                                <svg class="w-4 h-4 animate-spin inline mr-1" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            </span>
                            <svg class="w-4 h-4" wire:loading.remove wire:target="submitResponse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            {{ __('crud.decline_invitation') }}
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
