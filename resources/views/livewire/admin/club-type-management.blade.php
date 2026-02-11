@section('page-title', __('ui.club_types'))

<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold" style="color: var(--text-primary);">
            {{ __('ui.club_types') }}
        </h2>

        <div class="flex items-center gap-3">
            {{-- Search & Filter button --}}
            <button wire:click="toggleSearchModal" class="btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                {{ __('crud.search_filter') }}
                @if($search || $filter_is_active !== null)
                    <span class="ml-1 w-2 h-2 rounded-full" style="background: var(--color-primary);"></span>
                @endif
            </button>

            {{-- Create button --}}
            @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::CLUB_TYPES_CREATE))
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
                    @forelse($clubTypes as $clubType)
                        <tr wire:key="club-type-{{ $clubType->id }}" @if($clubType->trashed()) class="opacity-50" @endif>
                            <td style="color: var(--text-muted);">{{ $clubType->id }}</td>
                            <td>
                                @if($clubType->file && Storage::disk('uploads')->exists($clubType->file->file_path))
                                    <img src="{{ Storage::disk('uploads')->url($clubType->file->file_path) }}"
                                         class="w-10 h-10 rounded-lg object-cover"
                                         style="border: 1px solid var(--border-color);">
                                @else
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                                         style="background: var(--bg-hover); color: var(--text-muted);">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="font-medium">
                                {{ $clubType->title_ru }}
                                @if($clubType->trashed())
                                    <span class="badge badge-danger ml-2">{{ __('crud.deleted') }}</span>
                                @endif
                            </td>
                            <td>{{ $clubType->title_kk ?? '—' }}</td>
                            <td>{{ $clubType->title_en ?? '—' }}</td>
                            <td>
                                <code class="text-xs px-1.5 py-0.5 rounded"
                                      style="background: var(--bg-hover); color: var(--text-secondary);">
                                    {{ $clubType->value }}
                                </code>
                            </td>
                            <td>
                                <span class="badge" style="background: var(--bg-hover); color: var(--text-secondary);">
                                    {{ $clubType->level }}
                                </span>
                            </td>
                            <td>
                                @if($clubType->is_active ?? true)
                                    <span class="badge badge-success">{{ __('crud.active') }}</span>
                                @else
                                    <span class="badge badge-danger">{{ __('crud.inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    @if($clubType->trashed())
                                        @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::CLUB_TYPES_DELETE))
                                            <button wire:click="confirmDelete({{ $clubType->id }})"
                                                    class="btn-icon btn-icon-delete"
                                                    title="{{ __('crud.force_delete') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        @endif
                                        @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::CLUB_TYPES_DELETE))
                                            <button wire:click="restore({{ $clubType->id }})"
                                                    class="btn-icon"
                                                    style="color: var(--color-success);"
                                                    title="{{ __('crud.restore') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                                </svg>
                                            </button>
                                        @endif
                                    @else
                                        @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::CLUB_TYPES_UPDATE))
                                            <button wire:click="openEditModal({{ $clubType->id }})"
                                                    class="btn-icon btn-icon-edit"
                                                    title="{{ __('crud.edit') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                        @endif
                                        @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::CLUB_TYPES_DELETE))
                                            <button wire:click="confirmDelete({{ $clubType->id }})"
                                                    class="btn-icon btn-icon-delete"
                                                    title="{{ __('crud.delete') }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        @endif
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
        @if($clubTypes->hasPages())
            <div class="px-6 py-4" style="border-top: 1px solid var(--border-color);">
                {{ $clubTypes->links('vendor.pagination.tailwind') }}
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
            {{ $isEditing ? __('crud.edit_club_type') : __('crud.create_club_type') }}
        </x-slot>

        <form wire:submit="save">
            {{-- Image Upload --}}
            <div class="mb-4">
                <label class="form-label">{{ __('crud.club_type_image') }}</label>
                <div class="flex items-center gap-4">
                    @if($file_id || $image)
                        <div class="relative group">
                            @if($temporaryImageUrl)
                                <img src="{{ $temporaryImageUrl }}" class="w-20 h-20 rounded-xl object-cover border-2" style="border-color: var(--border-color);">
                            @elseif($existingImageUrl)
                                <img src="{{ $existingImageUrl }}" class="w-20 h-20 rounded-xl object-cover border-2" style="border-color: var(--border-color);">
                            @endif
                            <button type="button" wire:click="removeImage"
                                    class="absolute -top-2 -right-2 w-7 h-7 rounded-lg flex items-center justify-center text-white shadow-lg opacity-0 group-hover:opacity-100 transition-opacity"
                                    style="background: var(--color-danger);">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @else
                        <div class="w-20 h-20 rounded-xl flex items-center justify-center border-2 border-dashed"
                             style="background: var(--bg-hover); border-color: var(--border-color); color: var(--text-muted);">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                    <div>
                        <input type="file" wire:model="image" class="hidden" id="club-type-image-upload" accept="image/*">
                        <label for="club-type-image-upload" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium cursor-pointer transition-colors"
                               style="background: var(--bg-hover); color: var(--text-secondary); border: 1px solid var(--border-color);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            {{ $file_id || $image ? __('crud.change_image') : __('crud.upload_image') }}
                        </label>
                        @error('image') <p class="form-error mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

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

            {{-- Value and Level --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
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
                               placeholder="{{ __('crud.club_type_value_placeholder') }}">
                    </div>
                    @error('value') <p class="form-error">{{ $message }}</p> @enderror
                    @if(!$isEditing)
                        <p class="text-xs mt-1 flex items-center gap-1" style="color: var(--text-muted);">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('crud.club_type_value_hint') }}
                        </p>
                    @endif
                </div>

                {{-- Level --}}
                <div>
                    <label class="form-label">{{ __('crud.level') }}</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <input type="number" wire:model="level" min="1"
                               class="form-input pl-10 @error('level') is-invalid @enderror"
                               placeholder="{{ __('crud.enter_level') }}">
                    </div>
                    @error('level') <p class="form-error">{{ $message }}</p> @enderror
                    <p class="text-xs mt-1 flex items-center gap-1" style="color: var(--text-muted);">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ __('crud.club_type_level_hint') }}
                    </p>
                </div>
            </div>

            {{-- Is Active --}}
            <div class="flex items-center justify-between p-4 rounded-xl mb-2" style="background: var(--bg-hover);">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: var(--color-success-light);">
                        <svg class="w-5 h-5" style="color: var(--color-success);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium" style="color: var(--text-primary);">{{ __('crud.is_active') }}</p>
                        <p class="text-xs" style="color: var(--text-muted);">{{ __('crud.is_active_hint') }}</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model="is_active" class="sr-only peer">
                    <div class="w-11 h-6 rounded-full peer peer-focus:ring-2 peer-focus:ring-opacity-50 transition-all"
                         style="background: var(--border-color);"></div>
                    <div class="absolute left-0.5 top-0.5 w-5 h-5 rounded-full shadow transition-all peer-checked:translate-x-5"
                         style="background: white;"></div>
                    <style>
                        input:checked + div { background: var(--color-primary) !important; }
                    </style>
                </label>
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
            {{ $deletingIsSoftDeleted ? __('crud.confirm_force_delete') : __('crud.confirm_delete') }}
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
                {{ $deletingIsSoftDeleted ? __('crud.confirm_force_delete_text') : __('crud.confirm_delete_text') }}
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
                <span wire:loading.remove wire:target="delete">{{ $deletingIsSoftDeleted ? __('crud.yes_force_delete') : __('crud.yes_delete') }}</span>
                <span wire:loading wire:target="delete">{{ __('ui.loading') }}</span>
            </button>
        </x-slot>
    </x-modal>
</div>
