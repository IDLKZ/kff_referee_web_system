@php
    $locale = app()->getLocale();
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                {{ $match->stadium->{'title_' . $locale} }}
            </span>
        @endif
    </div>

    {{-- Current operation badge --}}
    @if($match->operation)
        <div class="mb-4">
            <span class="badge badge-warning">{{ $match->operation->{'title_' . $locale} }}</span>
        </div>
    @endif

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
    <div class="mb-3 flex-1">
        <h4 class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">
            {{ __('crud.assigned_judges') }}
        </h4>

        @if($match->match_judges->count())
            <div class="grid grid-cols-1 gap-2">
                @foreach($match->match_judges as $mj)
                    <div class="rounded-lg p-3" style="background: var(--bg-body); border: 1px solid var(--border-color);">
                        {{-- Judge type --}}
                        <div class="text-xs font-medium mb-1" style="color: var(--text-muted);">
                            {{ $mj->judge_type->{'title_' . $locale} ?? '—' }}
                        </div>

                        {{-- Judge name --}}
                        <div class="font-semibold text-sm mb-2" style="color: var(--text-primary);">
                            {{ $mj->user->last_name ?? '' }} {{ $mj->user->first_name ?? '' }}
                        </div>

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

                        {{-- Final status --}}
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs" style="color: var(--text-muted);">{{ __('crud.final_status') }}:</span>
                            @switch($mj->final_status)
                                @case(1)
                                    <span class="badge badge-success">{{ __('crud.final_status_approved') }}</span>
                                    @break
                                @case(-1)
                                    <span class="badge badge-danger">{{ __('crud.final_status_rejected') }}</span>
                                    @break
                                @default
                                    <span class="badge badge-warning">{{ __('crud.final_status_pending') }}</span>
                            @endswitch
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm" style="color: var(--text-muted);">{{ __('crud.no_judges_assigned') }}</p>
        @endif
    </div>

    {{-- Footer: Details button --}}
    <div class="flex justify-end pt-3" style="border-top: 1px solid var(--border-color);">
        <a href="{{ route('kff.head-referee-approve-detail', ['match' => $match->id]) }}"
           class="btn-secondary text-sm">
            {{ __('crud.view_details') }}
        </a>
    </div>
</div>
