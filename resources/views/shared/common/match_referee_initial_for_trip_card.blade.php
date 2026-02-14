@props(['match', 'locale' => 'ru'])

@php
$trip = $match->trips->first() ?? null;
$locale ??= app()->getLocale();
@endphp

<div class="card overflow-hidden hover:shadow-lg transition-shadow duration-200">
    {{-- Header with tournament badge --}}
    <div class="px-5 py-4 flex items-center justify-between" style="background: var(--bg-hover);">
        <span class="badge badge-info text-xs font-semibold">
            {{ $match->tournament->{'title_' . $locale} ?? '' }}
        </span>
        @if($match->operation)
            <span class="badge badge-warning text-xs">
                {{ $match->operation->{'title_' . $locale} ?? '' }}
            </span>
        @endif
    </div>

    {{-- Match Info --}}
    <div class="p-5">
        {{-- Teams --}}
        <div class="flex items-center justify-between gap-3 mb-4">
            <div class="flex-1 text-center">
                <div class="w-12 h-12 mx-auto mb-2 rounded-full flex items-center justify-center"
                     style="background: var(--color-primary-light);">
                    <svg class="w-6 h-6" style="color: var(--color-primary);"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
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
                    <svg class="w-6 h-6" style="color: var(--color-info);"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
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

            <div class="flex items-center gap-2 text-sm">
                <svg class="w-4 h-4 flex-shrink-0" style="color: var(--text-muted);"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7A2 2 0 0112 21H7a2 2 0 01-2-2V7z"/>
                </svg>
                <span style="color: var(--text-secondary);">{{ __('crud.round') }}: {{ $match->round ?? '—' }}</span>
            </div>
        </div>

        {{-- Trip Status --}}
        @if($trip && $trip->transport_type)
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
                    <div>
                        <span class="font-medium">{{ __('crud.transport_type') }}:</span>
                        {{ $trip->transport_type->{'title_' . $locale} ?? '' }}
                    </div>
                    @if($trip->city)
                        <div>
                            <span class="font-medium">{{ __('crud.departure_city') }}:</span>
                            {{ $trip->city->{'title_' . $locale} ?? '' }}
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="p-3 rounded-lg border-2 border-red-500" style="background: var(--color-danger-light);">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" style="color: var(--color-danger);"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span class="font-semibold" style="color: var(--color-danger);">
                        {{ __('crud.please_select_transport') }}
                    </span>
                </div>
            </div>
        @endif
    </div>

    {{-- Action Button --}}
    <div class="px-5 py-3" style="border-top: 1px solid var(--border-color);">
        <button wire:click="openTripModal({{ $match->id }})"
                class="w-full btn-primary text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
            {{ $trip ? __('crud.edit_trip') : __('crud.configure_trip') }}
        </button>
    </div>
</div>
