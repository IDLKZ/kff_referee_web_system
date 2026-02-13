@php
    $locale = app()->getLocale();
    $userId = auth()->id();
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

    {{-- Judge Requirements Summary --}}
    @if($match->judge_requirements->count())
        <div class="mb-3">
            <h4 class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">
                {{ __('crud.judge_requirements_section') }}
            </h4>
            <div class="flex flex-wrap gap-2">
                @foreach($match->judge_requirements as $req)
                    <span class="badge badge-secondary">
                        {{ $req->judge_type->{'title_' . $locale} ?? '—' }}: {{ $req->qty }}
                        @if($req->is_required)
                            <span style="color: var(--color-danger);">*</span>
                        @endif
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    {{-- My Judges Assignment for this match --}}
    <div class="flex-1">
        <h4 class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">
            {{ __('ui.my_assignment') }}
        </h4>

        @if($match->match_judges->count())
            <div class="space-y-2">
                @foreach($match->match_judges->where('judge_id', $userId) as $judge)
                    <div class="flex items-center justify-between gap-2 text-sm" style="background: var(--bg-body); padding: 0.5rem 0.75rem; border-radius: var(--radius-md);">
                        {{-- Judge type and response --}}
                        <div class="flex items-center gap-2">
                            <span style="color: var(--text-primary); font-weight: 500;">
                                {{ $judge->judge_type->{'title_' . $locale} ?? '—' }}
                            </span>
                            @if($judge->judge_response == 1)
                                <span class="badge badge-success">{{ __('crud.accepted') }}</span>
                            @elseif($judge->judge_response == -1)
                                <span class="badge badge-danger">{{ __('crud.declined') }}</span>
                            @else
                                <span class="badge badge-warning">{{ __('ui.waiting_for_response') }}</span>
                            @endif
                        </div>

                        {{-- Final status if applicable --}}
                        @if($judge->final_status != 0)
                            <span class="badge {{ $judge->final_status == 1 ? 'badge-success' : 'badge-danger' }}">
                                {{ $judge->final_status == 1 ? __('crud.final_status_approved') : __('crud.final_status_rejected') }}
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm" style="color: var(--text-muted);">{{ __('ui.not_assigned_yet') }}</p>
        @endif
    </div>

    {{-- Footer: Details button --}}
    <div class="flex justify-end pt-3" style="border-top: 1px solid var(--border-color);">
        <a href="{{ route('referee.referee-request-detail', ['match' => $match->id]) }}"
           class="btn-secondary text-sm">
            {{ __('crud.view_details') }}
        </a>
    </div>
</div>
