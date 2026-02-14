@props(['trip', 'locale' => 'ru'])

@php
$match = $trip->match ?? null;
$judge = $trip->judge ?? null;
$locale ??= app()->getLocale();
@endphp

<div class="card overflow-hidden hover:shadow-lg transition-shadow duration-200">
    {{-- Header with tournament badge --}}
    @if($match)
        <div class="px-5 py-4 flex items-center justify-between" style="background: var(--bg-hover);">
            <span class="badge badge-info text-xs font-semibold">
                {{ $match->tournament->{'title_' . $locale} ?? '' }}
            </span>
            @if($match->operation)
                <span class="badge badge-secondary text-xs">
                    {{ $match->operation->{'title_' . $locale} ?? '' }}
                </span>
            @endif
        </div>
    @endif

    {{-- Match Info --}}
    <div class="p-5">
        @if($match)
            {{-- Teams --}}
            <div class="flex items-center justify-between gap-3 mb-4">
                <div class="flex-1 text-center">
                    <div class="w-12 h-12 mx-auto mb-2 rounded-full flex items-center justify-center"
                         style="background: var(--color-primary-light);">
                        <span class="font-bold text-sm" style="color: var(--color-primary);">
                            {{ $match->ownerClub->{'short_name_' . $locale} ?? '—' }}
                        </span>
                    </div>
                    <p class="font-semibold text-sm" style="color: var(--text-primary);">
                        {{ $match->ownerClub->{'short_name_' . $locale} ?? '—' }}
                    </p>
                </div>

                <div class="text-lg font-bold" style="color: var(--text-muted);">
                    {{ __('crud.vs') }}
                </div>

                <div class="flex-1 text-center">
                    <div class="w-12 h-12 mx-auto mb-2 rounded-full flex items-center justify-center"
                         style="background: var(--color-info-light);">
                        <span class="font-bold text-sm" style="color: var(--color-info);">
                            {{ $match->guestClub->{'short_name_' . $locale} ?? '—' }}
                        </span>
                    </div>
                    <p class="font-semibold text-sm" style="color: var(--text-primary);">
                        {{ $match->guestClub->{'short_name_' . $locale} ?? '—' }}
                    </p>
                </div>
            </div>

            {{-- Match Details --}}
            <div class="space-y-2 mb-4">
                <div class="flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" style="color: var(--text-muted);"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span style="color: var(--text-secondary);">{{ $match->start_at?->format('d.m.Y H:i') ?? '—' }}</span>
                </div>

                <div class="flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" style="color: var(--text-muted);"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span style="color: var(--text-secondary);">
                        {{ $match->city->{'title_' . $locale} ?? '' }}
                        @if($match->stadium)
                            ({{ $match->stadium->{'title_' . $locale} ?? '' }})
                        @endif
                    </span>
                </div>
            </div>
        @endif

        {{-- Judge Info --}}
        @if($judge)
            <div class="flex items-center gap-3 mb-4 p-3 rounded-lg" style="background: var(--color-success-light);">
                <div class="w-10 h-10 rounded-full flex items-center justify-center"
                     style="background: var(--color-primary);">
                    <svg class="w-5 h-5" style="color: #fff;"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-sm" style="color: var(--text-primary);">
                        {{ $judge->last_name ?? '' }} {{ $judge->first_name ?? '' }}
                    </p>
                    @if($judge->role)
                        <p class="text-xs" style="color: var(--text-secondary);">
                            {{ $judge->role->{'title_' . $locale} ?? '' }}
                        </p>
                    @endif
                </div>
            </div>
        @endif

        {{-- Trip Details --}}
        <div class="p-3 rounded-lg" style="background: var(--color-success-light);">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5" style="color: var(--color-success);"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 13l4 4L19 7"/>
                </svg>
                <span class="font-semibold" style="color: var(--color-success);">
                    {{ __('crud.trip_configured') }}
                </span>
            </div>
            <div class="text-sm space-y-1" style="color: var(--text-secondary);">
                @if($trip->transport_type)
                    <div>
                        <span class="font-medium">{{ __('crud.transport_type') }}:</span>
                        {{ $trip->transport_type->{'title_' . $locale} ?? '' }}
                    </div>
                @endif
                @if($trip->city)
                    <div>
                        <span class="font-medium">{{ __('crud.departure_city') }}:</span>
                        {{ $trip->city->{'title_' . $locale} ?? '' }}
                    </div>
                @endif
                @if($trip->arrival_city)
                    <div>
                        <span class="font-medium">{{ __('crud.arrival_city') }}:</span>
                        {{ $trip->arrival_city->{'title_' . $locale} ?? '' }}
                    </div>
                @endif
                @if($trip->info)
                    <div>
                        <span class="font-medium">{{ __('crud.logist_info') }}:</span>
                        {{ $trip->info }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Action Button --}}
    <div class="px-5 py-3" style="border-top: 1px solid var(--border-color);">
        <a href="{{ route('kff.kff-trip-detail', $trip->match_id) }}"
           class="w-full btn-secondary text-sm inline-flex items-center justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            {{ __('crud.view_details') }}
        </a>
    </div>
</div>
