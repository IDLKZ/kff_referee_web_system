@section('page-title', __('ui.room_facilities'))

<div>
    {{-- Заголовок --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold" style="color: var(--text-primary);">
            {{ __('ui.room_facilities') }}
        </h2>

        <div class="flex items-center gap-3">
            {{-- Кнопка Поиск и фильтр --}}
            <button wire:click="toggleSearchModal" class="btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                {{ __('crud.search_filter') }}
                @if($search || $filterHotel || $filterFacility)
                    <span class="ml-1 w-2 h-2 rounded-full" style="background: var(--color-primary);"></span>
                @endif
            </button>

            {{-- Кнопка Создать --}}
            @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::ROOM_FACILITIES_CREATE))
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
                        {{-- Отель (сортируемый) --}}
                        <th class="cursor-pointer hover:bg-opacity-80 transition-colors" wire:click="sortBy('hotel')">
                            <div class="flex items-center gap-1">
                                {{ __('crud.hotel') }}
                                @if($sortField === 'hotel')
                                    <svg class="w-3 h-3" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </div>
                        </th>

                        {{-- Номер (сортируемый) --}}
                        <th class="cursor-pointer hover:bg-opacity-80 transition-colors" wire:click="sortBy('room')">
                            <div class="flex items-center gap-1">
                                {{ __('crud.room') }}
                                @if($sortField === 'room')
                                    <svg class="w-3 h-3" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </div>
                        </th>

                        {{-- Удобство (сортируемый) --}}
                        <th class="cursor-pointer hover:bg-opacity-80 transition-colors" wire:click="sortBy('facility')">
                            <div class="flex items-center gap-1">
                                {{ __('crud.facility') }}
                                @if($sortField === 'facility')
                                    <svg class="w-3 h-3" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </div>
                        </th>

                        {{-- Действия --}}
                        <th>{{ __('crud.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roomFacilities as $rf)
                        <tr wire:key="rf-{{ $rf->room_id }}-{{ $rf->facility_id }}">
                            {{-- Отель --}}
                            <td>
                                @if($rf->hotel_room && $rf->hotel_room->hotel)
                                    <span class="font-medium">{{ $rf->hotel_room->hotel->title_ru }}</span>
                                @else
                                    <span style="color: var(--text-muted);">—</span>
                                @endif
                            </td>

                            {{-- Номер --}}
                            <td>
                                @if($rf->hotel_room)
                                    {{ $rf->hotel_room->title_ru }}
                                @else
                                    <span style="color: var(--text-muted);">—</span>
                                @endif
                            </td>

                            {{-- Удобство --}}
                            <td>
                                @if($rf->facility)
                                    <span class="badge" style="background: var(--bg-hover); color: var(--text-secondary);">
                                        {{ $rf->facility->title_ru }}
                                    </span>
                                @else
                                    <span style="color: var(--text-muted);">—</span>
                                @endif
                            </td>

                            {{-- Действия --}}
                            <td>
                                <div class="flex items-center gap-1">
                                    @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::ROOM_FACILITIES_DELETE))
                                        <button wire:click="confirmDelete({{ $rf->room_id }}, {{ $rf->facility_id }})"
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
                            <td colspan="4" class="text-center py-8" style="color: var(--text-muted);">
                                {{ __('crud.no_results') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Пагинация --}}
        @if($roomFacilities->hasPages())
            <div class="px-6 py-4" style="border-top: 1px solid var(--border-color);">
                {{ $roomFacilities->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>

    {{-- Модальное окно: Поиск и фильтр --}}
    <x-modal wire:model="showSearchModal" maxWidth="md">
        <x-slot name="title">
            {{ __('crud.search_filter') }}
        </x-slot>

        <div class="space-y-4">
            {{-- Поиск --}}
            <div>
                <label class="form-label">{{ __('crud.search') }}</label>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="{{ __('crud.search_placeholder') }}"
                    class="form-input"
                >
            </div>

            {{-- Фильтр по отелю --}}
            <div>
                <label class="form-label">{{ __('crud.hotel') }}</label>
                <select wire:model.live="filterHotel" class="form-input">
                    <option value="">{{ __('crud.all_hotels') }}</option>
                    @foreach($this->getHotelOptions() as $id => $title)
                        <option value="{{ $id }}">{{ $title }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Фильтр по удобству --}}
            <div>
                <label class="form-label">{{ __('crud.facility') }}</label>
                <select wire:model.live="filterFacility" class="form-input">
                    <option value="">{{ __('crud.all_facilities') }}</option>
                    @foreach($this->getFacilityOptions() as $id => $title)
                        <option value="{{ $id }}">{{ $title }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Сортировка --}}
            <div>
                <label class="form-label">{{ __('crud.sort_by') }}</label>
                <div class="grid grid-cols-3 gap-2">
                    <button wire:click="sortBy('hotel')"
                            class="text-left px-3 py-2 rounded-md text-sm transition-colors {{ $sortField === 'hotel' ? 'btn-primary' : 'btn-secondary' }}">
                        {{ __('crud.hotel') }}
                        @if($sortField === 'hotel')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </button>
                    <button wire:click="sortBy('room')"
                            class="text-left px-3 py-2 rounded-md text-sm transition-colors {{ $sortField === 'room' ? 'btn-primary' : 'btn-secondary' }}">
                        {{ __('crud.room') }}
                        @if($sortField === 'room')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </button>
                    <button wire:click="sortBy('facility')"
                            class="text-left px-3 py-2 rounded-md text-sm transition-colors {{ $sortField === 'facility' ? 'btn-primary' : 'btn-secondary' }}">
                        {{ __('crud.facility') }}
                        @if($sortField === 'facility')
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

    {{-- Модальное окно: Создание связи --}}
    <x-modal wire:model="showFormModal" maxWidth="md">
        <x-slot name="title">
            {{ __('crud.create_room_facility') }}
        </x-slot>

        <div class="space-y-4">
            {{-- Шаг 1: Выбор отеля (фильтрует номера) --}}
            <div>
                <label class="form-label">
                    {{ __('crud.hotel') }} <span style="color: var(--color-danger);">*</span>
                </label>
                <select wire:model.live="selectedHotel" class="form-input">
                    <option value="">{{ __('crud.select_hotel') }}</option>
                    @foreach($this->getHotelOptions() as $id => $title)
                        <option value="{{ $id }}">{{ $title }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Шаг 2: Выбор номера (зависит от отеля) --}}
            <div>
                <label class="form-label">
                    {{ __('crud.room') }} <span style="color: var(--color-danger);">*</span>
                </label>
                <select wire:model="room_id"
                        class="form-input @error('room_id') is-invalid @enderror"
                        {{ !$selectedHotel ? 'disabled' : '' }}>
                    <option value="">{{ __('crud.select_room') }}</option>
                    @foreach($this->getRoomOptions() as $id => $title)
                        <option value="{{ $id }}">{{ $title }}</option>
                    @endforeach
                </select>
                @if(!$selectedHotel)
                    <p class="text-xs mt-1" style="color: var(--text-muted);">
                        {{ __('crud.select_hotel_first') }}
                    </p>
                @endif
                @error('room_id') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Шаг 3: Выбор удобства --}}
            <div>
                <label class="form-label">
                    {{ __('crud.facility') }} <span style="color: var(--color-danger);">*</span>
                </label>
                <select wire:model="facility_id"
                        class="form-input @error('facility_id') is-invalid @enderror">
                    <option value="">{{ __('crud.select_facility') }}</option>
                    @foreach($this->getFacilityOptions() as $id => $title)
                        <option value="{{ $id }}">{{ $title }}</option>
                    @endforeach
                </select>
                @error('facility_id') <p class="form-error">{{ $message }}</p> @enderror
            </div>
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
                {{ $deletingDescription }}
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
