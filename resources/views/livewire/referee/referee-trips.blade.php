@php $locale = app()->getLocale(); @endphp

<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold" style="color: var(--text-primary);">
            {{ __('ui.my_trips') }}
        </h2>

        <div class="flex items-center gap-3">
            {{-- Search input --}}
            <div class="relative">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    class="form-input pl-9 w-64"
                    placeholder="{{ __('crud.search') }}"
                />
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2" style="color: var(--text-muted);"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0118 0z"/>
                </svg>
            </div>

            {{-- Filter button --}}
            <button wire:click="toggleFilterModal" class="btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-2 2 2H3.293A1 1 0 003 6.586V4z"/>
                </svg>
                @if($filter_tournament_id || $filter_season_id)
                    <span class="ml-1 w-2 h-2 rounded-full" style="background: var(--color-primary);"></span>
                @endif
            </button>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex items-center gap-1 mb-6 overflow-x-auto" style="border-bottom: 2px solid var(--border-color);">
        <button
            wire:click="setTab('awaiting')"
            class="tab-item {{ $activeTab === 'awaiting' ? 'tab-item-active' : '' }}"
        >
            {{ __('crud.tab_awaiting_confirmation') }}
            <span class="tab-badge {{ $activeTab === 'awaiting' ? 'tab-badge-active' : '' }}">{{ $awaitingCount }}</span>
        </button>

        <button
            wire:click="setTab('my_trips')"
            class="tab-item {{ $activeTab === 'my_trips' ? 'tab-item-active' : '' }}"
        >
            {{ __('crud.tab_my_trips') }}
            <span class="tab-badge {{ $activeTab === 'my_trips' ? 'tab-badge-active' : '' }}">{{ $myTripsCount }}</span>
        </button>
    </div>

    {{-- Content --}}
    @if($activeTab === 'my_trips')
        {{-- My Trips --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($trips as $trip)
                @include('shared.common.match_trip_card', ['trip' => $trip, 'locale' => $locale])
            @empty
                <div class="col-span-full card p-8 text-center">
                    <p style="color: var(--text-muted);">{{ __('crud.no_results') }}</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($trips->hasPages())
            <div class="mt-6">
                {{ $trips->links() }}
            </div>
        @endif
    @else
        {{-- Awaiting Confirmation --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($matches as $match)
                @include('shared.common.match_referee_initial_for_trip_card', ['match' => $match, 'locale' => $locale])
            @empty
                <div class="col-span-full card p-8 text-center">
                    <p style="color: var(--text-muted);">{{ __('crud.no_results') }}</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
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
            {{-- Tournament --}}
            <div>
                <label class="form-label">{{ __('crud.tournament') }}</label>
                <select wire:model.live="filter_tournament_id" class="form-input">
                    <option value="">{{ __('crud.all_tournaments') }}</option>
                    @foreach($tournaments as $tournament)
                        <option value="{{ $tournament->id }}">{{ $tournament->{'title_' . $locale} }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Season --}}
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
            {{-- Transport Type --}}
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

            {{-- Departure City --}}
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

            {{-- Comment --}}
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
            <button wire:click="saveTrip" class="btn-primary">{{ __('crud.save') }}</button>
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
