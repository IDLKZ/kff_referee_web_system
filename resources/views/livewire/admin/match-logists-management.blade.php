@section('page-title', __('crud.match_logists'))

<div>
    {{-- Заголовок --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold" style="color: var(--text-primary);">
            {{ __('crud.match_logists') }}
        </h2>

        <div class="flex items-center gap-3">
            {{-- Кнопка Поиск и фильтр --}}
            <button wire:click="toggleSearchModal" class="btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                {{ __('crud.search_filter') }}
                @if($search || $filter_match_id || $filter_logist_id || $filter_role)
                    <span class="ml-1 w-2 h-2 rounded-full" style="background: var(--color-primary);"></span>
                @endif
            </button>

            {{-- Кнопка Создать --}}
            @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::MATCH_LOGISTS_CREATE))
                <button wire:click="openCreateModal" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('crud.create') }}
                </button>
            @endif
        </div>
    </div>

    {{-- Таблица --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        {{-- ID --}}
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

                        {{-- Матч --}}
                        <th class="cursor-pointer hover:bg-opacity-80 transition-colors" wire:click="sortBy('match_title')">
                            <div class="flex items-center gap-1">
                                {{ __('crud.match') }}
                                @if($sortField === 'match_title')
                                    <svg class="w-3 h-3" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </div>
                        </th>

                        {{-- Логист --}}
                        <th class="cursor-pointer hover:bg-opacity-80 transition-colors" wire:click="sortBy('logist_name')">
                            <div class="flex items-center gap-1">
                                {{ __('crud.logist') }}
                                @if($sortField === 'logist_name')
                                    <svg class="w-3 h-3" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </div>
                        </th>

                        {{-- Дата --}}
                        <th class="cursor-pointer hover:bg-opacity-80 transition-colors" wire:click="sortBy('match_date')">
                            <div class="flex items-center gap-1">
                                {{ __('crud.date') }}
                                @if($sortField === 'match_date')
                                    <svg class="w-3 h-3" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </div>
                        </th>

                        {{-- Информация о матче --}}
                        <th>{{ __('crud.info') }}</th>

                        {{-- Действия --}}
                        <th>{{ __('crud.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($matchLogists as $matchLogist)
                        <tr wire:key="ml-{{ $matchLogist->id }}">
                            <td style="color: var(--text-muted);">{{ $matchLogist->id }}</td>

                            {{-- Матч --}}
                            <td class="min-w-[250px]">
                                @if($matchLogist->match)
                                    <div class="font-medium text-sm">
                                        <div>{{ $matchLogist->match->ownerClub->short_title_ru }} vs {{ $matchLogist->match->guestClub->short_title_ru }}</div>
                                        <div class="text-xs mt-1" style="color: var(--text-muted);">
                                            {{ __('crud.stadium') }}: {{ $matchLogist->match->stadium?->title_ru ?? '—' }}
                                            @if($matchLogist->match->stadium->city)
                                                <span>, {{ $matchLogist->match->stadium->city->title_ru }}</span>
                                            @endif
                                        </div>
                                        @if($matchLogist->match->round)
                                            <div class="text-xs" style="color: var(--text-muted);">
                                                • {{ __('crud.round') }} {{ $matchLogist->match->round }}
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span style="color: var(--text-muted);">—</span>
                                @endif
                            </td>

                            {{-- Логист --}}
                            <td class="min-w-[200px]">
                                @if($matchLogist->user)
                                    <div>
                                        <div class="font-medium text-sm">{{ $matchLogist->user->full_name ?? $matchLogist->user->first_name }}</div>
                                        <div class="text-xs mt-1" style="color: var(--text-muted);">
                                            {{ $matchLogist->user->role->title_ru ?? '' }}
                                        </div>
                                    </div>
                                @else
                                    <span style="color: var(--text-muted);">—</span>
                                @endif
                            </td>

                            {{-- Дата --}}
                            <td style="color: var(--text-muted);">{{ $matchLogist->match->start_at?->format('d.m.Y H:i') ?? '—' }}</td>

                            {{-- Информация --}}
                            <td>
                                <div class="flex flex-wrap gap-2">
                                    @if($matchLogist->match->season)
                                        <span class="badge badge-info text-xs">{{ __('crud.season') }}: {{ $matchLogist->match->season->title_ru }}</span>
                                    @endif
                                    @if($matchLogist->match->city)
                                        <span class="badge badge-secondary text-xs">{{ __('crud.city') }}: {{ $matchLogist->match->city->title_ru }}</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Действия --}}
                            <td>
                                @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::MATCH_LOGISTS_UPDATE))
                                    <button wire:click="openEditModal({{ $matchLogist->id }})"
                                            class="btn-icon btn-icon-edit"
                                            title="{{ __('crud.edit') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 012 2v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                        </svg>
                                    </button>
                                @endif

                                @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::MATCH_LOGISTS_DELETE))
                                    <button wire:click="confirmDelete({{ $matchLogist->id }})"
                                            class="btn-icon btn-icon-delete"
                                            title="{{ __('crud.delete') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1 1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                @endif
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

        {{-- Пагинация --}}
        @if($matchLogists->hasPages())
            <div class="px-6 py-4" style="border-top: 1px solid var(--border-color);">
                {{ $matchLogists->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>

    {{-- Модальное окно: Поиск и фильтр --}}
    <x-modal wire:model="showSearchModal" maxWidth="md">
        <x-slot name="title">
            {{ __('crud.search_filter') }}
        </x-slot>

        <div class="space-y-4">
            {{-- Поиск по названию матча или пользователя ---
            <div>
                <label class="form-label">{{ __('crud.search') }}</label>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="{{ __('crud.match_logists_search_placeholder') }}"
                    class="form-input"
                >
            </div>

            {{-- Фильтр по матчу --}}
            <div>
                <label class="form-label">{{ __('crud.match') }}</label>
                <select wire:model.live="filter_match_id" class="form-input">
                    <option value="">{{ __('crud.all') }}</option>
                    @foreach($this->getMatchOptions() as $id => $title)
                        <option value="{{ $id }}">{{ $title }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Фильтр по логисту ---
            <div>
                <label class="form-label">{{ __('crud.logist') }}</label>
                <select wire:model.live="filter_logist_id" class="form-input">
                    <option value="">{{ __('crud.all') }}</option>
                    @foreach($this->getLogistOptions() as $id => $title)
                        <option value="{{ $id }}">{{ $title }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Фильтр по роли логиста ---
            <div>
                <label class="form-label">{{ __('crud.logist_role_filter') }}</label>
                <select wire:model.live="filter_role" class="form-input">
                    @foreach($this->getRoleFilterOptions() as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Сортировка --}}
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
                    <button wire:click="sortBy('match_title')"
                            class="text-left px-3 py-2 rounded-md text-sm transition-colors {{ $sortField === 'match_title' ? 'btn-primary' : 'btn-secondary' }}">
                        {{ __('crud.match') }}
                        @if($sortField === 'match_title')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </button>
                    <button wire:click="sortBy('logist_name')"
                            class="text-left px-3 py-2 rounded-md text-sm transition-colors {{ $sortField === 'logist_name' ? 'btn-primary' : 'btn-secondary' }}">
                        {{ __('crud.logist') }}
                        @if($sortField === 'logist_name')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </button>
                    <button wire:click="sortBy('match_date')"
                            class="text-left px-3 py-2 rounded-md text-sm transition-colors {{ $sortField === 'match_date' ? 'btn-primary' : 'btn-secondary' }}">
                        {{ __('crud.date') }}
                        @if($sortField === 'match_date')
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

    {{-- Модальное окно: Создание / Редактирование --}}
    <x-modal wire:model="showCreateModal" maxWidth="md">
        <x-slot name="title">
            {{ $editingId ? __('crud.edit_match_logist') : __('crud.create_match_logist') }}
        </x-slot>

        <div class="space-y-4">
            {{-- Матч * --}}
            <div>
                <label class="form-label">
                    {{ __('crud.match') }} <span style="color: var(--color-danger);">*</span>
                </label>
                <select wire:model="form_match_id"
                        class="form-input @error('form_match_id') is-invalid @enderror">
                    <option value="">{{ __('crud.select_match') }}</option>
                    @foreach($this->getMatchOptions() as $id => $title)
                        <option value="{{ $id }}">{{ $title }}</option>
                    @endforeach
                </select>
                @error('form_match_id') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Логист * --}}
            <div>
                <label class="form-label">
                    {{ __('crud.logist') }} <span style="color: var(--color-danger);">*</span>
                </label>
                <select wire:model="form_logist_id"
                        class="form-input @error('form_logist_id') is-invalid @enderror">
                    <option value="">{{ __('crud.select_logist') }}</option>
                    @foreach($this->getLogistOptions() as $id => $title)
                        <option value="{{ $id }}">{{ $title }}</option>
                    @endforeach
                </select>
                @error('form_logist_id') <p class="form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <x-slot name="footer">
            <button wire:click="$set('showCreateModal', false)" class="btn-secondary">
                {{ __('crud.cancel') }}
            </button>
            <button wire:click="save" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">{{ __('crud.save') }}</span>
                <span wire:loading wire:target="save">{{ __('ui.loading') }}</span>
            </button>
        </x-slot>
    </x-modal>

    {{-- Модальное окно: Подтверждение удаления --}}
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
