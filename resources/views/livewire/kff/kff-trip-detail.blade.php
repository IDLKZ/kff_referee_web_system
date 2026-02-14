@php
    $locale = app()->getLocale();
    use App\Constants\OperationConstants;
@endphp

<div>
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('kff.kff-trips') }}" class="link flex items-center gap-2 font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            {{ __('crud.back_to_list') }}
        </a>
    </div>

    {{-- Match Header — Club vs Club --}}
    <div class="card p-6 mb-6">
        <div class="flex flex-col items-center text-center mb-6">
            {{-- Matchup Block --}}
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
            <div class="flex items-start gap-2">
                <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <div>
                    <span style="color: var(--text-muted);">{{ __('crud.start_at') }}</span>
                    <span class="font-medium block" style="color: var(--text-primary);">
                        {{ $match->start_at?->format('d.m.Y H:i') ?? '—' }}
                    </span>
                </div>
            </div>
            <div class="flex items-start gap-2">
                <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <div>
                    <span style="color: var(--text-muted);">{{ __('crud.city') }}</span>
                    <span class="font-medium block" style="color: var(--text-primary);">
                        {{ $match->city->{'title_' . $locale} ?? '' }}
                    </span>
                </div>
            </div>
            <div class="flex items-start gap-2">
                <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
                <div>
                    <span style="color: var(--text-muted);">{{ __('crud.tournament') }}</span>
                    <span class="font-medium block" style="color: var(--text-primary);">
                        {{ $match->tournament->{'title_' . $locale} ?? '' }}
                    </span>
                </div>
            </div>
            @if($match->stadium)
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <div>
                        <span style="color: var(--text-muted);">{{ __('crud.stadium') }}</span>
                        <span class="font-medium block" style="color: var(--text-primary);">
                            {{ $match->stadium->{'title_' . $locale} ?? '' }}
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
                            {{ $match->season->{'title_' . $locale} ?? '' }}
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
    @if($match->judge_requirements && $match->judge_requirements->isNotEmpty())
        <div class="mb-6">
            <h2 class="text-sm font-semibold mb-3 uppercase tracking-wide" style="color: var(--text-muted);">
                {{ __('crud.judge_requirements_section') }}
            </h2>
            <div class="flex flex-wrap gap-3">
                @foreach($match->judge_requirements as $requirement)
                    <div class="card px-4 py-3 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center" style="background: var(--color-primary-light);">
                            <svg class="w-4 h-4" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs" style="color: var(--text-muted);">{{ $requirement->judge_type->{'title_' . $locale} ?? '' }}</p>
                            <p class="text-lg font-bold" style="color: var(--text-primary);">{{ $requirement->qty }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Judges with Trips Section --}}
    <div class="mb-6">
        <h2 class="text-sm font-semibold mb-4 uppercase tracking-wide" style="color: var(--text-muted);">
            {{ __('crud.trip_detail_judges') }}
        </h2>

        @forelse($judges as $judge)
            @php
                $hasTrip = !empty($judge['trip']);
                $tripReady = $hasTrip && $this->isTripReady($judge['trip']);
                $tripProcessing = $hasTrip && isset($judge['trip']['operation']) && $judge['trip']['operation']['value'] === OperationConstants::TRIP_PROCESSING;

                // Left border color: green = ready/processing, yellow = in progress, red = no trip
                if ($isReadOnly && $hasTrip) {
                    $borderColor = 'var(--color-success)';
                } elseif ($tripProcessing) {
                    $borderColor = 'var(--color-success)';
                } elseif ($hasTrip && $tripReady) {
                    $borderColor = 'var(--color-warning)';
                } elseif ($hasTrip) {
                    $borderColor = 'var(--color-warning)';
                } else {
                    $borderColor = 'var(--color-danger)';
                }

                // Initials
                $firstInitial = mb_substr($judge['user']['first_name'] ?? '', 0, 1);
                $lastInitial = mb_substr($judge['user']['last_name'] ?? '', 0, 1);
            @endphp

            <div class="card mb-4 overflow-hidden" style="border-left: 4px solid {{ $borderColor }};">
                {{-- Judge Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-4" style="border-bottom: 1px solid var(--border-color);">
                    <div class="flex items-center gap-3">
                        {{-- Avatar with Initials --}}
                        <div class="w-11 h-11 rounded-full flex items-center justify-center text-sm font-bold shrink-0"
                             style="background: var(--color-primary-light); color: var(--color-primary);">
                            {{ $lastInitial }}{{ $firstInitial }}
                        </div>
                        <div>
                            <p class="font-semibold" style="color: var(--text-primary);">
                                {{ $judge['user']['last_name'] ?? '' }} {{ $judge['user']['first_name'] ?? '' }}
                            </p>
                            <p class="text-xs" style="color: var(--text-muted);">
                                {{ $judge['judge_type']['title_' . $locale] ?? '' }}
                            </p>
                        </div>
                    </div>
                    {{-- Trip Status Badge --}}
                    <div class="shrink-0">
                        @if($hasTrip)
                            @if($isReadOnly)
                                <span class="badge badge-success">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    {{ __('crud.trip_configured') }}
                                </span>
                            @elseif($tripProcessing)
                                <span class="badge badge-success">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    {{ __('crud.ready_for_processing') }}
                                </span>
                            @elseif($tripReady)
                                <button wire:click="markTripReady({{ $judge['trip']['id'] }})"
                                        class="btn-primary text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                                    {{ __('crud.ready_for_processing') }}
                                    <span wire:loading wire:target="markTripReady({{ $judge['trip']['id'] }})">
                                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    </span>
                                </button>
                            @else
                                <span class="badge badge-warning">
                                    {{ __('crud.trip_not_ready') }}
                                </span>
                            @endif
                        @else
                            <span class="badge badge-danger">
                                {{ __('crud.trip_not_configured') }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Trip Details --}}
                @if($hasTrip)
                    <div class="p-4 space-y-4">
                        {{-- Main Trip Info — Summary Bar --}}
                        <div class="flex flex-wrap items-center gap-4 p-3 rounded-lg text-sm" style="background: var(--bg-hover);">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" style="color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                                <span style="color: var(--text-muted);">{{ __('crud.transport_type') }}:</span>
                                <span class="font-medium" style="color: var(--text-primary);">
                                    {{ $judge['trip']['transport_type']['title_' . $locale] ?? '' }}
                                </span>
                            </div>
                            <div class="w-px h-4" style="background: var(--border-color);"></div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" style="color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span style="color: var(--text-muted);">{{ __('crud.departure_city') }}:</span>
                                <span class="font-medium" style="color: var(--text-primary);">
                                    {{ $judge['trip']['city']['title_' . $locale] ?? '' }}
                                </span>
                            </div>
                            @if($judge['trip']['info'])
                                <div class="w-px h-4" style="background: var(--border-color);"></div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="truncate max-w-xs" style="color: var(--text-secondary);">{{ $judge['trip']['info'] }}</span>
                                </div>
                            @endif
                            @if(!$isReadOnly)
                                <button wire:click="openTripModal({{ $judge['id'] }})"
                                        class="ml-auto flex items-center gap-1 text-sm font-medium" style="color: var(--color-info);">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    {{ __('crud.edit_trip') }}
                                </button>
                            @endif
                        </div>

                        {{-- Route Section (Trip Migrations) --}}
                        @if($judge['trip']['trip_migrations'] && count($judge['trip']['trip_migrations']) > 0)
                            <div>
                                {{-- Section Divider --}}
                                <div class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4" style="color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                    <h4 class="text-xs font-semibold uppercase tracking-wider" style="color: var(--text-muted);">
                                        {{ __('crud.trip_migrations') }}
                                    </h4>
                                    <div class="flex-1 h-px" style="background: var(--border-color);"></div>
                                </div>

                                {{-- Visual Timeline --}}
                                <div class="space-y-2">
                                    @foreach($judge['trip']['trip_migrations'] as $index => $migration)
                                        <div class="flex items-center gap-3 p-3 rounded-lg" style="background: var(--bg-hover);">
                                            {{-- Timeline indicator --}}
                                            <div class="flex flex-col items-center shrink-0">
                                                <div class="w-2.5 h-2.5 rounded-full" style="background: var(--color-info);"></div>
                                                @if(!$loop->last)
                                                    <div class="w-0.5 h-4 mt-0.5" style="background: var(--color-info); opacity: 0.3;"></div>
                                                @endif
                                            </div>

                                            {{-- Migration content --}}
                                            <div class="flex-1 flex flex-wrap items-center gap-2 text-sm">
                                                <span class="badge badge-info text-xs">
                                                    {{ $migration['transport_type']['title_' . $locale] ?? '' }}
                                                </span>
                                                <span class="font-medium" style="color: var(--text-primary);">
                                                    {{ $migration['city']['title_' . $locale] ?? '' }}
                                                </span>
                                                <svg class="w-4 h-4 shrink-0" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                                </svg>
                                                <span class="font-medium" style="color: var(--text-primary);">
                                                    {{ $migration['arrival_city']['title_' . $locale] ?? '' }}
                                                </span>
                                                <span class="text-xs px-2 py-0.5 rounded-full" style="background: var(--color-info-light); color: var(--color-info);">
                                                    {{ \Carbon\Carbon::parse($migration['from_date'])->format('d.m H:i') }} — {{ \Carbon\Carbon::parse($migration['to_date'])->format('d.m H:i') }}
                                                </span>
                                            </div>

                                            {{-- Edit/Delete --}}
                                            @if(!$isReadOnly)
                                                <div class="flex items-center gap-1 shrink-0">
                                                    <button wire:click="openTripMigrationModalEdit({{ $migration['id'] }})"
                                                            class="btn-icon btn-icon-edit" title="{{ __('crud.edit') }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </button>
                                                    <button wire:click="deleteTripMigration({{ $migration['id'] }})"
                                                            wire:confirm="{{ __('crud.confirm_delete') }}"
                                                            class="btn-icon btn-icon-delete" title="{{ __('crud.delete') }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Accommodation Section (Trip Hotels) --}}
                        @if($judge['trip']['trip_hotels'] && count($judge['trip']['trip_hotels']) > 0)
                            <div>
                                {{-- Section Divider --}}
                                <div class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4" style="color: var(--color-warning);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <h4 class="text-xs font-semibold uppercase tracking-wider" style="color: var(--text-muted);">
                                        {{ __('crud.trip_hotels') }}
                                    </h4>
                                    <div class="flex-1 h-px" style="background: var(--border-color);"></div>
                                </div>

                                <div class="grid gap-2 sm:grid-cols-2">
                                    @foreach($judge['trip']['trip_hotels'] as $tripHotel)
                                        <div class="flex items-center justify-between p-3 rounded-lg border" style="border-color: var(--border-color); background: var(--bg-card);">
                                            <div class="text-sm min-w-0">
                                                <p class="font-medium truncate" style="color: var(--text-primary);">
                                                    {{ $tripHotel['hotel']['title_' . $locale] ?? '' }}
                                                </p>
                                                @if($tripHotel['hotel_room'])
                                                    <p class="text-xs truncate" style="color: var(--text-muted);">
                                                        {{ $tripHotel['hotel_room']['title_' . $locale] ?? '' }}
                                                    </p>
                                                @endif
                                                <span class="inline-block mt-1 text-xs px-2 py-0.5 rounded-full" style="background: var(--color-warning-light); color: var(--color-warning);">
                                                    {{ \Carbon\Carbon::parse($tripHotel['from_date'])->format('d.m') }} — {{ \Carbon\Carbon::parse($tripHotel['to_date'])->format('d.m') }}
                                                </span>
                                            </div>
                                            @if(!$isReadOnly)
                                                <div class="flex items-center gap-1 shrink-0 ml-2">
                                                    <button wire:click="openTripHotelModalEdit({{ $tripHotel['id'] }})"
                                                            class="btn-icon btn-icon-edit" title="{{ __('crud.edit') }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </button>
                                                    <button wire:click="deleteTripHotel({{ $tripHotel['id'] }})"
                                                            wire:confirm="{{ __('crud.confirm_delete') }}"
                                                            class="btn-icon btn-icon-delete" title="{{ __('crud.delete') }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Documents Section --}}
                        @if($judge['trip']['trip_documents'] && count($judge['trip']['trip_documents']) > 0)
                            <div>
                                {{-- Section Divider --}}
                                <div class="flex items-center gap-2 mb-3">
                                    <svg class="w-4 h-4" style="color: var(--color-success);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <h4 class="text-xs font-semibold uppercase tracking-wider" style="color: var(--text-muted);">
                                        {{ __('crud.trip_documents') }}
                                    </h4>
                                    <div class="flex-1 h-px" style="background: var(--border-color);"></div>
                                </div>

                                <div class="space-y-2">
                                    @foreach($judge['trip']['trip_documents'] as $document)
                                        <div class="flex items-center justify-between p-3 rounded-lg" style="background: var(--bg-hover);">
                                            <div class="flex items-center gap-3 text-sm min-w-0">
                                                <svg class="w-5 h-5 shrink-0" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                </svg>
                                                <span class="font-medium truncate" style="color: var(--text-primary);">
                                                    {{ $document['title'] }}
                                                </span>
                                                @if($document['total_price'])
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold shrink-0"
                                                          style="background: var(--color-success-light); color: var(--color-success);">
                                                        {{ number_format($document['total_price'], 0, '.', ' ') }} KZT
                                                    </span>
                                                @endif
                                            </div>
                                            @if(!$isReadOnly)
                                                <div class="flex items-center gap-1 shrink-0 ml-2">
                                                    <button wire:click="openTripDocumentModalEdit({{ $document['id'] }})"
                                                            class="btn-icon btn-icon-edit" title="{{ __('crud.edit') }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </button>
                                                    <button wire:click="deleteTripDocument({{ $document['id'] }})"
                                                            wire:confirm="{{ __('crud.confirm_delete') }}"
                                                            class="btn-icon btn-icon-delete" title="{{ __('crud.delete') }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Action Toolbar — Pill Buttons --}}
                        @if(!$isReadOnly)
                            <div class="flex flex-wrap gap-2 pt-2" style="border-top: 1px solid var(--border-color);">
                                <button wire:click="openTripMigrationModal({{ $judge['trip']['id'] }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium border transition-colors"
                                        style="color: var(--color-info); border-color: var(--color-info); background: transparent;"
                                        onmouseover="this.style.background='var(--color-info-light)'" onmouseout="this.style.background='transparent'">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    {{ __('crud.add_migration') }}
                                </button>
                                <button wire:click="openTripHotelModal({{ $judge['trip']['id'] }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium border transition-colors"
                                        style="color: var(--color-warning); border-color: var(--color-warning); background: transparent;"
                                        onmouseover="this.style.background='var(--color-warning-light)'" onmouseout="this.style.background='transparent'">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    {{ __('crud.add_hotel') }}
                                </button>
                                <button wire:click="openTripDocumentModal({{ $judge['trip']['id'] }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium border transition-colors"
                                        style="color: var(--color-success); border-color: var(--color-success); background: transparent;"
                                        onmouseover="this.style.background='var(--color-success-light)'" onmouseout="this.style.background='transparent'">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    {{ __('crud.add_document') }}
                                </button>
                            </div>
                        @endif
                    </div>
                @else
                    {{-- No Trip — Empty State --}}
                    @if(!$isReadOnly)
                        <div class="flex flex-col items-center justify-center py-10 px-6">
                            <div class="w-16 h-16 rounded-full flex items-center justify-center mb-4"
                                 style="background: var(--bg-hover);">
                                <svg class="w-8 h-8" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium mb-1" style="color: var(--text-secondary);">
                                {{ __('crud.no_trip_configured') }}
                            </p>
                            <p class="text-xs mb-4" style="color: var(--text-muted);">
                                {{ __('crud.configure_trip_hint') }}
                            </p>
                            <button wire:click="openTripModal({{ $judge['id'] }})"
                                    class="btn-primary text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                {{ __('crud.configure_trip') }}
                            </button>
                        </div>
                    @else
                        <div class="flex items-center justify-center py-6 px-4">
                            <span class="text-sm" style="color: var(--text-muted);">{{ __('crud.no_trip_configured') }}</span>
                        </div>
                    @endif
                @endif
            </div>
        @empty
            <div class="card p-10 text-center">
                <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4"
                     style="background: var(--bg-hover);">
                    <svg class="w-8 h-8" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <p class="font-medium" style="color: var(--text-secondary);">{{ __('crud.no_judges_assigned') }}</p>
            </div>
        @endforelse
    </div>

    {{-- Sticky Bottom Action Bar --}}
    @if(!$isReadOnly && $this->isAllTripsReady())
        <div class="sticky bottom-0 z-10 -mx-4 px-4 py-4" style="background: var(--bg-body); box-shadow: 0 -4px 12px rgba(0,0,0,0.08);">
            <div class="card p-4">
                @if($currentOperationValue === OperationConstants::SELECT_TRANSPORT_DEPARTURE)
                    <button wire:click="transitionToTripProcessing"
                            wire:confirm="{{ __('crud.confirm_action') }}"
                            class="btn-primary w-full justify-center"
                            wire:loading.attr="disabled">
                        <svg class="w-5 h-5" wire:loading.remove wire:target="transitionToTripProcessing" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                        <svg class="w-5 h-5 animate-spin" wire:loading wire:target="transitionToTripProcessing" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        {{ __('crud.ready_for_processing') }}
                    </button>
                @elseif($currentOperationValue === OperationConstants::TRIP_PROCESSING)
                    <button wire:click="transitionToWaitingForProtocol"
                            wire:confirm="{{ __('crud.confirm_action') }}"
                            class="btn-primary w-full justify-center"
                            wire:loading.attr="disabled">
                        <svg class="w-5 h-5" wire:loading.remove wire:target="transitionToWaitingForProtocol" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <svg class="w-5 h-5 animate-spin" wire:loading wire:target="transitionToWaitingForProtocol" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        {{ __('crud.complete_trip') }}
                    </button>
                @endif
            </div>
        </div>
    @endif

    {{-- Trip Modal --}}
    <x-modal wire:model="showTripModal" maxWidth="md">
        <x-slot name="title">{{ __('crud.configure_trip') }}</x-slot>

        <div class="space-y-4">
            <div>
                <label class="form-label">{{ __('crud.transport_type') }} <span class="text-red-500">*</span></label>
                <select wire:model.live="tripTransportTypeId" class="form-input @error('tripTransportTypeId') is-invalid @enderror">
                    <option value="">{{ __('crud.select_transport_type') }}</option>
                    @foreach($this->transportTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->{'title_' . $locale} }}</option>
                    @endforeach
                </select>
                @error('tripTransportTypeId')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label">{{ __('crud.departure_city') }} <span class="text-red-500">*</span></label>
                <select wire:model.live="tripDepartureCityId" class="form-input @error('tripDepartureCityId') is-invalid @enderror">
                    <option value="">{{ __('crud.select_departure_city') }}</option>
                    @foreach($this->cities as $city)
                        <option value="{{ $city->id }}">{{ $city->{'title_' . $locale} }}</option>
                    @endforeach
                </select>
                @error('tripDepartureCityId')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label">{{ __('crud.logist_info') }} ({{ __('crud.optional') }})</label>
                <textarea
                    wire:model.live="tripLogistInfo"
                    class="form-input"
                    rows="3"
                    placeholder="{{ __('crud.logist_info_placeholder') }}"
                ></textarea>
            </div>
        </div>

        <x-slot name="footer">
            <button wire:click="closeTripModal" class="btn-secondary">{{ __('crud.cancel') }}</button>
            <button wire:click="saveTrip" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading wire:target="saveTrip">
                    <svg class="w-4 h-4 animate-spin inline mr-1" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                </span>
                {{ __('crud.save') }}
            </button>
        </x-slot>
    </x-modal>

    {{-- TripMigration Modal --}}
    <x-modal wire:model="showTripMigrationModal" maxWidth="md">
        <x-slot name="title">{{ $tripMigrationId ? __('crud.edit_migration') : __('crud.add_migration') }}</x-slot>

        <div class="space-y-4">
            <div>
                <label class="form-label">{{ __('crud.transport_type') }} <span class="text-red-500">*</span></label>
                <select wire:model.live="tripMigrationTransportTypeId" class="form-input @error('tripMigrationTransportTypeId') is-invalid @enderror">
                    <option value="">{{ __('crud.select_transport_type') }}</option>
                    @foreach($this->transportTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->{'title_' . $locale} }}</option>
                    @endforeach
                </select>
                @error('tripMigrationTransportTypeId')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label">{{ __('crud.departure_city') }} <span class="text-red-500">*</span></label>
                <select wire:model.live="tripMigrationDepartureCityId" class="form-input @error('tripMigrationDepartureCityId') is-invalid @enderror">
                    <option value="">{{ __('crud.select_departure_city') }}</option>
                    @foreach($this->cities as $city)
                        <option value="{{ $city->id }}">{{ $city->{'title_' . $locale} }}</option>
                    @endforeach
                </select>
                @error('tripMigrationDepartureCityId')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label">{{ __('crud.arrival_city') }} <span class="text-red-500">*</span></label>
                <select wire:model.live="tripMigrationArrivalCityId" class="form-input @error('tripMigrationArrivalCityId') is-invalid @enderror">
                    <option value="">{{ __('crud.select_arrival_city') }}</option>
                    @foreach($this->cities as $city)
                        <option value="{{ $city->id }}">{{ $city->{'title_' . $locale} }}</option>
                    @endforeach
                </select>
                @error('tripMigrationArrivalCityId')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label">{{ __('crud.from_date') }} <span class="text-red-500">*</span></label>
                <input type="datetime-local"
                       wire:model.live="tripMigrationFromDate"
                       class="form-input @error('tripMigrationFromDate') is-invalid @enderror">
                @error('tripMigrationFromDate')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label">{{ __('crud.to_date') }} <span class="text-red-500">*</span></label>
                <input type="datetime-local"
                       wire:model.live="tripMigrationToDate"
                       class="form-input @error('tripMigrationToDate') is-invalid @enderror">
                @error('tripMigrationToDate')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label">{{ __('crud.info') }} ({{ __('crud.optional') }})</label>
                <textarea
                    wire:model.live="tripMigrationInfo"
                    class="form-input"
                    rows="3"
                    placeholder="{{ __('crud.info_placeholder') }}"
                ></textarea>
            </div>
        </div>

        <x-slot name="footer">
            <button wire:click="closeTripMigrationModal" class="btn-secondary">{{ __('crud.cancel') }}</button>
            <button wire:click="saveTripMigration" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading wire:target="saveTripMigration">
                    <svg class="w-4 h-4 animate-spin inline mr-1" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                </span>
                {{ __('crud.save') }}
            </button>
        </x-slot>
    </x-modal>

    {{-- TripHotel Modal --}}
    <x-modal wire:model="showTripHotelModal" maxWidth="md">
        <x-slot name="title">{{ $tripHotelId ? __('crud.edit_hotel') : __('crud.add_hotel') }}</x-slot>

        <div class="space-y-4">
            <div>
                <label class="form-label">{{ __('crud.hotel') }} <span class="text-red-500">*</span></label>
                <select wire:model.live="tripHotelHotelId" class="form-input @error('tripHotelHotelId') is-invalid @enderror">
                    <option value="">{{ __('crud.select_hotel') }}</option>
                    @foreach($this->hotels as $hotel)
                        <option value="{{ $hotel->id }}">{{ $hotel->{'title_' . $locale} }}</option>
                    @endforeach
                </select>
                @error('tripHotelHotelId')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label">{{ __('crud.room') }} ({{ __('crud.optional') }})</label>
                <select wire:model.live="tripHotelRoomId" class="form-input @error('tripHotelRoomId') is-invalid @enderror" {{ $this->hotelRooms->isEmpty() ? 'disabled' : '' }}>
                    <option value="">{{ __('crud.select_room') }}</option>
                    @foreach($this->hotelRooms as $room)
                        <option value="{{ $room->id }}">{{ $room->{'title_' . $locale} }}</option>
                    @endforeach
                </select>
                @error('tripHotelRoomId')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label">{{ __('crud.from_date') }} <span class="text-red-500">*</span></label>
                <input type="date"
                       wire:model.live="tripHotelFromDate"
                       class="form-input @error('tripHotelFromDate') is-invalid @enderror">
                @error('tripHotelFromDate')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label">{{ __('crud.to_date') }} <span class="text-red-500">*</span></label>
                <input type="date"
                       wire:model.live="tripHotelToDate"
                       class="form-input @error('tripHotelToDate') is-invalid @enderror">
                @error('tripHotelToDate')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label">{{ __('crud.info') }} ({{ __('crud.optional') }})</label>
                <textarea
                    wire:model.live="tripHotelInfo"
                    class="form-input"
                    rows="3"
                    placeholder="{{ __('crud.info_placeholder') }}"
                ></textarea>
            </div>
        </div>

        <x-slot name="footer">
            <button wire:click="closeTripHotelModal" class="btn-secondary">{{ __('crud.cancel') }}</button>
            <button wire:click="saveTripHotel" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading wire:target="saveTripHotel">
                    <svg class="w-4 h-4 animate-spin inline mr-1" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                </span>
                {{ __('crud.save') }}
            </button>
        </x-slot>
    </x-modal>

    {{-- TripDocument Modal --}}
    <x-modal wire:model="showTripDocumentModal" maxWidth="md">
        <x-slot name="title">{{ $tripDocumentId ? __('crud.edit_document') : __('crud.add_document') }}</x-slot>

        <div class="space-y-4">
            <div>
                <label class="form-label">{{ __('crud.title') }} <span class="text-red-500">*</span></label>
                <input type="text"
                       wire:model.live="tripDocumentTitle"
                       class="form-input @error('tripDocumentTitle') is-invalid @enderror"
                       placeholder="{{ __('crud.document_title_placeholder') }}">
                @error('tripDocumentTitle')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label">{{ __('crud.file') }} ({{ __('crud.optional') }})</label>
                <input type="file"
                       wire:model.live="tripDocumentFileUpload"
                       class="form-input @error('tripDocumentFileUpload') is-invalid @enderror">
                @error('tripDocumentFileUpload')
                    <p class="form-error">{{ $message }}</p>
                @enderror
                @if($tripDocumentFileId)
                    <p class="text-sm mt-1" style="color: var(--text-muted);">
                        {{ __('crud.file_already_uploaded') }}
                    </p>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ __('crud.price') }} ({{ __('crud.optional') }})</label>
                    <input type="number"
                           step="0.01"
                           wire:model.live="tripDocumentPrice"
                           class="form-input @error('tripDocumentPrice') is-invalid @enderror"
                           placeholder="0.00">
                    @error('tripDocumentPrice')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">{{ __('crud.qty') }} ({{ __('crud.optional') }})</label>
                    <input type="number"
                           step="0.01"
                           wire:model.live="tripDocumentQty"
                           class="form-input @error('tripDocumentQty') is-invalid @enderror"
                           placeholder="1">
                    @error('tripDocumentQty')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="form-label">{{ __('crud.total_price') }}</label>
                <input type="number"
                       step="0.01"
                       wire:model.live="tripDocumentTotalPrice"
                       class="form-input @error('tripDocumentTotalPrice') is-invalid @enderror"
                       placeholder="0.00">
                @error('tripDocumentTotalPrice')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label">{{ __('crud.info') }} ({{ __('crud.optional') }})</label>
                <textarea
                    wire:model.live="tripDocumentInfo"
                    class="form-input"
                    rows="3"
                    placeholder="{{ __('crud.info_placeholder') }}"
                ></textarea>
            </div>
        </div>

        <x-slot name="footer">
            <button wire:click="closeTripDocumentModal" class="btn-secondary">{{ __('crud.cancel') }}</button>
            <button wire:click="saveTripDocument" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading wire:target="saveTripDocument">
                    <svg class="w-4 h-4 animate-spin inline mr-1" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                </span>
                {{ __('crud.save') }}
            </button>
        </x-slot>
    </x-modal>

    {{-- Flash Message --}}
    @if(session('message'))
        <script>
            toastr.success('{{ session('message') }}');
        </script>
        @php
            session()->forget('message');
        @endphp
    @endif
</div>
