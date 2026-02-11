@section('page-title', __('crud.club_stadiums'))

<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold" style="color: var(--text-primary);">
            {{ __('crud.club_stadiums') }}
        </h2>

        <div class="flex items-center gap-3">
            {{-- Search & Filter button --}}
            <button wire:click="toggleSearchModal" class="btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                {{ __('crud.search_filter') }}
                @if($search || $filterClub || $filterStadium)
                    <span class="ml-1 w-2 h-2 rounded-full" style="background: var(--color-primary);"></span>
                @endif
            </button>

            {{-- Create button --}}
            @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::CLUB_STADIUMS_CREATE))
                <button wire:click="openCreateModal" class="btn-primary">
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
                        <th class="cursor-pointer hover:bg-opacity-80 transition-colors" wire:click="sortBy('club_id')">
                            <div class="flex items-center gap-1">
                                {{ __('crud.club') }}
                                @if($sortField === 'club_id')
                                    <svg class="w-3 h-3" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="cursor-pointer hover:bg-opacity-80 transition-colors" wire:click="sortBy('stadium_id')">
                            <div class="flex items-center gap-1">
                                {{ __('crud.stadium') }}
                                @if($sortField === 'stadium_id')
                                    <svg class="w-3 h-3" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th>{{ __('crud.city') }}</th>
                        <th>{{ __('crud.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clubStadiums as $clubStadium)
                        <tr wire:key="cs-{{ $clubStadium->club_id }}-{{ $clubStadium->stadium_id }}">
                            <td class="font-medium">
                                {{ $clubStadium->club->short_name_ru }}
                                @if($clubStadium->club->full_name_ru !== $clubStadium->club->short_name_ru)
                                    <div class="text-xs" style="color: var(--text-muted);">
                                        {{ $clubStadium->club->full_name_ru }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                {{ $clubStadium->stadium->title_ru }}
                                @if($clubStadium->stadium->city)
                                    <span class="text-xs ml-2" style="color: var(--text-muted);">
                                        ({{ $clubStadium->stadium->city->title_ru }})
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($clubStadium->stadium->city)
                                    <span class="badge" style="background: var(--bg-hover); color: var(--text-secondary);">
                                        {{ $clubStadium->stadium->city->title_ru }}
                                    </span>
                                @else
                                    <span style="color: var(--text-muted);">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::CLUB_STADIUMS_UPDATE))
                                        <button wire:click="openEditModal({{ $clubStadium->club_id }}, {{ $clubStadium->stadium_id }})"
                                                class="btn-icon btn-icon-edit"
                                                title="{{ __('crud.edit') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                    @endif

                                    @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::CLUB_STADIUMS_DELETE))
                                        <button wire:click="confirmDelete({{ $clubStadium->club_id }}, {{ $clubStadium->stadium_id }})"
                                                class="btn-icon btn-icon-delete"
                                                title="{{ __('crud.delete') }}">
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
                            <td colspan="4" class="text-center py-8" style="color: var(--text-muted);">
                                {{ __('crud.no_results') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($clubStadiums->hasPages())
            <div class="px-6 py-4" style="border-top: 1px solid var(--border-color);">
                {{ $clubStadiums->links('vendor.pagination.tailwind') }}
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

            {{-- Filter by Club --}}
            <div>
                <label class="form-label">{{ __('crud.club') }}</label>
                <select wire:model.live="filterClub" class="form-input">
                    <option value="">{{ __('crud.all_clubs') }}</option>
                    @foreach($this->getClubOptions() as $id => $title)
                        <option value="{{ $id }}">{{ $title }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter by Stadium --}}
            <div>
                <label class="form-label">{{ __('crud.stadium') }}</label>
                <select wire:model.live="filterStadium" class="form-input">
                    <option value="">{{ __('crud.all_stadiums') }}</option>
                    @foreach($this->getStadiumOptions() as $id => $title)
                        <option value="{{ $id }}">{{ $title }}</option>
                    @endforeach
                </select>
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

    {{-- Create / Edit Modal --}}
    <x-modal wire:model="showFormModal" maxWidth="md">
        <x-slot name="title">
            @if($managingClubId)
                {{ __('crud.manage_club_stadiums') }}
            @elseif($isEditing)
                {{ __('crud.edit_club_stadium') }}
            @else
                {{ __('crud.create_club_stadium') }}
            @endif
        </x-slot>

        @if($managingClubId)
            {{-- Manage Multiple Stadiums for a Club --}}
            <div class="space-y-4">
                <p class="text-sm" style="color: var(--text-secondary);">
                    {{ __('crud.select_stadiums_for_club') }}: <strong>{{ \App\Models\Club::find($managingClubId)?->short_name_ru }}</strong>
                </p>

                <div class="max-h-64 overflow-y-auto border rounded-md p-2" style="border-color: var(--border-color);">
                    @foreach($this->getStadiumOptions() as $id => $title)
                        <label class="flex items-center gap-2 p-2 hover:bg-gray-50 dark:hover:bg-gray-800 rounded cursor-pointer">
                            <input type="checkbox"
                                   value="{{ $id }}"
                                   wire:model.live="selectedStadiums"
                                   class="w-4 h-4 rounded" style="accent-color: var(--color-primary);">
                            <span>{{ $title }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <x-slot name="footer">
                <button wire:click="$set('showFormModal', false)" class="btn-secondary">
                    {{ __('crud.cancel') }}
                </button>
                <button wire:click="saveClubStadiums" class="btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="saveClubStadiums">{{ __('crud.save') }}</span>
                    <span wire:loading wire:target="saveClubStadiums">{{ __('ui.loading') }}</span>
                </button>
            </x-slot>
        @else
            {{-- Single Club-Stadium Association --}}
            <form wire:submit="save" class="space-y-4">
                {{-- Club --}}
                <div>
                    <label class="form-label">{{ __('crud.club') }} <span style="color:var(--color-danger);">*</span></label>
                    <select wire:model="club_id" class="form-input">
                        <option value="">{{ __('crud.select_club') }}</option>
                        @foreach($this->getClubOptions() as $id => $title)
                            <option value="{{ $id }}">{{ $title }}</option>
                        @endforeach
                    </select>
                    @error('club_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Stadium --}}
                <div>
                    <label class="form-label">{{ __('crud.stadium') }} <span style="color:var(--color-danger);">*</span></label>
                    <select wire:model="stadium_id" class="form-input">
                        <option value="">{{ __('crud.select_stadium') }}</option>
                        @foreach($this->getStadiumOptions() as $id => $title)
                            <option value="{{ $id }}">{{ $title }}</option>
                        @endforeach
                    </select>
                    @error('stadium_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </form>

            <x-slot name="footer">
                <button wire:click="$set('showFormModal', false)" class="btn-secondary">
                    {{ __('crud.cancel') }}
                </button>
                <button wire:click="save" class="btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">{{ __('crud.save') }}</span>
                    <span wire:loading wire:target="save">{{ __('ui.loading') }}</span>
                </button>
            </x-slot>
        @endif
    </x-modal>

    {{-- Delete Confirmation Modal --}}
    <x-modal wire:model="showDeleteModal" maxWidth="sm">
        <x-slot name="title">
            {{ __('crud.confirm_delete') }}
        </x-slot>

        <div class="text-center">
            <div class="mx-auto flex items-center justify-center w-12 h-12 rounded-full mb-4"
                 style="background: var(--color-danger-light);">
                <svg class="w-6 h-6" style="color: var(--color-danger);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <p style="color: var(--text-secondary);">
                {{ __('crud.confirm_delete_text') }}
            </p>
            <p class="mt-2 font-semibold" style="color: var(--text-primary);">
                {{ $deletingName }}
            </p>
        </div>

        <x-slot name="footer">
            <button wire:click="$set('showDeleteModal', false)" class="btn-secondary">
                {{ __('crud.cancel') }}
            </button>
            <button wire:click="delete" class="btn-danger" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="delete">{{ __('crud.yes_delete') }}</span>
                <span wire:loading wire:target="delete">{{ __('ui.loading') }}</span>
            </button>
        </x-slot>
    </x-modal>
</div>
