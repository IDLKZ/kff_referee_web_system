@section('page-title', __('ui.tournaments'))

<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold" style="color: var(--text-primary);">
            {{ __('ui.tournaments') }}
        </h2>

        <div class="flex items-center gap-3">
            {{-- Search & Filter button --}}
            <button wire:click="toggleSearchModal" class="btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                {{ __('crud.search_filter') }}
                @if($search || $filter_country_id || $filter_sex !== null || $filter_is_active !== null)
                    <span class="ml-1 w-2 h-2 rounded-full" style="background: var(--color-primary);"></span>
                @endif
            </button>

            {{-- Create button --}}
            @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::TOURNAMENTS_CREATE))
                <button
                    wire:click="$dispatch('openModal', { component: 'admin.tournament.tournament-form-modal' })"
                    class="btn-primary"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('crud.create') }}
                </button>
            @endif
        </div>
    </div>

    {{-- Table --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="cursor-pointer hover:bg-opacity-80 transition-colors" wire:click="sortBy('id')">
                            <div class="flex items-center gap-1">
                                ID
                                @if($sortField === 'id')
                                    <svg class="w-3 h-3" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th></th>
                        <th class="cursor-pointer hover:bg-opacity-80 transition-colors" wire:click="sortBy('title_ru')">
                            <div class="flex items-center gap-1">
                                {{ __('crud.title_ru') }}
                                @if($sortField === 'title_ru')
                                    <svg class="w-3 h-3" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th>{{ __('crud.country') }}</th>
                        <th class="cursor-pointer hover:bg-opacity-80 transition-colors" wire:click="sortBy('value')">
                            <div class="flex items-center gap-1">
                                {{ __('crud.value') }}
                                @if($sortField === 'value')
                                    <svg class="w-3 h-3" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="cursor-pointer hover:bg-opacity-80 transition-colors" wire:click="sortBy('level')">
                            <div class="flex items-center gap-1">
                                {{ __('crud.level') }}
                                @if($sortField === 'level')
                                    <svg class="w-3 h-3" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th>{{ __('crud.sex') }}</th>
                        <th class="cursor-pointer hover:bg-opacity-80 transition-colors" wire:click="sortBy('is_active')">
                            <div class="flex items-center gap-1">
                                {{ __('crud.status') }}
                                @if($sortField === 'is_active')
                                    <svg class="w-3 h-3" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th>{{ __('crud.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tournaments as $tournament)
                        <tr wire:key="tournament-{{ $tournament->id }}">
                            <td style="color: var(--text-muted);">{{ $tournament->id }}</td>
                            <td>
                                @if($tournament->file_id && $tournament->file && \Illuminate\Support\Facades\Storage::disk('uploads')->exists($tournament->file->file_path))
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('uploads')->url($tournament->file->file_path) }}"
                                         alt="{{ $tournament->title_ru }}"
                                         class="w-10 h-10 rounded object-cover"
                                         style="background: var(--bg-hover);">
                                @else
                                    <div class="w-10 h-10 rounded flex items-center justify-center"
                                         style="background: var(--bg-hover); color: var(--text-muted);">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="font-medium">{{ $tournament->title_ru }}</td>
                            <td>
                                @if($tournament->country)
                                    {{ $tournament->country->title_ru }}
                                @else
                                    <span style="color: var(--text-muted);">—</span>
                                @endif
                            </td>
                            <td>
                                <code class="text-xs px-1.5 py-0.5 rounded"
                                      style="background: var(--bg-hover); color: var(--text-secondary);">
                                    {{ $tournament->value }}
                                </code>
                            </td>
                            <td>{{ $tournament->level }}</td>
                            <td>
                                @if($tournament->sex === 1)
                                    {{ __('crud.sex_male') }}
                                @elseif($tournament->sex === 2)
                                    {{ __('crud.sex_female') }}
                                @else
                                    <span style="color: var(--text-muted);">{{ __('crud.sex_not_specified') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($tournament->is_active)
                                    <span class="badge badge-success">{{ __('crud.active') }}</span>
                                @else
                                    <span class="badge badge-danger">{{ __('crud.inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::TOURNAMENTS_UPDATE))
                                        <button
                                            wire:click="$dispatch('openModal', { component: 'admin.tournament.tournament-form-modal', arguments: { tournamentId: {{ $tournament->id }} } })"
                                            class="btn-icon btn-icon-edit"
                                            title="{{ __('crud.edit') }}"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                    @endif

                                    @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::TOURNAMENTS_DELETE))
                                        <button
                                            wire:click="$dispatch('openModal', { component: 'admin.tournament.tournament-delete-modal', arguments: { tournamentId: {{ $tournament->id }} } })"
                                            class="btn-icon btn-icon-delete"
                                            title="{{ __('crud.delete') }}"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-8" style="color: var(--text-muted);">
                                {{ __('crud.no_results') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($tournaments->hasPages())
            <div class="px-6 py-4" style="border-top: 1px solid var(--border-color);">
                {{ $tournaments->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>

    {{-- Search & Filter Modal --}}
    <x-modal wire:model="showSearchModal" maxWidth="md">
        <x-slot name="title">
            {{ __('crud.search_filter') }}
        </x-slot>

        <div class="space-y-4">
            {{-- Search --}}
            <div>
                <label class="form-label">{{ __('crud.search') }}</label>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="{{ __('crud.search_placeholder') }}"
                    class="form-input"
                >
            </div>

            {{-- Filter by Country --}}
            <div>
                <label class="form-label">{{ __('crud.country') }}</label>
                <select wire:model.live="filter_country_id" class="form-input">
                    <option value="">{{ __('crud.all_countries') }}</option>
                    @foreach($this->getCountryOptions() as $id => $title)
                        <option value="{{ $id }}">{{ $title }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter by Sex --}}
            <div>
                <label class="form-label">{{ __('crud.sex') }}</label>
                <select wire:model.live="filter_sex" class="form-input">
                    <option value="">{{ __('crud.all') }}</option>
                    @foreach($this->getSexOptions() as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter by Status --}}
            <div>
                <label class="form-label">{{ __('crud.status') }}</label>
                <select wire:model.live="filter_is_active" class="form-input">
                    <option value="">{{ __('crud.all') }}</option>
                    <option value="1">{{ __('crud.active') }}</option>
                    <option value="0">{{ __('crud.inactive') }}</option>
                </select>
            </div>

            {{-- Sorting options --}}
            <div>
                <label class="form-label">{{ __('crud.sort_by') }}</label>
                <div class="grid grid-cols-2 gap-2">
                    <button wire:click="sortBy('id')"
                            class="text-left px-3 py-2 rounded-md text-sm transition-colors {{ $sortField === 'id' ? 'btn-primary' : 'btn-secondary' }}">
                        {{ __('crud.id') }}
                        @if($sortField === 'id')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </button>
                    <button wire:click="sortBy('title_ru')"
                            class="text-left px-3 py-2 rounded-md text-sm transition-colors {{ $sortField === 'title_ru' ? 'btn-primary' : 'btn-secondary' }}">
                        {{ __('crud.title_ru') }}
                        @if($sortField === 'title_ru')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </button>
                    <button wire:click="sortBy('value')"
                            class="text-left px-3 py-2 rounded-md text-sm transition-colors {{ $sortField === 'value' ? 'btn-primary' : 'btn-secondary' }}">
                        {{ __('crud.value') }}
                        @if($sortField === 'value')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </button>
                    <button wire:click="sortBy('level')"
                            class="text-left px-3 py-2 rounded-md text-sm transition-colors {{ $sortField === 'level' ? 'btn-primary' : 'btn-secondary' }}">
                        {{ __('crud.level') }}
                        @if($sortField === 'level')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </button>
                    <button wire:click="sortBy('is_active')"
                            class="text-left px-3 py-2 rounded-md text-sm transition-colors {{ $sortField === 'is_active' ? 'btn-primary' : 'btn-secondary' }}">
                        {{ __('crud.status') }}
                        @if($sortField === 'is_active')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </button>
                </div>
            </div>
        </div>

        <x-slot name="footer">
            <button wire:click="clearFilters" class="btn-secondary">
                {{ __('crud.clear') }}
            </button>
            <button wire:click="$set('showSearchModal', false)" class="btn-primary">
                {{ __('crud.apply') }}
            </button>
        </x-slot>
    </x-modal>
</div>
