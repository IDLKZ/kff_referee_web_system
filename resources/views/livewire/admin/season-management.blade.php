@section('page-title', __('ui.seasons'))

<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold" style="color: var(--text-primary);">
            {{ __('ui.seasons') }}
        </h2>

        <div class="flex items-center gap-3">
            {{-- Search & Filter button --}}
            <button wire:click="toggleSearchModal" class="btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                {{ __('crud.search_filter') }}
                @if($search)
                    <span class="ml-1 w-2 h-2 rounded-full" style="background: var(--color-primary);"></span>
                @endif
            </button>

            {{-- Create button --}}
            @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::SEASONS_CREATE))
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
                        <th>{{ __('crud.title_kk') }}</th>
                        <th>{{ __('crud.title_en') }}</th>
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
                        <th>{{ __('crud.period') }}</th>
                        <th>{{ __('crud.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($seasons as $season)
                        <tr wire:key="season-{{ $season->id }}">
                            <td style="color: var(--text-muted);">{{ $season->id }}</td>
                            <td class="font-medium">{{ $season->title_ru }}</td>
                            <td>{{ $season->title_kk ?? '—' }}</td>
                            <td>{{ $season->title_en ?? '—' }}</td>
                            <td>
                                <code class="text-xs px-1.5 py-0.5 rounded"
                                      style="background: var(--bg-hover); color: var(--text-secondary);">
                                    {{ $season->value }}
                                </code>
                            </td>
                            <td>
                                @if($season->start_at || $season->end_at)
                                    <span class="text-xs" style="color: var(--text-secondary);">
                                        {{ $season->start_at?->format('d.m.Y') }} — {{ $season->end_at?->format('d.m.Y') }}
                                    </span>
                                @else
                                    <span style="color: var(--text-muted);">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::SEASONS_UPDATE))
                                        <button wire:click="openEditModal({{ $season->id }})"
                                                class="btn-icon btn-icon-edit"
                                                title="{{ __('crud.edit') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                    @endif

                                    @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::SEASONS_DELETE))
                                        <button wire:click="confirmDelete({{ $season->id }})"
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
                            <td colspan="7" class="text-center py-8" style="color: var(--text-muted);">
                                {{ __('crud.no_results') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($seasons->hasPages())
            <div class="px-6 py-4" style="border-top: 1px solid var(--border-color);">
                {{ $seasons->links('vendor.pagination.tailwind') }}
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

    {{-- Create / Edit Modal --}}
    <x-modal wire:model="showFormModal" maxWidth="lg">
        <x-slot name="title">
            {{ $isEditing ? __('crud.edit_season') : __('crud.create_season') }}
        </x-slot>

        <form wire:submit="save">
            {{-- Titles --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                {{-- Title RU --}}
                <div>
                    <label class="form-label">{{ __('crud.title_ru') }} <span style="color:var(--color-danger);">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold" style="color: var(--text-muted);">RU</span>
                        <input type="text" wire:model="title_ru"
                               class="form-input pl-9 @error('title_ru') is-invalid @enderror">
                    </div>
                    @error('title_ru') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Title KK --}}
                <div>
                    <label class="form-label">{{ __('crud.title_kk') }}</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold" style="color: var(--text-muted);">KK</span>
                        <input type="text" wire:model="title_kk"
                               class="form-input pl-9 @error('title_kk') is-invalid @enderror">
                    </div>
                    @error('title_kk') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Title EN --}}
                <div>
                    <label class="form-label">{{ __('crud.title_en') }}</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold" style="color: var(--text-muted);">EN</span>
                        <input type="text" wire:model="title_en"
                               class="form-input pl-9 @error('title_en') is-invalid @enderror">
                    </div>
                    @error('title_en') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Value and Dates --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                {{-- Value --}}
                <div>
                    <label class="form-label">{{ __('crud.value') }} <span style="color:var(--color-danger);">*</span></label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                        </svg>
                        <input type="text" wire:model="value"
                               class="form-input pl-10 @error('value') is-invalid @enderror"
                               {{ $isEditing ? 'disabled' : '' }}
                               placeholder="{{ __('crud.season_value_placeholder') }}">
                    </div>
                    @error('value') <p class="form-error">{{ $message }}</p> @enderror
                    @if(!$isEditing)
                        <p class="text-xs mt-1 flex items-center gap-1" style="color: var(--text-muted);">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('crud.season_value_hint') }}
                        </p>
                    @endif
                </div>

                {{-- Start At --}}
                <div>
                    <label class="form-label">{{ __('crud.start_at') }}</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <input type="date" wire:model="start_at"
                               class="form-input pl-10 @error('start_at') is-invalid @enderror">
                    </div>
                    @error('start_at') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- End At --}}
                <div>
                    <label class="form-label">{{ __('crud.end_at') }}</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <input type="date" wire:model="end_at"
                               class="form-input pl-10 @error('end_at') is-invalid @enderror">
                    </div>
                    @error('end_at') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </form>

        <x-slot name="footer">
            <button wire:click="$set('showFormModal', false)" class="btn-secondary">
                {{ __('crud.cancel') }}
            </button>
            <button type="submit" wire:click="save" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save" class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('crud.save') }}
                </span>
                <span wire:loading wire:target="save" class="flex items-center gap-2">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ __('ui.loading') }}
                </span>
            </button>
        </x-slot>
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
                {{ $deletingInfo }}
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
