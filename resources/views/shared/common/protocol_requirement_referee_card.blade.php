@php
    $locale = app()->getLocale();
    $userId = auth()->id();
    $match = $requirement->match;
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

    {{-- Protocol Requirements for this judge's type --}}
    <div class="flex-1">
        <h4 class="text-sm font-semibold mb-2" style="color: var(--text-secondary);">
            {{ __('ui.protocol_requirements') }}
        </h4>

        <div class="space-y-2">
            <div class="flex items-center justify-between gap-2 text-sm" style="background: var(--bg-body); padding: 0.5rem 0.75rem; border-radius: var(--radius-md);">
                <div class="flex items-center gap-2">
                    <span style="color: var(--text-primary); font-weight: 500;">
                        {{ $requirement->{'title_' . $locale} ?? '—' }}
                    </span>
                    @if($requirement->is_required)
                        <span class="badge badge-danger">{{ __('crud.required_document') }}</span>
                    @else
                        <span class="badge badge-secondary">{{ __('crud.optional_document') }}</span>
                    @endif
                </div>
            </div>

            @if($requirement->{'info_' . $locale})
                <p class="text-xs mt-2" style="color: var(--text-muted);">
                    {{ $requirement->{'info_' . $locale} }}
                </p>
            @endif

            @if($requirement->extensions && !empty(json_decode($requirement->extensions)))
                <div class="flex flex-wrap gap-1 mt-2">
                    @foreach(json_decode($requirement->extensions) as $ext)
                        <span class="text-xs px-2 py-0.5 rounded" style="background: var(--bg-hover); color: var(--text-muted);">
                            {{ $ext }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Footer: Details button --}}
    <div class="flex justify-end pt-3" style="border-top: 1px solid var(--border-color);">
        <a href="{{ route('referee.referee-protocol-detail', ['matchId' => $match->id]) }}"
           class="btn-secondary text-sm">
            {{ __('crud.view_details') }}
        </a>
    </div>
</div>
