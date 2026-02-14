@php $locale = app()->getLocale(); @endphp

<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold" style="color: var(--text-primary);">
            {{ __('crud.trips') }}
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
                @include('shared.common.match_kff_trip_card', ['trip' => $trip, 'locale' => $locale])
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
                @include('shared.common.match_kff_initial_for_trip_card', ['match' => $match, 'locale' => $locale])
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

    {{-- Match Detail Modal --}}
    <x-modal wire:model="showMatchDetailModal" maxWidth="xl">
        <x-slot name="title">{{ __('crud.match_details') }}</x-slot>

        @if($selectedMatch)
            <div class="space-y-4">
                {{-- Match Info --}}
                <div class="p-4 rounded-lg" style="background: var(--bg-hover);">
                    <div class="flex items-center justify-between gap-4 mb-4">
                        <div class="flex items-center gap-3 flex-1">
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center"
                                     style="background: var(--color-primary-light);">
                                    <span class="font-bold text-sm" style="color: var(--color-primary);">
                                        {{ $selectedMatch->ownerClub->{'short_name_' . $locale} ?? '—' }}
                                    </span>
                                </div>
                                <div class="text-lg font-bold" style="color: var(--text-muted);">
                                    {{ __('crud.vs') }}
                                </div>
                            </div>
                            <div class="w-10 h-10 rounded-full flex items-center justify-center"
                                 style="background: var(--color-info-light);">
                                <span class="font-bold text-sm" style="color: var(--color-info);">
                                    {{ $selectedMatch->guestClub->{'short_name_' . $locale} ?? '—' }}
                                </span>
                            </div>
                        </div>
                        @if($selectedMatch->operation)
                            <span class="badge badge-warning">
                                {{ $selectedMatch->operation->{'title_' . $locale} ?? '' }}
                            </span>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span style="color: var(--text-muted);">{{ __('crud.tournament') }}:</span>
                            <span class="font-medium" style="color: var(--text-primary);">
                                {{ $selectedMatch->tournament->{'title_' . $locale} ?? '' }}
                            </span>
                        </div>
                        <div>
                            <span style="color: var(--text-muted);">{{ __('crud.season') }}:</span>
                            <span class="font-medium" style="color: var(--text-primary);">
                                {{ $selectedMatch->season->{'title_' . $locale} ?? '' }}
                            </span>
                        </div>
                        <div>
                            <span style="color: var(--text-muted);">{{ __('crud.start_at') }}:</span>
                            <span class="font-medium" style="color: var(--text-primary);">
                                {{ $selectedMatch->start_at?->format('d.m.Y H:i') ?? '—' }}
                            </span>
                        </div>
                        <div>
                            <span style="color: var(--text-muted);">{{ __('crud.city') }}:</span>
                            <span class="font-medium" style="color: var(--text-primary);">
                                {{ $selectedMatch->city->{'title_' . $locale} ?? '' }}
                            </span>
                        </div>
                        @if($selectedMatch->stadium)
                            <div class="col-span-2">
                                <span style="color: var(--text-muted);">{{ __('crud.stadium') }}:</span>
                                <span class="font-medium" style="color: var(--text-primary);">
                                    {{ $selectedMatch->stadium->{'title_' . $locale} ?? '' }}
                                </span>
                            </div>
                        @endif
                        <div>
                            <span style="color: var(--text-muted);">{{ __('crud.round') }}:</span>
                            <span class="font-medium" style="color: var(--text-primary);">
                                {{ $selectedMatch->round ?? '—' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Assigned Judges --}}
                @if($selectedMatch->match_judges && $selectedMatch->match_judges->isNotEmpty())
                    <div>
                        <h4 class="font-semibold mb-3" style="color: var(--text-primary);">
                            {{ __('crud.assigned_judges') }}
                        </h4>
                        <div class="space-y-2">
                            @foreach($selectedMatch->match_judges as $judge)
                                @if($judge->final_status == 1)
                                    <div class="flex items-center justify-between p-3 rounded-lg"
                                         style="background: var(--bg-hover);">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center"
                                                 style="background: var(--color-primary-light);">
                                                <svg class="w-5 h-5" style="color: var(--color-primary);"
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-medium text-sm" style="color: var(--text-primary);">
                                                    {{ $judge->user->last_name ?? '' }} {{ $judge->user->first_name ?? '' }}
                                                </p>
                                                <p class="text-xs" style="color: var(--text-muted);">
                                                    {{ $judge->judge_type->{'title_' . $locale} ?? '' }}
                                                </p>
                                            </div>
                                        </div>
                                        @if($judge->judge_response === 1 and $judge->final_status === 1 and $judge->is_actual == true)
                                            <span class="badge badge-success">
                                            {{ __('crud.judge_response_accepted') }}
                                        </span>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <x-slot name="footer">
            <button wire:click="closeMatchDetailModal" class="btn-secondary">{{ __('crud.close') }}</button>
        </x-slot>
    </x-modal>

    {{-- Trip Modal for Judge --}}
    <x-modal wire:model="showTripModal" maxWidth="md">
        <x-slot name="title">{{ __('crud.configure_trip_for_judge') }}</x-slot>

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

            {{-- Logist Info --}}
            <div>
                <label class="form-label">{{ __('crud.logist_info') }} ({{ __('crud.optional') }})</label>
                <textarea
                    wire:model.live="trip_logist_info"
                    class="form-input"
                    rows="3"
                    placeholder="{{ __('crud.logist_info_placeholder') }}"
                ></textarea>
            </div>
        </div>

        <x-slot name="footer">
            <button wire:click="closeTripModal" class="btn-secondary">{{ __('crud.cancel') }}</button>
            <button wire:click="saveTripForJudge" class="btn-primary">{{ __('crud.save') }}</button>
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
