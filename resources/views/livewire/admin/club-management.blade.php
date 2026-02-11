@section('page-title', __('crud.clubs'))

<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold" style="color: var(--text-primary);">
            {{ __('crud.clubs') }}
        </h2>

        <div class="flex items-center gap-3">
            {{-- Search & Filter button --}}
            <button wire:click="toggleSearchModal" class="btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                {{ __('crud.search_filter') }}
                @if($search || $filterCity || $filterType || $filterParent || $filterActive)
                    <span class="ml-1 w-2 h-2 rounded-full" style="background: var(--color-primary);"></span>
                @endif
            </button>

            {{-- Create button --}}
            @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::CLUBS_CREATE))
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
                        <th>{{ __('crud.image') }}</th>
                        <th class="cursor-pointer hover:bg-opacity-80 transition-colors" wire:click="sortBy('short_name_ru')">
                            <div class="flex items-center gap-1">
                                {{ __('crud.short_name') }}
                                @if($sortField === 'short_name_ru')
                                    <svg class="w-3 h-3" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th>{{ __('crud.full_name') }}</th>
                        <th>{{ __('crud.city') }}</th>
                        <th>{{ __('crud.club_type') }}</th>
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
                    @forelse($clubs as $club)
                        <tr wire:key="club-{{ $club->id }}" class="{{ $club->trashed() ? 'opacity-60' : '' }}">
                            <td style="color: var(--text-muted);">{{ $club->id }}</td>
                            <td>
                                @if($club->file_id && $club->file)
                                    <img src="{{ $club->file->url }}" alt="{{ $club->short_name_ru }}" class="w-10 h-10 rounded-lg object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: var(--bg-hover);">
                                        <svg class="w-6 h-6" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="font-medium">
                                {{ $club->short_name_ru }}
                                @if($club->parent_id && $club->club)
                                    <div class="text-xs flex items-center gap-1" style="color: var(--text-muted);">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                        {{ $club->club->short_name_ru }}
                                    </div>
                                @endif
                            </td>
                            <td class="max-w-xs truncate">{{ $club->full_name_ru }}</td>
                            <td>
                                @if($club->city)
                                    <span class="badge" style="background: var(--bg-hover); color: var(--text-secondary);">
                                        {{ $club->city->title_ru }}
                                    </span>
                                @else
                                    <span style="color: var(--text-muted);">—</span>
                                @endif
                            </td>
                            <td>
                                @if($club->club_type)
                                    <span class="badge" style="background: var(--color-primary); color: white;">
                                        {{ $club->club_type->title_ru }}
                                    </span>
                                @else
                                    <span style="color: var(--text-muted);">—</span>
                                @endif
                            </td>
                            <td>
                                @if($club->trashed())
                                    <span class="badge badge-danger">{{ __('crud.deleted') }}</span>
                                @elseif($club->is_active)
                                    <span class="badge badge-success">{{ __('crud.active') }}</span>
                                @else
                                    <span class="badge badge-danger">{{ __('crud.inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::CLUBS_UPDATE))
                                        @if(!$club->trashed())
                                            <button wire:click="openEditModal({{ $club->id }})"
                                                    class="btn-icon btn-icon-edit"
                                                    title="{{ __('crud.edit') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                        @endif
                                    @endif

                                    @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::CLUBS_DELETE))
                                        @if($club->trashed())
                                            <button wire:click="confirmDelete({{ $club->id }})"
                                                    class="btn-icon btn-icon-delete"
                                                    title="{{ __('crud.force_delete') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        @else
                                            <button wire:click="confirmDelete({{ $club->id }})"
                                                    class="btn-icon btn-icon-delete"
                                                    title="{{ __('crud.delete') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        @endif
                                    @endif

                                    @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::CLUBS_DELETE))
                                        @if($club->trashed())
                                            <button wire:click="restore({{ $club->id }})"
                                                    class="btn-icon"
                                                    style="color: var(--color-success);"
                                                    title="{{ __('crud.restore') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-8" style="color: var(--text-muted);">
                                {{ __('crud.no_results') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($clubs->hasPages())
            <div class="px-6 py-4" style="border-top: 1px solid var(--border-color);">
                {{ $clubs->links('vendor.pagination.tailwind') }}
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

            {{-- Filter by City --}}
            <div>
                <label class="form-label">{{ __('crud.city') }}</label>
                <select wire:model.live="filterCity" class="form-input">
                    <option value="">{{ __('crud.all_cities') }}</option>
                    @foreach($this->getCityOptions() as $id => $title)
                        <option value="{{ $id }}">{{ $title }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter by Club Type --}}
            <div>
                <label class="form-label">{{ __('crud.club_type') }}</label>
                <select wire:model.live="filterType" class="form-input">
                    <option value="">{{ __('crud.all_types') }}</option>
                    @foreach($this->getClubTypeOptions() as $id => $title)
                        <option value="{{ $id }}">{{ $title }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter by Parent Club --}}
            <div>
                <label class="form-label">{{ __('crud.parent_club') }}</label>
                <select wire:model.live="filterParent" class="form-input">
                    <option value="">{{ __('crud.all') }}</option>
                    @foreach($this->getParentClubOptions() as $id => $title)
                        <option value="{{ $id }}">{{ $title }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter by Status --}}
            <div>
                <label class="form-label">{{ __('crud.status') }}</label>
                <select wire:model.live="filterActive" class="form-input">
                    <option value="">{{ __('crud.all') }}</option>
                    <option value="true">{{ __('crud.active') }}</option>
                    <option value="false">{{ __('crud.inactive') }}</option>
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
    <x-modal wire:model="showFormModal" maxWidth="5xl">
        <x-slot name="title">
            {{ $isEditing ? __('crud.edit_club') : __('crud.create_club') }}
        </x-slot>

        <div x-data="{ currentTab: 'main' }">
            {{-- Tab Navigation --}}
            <div class="flex border-b mb-4" style="border-color: var(--border-color);">
                <button :class="currentTab === 'main' ? 'border-b-2' : 'border-b-2 border-transparent'"
                        :style="currentTab === 'main' ? 'border-color: var(--color-primary); color: var(--color-primary);' : 'color: var(--text-secondary);'"
                        @click="currentTab = 'main'"
                        class="px-4 py-2 text-sm font-medium transition-colors hover:text-theme-primary">
                    {{ __('crud.main_info') }}
                </button>
                <button :class="currentTab === 'details' ? 'border-b-2' : 'border-b-2 border-transparent'"
                        :style="currentTab === 'details' ? 'border-color: var(--color-primary); color: var(--color-primary);' : 'color: var(--text-secondary);'"
                        @click="currentTab = 'details'"
                        class="px-4 py-2 text-sm font-medium transition-colors hover:text-theme-primary">
                    {{ __('crud.details') }}
                </button>
                <button :class="currentTab === 'contacts' ? 'border-b-2' : 'border-b-2 border-transparent'"
                        :style="currentTab === 'contacts' ? 'border-color: var(--color-primary); color: var(--color-primary);' : 'color: var(--text-secondary);'"
                        @click="currentTab = 'contacts'"
                        class="px-4 py-2 text-sm font-medium transition-colors hover:text-theme-primary">
                    {{ __('crud.contacts') }}
                </button>
            </div>

            {{-- Tab Content --}}
            <form wire:submit="save">
                {{-- Main Info Tab --}}
                <div x-show="currentTab === 'main'" class="space-y-4">
                    {{-- Image Upload --}}
                    <div class="flex items-center gap-4">
                        <div class="w-20 h-20 rounded-lg overflow-hidden flex items-center justify-center"
                             style="border: 2px dashed var(--border-color);">
                            @if($file_id && $isEditing)
                                <img src="{{ \App\Models\File::find($file_id)?->url }}" alt="Preview" class="w-full h-full object-cover">
                            @elseif($image)
                                <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="w-full h-full object-cover">
                            @else
                                <svg class="w-10 h-10" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <label class="btn-secondary cursor-pointer">
                                <span>{{ __('crud.upload_image') }}</span>
                                <input type="file" wire:model="image" accept="image/*" class="hidden">
                            </label>
                            @if($file_id && $isEditing)
                                <button type="button" wire:click="removeImage" class="ml-2 text-sm" style="color: var(--color-danger);">
                                    {{ __('crud.remove_image') }}
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Parent Club --}}
                        <div>
                            <label class="form-label">{{ __('crud.parent_club') }}</label>
                            <select wire:model="parent_id" class="form-input">
                                <option value="">{{ __('crud.no_parent') }}</option>
                                @foreach($this->getParentClubOptions() as $id => $title)
                                    <option value="{{ $id }}">{{ $title }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- City --}}
                        <div>
                            <label class="form-label">{{ __('crud.city') }}</label>
                            <select wire:model="city_id" class="form-input">
                                <option value="">{{ __('crud.select_city') }}</option>
                                @foreach($this->getCityOptions() as $id => $title)
                                    <option value="{{ $id }}">{{ $title }}</option>
                                @endforeach
                            </select>
                            @error('city_id') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        {{-- Club Type --}}
                        <div>
                            <label class="form-label">{{ __('crud.club_type') }}</label>
                            <select wire:model="type_id" class="form-input">
                                <option value="">{{ __('crud.select_type') }}</option>
                                @foreach($this->getClubTypeOptions() as $id => $title)
                                    <option value="{{ $id }}">{{ $title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Is Active --}}
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" wire:model="is_active"
                                   class="w-4 h-4 rounded" style="accent-color: var(--color-primary);">
                            <span class="text-sm" style="color: var(--text-secondary);">{{ __('crud.is_active') }}</span>
                        </label>
                    </div>
                </div>

                {{-- Details Tab --}}
                <div x-show="currentTab === 'details'" class="space-y-4">
                    {{-- Short Names --}}
                    <div>
                        <label class="form-label block mb-2">{{ __('crud.short_name') }}</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="text-xs" style="color: var(--text-muted);">
                                    {{ __('crud.short_name_ru') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="short_name_ru"
                                       class="form-input @error('short_name_ru') is-invalid @enderror">
                                @error('short_name_ru') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs" style="color: var(--text-muted);">
                                    {{ __('crud.short_name_kk') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="short_name_kk"
                                       class="form-input @error('short_name_kk') is-invalid @enderror">
                                @error('short_name_kk') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs" style="color: var(--text-muted);">
                                    {{ __('crud.short_name_en') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="short_name_en"
                                       class="form-input @error('short_name_en') is-invalid @enderror">
                                @error('short_name_en') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Full Names --}}
                    <div>
                        <label class="form-label block mb-2">{{ __('crud.full_name') }}</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="text-xs" style="color: var(--text-muted);">
                                    {{ __('crud.full_name_ru') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="full_name_ru"
                                       class="form-input @error('full_name_ru') is-invalid @enderror">
                                @error('full_name_ru') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs" style="color: var(--text-muted);">
                                    {{ __('crud.full_name_kk') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="full_name_kk"
                                       class="form-input @error('full_name_kk') is-invalid @enderror">
                                @error('full_name_kk') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs" style="color: var(--text-muted);">
                                    {{ __('crud.full_name_en') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="full_name_en"
                                       class="form-input @error('full_name_en') is-invalid @enderror">
                                @error('full_name_en') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Descriptions --}}
                    <div>
                        <label class="form-label block mb-2">{{ __('crud.description') }}</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="text-xs" style="color: var(--text-muted);">{{ __('crud.description_ru') }}</label>
                                <textarea wire:model="description_ru" rows="2" class="form-input"></textarea>
                            </div>
                            <div>
                                <label class="text-xs" style="color: var(--text-muted);">{{ __('crud.description_kk') }}</label>
                                <textarea wire:model="description_kk" rows="2" class="form-input"></textarea>
                            </div>
                            <div>
                                <label class="text-xs" style="color: var(--text-muted);">{{ __('crud.description_en') }}</label>
                                <textarea wire:model="description_en" rows="2" class="form-input"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- BIN and Foundation Date --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">{{ __('crud.bin') }}</label>
                            <input type="text" wire:model="bin" maxlength="12"
                                   class="form-input @error('bin') is-invalid @enderror"
                                   placeholder="{{ __('crud.bin_placeholder') }}">
                            @error('bin') <p class="form-error">{{ $message }}</p> @enderror
                            <p class="text-xs mt-1" style="color: var(--text-muted);">{{ __('crud.bin_hint') }}</p>
                        </div>
                        <div>
                            <label class="form-label">{{ __('crud.foundation_date') }}</label>
                            <input type="date" wire:model="foundation_date"
                                   class="form-input @error('foundation_date') is-invalid @enderror">
                            @error('foundation_date') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Contacts Tab --}}
                <div x-show="currentTab === 'contacts'" class="space-y-4">
                    {{-- Addresses --}}
                    <div>
                        <label class="form-label block mb-2">{{ __('crud.address') }}</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="text-xs" style="color: var(--text-muted);">{{ __('crud.address_ru') }}</label>
                                <input type="text" wire:model="address_ru"
                                       class="form-input @error('address_ru') is-invalid @enderror">
                                @error('address_ru') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs" style="color: var(--text-muted);">{{ __('crud.address_kk') }}</label>
                                <input type="text" wire:model="address_kk"
                                       class="form-input @error('address_kk') is-invalid @enderror">
                                @error('address_kk') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs" style="color: var(--text-muted);">{{ __('crud.address_en') }}</label>
                                <input type="text" wire:model="address_en"
                                       class="form-input @error('address_en') is-invalid @enderror">
                                @error('address_en') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Phone and Website --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">{{ __('crud.phone') }}</label>
                            <input type="text" wire:model="phone"
                                   class="form-input @error('phone') is-invalid @enderror"
                                   placeholder="{{ __('crud.phone_placeholder') }}">
                            @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                            <p class="text-xs mt-1" style="color: var(--text-muted);">{{ __('crud.phone_hint') }}</p>
                        </div>
                        <div>
                            <label class="form-label">{{ __('crud.website') }}</label>
                            <input type="url" wire:model="website"
                                   class="form-input @error('website') is-invalid @enderror"
                                   placeholder="https://">
                            @error('website') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </form>
        </div>

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
            {{ $clubs->first()?->trashed() && \App\Models\Club::withTrashed()->find($deletingClubId)?->trashed()
                ? __('crud.confirm_force_delete')
                : __('crud.confirm_delete') }}
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
                {{ \App\Models\Club::withTrashed()->find($deletingClubId)?->trashed()
                    ? __('crud.confirm_force_delete_text')
                    : __('crud.confirm_delete_text') }}
            </p>
            <p class="mt-2 font-semibold" style="color: var(--text-primary);">
                {{ $deletingClubName }}
            </p>
        </div>

        <x-slot name="footer">
            <button wire:click="$set('showDeleteModal', false)" class="btn-secondary">
                {{ __('crud.cancel') }}
            </button>
            <button wire:click="delete" class="btn-danger" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="delete">
                    {{ \App\Models\Club::withTrashed()->find($deletingClubId)?->trashed()
                        ? __('crud.yes_force_delete')
                        : __('crud.yes_delete') }}
                </span>
                <span wire:loading wire:target="delete">{{ __('ui.loading') }}</span>
            </button>
        </x-slot>
    </x-modal>
</div>
