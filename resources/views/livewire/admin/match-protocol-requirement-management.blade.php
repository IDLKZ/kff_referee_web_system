@section('page-title', __('crud.match_protocol_requirements'))

<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold" style="color: var(--text-primary);">
            {{ __('crud.match_protocol_requirements') }}
        </h2>

        <div class="flex items-center gap-3">
            {{-- Search & Filter button --}}
            <button wire:click="toggleSearchModal" class="btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                {{ __('crud.search_filter') }}
                @if($search || $filterTournamentId || $filterJudgeTypeId || $filterIsRequired !== null)
                    <span class="ml-1 w-2 h-2 rounded-full" style="background: var(--color-primary);"></span>
                @endif
            </button>

            {{-- Create button --}}
            @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::MATCH_PROTOCOL_REQUIREMENTS_CREATE))
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
                        <th>{{ __('crud.tournament') }}</th>
                        <th>{{ __('crud.match') }}</th>
                        <th>{{ __('crud.judge_type') }}</th>
                        <th class="cursor-pointer hover:bg-opacity-80 transition-colors" wire:click="sortBy('is_required')">
                            <div class="flex items-center gap-1">
                                {{ __('crud.is_required') }}
                                @if($sortField === 'is_required')
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
                    @forelse($requirements as $requirement)
                        <tr wire:key="requirement-{{ $requirement->id }}">
                            <td style="color: var(--text-muted);">{{ $requirement->id }}</td>
                            <td>
                                <div>
                                    <div class="font-medium">{{ $requirement->title_ru }}</div>
                                    <div class="text-sm" style="color: var(--text-muted);">
                                        {{ $requirement->title_kk ?? '—' }} / {{ $requirement->title_en ?? '—' }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-secondary">{{ $requirement->tournament->title_ru ?? '—' }}</span>
                            </td>
                            <td>
                                @if($requirement->match)
                                    <span class="text-sm font-medium" style="color: var(--text-primary);">
                                        {{ $requirement->match->ownerClub->title_ru ?? '?' }} — {{ $requirement->match->guestClub->title_ru ?? '?' }}
                                    </span>
                                @else
                                    <span style="color: var(--text-muted);">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $requirement->judge_type->title_ru ?? '—' }}</span>
                            </td>
                            <td>
                                @if($requirement->is_required)
                                    <span class="badge badge-success">{{ __('crud.yes') }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ __('crud.no') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::MATCH_PROTOCOL_REQUIREMENTS_UPDATE))
                                        <button wire:click="openEditModal({{ $requirement->id }})"
                                                class="btn-icon btn-icon-edit"
                                                title="{{ __('crud.edit') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                    @endif

                                    @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::MATCH_PROTOCOL_REQUIREMENTS_DELETE))
                                        <button wire:click="confirmDelete({{ $requirement->id }})"
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
        @if($requirements->hasPages())
            <div class="px-6 py-4" style="border-top: 1px solid var(--border-color);">
                {{ $requirements->links('vendor.pagination.tailwind') }}
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

            {{-- Filter by Tournament --}}
            <div>
                <label class="form-label">{{ __('crud.tournament') }}</label>
                <select wire:model.live="filterTournamentId" class="form-input">
                    <option value="">{{ __('crud.all') }}</option>
                    @foreach($tournaments as $tournament)
                        <option value="{{ $tournament->id }}">{{ $tournament->title_ru }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter by Judge Type --}}
            <div>
                <label class="form-label">{{ __('crud.judge_type') }}</label>
                <select wire:model.live="filterJudgeTypeId" class="form-input">
                    <option value="">{{ __('crud.all') }}</option>
                    @foreach($judgeTypes as $judgeType)
                        <option value="{{ $judgeType->id }}">{{ $judgeType->title_ru }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter by Required --}}
            <div>
                <label class="form-label">{{ __('crud.is_required') }}</label>
                <select wire:model.live="filterIsRequired" class="form-input">
                    <option value="">{{ __('crud.all') }}</option>
                    <option value="1">{{ __('crud.yes') }}</option>
                    <option value="0">{{ __('crud.no') }}</option>
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
                    <button wire:click="sortBy('is_required')"
                            class="text-left px-3 py-2 rounded-md text-sm transition-colors {{ $sortField === 'is_required' ? 'btn-primary' : 'btn-secondary' }}">
                        {{ __('crud.is_required') }}
                        @if($sortField === 'is_required')
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
    <x-modal wire:model="showFormModal" maxWidth="lg">
        <x-slot name="title">
            {{ $isEditing ? __('crud.edit_match_protocol_requirement') : __('crud.create_match_protocol_requirement') }}
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
                {{-- Tournament --}}
                <div>
                    <label class="form-label">{{ __('crud.tournament') }} <span style="color:var(--color-danger);">*</span></label>
                    <select wire:model="tournament_id"
                            class="form-input @error('tournament_id') is-invalid @enderror">
                        <option value="">{{ __('crud.select') }}</option>
                        @foreach($tournaments as $tournament)
                            <option value="{{ $tournament->id }}">{{ $tournament->title_ru }}</option>
                        @endforeach
                    </select>
                    @error('tournament_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Judge Type --}}
                <div>
                    <label class="form-label">{{ __('crud.judge_type') }} <span style="color:var(--color-danger);">*</span></label>
                    <select wire:model="judge_type_id"
                            class="form-input @error('judge_type_id') is-invalid @enderror">
                        <option value="">{{ __('crud.select') }}</option>
                        @foreach($judgeTypes as $judgeType)
                            <option value="{{ $judgeType->id }}">{{ $judgeType->title_ru }}</option>
                        @endforeach
                    </select>
                    @error('judge_type_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                {{-- Match ID (optional) --}}
                <div>
                    <label class="form-label">{{ __('crud.match_id') }}</label>
                    <input type="number" wire:model="match_id"
                           class="form-input @error('match_id') is-invalid @enderror"
                           placeholder="{{ __('crud.optional') }}">
                    @error('match_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Is Required --}}
                <div class="flex items-end">
                    <label class="flex items-center gap-2 cursor-pointer select-none pb-2">
                        <input type="checkbox" wire:model="is_required"
                               class="w-5 h-5 rounded" style="accent-color: var(--color-primary);">
                        <span class="text-sm" style="color: var(--text-secondary);">{{ __('crud.is_required') }}</span>
                    </label>
                </div>
            </div>

            {{-- Info fields (trilingual) --}}
            <div class="mb-4">
                <label class="form-label block mb-2">{{ __('crud.info_ru') }}</label>
                <textarea wire:model="info_ru" rows="2"
                          class="form-input @error('info_ru') is-invalid @enderror"
                          placeholder="{{ __('crud.info_placeholder') }}"></textarea>
                @error('info_ru') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label block mb-2">{{ __('crud.info_kk') }}</label>
                <textarea wire:model="info_kk" rows="2"
                          class="form-input @error('info_kk') is-invalid @enderror"
                          placeholder="{{ __('crud.info_placeholder') }}"></textarea>
                @error('info_kk') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label block mb-2">{{ __('crud.info_en') }}</label>
                <textarea wire:model="info_en" rows="2"
                          class="form-input @error('info_en') is-invalid @enderror"
                          placeholder="{{ __('crud.info_placeholder') }}"></textarea>
                @error('info_en') <p class="form-error">{{ $message }}</p> @enderror
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
