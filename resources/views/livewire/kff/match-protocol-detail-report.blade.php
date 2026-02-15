@section('page-title', __('ui.protocol_report_detail'))

@php
    $locale = app()->getLocale();
@endphp

<div>
    {{-- Match Details Header --}}
    <div class="card p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold" style="color: var(--text-primary);">
                {{ __('ui.match_details') }}
            </h2>
            <a href="{{ route('kff.protocol-review') }}" class="btn-secondary text-sm">
                {{ __('crud.back_to_list') }}
            </a>
        </div>

        {{-- Tournament, Season, Date --}}
        <div class="flex flex-wrap items-center gap-2 mb-4">
            @if($match->tournament)
                <span class="badge badge-info">{{ $match->tournament->{'title_' . $locale} }}</span>
            @endif
            @if($match->season)
                <span class="badge badge-purple">{{ $match->season->{'title_' . $locale} }}</span>
            @endif
            @if($match->round)
                <span class="badge badge-secondary">{{ __('crud.round') }}: {{ $match->round }}</span>
            @endif
            @if($match->start_at)
                <span class="badge badge-secondary">{{ $match->start_at->format('d.m.Y H:i') }}</span>
            @endif
            @if($currentOperation)
                <span class="badge badge-warning">{{ $currentOperation->{'title_' . $locale} }}</span>
            @endif
        </div>

        {{-- Match Title --}}
        <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">
            {{ $match->ownerClub->{'short_name_' . $locale} ?? '—' }}
            <span style="color: var(--text-muted);">{{ __('crud.vs') }}</span>
            {{ $match->guestClub->{'short_name_' . $locale} ?? '—' }}
        </h3>

        {{-- Location --}}
        <div class="flex flex-wrap items-center gap-4 text-sm mb-4" style="color: var(--text-secondary);">
            @if($match->city)
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $match->city->{'title_' . $locale} }}
                </span>
            @endif
            @if($match->stadium)
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    {{ $match->stadium->{'title_' . $locale} }}
                </span>
            @endif
        </div>

        {{-- Judge Requirements --}}
        @if($match->judge_requirements->count())
            <div class="mb-4">
                <h4 class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                    {{ __('crud.judge_requirements_section') }}
                </h4>
                <div class="flex flex-wrap gap-2">
                    @foreach($match->judge_requirements as $req)
                        <span class="badge badge-secondary">
                            {{ $req->judge_type->{'title_' . $locale} ?? '—' }}: {{ $req->qty }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Match Judges --}}
        @php
            $assignedJudges = $match->match_judges
                ->where('judge_response', 1)
                ->where('final_status', 1)
                ->where('is_actual', true);
        @endphp
        @if($assignedJudges->count())
            <div>
                <h4 class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                    {{ __('crud.assigned_judges') }}
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($assignedJudges as $mj)
                        <div class="rounded-lg p-3" style="background: var(--bg-body); border: 1px solid var(--border-color);">
                            <div class="font-semibold text-sm mb-1" style="color: var(--text-primary);">
                                {{ $mj->user->last_name ?? '' }} {{ $mj->user->first_name ?? '' }}
                            </div>
                            <span class="text-xs" style="color: var(--text-muted);">
                                {{ $mj->judge_type->{'title_' . $locale} ?? '—' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Review Section --}}
    <div class="card p-6">
        <h2 class="text-xl font-bold mb-4" style="color: var(--text-primary);">
            {{ __('ui.document_review') }}
        </h2>

        @if(!$isReviewAvailable)
            <div class="p-4 rounded-lg mb-6" style="background: var(--color-warning-light); border-left: 4px solid var(--color-warning);">
                <p class="text-sm" style="color: var(--color-warning);">
                    {{ __('ui.review_not_available_hint') }}
                </p>
            </div>
        @endif

        {{-- Report Info --}}
        <div class="mb-6 pb-4" style="border-bottom: 1px solid var(--border-color);">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <span class="text-sm" style="color: var(--text-muted);">{{ __('crud.reported_by') }}:</span>
                    <div class="font-medium" style="color: var(--text-primary);">
                        @if($report->judge)
                            {{ $report->judge->last_name ?? '' }} {{ $report->judge->first_name ?? '' }}
                        @else
                            —
                        @endif
                    </div>
                </div>
                <div>
                    <span class="text-sm" style="color: var(--text-muted);">{{ __('crud.checked_by') }}:</span>
                    <div class="font-medium" style="color: var(--text-primary);">
                        @if($report->checked_by)
                            {{ $report->checked_by->last_name ?? '' }} {{ $report->checked_by->first_name ?? '' }}
                        @else
                            —
                        @endif
                    </div>
                </div>
                <div>
                    <span class="text-sm" style="color: var(--text-muted);">{{ __('crud.status') }}:</span>
                    <div class="font-medium" style="color: var(--text-primary);">
                        @if($report->is_accepted === true)
                            <span class="badge badge-success">{{ __('crud.report_accepted') }}</span>
                        @elseif($report->is_accepted === false)
                            <span class="badge badge-danger">{{ __('crud.report_rejected') }}</span>
                        @else
                            <span class="badge badge-warning">{{ __('crud.report_pending_review') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            @if($report->final_comment)
                <div class="mt-4">
                    <span class="text-sm font-medium" style="color: var(--text-secondary);">{{ __('crud.final_comment') }}:</span>
                    <div class="mt-1 p-3 rounded" style="background: var(--bg-body); color: var(--text-primary);">
                        {{ $report->final_comment }}
                    </div>
                </div>
            @endif
        </div>

        {{-- Documents Review --}}
        <div class="space-y-4">
            <h3 class="text-lg font-semibold mb-3" style="color: var(--text-primary);">
                {{ __('crud.report_documents') }}
            </h3>

            @if($report->match_report_documents->count())
                @foreach($report->match_report_documents as $doc)
                    <div class="rounded-xl overflow-hidden" style="background: var(--bg-card); border: 1px solid var(--border-color);">
                        {{-- Document Header --}}
                        <div class="p-4 pb-3" style="background: var(--bg-body); border-bottom: 1px solid var(--border-color);">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <span class="font-semibold" style="color: var(--text-primary);">
                                            @if($doc->match_protocol_requirement)
                                                {{ $doc->match_protocol_requirement->{'title_' . $locale} ?? __('crud.document') }}
                                            @else
                                                {{ __('crud.document') }}
                                            @endif
                                        </span>
                                    </div>

                                    {{-- Judge Info & File Link --}}
                                    <div class="flex items-center gap-4 text-sm">
                                        @if($doc->user)
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background: var(--color-primary-light); color: var(--color-primary);">
                                                    <span class="text-xs font-semibold">{{ mb_substr($doc->user->first_name ?? '', 0, 1) }}{{ mb_substr($doc->user->last_name ?? '', 0, 1) }}</span>
                                                </div>
                                                <span style="color: var(--text-secondary);">
                                                    {{ $doc->user->last_name ?? '' }} {{ $doc->user->first_name ?? '' }}
                                                </span>
                                            </div>
                                        @endif

                                        @if($doc->file)
                                            <a href="{{ route('files.download', $doc->file_id) }}"
                                               target="_blank"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded text-sm"
                                               style="background: var(--color-info-light); color: var(--color-info); transition: background var(--transition-fast);">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                </svg>
                                                {{ __('crud.download') }}
                                            </a>
                                        @endif
                                    </div>

                                    {{-- Upload Comment --}}
                                    @if($doc->comment)
                                        <div class="mt-2 text-xs" style="color: var(--text-muted);">
                                            <span class="font-medium">{{ __('crud.upload_comment') }}:</span>
                                            <span>{{ $doc->comment }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Review Section --}}
                        <div class="p-4">
                            @if($isReviewAvailable && $doc->is_accepted === null)
                                {{-- Review Form --}}
                                <div class="space-y-4">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        {{-- Accept Button --}}
                                        <button wire:click="acceptDocument({{ $doc->id }})"
                                                @class([
                                                    'flex-1 px-4 py-3 rounded-lg text-sm font-medium flex items-center justify-center gap-2 transition-all duration-200',
                                                    '!bg-[var(--color-success)] !text-white !border-[var(--color-success)]' => ($reviewData[$doc->id]['is_accepted'] ?? null) === true,
                                                    'bg-[var(--color-success-light)] text-[var(--color-success)] border-2 border-[var(--color-success-light)] hover:!bg-[var(--color-success)] hover:!text-white hover:!border-[var(--color-success)]' => ($reviewData[$doc->id]['is_accepted'] ?? null) !== true,
                                                ])>
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            {{ __('crud.accept') }}
                                        </button>

                                        {{-- Reject Button --}}
                                        <button wire:click="rejectDocument({{ $doc->id }})"
                                                @class([
                                                    'flex-1 px-4 py-3 rounded-lg text-sm font-medium flex items-center justify-center gap-2 transition-all duration-200',
                                                    '!bg-[var(--color-danger)] !text-white !border-[var(--color-danger)]' => ($reviewData[$doc->id]['is_accepted'] ?? null) === false,
                                                    'bg-[var(--color-danger-light)] text-[var(--color-danger)] border-2 border-[var(--color-danger-light)] hover:!bg-[var(--color-danger)] hover:!text-white hover:!border-[var(--color-danger)]' => ($reviewData[$doc->id]['is_accepted'] ?? null) !== false,
                                                ])>
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            {{ __('crud.reject') }}
                                        </button>
                                    </div>

                                    {{-- Final Comment --}}
                                    <div>
                                        <label class="form-label text-sm font-medium">{{ __('crud.final_comment') }}</label>
                                        <textarea wire:model.live="reviewData.{{ $doc->id }}.final_comment"
                                                  class="form-input"
                                                  rows="3"
                                                  placeholder="{{ __('crud.final_comment_placeholder') }}"></textarea>
                                    </div>

                                    {{-- Save Button --}}
                                    <button wire:click="updateDocumentReview({{ $doc->id }})"
                                            class="w-full px-6 py-3 rounded-lg text-sm font-semibold flex items-center justify-center gap-2 transition-all duration-200"
                                            style="background: var(--color-primary);"
                                            onmouseenter="this.style.background='var(--color-primary-hover)'"
                                            onmouseleave="this.style.background='var(--color-primary)'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('crud.save_decision') }}
                                    </button>
                                </div>
                            @elseif($doc->is_accepted !== null)
                                {{-- Reviewed Status --}}
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        {{-- Status Badge --}}
                                        @if($doc->is_accepted === true)
                                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg" style="background: var(--color-success-light); color: var(--color-success);">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span class="font-semibold">{{ __('crud.report_accepted') }}</span>
                                            </div>
                                        @else
                                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg" style="background: var(--color-danger-light); color: var(--color-danger);">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l-2-2m4 4V4"/>
                                                </svg>
                                                <span class="font-semibold">{{ __('crud.report_rejected') }}</span>
                                            </div>
                                        @endif

                                        @if($doc->checked_by)
                                            <div class="text-sm flex items-center gap-1.5 px-3 py-1.5 rounded" style="background: var(--bg-body); color: var(--text-muted);">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h4v4a7 7 0 007 7h-4z"/>
                                                </svg>
                                                <span>{{ $doc->checked_by->last_name ?? '' }} {{ $doc->checked_by->first_name ?? '' }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Final Comment --}}
                                    @if($doc->final_comment)
                                        <div class="text-sm p-3 rounded-lg" style="background: var(--bg-input); color: var(--text-secondary);">
                                            <div class="font-medium mb-1" style="color: var(--text-primary);">{{ __('crud.final_comment') }}:</div>
                                            <div>{{ $doc->final_comment }}</div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="p-12 text-center rounded-xl" style="background: var(--bg-body); border: 2px dashed var(--border-color);">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center" style="background: var(--color-warning-light); color: var(--color-warning);">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <p style="color: var(--text-muted);">{{ __('crud.no_documents') }}</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Complete Actions --}}
        @if($isReviewAvailable && $canComplete)
            <div class="mt-6 pt-6" style="border-top: 1px solid var(--border-color);">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">
                    {{ __('ui.complete_review') }}
                </h3>
                <div class="flex flex-wrap gap-3">
                    <button wire:click="$set('showCompleteModal', true)" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ __('crud.complete_successfully') }}
                    </button>
                    <button wire:click="$set('showReprocessModal', true)" class="btn-secondary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        {{ __('crud.send_reprocessing') }}
                    </button>
                </div>
            </div>
        @endif
    </div>

    {{-- Complete Modal --}}
    <x-modal wire:model="showCompleteModal" maxWidth="md">
        <x-slot name="title">{{ __('crud.confirm_complete') }}</x-slot>

        <div class="space-y-4">
            <p class="text-sm" style="color: var(--text-secondary);">
                {{ __('crud.confirm_complete_hint') }}
            </p>

            <div>
                <label class="form-label">{{ __('crud.final_comment') }}</label>
                <textarea wire:model="finalComment"
                          class="form-input"
                          rows="4"
                          placeholder="{{ __('crud.final_comment_placeholder') }}"></textarea>
            </div>
        </div>

        <x-slot name="footer">
            <button wire:click="$set('showCompleteModal', false)" class="btn-secondary">
                {{ __('crud.cancel') }}
            </button>
            <button wire:click="completeReport" class="btn-primary">
                {{ __('crud.complete') }}
            </button>
        </x-slot>
    </x-modal>

    {{-- Reprocess Modal --}}
    <x-modal wire:model="showReprocessModal" maxWidth="md">
        <x-slot name="title">{{ __('crud.confirm_reprocess') }}</x-slot>

        <div class="space-y-4">
            <p class="text-sm" style="color: var(--text-secondary);">
                {{ __('crud.confirm_reprocess_hint') }}
            </p>

            <div>
                <label class="form-label">{{ __('crud.final_comment') }}</label>
                <textarea wire:model="finalComment"
                          class="form-input"
                          rows="4"
                          placeholder="{{ __('crud.final_comment_placeholder') }}"></textarea>
            </div>
        </div>

        <x-slot name="footer">
            <button wire:click="$set('showReprocessModal', false)" class="btn-secondary">
                {{ __('crud.cancel') }}
            </button>
            <button wire:click="reprocessReport" class="btn-danger">
                {{ __('crud.send_reprocessing') }}
            </button>
        </x-slot>
    </x-modal>
</div>
