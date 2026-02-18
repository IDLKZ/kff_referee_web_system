@php
    $locale = app()->getLocale();
    $match = $trip->match;
@endphp

<div>
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('referee.referee-trips') }}" class="link flex items-center gap-2 font-medium">
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

    {{-- Trip Details --}}
    <div class="mb-6">
        <h2 class="text-sm font-semibold mb-4 uppercase tracking-wide" style="color: var(--text-muted);">
            {{ __('crud.trip_detail_judges') }}
        </h2>

        <div class="card mb-4 overflow-hidden" style="border-left: 4px solid var(--color-success);">
            {{-- Judge Header --}}
            <div class="flex items-center justify-between gap-3 p-5" style="border-bottom: 1px solid var(--border-color);">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full flex items-center justify-center text-sm font-bold shrink-0"
                         style="background: var(--color-primary-light); color: var(--color-primary);">
                        {{ mb_substr(auth()->user()->last_name ?? '', 0, 1) }}{{ mb_substr(auth()->user()->first_name ?? '', 0, 1) }}
                    </div>
                    <div>
                        <p class="font-semibold" style="color: var(--text-primary);">
                            {{ auth()->user()->last_name }} {{ auth()->user()->first_name }}
                        </p>
                    </div>
                </div>
                <span class="badge badge-success">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('crud.trip_configured') }}
                </span>
            </div>

            <div class="p-5 space-y-5">
                {{-- Main Trip Info — Summary Bar --}}
                <div class="flex flex-wrap items-center gap-4 p-4 rounded-xl text-sm" style="background: var(--bg-hover);">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                             style="background: var(--color-info-light);">
                            <svg class="w-4 h-4" style="color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs block" style="color: var(--text-muted);">{{ __('crud.transport_type') }}</span>
                            <span class="font-semibold" style="color: var(--text-primary);">
                                {{ $trip->transport_type->{'title_' . $locale} ?? '' }}
                            </span>
                        </div>
                    </div>
                    <div class="w-px h-8 hidden sm:block" style="background: var(--border-color);"></div>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                             style="background: var(--color-info-light);">
                            <svg class="w-4 h-4" style="color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs block" style="color: var(--text-muted);">{{ __('crud.departure_city') }}</span>
                            <span class="font-semibold" style="color: var(--text-primary);">
                                {{ $trip->city->{'title_' . $locale} ?? '' }}
                            </span>
                        </div>
                    </div>
                    @if($trip->arrival_city)
                        <div class="w-px h-8 hidden sm:block" style="background: var(--border-color);"></div>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                                 style="background: var(--color-success-light);">
                                <svg class="w-4 h-4" style="color: var(--color-success);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <span class="text-xs block" style="color: var(--text-muted);">{{ __('crud.arrival_city') }}</span>
                                <span class="font-semibold" style="color: var(--text-primary);">
                                    {{ $trip->arrival_city->{'title_' . $locale} ?? '' }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Logist Info --}}
                @if($trip->info)
                    <div class="flex items-start gap-3 p-3 rounded-lg" style="background: var(--color-info-light);">
                        <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm" style="color: var(--text-primary);">{{ $trip->info }}</p>
                    </div>
                @endif

                {{-- Route Section (Trip Migrations) --}}
                @if($trip->trip_migrations->isNotEmpty())
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-4 h-4" style="color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            <h4 class="text-xs font-semibold uppercase tracking-wider" style="color: var(--text-muted);">
                                {{ __('crud.trip_migrations') }}
                            </h4>
                            <div class="flex-1 h-px" style="background: var(--border-color);"></div>
                            <span class="badge badge-info text-xs">{{ $trip->trip_migrations->count() }}</span>
                        </div>

                        <div class="space-y-2">
                            @foreach($trip->trip_migrations as $index => $migration)
                                <div class="flex items-center gap-3 p-3 rounded-lg transition-colors duration-150"
                                     style="background: var(--bg-hover);">
                                    <div class="flex flex-col items-center shrink-0">
                                        <div class="w-3 h-3 rounded-full border-2" style="border-color: var(--color-info); background: {{ $loop->first ? 'var(--color-info)' : 'var(--bg-card)' }};"></div>
                                        @if(!$loop->last)
                                            <div class="w-0.5 h-6 mt-0.5" style="background: var(--color-info); opacity: 0.3;"></div>
                                        @endif
                                    </div>

                                    <div class="flex-1 flex flex-wrap items-center gap-2 text-sm">
                                        <span class="badge badge-info text-xs">
                                            {{ $migration->transport_type->{'title_' . $locale} ?? '' }}
                                        </span>
                                        <span class="font-semibold" style="color: var(--text-primary);">
                                            {{ $migration->city->{'title_' . $locale} ?? '' }}
                                        </span>
                                        <svg class="w-4 h-4 shrink-0" style="color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                        </svg>
                                        <span class="font-semibold" style="color: var(--text-primary);">
                                            {{ $migration->arrival_city->{'title_' . $locale} ?? '' }}
                                        </span>
                                        <span class="text-xs px-2.5 py-1 rounded-full font-medium" style="background: var(--color-info-light); color: var(--color-info);">
                                            {{ $migration->from_date?->format('d.m H:i') }} — {{ $migration->to_date?->format('d.m H:i') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Accommodation Section (Trip Hotels) --}}
                @if($trip->trip_hotels->isNotEmpty())
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-4 h-4" style="color: var(--color-warning);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <h4 class="text-xs font-semibold uppercase tracking-wider" style="color: var(--text-muted);">
                                {{ __('crud.trip_hotels') }}
                            </h4>
                            <div class="flex-1 h-px" style="background: var(--border-color);"></div>
                            <span class="badge badge-warning text-xs">{{ $trip->trip_hotels->count() }}</span>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            @foreach($trip->trip_hotels as $tripHotel)
                                <div class="p-4 rounded-xl border transition-colors duration-150" style="border-color: var(--border-color); background: var(--bg-card);">
                                    <div class="flex items-start gap-3">
                                        <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0"
                                             style="background: var(--color-warning-light);">
                                            <svg class="w-5 h-5" style="color: var(--color-warning);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="font-semibold text-sm truncate" style="color: var(--text-primary);">
                                                {{ $tripHotel->hotel->{'title_' . $locale} ?? '' }}
                                            </p>
                                            @if($tripHotel->hotel_room)
                                                <p class="text-xs truncate mt-0.5" style="color: var(--text-muted);">
                                                    {{ $tripHotel->hotel_room->{'title_' . $locale} ?? '' }}
                                                </p>
                                            @endif
                                            <span class="inline-block mt-2 text-xs px-2.5 py-1 rounded-full font-medium"
                                                  style="background: var(--color-warning-light); color: var(--color-warning);">
                                                {{ $tripHotel->from_date?->format('d.m') }} — {{ $tripHotel->to_date?->format('d.m') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Documents Section --}}
                @if($trip->trip_documents->isNotEmpty())
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-4 h-4" style="color: var(--color-success);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h4 class="text-xs font-semibold uppercase tracking-wider" style="color: var(--text-muted);">
                                {{ __('crud.trip_documents') }}
                            </h4>
                            <div class="flex-1 h-px" style="background: var(--border-color);"></div>
                            <span class="badge badge-success text-xs">{{ $trip->trip_documents->count() }}</span>
                        </div>

                        <div class="space-y-2">
                            @foreach($trip->trip_documents as $document)
                                <div class="flex items-center justify-between p-3 rounded-lg transition-colors duration-150"
                                     style="background: var(--bg-hover);">
                                    <div class="flex items-center gap-3 text-sm min-w-0">
                                        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0"
                                             style="background: var(--color-success-light);">
                                            <svg class="w-4 h-4" style="color: var(--color-success);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="font-medium truncate block" style="color: var(--text-primary);">
                                                {{ $document->title }}
                                            </span>
                                            @if($document->info)
                                                <span class="text-xs truncate block" style="color: var(--text-muted);">
                                                    {{ $document->info }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0 ml-3">
                                        @if($document->file)
                                            <a href="{{ route('files.download', $document->file->id) }}"
                                               class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium transition-colors"
                                               style="color: var(--color-info); background: var(--color-info-light);"
                                               title="{{ $document->file->filename }}">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                </svg>
                                                {{ __('crud.download') }}
                                            </a>
                                        @endif
                                        @if($document->total_price)
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold"
                                                  style="background: var(--color-success-light); color: var(--color-success);">
                                                {{ number_format($document->total_price, 0, '.', ' ') }} KZT
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Total Cost Summary --}}
                        @php
                            $totalCost = $trip->trip_documents->sum('total_price');
                        @endphp
                        @if($totalCost > 0)
                            <div class="flex items-center justify-between p-4 mt-3 rounded-xl"
                                 style="background: var(--color-success-light); border: 1px solid var(--color-success); border-opacity: 0.2;">
                                <span class="font-semibold text-sm" style="color: var(--color-success);">
                                    {{ __('crud.total') }}
                                </span>
                                <span class="text-lg font-bold" style="color: var(--color-success);">
                                    {{ number_format($totalCost, 0, '.', ' ') }} KZT
                                </span>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
