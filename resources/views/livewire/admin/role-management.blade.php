@section('page-title', __('ui.roles'))

<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold" style="color: var(--text-primary);">
            {{ __('ui.roles') }}
        </h2>

        <div class="flex items-center gap-3">
            {{-- Search --}}
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                     style="color: var(--text-muted);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="{{ __('crud.search') }}"
                    class="form-input form-input-icon"
                    style="width: 220px; padding-left: 2.25rem;"
                >
            </div>

            {{-- Create button --}}
            @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::ROLES_CREATE))
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
                        <th>ID</th>
                        <th>{{ __('crud.title_ru') }}</th>
                        <th>{{ __('crud.title_kk') }}</th>
                        <th>{{ __('crud.title_en') }}</th>
                        <th>{{ __('crud.value') }}</th>
                        <th>{{ __('crud.group') }}</th>
                        <th>{{ __('crud.can_register') }}</th>
                        <th>{{ __('crud.status') }}</th>
                        <th>{{ __('crud.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr wire:key="role-{{ $role->id }}">
                            <td style="color: var(--text-muted);">{{ $role->id }}</td>
                            <td class="font-medium">{{ $role->title_ru }}</td>
                            <td>{{ $role->title_kk ?? '—' }}</td>
                            <td>{{ $role->title_en ?? '—' }}</td>
                            <td>
                                <code class="text-xs px-1.5 py-0.5 rounded"
                                      style="background: var(--bg-hover); color: var(--text-secondary);">
                                    {{ $role->value }}
                                </code>
                            </td>
                            <td>
                                <code class="text-xs px-1.5 py-0.5 rounded"
                                      style="background: var(--bg-hover); color: var(--text-secondary);">
                                    {{ $role->group }}
                                </code>
                            </td>
                            <td>
                                @if($role->can_register)
                                    <span class="badge badge-success">{{ __('crud.yes') }}</span>
                                @else
                                    <span class="badge badge-danger">{{ __('crud.no') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($role->is_active)
                                    <span class="badge badge-success">{{ __('crud.active') }}</span>
                                @else
                                    <span class="badge badge-danger">{{ __('crud.inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::ROLES_UPDATE))
                                        <button wire:click="openEditModal({{ $role->id }})"
                                                class="btn-icon btn-icon-edit"
                                                title="{{ __('crud.edit') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                    @endif

                                    @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::ROLES_DELETE))
                                        <button wire:click="confirmDelete({{ $role->id }})"
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
    </div>

    {{-- Create / Edit Modal --}}
    <x-modal wire:model="showFormModal" maxWidth="lg">
        <x-slot name="title">
            {{ $isEditing ? __('crud.edit_role') : __('crud.create_role') }}
        </x-slot>

        <form wire:submit="save">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                {{-- Title RU --}}
                <div>
                    <label class="form-label">{{ __('crud.title_ru') }} <span style="color:var(--color-danger);">*</span></label>
                    <input type="text" wire:model="title_ru"
                           class="form-input @error('title_ru') is-invalid @enderror">
                    @error('title_ru') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Title KK --}}
                <div>
                    <label class="form-label">{{ __('crud.title_kk') }}</label>
                    <input type="text" wire:model="title_kk"
                           class="form-input @error('title_kk') is-invalid @enderror">
                    @error('title_kk') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Title EN --}}
                <div>
                    <label class="form-label">{{ __('crud.title_en') }}</label>
                    <input type="text" wire:model="title_en"
                           class="form-input @error('title_en') is-invalid @enderror">
                    @error('title_en') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                {{-- Value --}}
                <div>
                    <label class="form-label">{{ __('crud.value') }} <span style="color:var(--color-danger);">*</span></label>
                    <input type="text" wire:model="value"
                           class="form-input @error('value') is-invalid @enderror"
                           {{ $isEditing ? 'disabled' : '' }}>
                    @error('value') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Group --}}
                <div>
                    <label class="form-label">{{ __('crud.group') }} <span style="color:var(--color-danger);">*</span></label>
                    <select wire:model="group"
                            class="form-input @error('group') is-invalid @enderror">
                        <option value="">—</option>
                        @foreach($this->getGroupOptions() as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('group') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center gap-6 mb-2">
                {{-- Can Register --}}
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" wire:model="can_register"
                           class="w-4 h-4 rounded" style="accent-color: var(--color-primary);">
                    <span class="text-sm" style="color: var(--text-secondary);">{{ __('crud.can_register') }}</span>
                </label>

                {{-- Is Active --}}
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" wire:model="is_active"
                           class="w-4 h-4 rounded" style="accent-color: var(--color-primary);">
                    <span class="text-sm" style="color: var(--text-secondary);">{{ __('crud.is_active') }}</span>
                </label>
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
                {{ $deletingRoleName }}
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
