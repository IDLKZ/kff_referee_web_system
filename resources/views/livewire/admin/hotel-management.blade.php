@section('page-title', __('crud.hotels'))

<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold" style="color: var(--text-primary);">
            {{ __('crud.hotels') }}
        </h2>

        <div class="flex items-center gap-3">
            {{-- Search & Filter button --}}
            <button wire:click="toggleSearchModal" class="btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                {{ __('crud.search_filter') }}
                @if($search || $filterCity || $filterStar || $filterPartner)
                    <span class="ml-1 w-2 h-2 rounded-full" style="background: var(--color-primary);"></span>
                @endif
            </button>

            {{-- Create button --}}
            @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::HOTELS_CREATE))
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
                        <th>{{ __('crud.city') }}</th>
                        <th class="cursor-pointer hover:bg-opacity-80 transition-colors" wire:click="sortBy('star')">
                            <div class="flex items-center gap-1">
                                {{ __('crud.star') }}
                                @if($sortField === 'star')
                                    <svg class="w-3 h-3" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th>{{ __('crud.is_partner') }}</th>
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
                    @forelse($hotels as $hotel)
                        <tr wire:key="hotel-{{ $hotel->id }}">
                            <td style="color: var(--text-muted);">{{ $hotel->id }}</td>
                            <td>
                                @if($hotel->file_id && $hotel->file)
                                    <img src="{{ $hotel->file->url }}" alt="{{ $hotel->title_ru }}" class="w-10 h-10 rounded-lg object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: var(--bg-hover);">
                                        <svg class="w-6 h-6" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="font-medium">
                                {{ $hotel->title_ru }}
                                @if($hotel->title_kk)
                                    <div class="text-xs" style="color: var(--text-muted);">{{ $hotel->title_kk }}</div>
                                @endif
                            </td>
                            <td>
                                @if($hotel->city)
                                    <span class="badge" style="background: var(--bg-hover); color: var(--text-secondary);">
                                        {{ $hotel->city->title_ru }}
                                    </span>
                                @else
                                    <span style="color: var(--text-muted);">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-1" style="color: var(--color-amber);">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $hotel->star)
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                            </svg>
                                        @endif
                                    @endfor
                                    <span class="text-xs">({{ $hotel->star }})</span>
                                </div>
                            </td>
                            <td>
                                @if($hotel->is_partner)
                                    <span class="badge" style="background: var(--color-primary); color: white;">
                                        {{ __('crud.partner') }}
                                    </span>
                                @else
                                    <span style="color: var(--text-muted);">—</span>
                                @endif
                            </td>
                            <td>
                                @if($hotel->is_active)
                                    <span class="badge badge-success">{{ __('crud.active') }}</span>
                                @else
                                    <span class="badge badge-danger">{{ __('crud.inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::HOTELS_UPDATE))
                                        <button wire:click="openEditModal({{ $hotel->id }})"
                                                class="btn-icon btn-icon-edit"
                                                title="{{ __('crud.edit') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                    @endif

                                    @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::HOTELS_DELETE))
                                        <button wire:click="confirmDelete({{ $hotel->id }})"
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
        @if($hotels->hasPages())
            <div class="px-6 py-4" style="border-top: 1px solid var(--border-color);">
                {{ $hotels->links('vendor.pagination.tailwind') }}
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

            {{-- Filter by Star --}}
            <div>
                <label class="form-label">{{ __('crud.star') }}</label>
                <select wire:model.live="filterStar" class="form-input">
                    <option value="">{{ __('crud.all') }}</option>
                    <option value="5">5 ★</option>
                    <option value="4">4 ★</option>
                    <option value="3">3 ★</option>
                    <option value="2">2 ★</option>
                    <option value="1">1 ★</option>
                    <option value="0">0 ★</option>
                </select>
            </div>

            {{-- Filter by Partner --}}
            <div>
                <label class="form-label">{{ __('crud.is_partner') }}</label>
                <select wire:model.live="filterPartner" class="form-input">
                    <option value="">{{ __('crud.all') }}</option>
                    <option value="true">{{ __('crud.partner') }}</option>
                    <option value="false">{{ __('crud.not_partner') }}</option>
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
            {{ $isEditing ? __('crud.edit_hotel') : __('crud.create_hotel') }}
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

                        {{-- Star Rating --}}
                        <div>
                            <label class="form-label">{{ __('crud.star') }} <span style="color:var(--color-danger);">*</span></label>
                            <input type="number" wire:model="star" min="0" max="5"
                                   class="form-input @error('star') is-invalid @enderror">
                            @error('star') <p class="form-error">{{ $message }}</p> @enderror
                            <p class="text-xs mt-1" style="color: var(--text-muted);">{{ __('crud.star_hint') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-6">
                        {{-- Is Active --}}
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" wire:model="is_active"
                                   class="w-4 h-4 rounded" style="accent-color: var(--color-primary);">
                            <span class="text-sm" style="color: var(--text-secondary);">{{ __('crud.is_active') }}</span>
                        </label>

                        {{-- Is Partner --}}
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" wire:model="is_partner"
                                   class="w-4 h-4 rounded" style="accent-color: var(--color-primary);">
                            <span class="text-sm" style="color: var(--text-secondary);">{{ __('crud.is_partner') }}</span>
                        </label>
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

                    {{-- Email, Website --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">{{ __('crud.email') }}</label>
                            <input type="email" wire:model="email"
                                   class="form-input @error('email') is-invalid @enderror"
                                   placeholder="email@example.com">
                            @error('email') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">{{ __('crud.website') }}</label>
                            <input type="url" wire:model="website"
                                   class="form-input @error('website') is-invalid @enderror"
                                   placeholder="https://">
                            @error('website') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Coordinates --}}
                    <div>
                        <label class="form-label block mb-2">{{ __('crud.coordinates') }}</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs" style="color: var(--text-muted);">{{ __('crud.lat') }}</label>
                                <input type="number" wire:model="lat" step="any"
                                       class="form-input @error('lat') is-invalid @enderror"
                                       placeholder="-90.000000">
                                @error('lat') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="text-xs" style="color: var(--text-muted);">{{ __('crud.lon') }}</label>
                                <input type="number" wire:model="lon" step="any"
                                       class="form-input @error('lon') is-invalid @enderror"
                                       placeholder="180.000000">
                                @error('lon') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
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
                {{ $deletingHotelName }}
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
