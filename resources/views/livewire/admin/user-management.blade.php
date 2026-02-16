@section('page-title', __('ui.users'))

<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold" style="color: var(--text-primary);">
            {{ __('ui.users') }}
        </h2>

        <div class="flex items-center gap-3">
            {{-- Search & Filter button --}}
            <button wire:click="toggleSearchModal" class="btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                {{ __('crud.search_filter') }}
                @if($search || $filter_role_id || $filter_is_active !== null || $filter_is_verified !== null)
                    <span class="ml-1 w-2 h-2 rounded-full" style="background: var(--color-primary);"></span>
                @endif
            </button>

            {{-- Create button --}}
            @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::USERS_CREATE))
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
                        <th></th>
                        <th class="cursor-pointer hover:bg-opacity-80 transition-colors" wire:click="sortBy('last_name')">
                            <div class="flex items-center gap-1">
                                {{ __('crud.full_name') }}
                                @if($sortField === 'last_name')
                                    <svg class="w-3 h-3" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th>{{ __('crud.phone') }}</th>
                        <th>{{ __('crud.email') }}</th>
                        <th>{{ __('crud.role') }}</th>
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
                        <th class="cursor-pointer hover:bg-opacity-80 transition-colors" wire:click="sortBy('is_verified')">
                            <div class="flex items-center gap-1">
                                {{ __('crud.verified') }}
                                @if($sortField === 'is_verified')
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
                    @forelse($users as $user)
                        <tr wire:key="user-{{ $user->id }}">
                            <td>
                                @if($user->image_id && $user->file && \Illuminate\Support\Facades\Storage::disk('uploads')->exists($user->file->file_path))
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('uploads')->url($user->file->file_path) }}"
                                         alt="{{ $user->first_name }}"
                                         class="w-10 h-10 rounded-full object-cover"
                                         style="background: var(--bg-hover);"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="w-10 h-10 rounded-full hidden items-center justify-center text-sm font-semibold"
                                         style="background: var(--color-primary); color: var(--text-on-primary);">
                                        {{ mb_substr($user->first_name, 0, 1) }}{{ mb_substr($user->last_name, 0, 1) }}
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold"
                                         style="background: var(--color-primary); color: var(--text-on-primary);">
                                        {{ mb_substr($user->first_name, 0, 1) }}{{ mb_substr($user->last_name, 0, 1) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="font-medium">{{ $user->last_name }} {{ $user->first_name }}</div>
                                @if($user->patronymic)
                                    <div class="text-sm" style="color: var(--text-muted);">{{ $user->patronymic }}</div>
                                @endif
                                <div class="text-xs" style="color: var(--text-muted);">{{ '@'.($user->username) }}</div>
                            </td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role)
                                    <span class="badge" style="background: var(--bg-hover); color: var(--text-secondary);">
                                        {{ $user->role->{'title_' . app()->getLocale()} ?? $user->role->title_ru }}
                                    </span>
                                @else
                                    <span style="color: var(--text-muted);">—</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge badge-success">{{ __('crud.active') }}</span>
                                @else
                                    <span class="badge badge-danger">{{ __('crud.inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_verified)
                                    <span class="badge badge-success">{{ __('crud.yes') }}</span>
                                @else
                                    <span class="badge badge-danger">{{ __('crud.no') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::USERS_UPDATE))
                                        <button wire:click="openEditModal({{ $user->id }})"
                                                class="btn-icon btn-icon-edit"
                                                title="{{ __('crud.edit') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                    @endif

                                    @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::USERS_DELETE))
                                        <button wire:click="confirmDelete({{ $user->id }})"
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
        @if($users->hasPages())
            <div class="px-6 py-4" style="border-top: 1px solid var(--border-color);">
                {{ $users->links('vendor.pagination.tailwind') }}
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

            {{-- Filter by Role --}}
            <div>
                <label class="form-label">{{ __('crud.role') }}</label>
                <select wire:model.live="filter_role_id" class="form-input">
                    <option value="">{{ __('crud.all_roles') }}</option>
                    @foreach($this->getRoleOptions() as $id => $title)
                        <option value="{{ $id }}">{{ $title }}</option>
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

            {{-- Filter by Verified --}}
            <div>
                <label class="form-label">{{ __('crud.verified') }}</label>
                <select wire:model.live="filter_is_verified" class="form-input">
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
    <x-modal wire:model="showFormModal" maxWidth="xl">
        <x-slot name="title">
            {{ $isEditing ? __('crud.edit_user') : __('crud.create_user') }}
        </x-slot>

        <form wire:submit="save">
            <div class="space-y-4 max-h-[70vh] overflow-y-auto pr-1">
                {{-- Image Upload --}}
                <div>
                    <label class="form-label">{{ __('crud.photo') }}</label>
                    <div class="flex flex-col items-center gap-3">
                        @if($image_id || $image)
                            <div class="relative">
                                @if($temporaryImageUrl)
                                    <img src="{{ $temporaryImageUrl }}" class="w-24 h-24 rounded-full object-cover">
                                @elseif($existingImageUrl)
                                    <img src="{{ $existingImageUrl }}" class="w-24 h-24 rounded-full object-cover">
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
                            <div class="w-24 h-24 rounded-full flex items-center justify-center"
                                 style="background: var(--bg-hover); color: var(--text-muted);">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        @endif
                        <input type="file" wire:model="image" class="hidden" id="image-upload" accept="image/*">
                        <label for="image-upload" class="btn-secondary text-sm cursor-pointer">
                            {{ $image_id || $image ? __('crud.change_photo') : __('crud.upload_photo') }}
                        </label>
                        @error('image') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Role --}}
                <div>
                    <label class="form-label">{{ __('crud.role') }}</label>
                    <select wire:model="role_id"
                            class="form-input @error('role_id') is-invalid @enderror">
                        <option value="">{{ __('crud.select_role') }}</option>
                        @foreach($this->getRoleOptions() as $id => $title)
                            <option value="{{ $id }}">{{ $title }}</option>
                        @endforeach
                    </select>
                    @error('role_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Sex --}}
                <div>
                    <label class="form-label">{{ __('crud.sex') }}</label>
                    <select wire:model="sex"
                            class="form-input @error('sex') is-invalid @enderror">
                        @foreach($this->getSexOptions() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('sex') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Last Name --}}
                <div>
                    <label class="form-label">{{ __('crud.last_name') }} <span style="color:var(--color-danger);">*</span></label>
                    <input type="text" wire:model="last_name"
                           class="form-input @error('last_name') is-invalid @enderror">
                    @error('last_name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- First Name --}}
                <div>
                    <label class="form-label">{{ __('crud.first_name') }} <span style="color:var(--color-danger);">*</span></label>
                    <input type="text" wire:model="first_name"
                           class="form-input @error('first_name') is-invalid @enderror">
                    @error('first_name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Patronymic --}}
                <div>
                    <label class="form-label">{{ __('crud.patronymic') }}</label>
                    <input type="text" wire:model="patronymic"
                           class="form-input @error('patronymic') is-invalid @enderror">
                    @error('patronymic') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label class="form-label">{{ __('crud.phone') }} <span style="color:var(--color-danger);">*</span></label>
                    <input type="text" wire:model="phone"
                           placeholder="+7(777)123-45-67"
                           class="form-input @error('phone') is-invalid @enderror">
                    @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="form-label">{{ __('crud.email') }} <span style="color:var(--color-danger);">*</span></label>
                    <input type="email" wire:model="email"
                           class="form-input @error('email') is-invalid @enderror">
                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Username --}}
                <div>
                    <label class="form-label">{{ __('crud.username') }} <span style="color:var(--color-danger);">*</span></label>
                    <input type="text" wire:model="username"
                           placeholder="A-Za-z0-9_@"
                           class="form-input @error('username') is-invalid @enderror">
                    @error('username') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- IIN --}}
                <div>
                    <label class="form-label">{{ __('crud.iin') }}</label>
                    <input type="text" wire:model="iin"
                           placeholder="12 цифр"
                           maxlength="12"
                           class="form-input @error('iin') is-invalid @enderror">
                    @error('iin') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Birth Date --}}
                <div>
                    <label class="form-label">{{ __('crud.birth_date') }}</label>
                    <input type="date" wire:model="birth_date"
                           class="form-input @error('birth_date') is-invalid @enderror">
                    @error('birth_date') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="form-label">
                        {{ $isEditing ? __('crud.password_new') : __('crud.password') }}
                        @if(!$isEditing)<span style="color:var(--color-danger);">*</span>@endif
                    </label>
                    <input type="password" wire:model="password"
                           placeholder="@if($isEditing){{ __('crud.password_leave_empty') }}@endif"
                           class="form-input @error('password') is-invalid @enderror">
                    @error('password') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Status checkboxes --}}
                <div>
                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" wire:model="is_active"
                                   class="w-4 h-4 rounded" style="accent-color: var(--color-primary);">
                            <span class="text-sm" style="color: var(--text-secondary);">{{ __('crud.is_active') }}</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" wire:model="is_verified"
                                   class="w-4 h-4 rounded" style="accent-color: var(--color-primary);">
                            <span class="text-sm" style="color: var(--text-secondary);">{{ __('crud.is_verified') }}</span>
                        </label>
                    </div>
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
                {{ $deletingUserInfo }}
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
