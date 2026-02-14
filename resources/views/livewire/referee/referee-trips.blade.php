@php $locale = app()->getLocale(); @endphp

<div>
    {{-- Page Header --}}
    <div class="card p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0"
                     style="background: var(--color-info-light);">
                    <svg class="w-6 h-6" style="color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold" style="color: var(--text-primary);">
                        {{ __('ui.my_trips') }}
                    </h1>
                    <p class="text-sm mt-0.5" style="color: var(--text-muted);">
                        {{ __('crud.total') }}: {{ $awaitingCount + $myTripsCount }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                {{-- Search input --}}
                <div class="relative">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        class="form-input pl-10 w-64"
                        placeholder="{{ __('crud.search') }}..."
                    />
                    <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2" style="color: var(--text-muted);"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0118 0z"/>
                    </svg>
                </div>

                {{-- Filter button --}}
                <button wire:click="toggleFilterModal"
                        class="btn-secondary relative"
                        style="padding: 0.625rem;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-2 2 2H3.293A1 1 0 003 6.586V4z"/>
                    </svg>
                    @if($filter_tournament_id || $filter_season_id)
                        <span class="absolute -top-1 -right-1 w-3 h-3 rounded-full"
                              style="background: var(--color-primary); border: 2px solid var(--bg-card);"></span>
                    @endif
                </button>
            </div>
        </div>
    </div>

    {{-- Stats Tabs --}}
    <div class="grid grid-cols-2 gap-4 mb-6">
        <button wire:click="setTab('awaiting')"
                class="card p-4 transition-all duration-200 cursor-pointer"
                style="{{ $activeTab === 'awaiting' ? 'border: 2px solid var(--color-warning); box-shadow: 0 0 0 3px var(--color-warning-light);' : 'border: 2px solid transparent;' }}">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0"
                     style="background: var(--color-warning-light);">
                    <svg class="w-6 h-6" style="color: var(--color-warning);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-left">
                    <p class="text-2xl font-bold" style="color: var(--text-primary);">{{ $awaitingCount }}</p>
                    <p class="text-xs font-medium" style="color: var(--text-muted);">{{ __('crud.tab_awaiting_confirmation') }}</p>
                </div>
            </div>
        </button>

        <button wire:click="setTab('my_trips')"
                class="card p-4 transition-all duration-200 cursor-pointer"
                style="{{ $activeTab === 'my_trips' ? 'border: 2px solid var(--color-success); box-shadow: 0 0 0 3px var(--color-success-light);' : 'border: 2px solid transparent;' }}">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0"
                     style="background: var(--color-success-light);">
                    <svg class="w-6 h-6" style="color: var(--color-success);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-left">
                    <p class="text-2xl font-bold" style="color: var(--text-primary);">{{ $myTripsCount }}</p>
                    <p class="text-xs font-medium" style="color: var(--text-muted);">{{ __('crud.tab_my_trips') }}</p>
                </div>
            </div>
        </button>
    </div>

    {{-- Content --}}
    @if($activeTab === 'my_trips')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($trips as $trip)
                @include('shared.common.match_trip_card', ['trip' => $trip, 'locale' => $locale])
            @empty
                <div class="col-span-full card p-12 text-center">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4"
                         style="background: var(--bg-hover);">
                        <svg class="w-8 h-8" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                        </svg>
                    </div>
                    <p class="font-medium mb-1" style="color: var(--text-secondary);">{{ __('crud.no_results') }}</p>
                </div>
            @endforelse
        </div>

        @if($trips->hasPages())
            <div class="mt-6">
                {{ $trips->links() }}
            </div>
        @endif
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($matches as $match)
                @include('shared.common.match_referee_initial_for_trip_card', ['match' => $match, 'locale' => $locale])
            @empty
                <div class="col-span-full card p-12 text-center">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4"
                         style="background: var(--bg-hover);">
                        <svg class="w-8 h-8" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="font-medium mb-1" style="color: var(--text-secondary);">{{ __('crud.no_results') }}</p>
                </div>
            @endforelse
        </div>

        @if($matches->hasPages())
            <div class="mt-6">
                {{ $matches->links() }}
            </div>
        @endif
    @endif

    {{-- Filter Modal --}}
    <x-modal wire:model="showFilterModal" maxWidth="md">
        <x-slot name="title">{{ __('crud.search_filter') }}</x-slot>

        <div class="space-y-4">
            <div>
                <label class="form-label">{{ __('crud.tournament') }}</label>
                <select wire:model.live="filter_tournament_id" class="form-input">
                    <option value="">{{ __('crud.all_tournaments') }}</option>
                    @foreach($tournaments as $tournament)
                        <option value="{{ $tournament->id }}">{{ $tournament->{'title_' . $locale} }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">{{ __('crud.season') }}</label>
                <select wire:model.live="filter_season_id" class="form-input">
                    <option value="">{{ __('crud.all_seasons') }}</option>
                    @foreach($seasons as $season)
                        <option value="{{ $season->id }}">{{ $season->{'title_' . $locale} }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <x-slot name="footer">
            <button wire:click="clearFilters" class="btn-secondary">{{ __('crud.clear') }}</button>
            <button wire:click="applyFilters" class="btn-primary">{{ __('crud.apply') }}</button>
        </x-slot>
    </x-modal>

    {{-- Trip Modal --}}
    <x-modal wire:model="showTripModal" maxWidth="md">
        <x-slot name="title">{{ __('crud.trip_details') }}</x-slot>

        <div class="space-y-4">
            <div>
                <label class="form-label">{{ __('crud.transport_type') }} <span class="text-red-500">*</span></label>
                <select wire:model.live="trip_transport_type_id" class="form-input @error('trip_transport_type_id') is-invalid @enderror">
                    <option value="">{{ __('crud.select_transport_type') }}</option>
                    @foreach($transportTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->{'title_' . $locale} }}</option>
                    @endforeach
                </select>
                @error('trip_transport_type_id')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label">{{ __('crud.departure_city') }} <span class="text-red-500">*</span></label>
                <select wire:model.live="trip_departure_city_id" class="form-input @error('trip_departure_city_id') is-invalid @enderror">
                    <option value="">{{ __('crud.select_departure_city') }}</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}">{{ $city->{'title_' . $locale} }}</option>
                    @endforeach
                </select>
                @error('trip_departure_city_id')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="form-label">{{ __('crud.judge_comment') }} ({{ __('crud.optional') }})</label>
                <textarea
                    wire:model.live="trip_judge_comment"
                    class="form-input"
                    rows="3"
                    placeholder="{{ __('crud.comment_placeholder') }}"
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
