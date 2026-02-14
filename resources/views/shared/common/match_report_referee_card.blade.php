@php
    $locale = app()->getLocale();
    $userId = auth()->id();
    $match = $report->match;
    $documentsCount = \App\Models\MatchReportDocument::where('match_report_id', $report->id)
        ->where('judge_id', $userId)
        ->count();
@endphp

<div class="card p-5 flex flex-col h-full">
    {{-- Header: Tournament, Season, Round, Date --}}
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
        @if($match->start_at)
            <span class="badge badge-secondary">{{ $match->start_at->format('d.m.Y H:i') }}</span>
        @endif
    </div>

    {{-- Match title: ownerClub vs guestClub --}}
    <h3 class="text-base font-semibold mb-2" style="color: var(--text-primary);">
        {{ $match->ownerClub->{'short_name_' . $locale} ?? '—' }}
        <span style="color: var(--text-muted);">{{ __('crud.vs') }}</span>
        {{ $match->guestClub->{'short_name_' . $locale} ?? '—' }}
    </h3>

    {{-- Location: City + Stadium --}}
    <div class="flex flex-wrap items-center gap-3 mb-3 text-sm" style="color: var(--text-secondary);">
        @if($match->city)
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                {{ $match->city->{'title_' . $locale} }}
            </span>
        @endif
        @if($match->stadium)
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h-1m-3-4h6m-7-4H3m2 0h5"/></svg>
                {{ $match->stadium->{'title_' . $locale} }}
            </span>
        @endif
    </div>

    {{-- Current operation badge --}}
    @if($match->operation)
        <div class="mb-3">
            <span class="badge badge-warning">{{ $match->operation->{'title_' . $locale} }}</span>
        </div>
    @endif

    {{-- Report Status --}}
    <div class="mb-3">
        <h4 class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">
            {{ __('ui.report_status') }}
        </h4>
        <div class="flex flex-wrap gap-2">
            @if($report->is_finished)
                <span class="badge badge-success">{{ __('crud.report_submitted') }}</span>
            @else
                <span class="badge badge-warning">{{ __('crud.report_not_submitted') }}</span>
            @endif

            @if($report->is_accepted === true)
                <span class="badge badge-success">{{ __('crud.report_accepted') }}</span>
            @elseif($report->is_accepted === false)
                <span class="badge badge-danger">{{ __('crud.report_rejected') }}</span>
            @else
                <span class="badge badge-secondary">{{ __('crud.report_pending_review') }}</span>
            @endif
        </div>
    </div>

    {{-- Documents Count --}}
    <div class="flex-1">
        <h4 class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">
            {{ __('crud.documents_count') }}
        </h4>
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background: var(--color-info-light);">
                <svg class="w-4 h-4" style="color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <span class="text-lg font-bold" style="color: var(--text-primary);">{{ $documentsCount }}</span>
        </div>

        @if($report->final_comment)
            <p class="text-xs mt-2" style="color: var(--text-muted);">
                {{ __('crud.final_comment') }}: {{ $report->final_comment }}
            </p>
        @endif
    </div>

    {{-- Footer: Details button --}}
    <div class="flex justify-end pt-3" style="border-top: 1px solid var(--border-color);">
        <a href="{{ route('referee.referee-protocol-detail', ['matchId' => $match->id]) }}"
           class="btn-secondary text-sm">
            {{ __('crud.view_details') }}
        </a>
    </div>
</div>
