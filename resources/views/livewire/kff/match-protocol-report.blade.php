@section('page-title', __('ui.protocol_review'))

<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold" style="color: var(--text-primary);">
            {{ __('ui.protocol_review') }}
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
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            {{-- Filter button --}}
            <button wire:click="toggleFilterModal" class="btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                @if($filter_tournament_id || $filter_season_id || $search)
                    <span class="ml-1 w-2 h-2 rounded-full" style="background: var(--color-primary);"></span>
                @endif
            </button>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex items-center gap-1 mb-6 overflow-x-auto" style="border-bottom: 2px solid var(--border-color);">
        <button
            wire:click="setTab('waiting')"
            class="tab-item {{ $activeTab === 'waiting' ? 'tab-item-active' : '' }}"
        >
            {{ __('crud.tab_waiting_review') }}
            <span class="tab-badge {{ $activeTab === 'waiting' ? 'tab-badge-active' : '' }}">{{ $waitingCount }}</span>
        </button>

        <button
            wire:click="setTab('reprocessing')"
            class="tab-item {{ $activeTab === 'reprocessing' ? 'tab-item-active' : '' }}"
        >
            {{ __('crud.tab_reprocessing') }}
            <span class="tab-badge {{ $activeTab === 'reprocessing' ? 'tab-badge-active' : '' }}">{{ $reprocessingCount }}</span>
        </button>

        <button
            wire:click="setTab('all')"
            class="tab-item {{ $activeTab === 'all' ? 'tab-item-active' : '' }}"
        >
            {{ __('crud.tab_all_reports') }}
            <span class="tab-badge {{ $activeTab === 'all' ? 'tab-badge-active' : '' }}">{{ $allCount }}</span>
        </button>
    </div>

    {{-- Report cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($reports as $report)
            @include('shared.common.match_report_card', ['report' => $report])
        @empty
            <div class="col-span-full card p-8 text-center">
                <p style="color: var(--text-muted);">{{ __('crud.no_results') }}</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($reports->hasPages())
        <div class="mt-6">
            {{ $reports->links() }}
        </div>
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
                        <option value="{{ $tournament->id }}">{{ $tournament->{'title_' . app()->getLocale()} }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Season --}}
            <div>
                <label class="form-label">{{ __('crud.season') }}</label>
                <select wire:model.live="filter_season_id" class="form-input">
                    <option value="">{{ __('crud.all_seasons') }}</option>
                    @foreach($seasons as $season)
                        <option value="{{ $season->id }}">{{ $season->{'title_' . app()->getLocale()} }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Sort --}}
            <div>
                <label class="form-label">{{ __('crud.sort_by') }}</label>
                <select wire:model="sort_field" class="form-input">
                    <option value="created_at">{{ __('crud.created_at') }}</option>
                    <option value="updated_at">{{ __('crud.updated_at') }}</option>
                </select>
            </div>

            {{-- Sort direction --}}
            <div>
                <label class="form-label">{{ __('crud.sort_direction') }}</label>
                <select wire:model="sort_direction" class="form-input">
                    <option value="desc">{{ __('crud.descending') }}</option>
                    <option value="asc">{{ __('crud.ascending') }}</option>
                </select>
            </div>
        </div>

        <x-slot name="footer">
            <button wire:click="clearFilters" class="btn-secondary">{{ __('crud.clear') }}</button>
            <button wire:click="applyFilters" class="btn-primary">{{ __('crud.apply') }}</button>
        </x-slot>
    </x-modal>
</div>
