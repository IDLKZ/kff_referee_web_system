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
                                        <button wire:click="openEditModal({{ $tournament->id }})"
                                                class="btn-icon btn-icon-edit"
                                                title="{{ __('crud.edit') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                    @endif

                                    @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::TOURNAMENTS_DELETE))
                                        <button wire:click="confirmDelete({{ $tournament->id }})"
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
    <x-modal wire:model="showFormModal" maxWidth="xl">
        <x-slot name="title">
            {{ $isEditing ? __('crud.edit_tournament') : __('crud.create_tournament') }}
        </x-slot>

        <form wire:submit="save">
            <div class="space-y-4 max-h-[70vh] overflow-y-auto pr-1">
                {{-- Image Upload --}}
                <div>
                    <label class="form-label">{{ __('crud.tournament_image') }}</label>
                    <div class="flex items-center gap-4">
                        @if($image_id || $image)
                            <div class="relative group">
                                @if($temporaryImageUrl)
                                    <img src="{{ $temporaryImageUrl }}" class="w-20 h-20 rounded-xl object-cover border-2" style="border-color: var(--border-color);">
                                @elseif($existingImageUrl)
                                    <img src="{{ $existingImageUrl }}" class="w-20 h-20 rounded-xl object-cover border-2" style="border-color: var(--border-color);">
                                @endif
                                <button type="button" wire:click="removeImage"
                                        class="absolute -top-2 -right-2 w-6 h-6 rounded-full flex items-center justify-center text-white"
                                        style="background: var(--color-danger);">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <input type="file" wire:model="image" class="hidden" id="tournament-image-upload" accept="image/*">
                            <label for="tournament-image-upload" class="btn-secondary text-sm cursor-pointer">
                                {{ $image_id || $image ? __('crud.change_image') : __('crud.upload_image') }}
                            </label>
                            @error('image') <p class="form-error mt-2">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Title RU --}}
                <div>
                    <label class="form-label">{{ __('crud.title_ru') }} <span style="color:var(--color-danger);">*</span></label>
                    <input type="text" wire:model="title_ru"
                           class="form-input @error('title_ru') is-invalid @enderror"
                           placeholder="{{ __('crud.enter_title_ru') }}">
                    @error('title_ru') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Title KK --}}
                <div>
                    <label class="form-label">{{ __('crud.title_kk') }}</label>
                    <input type="text" wire:model="title_kk"
                           class="form-input @error('title_kk') is-invalid @enderror"
                           placeholder="{{ __('crud.enter_title_kk') }}">
                    @error('title_kk') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Title EN --}}
                <div>
                    <label class="form-label">{{ __('crud.title_en') }}</label>
                    <input type="text" wire:model="title_en"
                           class="form-input @error('title_en') is-invalid @enderror"
                           placeholder="{{ __('crud.enter_title_en') }}">
                    @error('title_en') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Short Title RU --}}
                <div>
                    <label class="form-label">{{ __('crud.short_title_ru') }} <span style="color:var(--color-danger);">*</span></label>
                    <input type="text" wire:model="short_title_ru"
                           class="form-input @error('short_title_ru') is-invalid @enderror"
                           placeholder="{{ __('crud.enter_short_title_ru') }}">
                    @error('short_title_ru') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Short Title KK --}}
                <div>
                    <label class="form-label">{{ __('crud.short_title_kk') }} <span style="color:var(--color-danger);">*</span></label>
                    <input type="text" wire:model="short_title_kk"
                           class="form-input @error('short_title_kk') is-invalid @enderror"
                           placeholder="{{ __('crud.enter_short_title_kk') }}">
                    @error('short_title_kk') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Short Title EN --}}
                <div>
                    <label class="form-label">{{ __('crud.short_title_en') }} <span style="color:var(--color-danger);">*</span></label>
                    <input type="text" wire:model="short_title_en"
                           class="form-input @error('short_title_en') is-invalid @enderror"
                           placeholder="{{ __('crud.enter_short_title_en') }}">
                    @error('short_title_en') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Value --}}
                <div>
                    <label class="form-label">{{ __('crud.value') }} <span style="color:var(--color-danger);">*</span></label>
                    <input type="text" wire:model="value"
                           class="form-input @error('value') is-invalid @enderror"
                           {{ $isEditing ? 'disabled' : '' }}
                           placeholder="{{ __('crud.tournament_value_placeholder') }}">
                    @error('value') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Country --}}
                <div>
                    <label class="form-label">{{ __('crud.country') }}</label>
                    <select wire:model="country_id"
                            class="form-input @error('country_id') is-invalid @enderror">
                        <option value="">{{ __('crud.select_country') }}</option>
                        @foreach($this->getCountryOptions() as $id => $title)
                            <option value="{{ $id }}">{{ $title }}</option>
                        @endforeach
                    </select>
                    @error('country_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Level --}}
                <div>
                    <label class="form-label">{{ __('crud.level') }} <span style="color:var(--color-danger);">*</span></label>
                    <input type="number" wire:model="level" min="1"
                           class="form-input @error('level') is-invalid @enderror"
                           placeholder="{{ __('crud.enter_level') }}">
                    @error('level') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Sex --}}
                <div>
                    <label class="form-label">{{ __('crud.sex') }} <span style="color:var(--color-danger);">*</span></label>
                    <select wire:model="sex"
                            class="form-input @error('sex') is-invalid @enderror">
                        @foreach($this->getSexOptions() as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('sex') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Description RU --}}
                <div>
                    <label class="form-label">{{ __('crud.description_ru') }}</label>
                    <textarea wire:model="description_ru" rows="3"
                              class="form-input @error('description_ru') is-invalid @enderror resize-none"
                              placeholder="{{ __('crud.enter_description_ru') }}"></textarea>
                    @error('description_ru') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Description KK --}}
                <div>
                    <label class="form-label">{{ __('crud.description_kk') }}</label>
                    <textarea wire:model="description_kk" rows="3"
                              class="form-input @error('description_kk') is-invalid @enderror resize-none"
                              placeholder="{{ __('crud.enter_description_kk') }}"></textarea>
                    @error('description_kk') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Description EN --}}
                <div>
                    <label class="form-label">{{ __('crud.description_en') }}</label>
                    <textarea wire:model="description_en" rows="3"
                              class="form-input @error('description_en') is-invalid @enderror resize-none"
                              placeholder="{{ __('crud.enter_description_en') }}"></textarea>
                    @error('description_en') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" wire:model="is_active"
                               class="w-4 h-4 rounded" style="accent-color: var(--color-primary);">
                        <span class="text-sm" style="color: var(--text-secondary);">{{ __('crud.is_active') }}</span>
                    </label>
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
                {{ $deletingTournamentInfo }}
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
