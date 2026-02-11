@section('page-title', __('crud.hotel_rooms'))

<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold" style="color: var(--text-primary);">
            {{ __('crud.hotel_rooms') }}
        </h2>

        <div class="flex items-center gap-3">
            {{-- Search & Filter button --}}
            <button wire:click="toggleSearchModal" class="btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                {{ __('crud.search_filter') }}
                @if($search || $filterHotel || $filterBedQuantity || $filterHasAc || $filterHasWifi)
                    <span class="ml-1 w-2 h-2 rounded-full" style="background: var(--color-primary);"></span>
                @endif
            </button>

            {{-- Create button --}}
            @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::HOTEL_ROOMS_CREATE))
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
                        <th class="cursor-pointer hover:bg-opacity-80 transition-colors" wire:click="sortBy('title_ru')">
                            <div class="flex items-center gap-1">
                                {{ __('crud.title') }}
                                @if($sortField === 'title_ru')
                                    <svg class="w-3 h-3" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th>{{ __('crud.hotel') }}</th>
                        <th class="cursor-pointer hover:bg-opacity-80 transition-colors" wire:click="sortBy('bed_quantity')">
                            <div class="flex items-center gap-1">
                                {{ __('crud.bed_quantity') }}
                                @if($sortField === 'bed_quantity')
                                    <svg class="w-3 h-3" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="cursor-pointer hover:bg-opacity-80 transition-colors" wire:click="sortBy('room_size')">
                            <div class="flex items-center gap-1">
                                {{ __('crud.room_size') }}
                                @if($sortField === 'room_size')
                                    <svg class="w-3 h-3" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th>{{ __('crud.amenities') }}</th>
                        <th>{{ __('crud.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $room)
                        <tr wire:key="room-{{ $room->id }}">
                            <td style="color: var(--text-muted);">{{ $room->id }}</td>
                            <td>
                                @if($room->file_id && $room->file)
                                    <img src="{{ $room->file->url }}" alt="{{ $room->title_ru }}" class="w-10 h-10 rounded-lg object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: var(--bg-hover);">
                                        <svg class="w-6 h-6" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="font-medium">
                                {{ $room->title_ru }}
                                @if($room->title_kk)
                                    <div class="text-xs" style="color: var(--text-muted);">{{ $room->title_kk }}</div>
                                @endif
                            </td>
                            <td>
                                @if($room->hotel)
                                    <span class="badge" style="background: var(--bg-hover); color: var(--text-secondary);">
                                        {{ $room->hotel->title_ru }}
                                    </span>
                                @else
                                    <span style="color: var(--text-muted);">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>{{ $room->bed_quantity }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                    </svg>
                                    <span>{{ $room->room_size }} m²</span>
                                </div>
                            </td>
                            <td>
                                <div class="flex flex-wrap gap-1">
                                    @if($room->wifi)
                                        <span class="text-xs" title="{{ __('crud.wifi') }}" style="color: var(--color-success);">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                                            </svg>
                                        </span>
                                    @endif
                                    @if($room->air_conditioning)
                                        <span class="text-xs" title="{{ __('crud.air_conditioning') }}" style="color: var(--color-info);">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        </span>
                                    @endif
                                    @if($room->tv)
                                        <span class="text-xs" title="{{ __('crud.tv') }}" style="color: var(--color-primary);">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        </span>
                                    @endif
                                    @if($room->private_bathroom)
                                        <span class="text-xs" title="{{ __('crud.private_bathroom') }}" style="color: var(--color-amber);">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                            </svg>
                                        </span>
                                    @endif
                                    @if($room->smoking_allowed)
                                        <span class="text-xs" title="{{ __('crud.smoking_allowed') }}" style="color: var(--color-danger);">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                                            </svg>
                                        </span>
                                    @endif
                                    @if($room->room_facilities && $room->room_facilities->count() > 0)
                                        <span class="text-xs badge" style="background: var(--bg-hover);">+{{ $room->room_facilities->count() }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::HOTEL_ROOMS_UPDATE))
                                        <button wire:click="openEditModal({{ $room->id }})"
                                                class="btn-icon btn-icon-edit"
                                                title="{{ __('crud.edit') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                    @endif

                                    @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::HOTEL_ROOMS_DELETE))
                                        <button wire:click="confirmDelete({{ $room->id }})"
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
                            <td colspan="8" class="text-center py-8" style="color: var(--text-muted);">
                                {{ __('crud.no_results') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($rooms->hasPages())
            <div class="px-6 py-4" style="border-top: 1px solid var(--border-color);">
                {{ $rooms->links('vendor.pagination.tailwind') }}
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

            {{-- Filter by Hotel --}}
            <div>
                <label class="form-label">{{ __('crud.hotel') }}</label>
                <select wire:model.live="filterHotel" class="form-input">
                    <option value="">{{ __('crud.all_hotels') }}</option>
                    @foreach($this->getHotelOptions() as $id => $title)
                        <option value="{{ $id }}">{{ $title }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter by Bed Quantity --}}
            <div>
                <label class="form-label">{{ __('crud.bed_quantity') }}</label>
                <select wire:model.live="filterBedQuantity" class="form-input">
                    <option value="">{{ __('crud.all') }}</option>
                    @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>

            {{-- Filter by AC --}}
            <div>
                <label class="form-label">{{ __('crud.air_conditioning') }}</label>
                <select wire:model.live="filterHasAc" class="form-input">
                    <option value="">{{ __('crud.all') }}</option>
                    <option value="1">{{ __('crud.yes') }}</option>
                    <option value="0">{{ __('crud.no') }}</option>
                </select>
            </div>

            {{-- Filter by WiFi --}}
            <div>
                <label class="form-label">{{ __('crud.wifi') }}</label>
                <select wire:model.live="filterHasWifi" class="form-input">
                    <option value="">{{ __('crud.all') }}</option>
                    <option value="1">{{ __('crud.yes') }}</option>
                    <option value="0">{{ __('crud.no') }}</option>
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
    <x-modal wire:model="showFormModal" maxWidth="2xl">
        <x-slot name="title">
            {{ $isEditing ? __('crud.edit_hotel_room') : __('crud.create_hotel_room') }}
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
                <button :class="currentTab === 'amenities' ? 'border-b-2' : 'border-b-2 border-transparent'"
                        :style="currentTab === 'amenities' ? 'border-color: var(--color-primary); color: var(--color-primary);' : 'color: var(--text-secondary);'"
                        @click="currentTab = 'amenities'"
                        class="px-4 py-2 text-sm font-medium transition-colors hover:text-theme-primary">
                    {{ __('crud.amenities') }}
                </button>
                <button :class="currentTab === 'facilities' ? 'border-b-2' : 'border-b-2 border-transparent'"
                        :style="currentTab === 'facilities' ? 'border-color: var(--color-primary); color: var(--color-primary);' : 'color: var(--text-secondary);'"
                        @click="currentTab = 'facilities'"
                        class="px-4 py-2 text-sm font-medium transition-colors hover:text-theme-primary">
                    {{ __('crud.facilities') }}
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
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

                    {{-- Hotel --}}
                    <div>
                        <label class="form-label">{{ __('crud.hotel') }} <span style="color:var(--color-danger);">*</span></label>
                        <select wire:model="hotel_id" class="form-input">
                            <option value="">{{ __('crud.select_hotel') }}</option>
                            @foreach($this->getHotelOptions() as $id => $title)
                                <option value="{{ $id }}">{{ $title }}</option>
                            @endforeach
                        </select>
                        @error('hotel_id') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Bed Quantity --}}
                        <div>
                            <label class="form-label">{{ __('crud.bed_quantity') }} <span style="color:var(--color-danger);">*</span></label>
                            <input type="number" wire:model="bed_quantity" min="1" max="20"
                                   class="form-input @error('bed_quantity') is-invalid @enderror">
                            @error('bed_quantity') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        {{-- Room Size --}}
                        <div>
                            <label class="form-label">{{ __('crud.room_size') }} <span style="color:var(--color-danger);">*</span></label>
                            <input type="number" wire:model="room_size" min="0" max="1000" step="0.1"
                                   class="form-input @error('room_size') is-invalid @enderror"
                                   placeholder="0.0">
                            @error('room_size') <p class="form-error">{{ $message }}</p> @enderror
                            <p class="text-xs mt-1" style="color: var(--text-muted);">{{ __('crud.room_size_unit') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Details Tab --}}
                <div x-show="currentTab === 'details'" class="space-y-4">
                    {{-- Titles --}}
                    <div>
                        <label class="form-label block mb-2">{{ __('crud.title') }}</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="text-xs" style="color: var(--text-muted);">
                                    {{ __('crud.title_ru') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="title_ru"
                                       class="form-input @error('title_ru') is-invalid @enderror">
                                @error('title_ru') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs" style="color: var(--text-muted);">{{ __('crud.title_kk') }}</label>
                                <input type="text" wire:model="title_kk"
                                       class="form-input @error('title_kk') is-invalid @enderror">
                                @error('title_kk') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs" style="color: var(--text-muted);">{{ __('crud.title_en') }}</label>
                                <input type="text" wire:model="title_en"
                                       class="form-input @error('title_en') is-invalid @enderror">
                                @error('title_en') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Descriptions --}}
                    <div>
                        <label class="form-label block mb-2">{{ __('crud.description') }}</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="text-xs" style="color: var(--text-muted);">{{ __('crud.description_ru') }}</label>
                                <textarea wire:model="description_ru" rows="3" class="form-input"></textarea>
                            </div>
                            <div>
                                <label class="text-xs" style="color: var(--text-muted);">{{ __('crud.description_kk') }}</label>
                                <textarea wire:model="description_kk" rows="3" class="form-input"></textarea>
                            </div>
                            <div>
                                <label class="text-xs" style="color: var(--text-muted);">{{ __('crud.description_en') }}</label>
                                <textarea wire:model="description_en" rows="3" class="form-input"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Amenities Tab --}}
                <div x-show="currentTab === 'amenities'" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        {{-- Air Conditioning --}}
                        <label class="flex items-center gap-3 p-3 rounded-lg cursor-pointer select-none"
                               style="background: var(--bg-secondary);">
                            <input type="checkbox" wire:model="air_conditioning"
                                   class="w-5 h-5 rounded" style="accent-color: var(--color-primary);">
                            <div class="flex-1">
                                <div class="font-medium" style="color: var(--text-primary);">
                                    {{ __('crud.air_conditioning') }}
                                </div>
                                <div class="text-xs" style="color: var(--text-muted);">
                                    {{ __('crud.air_conditioning_hint') }}
                                </div>
                            </div>
                            <svg class="w-6 h-6" style="color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </label>

                        {{-- Private Bathroom --}}
                        <label class="flex items-center gap-3 p-3 rounded-lg cursor-pointer select-none"
                               style="background: var(--bg-secondary);">
                            <input type="checkbox" wire:model="private_bathroom"
                                   class="w-5 h-5 rounded" style="accent-color: var(--color-primary);">
                            <div class="flex-1">
                                <div class="font-medium" style="color: var(--text-primary);">
                                    {{ __('crud.private_bathroom') }}
                                </div>
                                <div class="text-xs" style="color: var(--text-muted);">
                                    {{ __('crud.private_bathroom_hint') }}
                                </div>
                            </div>
                            <svg class="w-6 h-6" style="color: var(--color-amber);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </label>

                        {{-- TV --}}
                        <label class="flex items-center gap-3 p-3 rounded-lg cursor-pointer select-none"
                               style="background: var(--bg-secondary);">
                            <input type="checkbox" wire:model="tv"
                                   class="w-5 h-5 rounded" style="accent-color: var(--color-primary);">
                            <div class="flex-1">
                                <div class="font-medium" style="color: var(--text-primary);">
                                    {{ __('crud.tv') }}
                                </div>
                                <div class="text-xs" style="color: var(--text-muted);">
                                    {{ __('crud.tv_hint') }}
                                </div>
                            </div>
                            <svg class="w-6 h-6" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </label>

                        {{-- WiFi --}}
                        <label class="flex items-center gap-3 p-3 rounded-lg cursor-pointer select-none"
                               style="background: var(--bg-secondary);">
                            <input type="checkbox" wire:model="wifi"
                                   class="w-5 h-5 rounded" style="accent-color: var(--color-primary);">
                            <div class="flex-1">
                                <div class="font-medium" style="color: var(--text-primary);">
                                    {{ __('crud.wifi') }}
                                </div>
                                <div class="text-xs" style="color: var(--text-muted);">
                                    {{ __('crud.wifi_hint') }}
                                </div>
                            </div>
                            <svg class="w-6 h-6" style="color: var(--color-success);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                            </svg>
                        </label>

                        {{-- Smoking Allowed --}}
                        <label class="flex items-center gap-3 p-3 rounded-lg cursor-pointer select-none"
                               style="background: var(--bg-secondary);">
                            <input type="checkbox" wire:model="smoking_allowed"
                                   class="w-5 h-5 rounded" style="accent-color: var(--color-primary);">
                            <div class="flex-1">
                                <div class="font-medium" style="color: var(--text-primary);">
                                    {{ __('crud.smoking_allowed') }}
                                </div>
                                <div class="text-xs" style="color: var(--text-muted);">
                                    {{ __('crud.smoking_allowed_hint') }}
                                </div>
                            </div>
                            <svg class="w-6 h-6" style="color: var(--color-danger);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                            </svg>
                        </label>
                    </div>
                </div>

                {{-- Facilities Tab --}}
                <div x-show="currentTab === 'facilities'" class="space-y-4">
                    <div>
                        <label class="form-label block mb-2">{{ __('crud.facilities') }}</label>
                        <p class="text-sm mb-3" style="color: var(--text-muted);">
                            {{ __('crud.facilities_hint') }}
                        </p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($this->getFacilityOptions() as $facility)
                                <label class="flex items-center gap-2 p-3 rounded-lg cursor-pointer select-none"
                                       style="background: var(--bg-secondary);"
                                       :style="selectedFacilities.includes({{ $facility['id'] }}) ? 'background: var(--color-primary-light); border: 1px solid var(--color-primary);' : ''">
                                    <input type="checkbox" wire:model="selectedFacilities" value="{{ $facility['id'] }}"
                                           class="w-4 h-4 rounded" style="accent-color: var(--color-primary);">
                                    <span class="text-sm" style="color: var(--text-secondary);">{{ $facility['title_ru'] }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('selectedFacilities') <p class="form-error">{{ $message }}</p> @enderror
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
                {{ $deletingRoomName }}
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
