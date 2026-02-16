@section('page-title', __('ui.judge_cities'))

<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold" style="color: var(--text-primary);">
            {{ __('ui.judge_cities') }}
        </h2>

        <div class="flex items-center gap-3">
            {{-- Search & Sort button --}}
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
            @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::JUDGE_CITIES_CREATE))
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
                        <th class="cursor-pointer hover:bg-opacity-80 transition-colors" wire:click="sortBy('user_id')">
                            <div class="flex items-center gap-1">
                                {{ __('crud.judge') }}
                                @if($sortField === 'user_id')
                                    <svg class="w-3 h-3" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="cursor-pointer hover:bg-opacity-80 transition-colors" wire:click="sortBy('city_id')">
                            <div class="flex items-center gap-1">
                                {{ __('crud.city') }}
                                @if($sortField === 'city_id')
                                    <svg class="w-3 h-3" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th>{{ __('crud.username') }}</th>
                        <th>{{ __('crud.city_title') }}</th>
                        <th>{{ __('crud.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr wire:key="judge-city-{{ $item->id }}">
                            <td style="color: var(--text-muted);">{{ $item->id }}</td>
                            <td class="font-medium">
                                @if($item->user)
                                    {{ $item->user->last_name }} {{ $item->user->first_name }}
                                @else
                                    <span style="color: var(--text-muted);">{{ __('crud.deleted_user') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($item->city)
                                    {{ $item->city->title_ru }}
                                @else
                                    <span style="color: var(--text-muted);">{{ __('crud.deleted_city') }}</span>
                                @endif
                            </td>
                            <td style="color: var(--text-muted);">
                                @if($item->user){{ $item->user->username }}@endif
                            </td>
                            <td style="color: var(--text-muted);">
                                @if($item->city){{ $item->city->value ?? '—' }}@endif
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::JUDGE_CITIES_UPDATE))
                                        <button wire:click="openEditModal({{ $item->id }})"
                                                class="btn-icon btn-icon-edit"
                                                title="{{ __('crud.edit') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                    @endif

                                    @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::JUDGE_CITIES_DELETE))
                                        <button wire:click="confirmDelete({{ $item->id }})"
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
                            <td colspan="6" class="text-center py-8" style="color: var(--text-muted);">
                                {{ __('crud.no_results') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($items->hasPages())
            <div class="px-6 py-4" style="border-top: 1px solid var(--border-color);">
                {{ $items->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>

    {{-- Search & Sort Modal --}}
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
                    <button wire:click="sortBy('user_name')"
                            class="text-left px-3 py-2 rounded-md text-sm transition-colors {{ $sortField === 'user_name' ? 'btn-primary' : 'btn-secondary' }}">
                        {{ __('crud.judge') }}
                        @if($sortField === 'user_name')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </button>
                    <button wire:click="sortBy('city_name')"
                            class="text-left px-3 py-2 rounded-md text-sm transition-colors {{ $sortField === 'city_name' ? 'btn-primary' : 'btn-secondary' }}">
                        {{ __('crud.city') }}
                        @if($sortField === 'city_name')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </button>
                </div>
            </div>
        </div>

        <x-slot name="footer">
            <button wire:click="clearSearch" class="btn-secondary">
                {{ __('crud.clear') }}
            </button>
            <button wire:click="$set('showSearchModal', false)" class="btn-primary">
                {{ __('crud.apply') }}
            </button>
        </x-slot>
    </x-modal>

    {{-- Create / Edit Modal --}}
    <x-modal wire:model="showFormModal" maxWidth="2xl">
        <x-slot name="title">
            {{ $editingId ? __('crud.edit_judge_city') : __('crud.create_judge_city') }}
        </x-slot>

        <form wire:submit="save">
            <div class="space-y-4 max-h-[85vh] overflow-y-auto pr-1">
                {{-- User (Judge) searchable select --}}
                <div>
                    <label class="form-label">{{ __('crud.judge') }} <span style="color:var(--color-danger);">*</span></label>
                    <div x-data="{ open: false }" @click.away="open = false" class="relative">
                        @if($user_id)
                            <div class="form-input flex items-center justify-between">
                                <span class="truncate" style="color: var(--text-primary);">{{ $selectedUserName }}</span>
                                <button wire:click="clearUser" type="button" class="ml-2 shrink-0 p-0.5 rounded transition-colors"
                                        style="color: var(--text-muted);"
                                        x-on:mouseenter="$el.style.color = 'var(--color-danger)'"
                                        x-on:mouseleave="$el.style.color = 'var(--text-muted)'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        @else
                            <input type="text"
                                   wire:model.live.debounce.300ms="userSearch"
                                   @focus="open = true"
                                   placeholder="{{ __('crud.search_user_placeholder') }}"
                                   class="form-input @error('user_id') is-invalid @enderror">
                            <div x-show="open" x-cloak x-transition
                                 class="absolute z-50 w-full mt-1 max-h-48 overflow-y-auto rounded-lg shadow-lg"
                                 style="background: var(--bg-primary); border: 1px solid var(--border-color);">
                                @if(strlen($userSearch) >= 2)
                                    @forelse($userOptions as $userOption)
                                        <button wire:click="selectUser({{ $userOption->id }})"
                                                @click="open = false"
                                                type="button"
                                                class="w-full text-left px-3 py-2 text-sm cursor-pointer transition-colors"
                                                style="color: var(--text-primary);"
                                                x-on:mouseenter="$el.style.backgroundColor = 'var(--bg-hover)'"
                                                x-on:mouseleave="$el.style.backgroundColor = 'transparent'">
                                            <div class="font-medium">{{ $userOption->last_name }} {{ $userOption->first_name }}</div>
                                            <div class="text-xs" style="color: var(--text-muted);">{{ $userOption->email }}</div>
                                        </button>
                                    @empty
                                        <div class="px-3 py-2 text-sm" style="color: var(--text-muted);">
                                            {{ __('crud.no_results') }}
                                        </div>
                                    @endforelse
                                @else
                                    <div class="px-3 py-2 text-sm" style="color: var(--text-muted);">
                                        {{ __('crud.type_to_search') }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                    @error('user_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- City searchable select --}}
                <div>
                    <label class="form-label">{{ __('crud.city') }} <span style="color:var(--color-danger);">*</span></label>
                    <div x-data="{ open: false }" @click.away="open = false" class="relative">
                        @if($city_id)
                            <div class="form-input flex items-center justify-between">
                                <span class="truncate" style="color: var(--text-primary);">{{ $selectedCityName }}</span>
                                <button wire:click="clearCity" type="button" class="ml-2 shrink-0 p-0.5 rounded transition-colors"
                                        style="color: var(--text-muted);"
                                        x-on:mouseenter="$el.style.color = 'var(--color-danger)'"
                                        x-on:mouseleave="$el.style.color = 'var(--text-muted)'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        @else
                            <input type="text"
                                   wire:model.live.debounce.300ms="citySearch"
                                   @focus="open = true"
                                   placeholder="{{ __('crud.search_city_placeholder') }}"
                                   class="form-input @error('city_id') is-invalid @enderror">
                            <div x-show="open" x-cloak x-transition
                                 class="absolute z-50 w-full mt-1 max-h-48 overflow-y-auto rounded-lg shadow-lg"
                                 style="background: var(--bg-primary); border: 1px solid var(--border-color);">
                                @if(strlen($citySearch) >= 2)
                                    @forelse($cityOptions as $cityOption)
                                        <button wire:click="selectCity({{ $cityOption->id }})"
                                                @click="open = false"
                                                type="button"
                                                class="w-full text-left px-3 py-2 text-sm cursor-pointer transition-colors"
                                                style="color: var(--text-primary);"
                                                x-on:mouseenter="$el.style.backgroundColor = 'var(--bg-hover)'"
                                                x-on:mouseleave="$el.style.backgroundColor = 'transparent'">
                                            <div class="font-medium">{{ $cityOption->title_ru }}</div>
                                            @if($cityOption->title_kk || $cityOption->title_en)
                                                <div class="text-xs" style="color: var(--text-muted);">
                                                    {{ $cityOption->title_kk }} {{ $cityOption->title_en ? '/ ' . $cityOption->title_en : '' }}
                                                </div>
                                            @endif
                                        </button>
                                    @empty
                                        <div class="px-3 py-2 text-sm" style="color: var(--text-muted);">
                                            {{ __('crud.no_results') }}
                                        </div>
                                    @endforelse
                                @else
                                    <div class="px-3 py-2 text-sm" style="color: var(--text-muted);">
                                        {{ __('crud.type_to_search') }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                    @error('city_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>
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
