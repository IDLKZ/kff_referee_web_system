@php $locale = app()->getLocale(); @endphp
@section('page-title', __('ui.referee_request'))

<div>
    {{-- Page Header --}}
    <div class="card p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0"
                     style="background: var(--color-primary-light);">
                    <svg class="w-6 h-6" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold" style="color: var(--text-primary);">
                        {{ __('ui.referee_request') }}
                    </h1>
                    <p class="text-sm mt-0.5" style="color: var(--text-muted);">
                        {{ __('crud.total') }}: {{ $waitingCount + $acceptedCount + $declinedCount }}
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
                    @if($filter_tournament_id || $filter_season_id || $filter_club_id)
                        <span class="absolute -top-1 -right-1 w-3 h-3 rounded-full"
                              style="background: var(--color-primary); border: 2px solid var(--bg-card);"></span>
                    @endif
                </button>
            </div>
        </div>
    </div>

    {{-- Stats Summary --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <button wire:click="setTab('waiting')"
                class="card p-4 text-center transition-all duration-200 cursor-pointer"
                style="{{ $activeTab === 'waiting' ? 'border: 2px solid var(--color-warning); box-shadow: 0 0 0 3px var(--color-warning-light);' : 'border: 2px solid transparent;' }}">
            <div class="w-10 h-10 rounded-full flex items-center justify-center mx-auto mb-2"
                 style="background: var(--color-warning-light);">
                <svg class="w-5 h-5" style="color: var(--color-warning);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color: var(--text-primary);">{{ $waitingCount }}</p>
            <p class="text-xs font-medium mt-1" style="color: var(--text-muted);">{{ __('ui.tab_waiting_response') }}</p>
        </button>

        <button wire:click="setTab('accepted')"
                class="card p-4 text-center transition-all duration-200 cursor-pointer"
                style="{{ $activeTab === 'accepted' ? 'border: 2px solid var(--color-success); box-shadow: 0 0 0 3px var(--color-success-light);' : 'border: 2px solid transparent;' }}">
            <div class="w-10 h-10 rounded-full flex items-center justify-center mx-auto mb-2"
                 style="background: var(--color-success-light);">
                <svg class="w-5 h-5" style="color: var(--color-success);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color: var(--text-primary);">{{ $acceptedCount }}</p>
            <p class="text-xs font-medium mt-1" style="color: var(--text-muted);">{{ __('ui.tab_accepted') }}</p>
        </button>

        <button wire:click="setTab('declined')"
                class="card p-4 text-center transition-all duration-200 cursor-pointer"
                style="{{ $activeTab === 'declined' ? 'border: 2px solid var(--color-danger); box-shadow: 0 0 0 3px var(--color-danger-light);' : 'border: 2px solid transparent;' }}">
            <div class="w-10 h-10 rounded-full flex items-center justify-center mx-auto mb-2"
                 style="background: var(--color-danger-light);">
                <svg class="w-5 h-5" style="color: var(--color-danger);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-2xl font-bold" style="color: var(--text-primary);">{{ $declinedCount }}</p>
            <p class="text-xs font-medium mt-1" style="color: var(--text-muted);">{{ __('ui.tab_declined') }}</p>
        </button>
    </div>

    {{-- Match cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($matches as $match)
            @include('shared.common.match_card_for_referee_request', ['match' => $match])
        @empty
            <div class="col-span-full card p-12 text-center">
                <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4"
                     style="background: var(--bg-hover);">
                    <svg class="w-8 h-8" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p class="font-medium mb-1" style="color: var(--text-secondary);">{{ __('crud.no_results') }}</p>
                <p class="text-sm" style="color: var(--text-muted);">
                    @if($activeTab === 'waiting')
                        {{ __('crud.no_pending_requests') }}
                    @else
                        {{ __('crud.no_results') }}
                    @endif
                </p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($matches->hasPages())
        <div class="mt-6">
            {{ $matches->links() }}
        </div>
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

            <div>
                <label class="form-label">{{ __('crud.club') }}</label>
                <select wire:model.live="filter_club_id" class="form-input">
                    <option value="">{{ __('crud.all_clubs') }}</option>
                    @foreach($clubs as $club)
                        <option value="{{ $club->id }}">{{ $club->{'short_name_' . $locale} }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <x-slot name="footer">
            <button wire:click="clearFilters" class="btn-secondary">{{ __('crud.clear') }}</button>
            <button wire:click="applyFilters" class="btn-primary">{{ __('crud.apply') }}</button>
        </x-slot>
    </x-modal>
</div>
