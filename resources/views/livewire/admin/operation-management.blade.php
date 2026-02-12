@section('page-title', __('ui.operations'))

<div>
    {{-- Заголовок --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold" style="color: var(--text-primary);">
            {{ __('ui.operations') }}
        </h2>

        <div class="flex items-center gap-3">
            {{-- Кнопка Поиск и фильтр --}}
            <button wire:click="toggleSearchModal" class="btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                {{ __('crud.search_filter') }}
                @if($search || $filter_category_id || $filter_is_active !== null || $filter_can_reject !== null)
                    <span class="ml-1 w-2 h-2 rounded-full" style="background: var(--color-primary);"></span>
                @endif
            </button>

            {{-- Кнопка Создать --}}
            @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::OPERATIONS_CREATE))
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

                        {{-- Категория --}}
                        <th class="cursor-pointer hover:bg-opacity-80 transition-colors" wire:click="sortBy('category_id')">
                            <div class="flex items-center gap-1">
                                {{ __('crud.category') }}
                                @if($sortField === 'category_id')
                                    <svg class="w-3 h-3" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </div>
                        </th>

                        {{-- Название --}}
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

                        {{-- Системный код --}}
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

                        {{-- Статус --}}
                        <th>{{ __('crud.status') }}</th>

                        {{-- Флаги --}}
                        <th>{{ __('crud.flags') }}</th>

                        {{-- Связи --}}
                        <th>{{ __('crud.links') }}</th>

                        {{-- Действия --}}
                        <th>{{ __('crud.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($operations as $operation)
                        <tr wire:key="op-{{ $operation->id }}">
                            <td style="color: var(--text-muted);">{{ $operation->id }}</td>

                            {{-- Категория --}}
                            <td>
                                @if($operation->category_operation)
                                    <span class="text-sm font-medium">{{ $operation->category_operation->title_ru }}</span>
                                @else
                                    <span style="color: var(--text-muted);">—</span>
                                @endif
                            </td>

                            {{-- Название --}}
                            <td class="font-medium">
                                <div>{{ $operation->title_ru }}</div>
                                @if($operation->description_ru)
                                    <div class="text-xs mt-1 truncate max-w-xs" style="color: var(--text-muted);" title="{{ $operation->description_ru }}">
                                        {{ $operation->description_ru }}
                                    </div>
                                @endif
                            </td>

                            {{-- Системный код --}}
                            <td>
                                <code class="text-xs px-1.5 py-0.5 rounded"
                                      style="background: var(--bg-hover); color: var(--text-secondary);">
                                    {{ $operation->value }}
                                </code>
                            </td>

                            {{-- Статус --}}
                            <td>
                                @if($operation->is_active)
                                    <span class="badge badge-success text-xs">{{ __('crud.active') }}</span>
                                @else
                                    <span class="badge badge-secondary text-xs">{{ __('crud.inactive') }}</span>
                                @endif
                            </td>

                            {{-- Флаги --}}
                            <td>
                                <div class="flex flex-wrap gap-1">
                                    @if($operation->is_first)
                                        <span class="badge badge-info text-xs" title="{{ __('crud.is_first_hint') }}">
                                            {{ __('crud.first') }}
                                        </span>
                                    @endif
                                    @if($operation->is_last)
                                        <span class="badge badge-warning text-xs" title="{{ __('crud.is_last_hint') }}">
                                            {{ __('crud.last') }}
                                        </span>
                                    @endif
                                    @if($operation->can_reject)
                                        <span class="badge badge-danger text-xs" title="{{ __('crud.can_reject') }}">
                                            {{ __('crud.rejection') }}
                                        </span>
                                    @endif
                                    @if($operation->result !== null)
                                        <span class="badge badge-purple text-xs" title="{{ __('crud.result') }}: {{ $operation->result }}">
                                            {{ __('crud.result') }}: {{ $operation->result }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Связи --}}
                            <td>
                                <div class="text-xs space-y-1">
                                    <div>
                                        <span style="color: var(--text-muted);">{{ __('crud.previous') }}:</span>
                                        @if($operation->previous_id && $operation->operation)
                                            <span class="ml-1">{{ $operation->operation->title_ru }}</span>
                                        @else
                                            <span style="color: var(--text-muted);">—</span>
                                        @endif
                                    </div>
                                    <div>
                                        <span style="color: var(--text-muted);">{{ __('crud.next') }}:</span>
                                        @if($operation->next_id)
                                            <span class="ml-1">{{ App\Models\Operation::find($operation->next_id)?->title_ru ?? '—' }}</span>
                                        @else
                                            <span style="color: var(--text-muted);">—</span>
                                        @endif
                                    </div>
                                    @if($operation->on_reject_id)
                                        <div>
                                            <span style="color: var(--text-muted);">{{ __('crud.on_reject') }}:</span>
                                            <span class="ml-1 text-red-600">{{ App\Models\Operation::find($operation->on_reject_id)?->title_ru ?? '—' }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            {{-- Действия --}}
                            <td>
                                <div class="flex items-center gap-1">
                                    @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::OPERATIONS_UPDATE))
                                        <button wire:click="openEditModal({{ $operation->id }})"
                                                class="btn-icon btn-icon-edit"
                                                title="{{ __('crud.edit') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                    @endif

                                    @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::OPERATIONS_DELETE))
                                        <button wire:click="confirmDelete({{ $operation->id }})"
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

        {{-- Пагинация --}}
        @if($operations->hasPages())
            <div class="px-6 py-4" style="border-top: 1px solid var(--border-color);">
                {{ $operations->links('vendor.pagination.tailwind') }}
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

            {{-- Фильтр по категории --}}
            <div>
                <label class="form-label">{{ __('crud.category') }}</label>
                <select wire:model.live="filter_category_id" class="form-input">
                    <option value="">{{ __('crud.all_categories') }}</option>
                    @foreach($this->getCategoryOptions() as $id => $title)
                        <option value="{{ $id }}">{{ $title }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Фильтр по статусу --}}
            <div>
                <label class="form-label">{{ __('crud.status') }}</label>
                <select wire:model.live="filter_is_active" class="form-input">
                    <option value="">{{ __('crud.all') }}</option>
                    <option value="1">{{ __('crud.active') }}</option>
                    <option value="0">{{ __('crud.inactive') }}</option>
                </select>
            </div>

            {{-- Фильтр по возможности отклонения --}}
            <div>
                <label class="form-label">{{ __('crud.can_reject') }}</label>
                <select wire:model.live="filter_can_reject" class="form-input">
                    <option value="">{{ __('crud.all') }}</option>
                    <option value="1">{{ __('crud.yes') }}</option>
                    <option value="0">{{ __('crud.no') }}</option>
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
                    <button wire:click="sortBy('title_ru')"
                            class="text-left px-3 py-2 rounded-md text-sm transition-colors {{ $sortField === 'title_ru' ? 'btn-primary' : 'btn-secondary' }}">
                        {{ __('crud.title_ru') }}
                        @if($sortField === 'title_ru')
                            <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </button>
                    <button wire:click="sortBy('category_id')"
                            class="text-left px-3 py-2 rounded-md text-sm transition-colors {{ $sortField === 'category_id' ? 'btn-primary' : 'btn-secondary' }}">
                        {{ __('crud.category') }}
                        @if($sortField === 'category_id')
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
    <div x-data="{ show: @entangle('showFormModal') }"
         x-show="show"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display: none;">
        {{-- Backdrop --}}
        <div x-show="show"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="show = false"
             class="fixed inset-0"
             style="background: rgba(0,0,0,0.5);"></div>

        {{-- Dialog --}}
        <div x-show="show"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative w-full max-w-2xl rounded-xl overflow-hidden flex flex-col max-h-[90vh]"
             style="background: var(--bg-card); box-shadow: var(--shadow-lg);"
             @click.outside="show = false"
             @keydown.escape.window="show = false">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 flex-shrink-0"
                 style="border-bottom: 1px solid var(--border-color);">
                <h3 class="text-lg font-semibold" style="color: var(--text-primary);">
                    {{ $isEditing ? __('crud.edit_operation') : __('crud.create_operation') }}
                </h3>
                <button @click="show = false" class="p-1 rounded-md transition-colors flex-shrink-0"
                        style="color: var(--text-muted);"
                        onmouseover="this.style.color='var(--text-primary)'"
                        onmouseout="this.style.color='var(--text-muted)'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Body with scroll --}}
            <div class="px-6 py-5 overflow-y-auto">
                <div class="space-y-4">
                    {{-- Категория * --}}
                    <div>
                        <label class="form-label">
                            {{ __('crud.category') }} <span style="color: var(--color-danger);">*</span>
                        </label>
                        <select wire:model="category_id"
                                class="form-input @error('category_id') is-invalid @enderror">
                            <option value="">{{ __('crud.select_category') }}</option>
                            @foreach($this->getCategoryOptions() as $id => $title)
                                <option value="{{ $id }}">{{ $title }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Название (RU) * --}}
                    <div>
                        <label class="form-label">
                            {{ __('crud.title_ru') }} <span style="color: var(--color-danger);">*</span>
                        </label>
                        <input
                            type="text"
                            wire:model="title_ru"
                            class="form-input @error('title_ru') is-invalid @enderror"
                            placeholder="{{ __('crud.enter_title_ru') }}"
                        >
                        @error('title_ru') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Название (KK) --}}
                    <div>
                        <label class="form-label">{{ __('crud.title_kk') }}</label>
                        <input
                            type="text"
                            wire:model="title_kk"
                            class="form-input @error('title_kk') is-invalid @enderror"
                            placeholder="{{ __('crud.enter_title_kk') }}"
                        >
                        @error('title_kk') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Название (EN) --}}
                    <div>
                        <label class="form-label">{{ __('crud.title_en') }}</label>
                        <input
                            type="text"
                            wire:model="title_en"
                            class="form-input @error('title_en') is-invalid @enderror"
                            placeholder="{{ __('crud.enter_title_en') }}"
                        >
                        @error('title_en') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Описание (RU) --}}
                    <div>
                        <label class="form-label">{{ __('crud.description_ru') }}</label>
                        <textarea
                            wire:model="description_ru"
                            rows="2"
                            class="form-input @error('description_ru') is-invalid @enderror"
                            placeholder="{{ __('crud.enter_description_ru') }}"
                        ></textarea>
                        @error('description_ru') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Описание (KK) --}}
                    <div>
                        <label class="form-label">{{ __('crud.description_kk') }}</label>
                        <textarea
                            wire:model="description_kk"
                            rows="2"
                            class="form-input @error('description_kk') is-invalid @enderror"
                            placeholder="{{ __('crud.enter_description_kk') }}"
                        ></textarea>
                        @error('description_kk') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Описание (EN) --}}
                    <div>
                        <label class="form-label">{{ __('crud.description_en') }}</label>
                        <textarea
                            wire:model="description_en"
                            rows="2"
                            class="form-input @error('description_en') is-invalid @enderror"
                            placeholder="{{ __('crud.enter_description_en') }}"
                        ></textarea>
                        @error('description_en') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Системный код * --}}
                    <div>
                        <label class="form-label">
                            {{ __('crud.value') }} <span style="color: var(--color-danger);">*</span>
                        </label>
                        <input
                            type="text"
                            wire:model="value"
                            class="form-input @error('value') is-invalid @enderror"
                            placeholder="{{ __('crud.operation_value_placeholder') }}"
                        >
                        @error('value') <p class="form-error">{{ $message }}</p> @enderror
                        <p class="text-xs mt-1" style="color: var(--text-muted);">
                            {{ __('crud.operation_value_hint') }}
                        </p>
                    </div>

                    {{-- Флаги --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Первая в цепочке --}}
                        <div>
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" wire:model="is_first"
                                       class="w-4 h-4 rounded" style="accent-color: var(--color-primary);">
                                <span class="text-sm" style="color: var(--text-secondary);">{{ __('crud.is_first_label') }}</span>
                            </label>
                        </div>

                        {{-- Последняя в цепочке --}}
                        <div>
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" wire:model="is_last"
                                       class="w-4 h-4 rounded" style="accent-color: var(--color-primary);">
                                <span class="text-sm" style="color: var(--text-secondary);">{{ __('crud.is_last_label') }}</span>
                            </label>
                        </div>

                        {{-- Возможность отклонения --}}
                        <div>
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" wire:model="can_reject"
                                       class="w-4 h-4 rounded" style="accent-color: var(--color-primary);">
                                <span class="text-sm" style="color: var(--text-secondary);">{{ __('crud.can_reject') }}</span>
                            </label>
                        </div>

                        {{-- Активна --}}
                        <div>
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" wire:model="is_active"
                                       class="w-4 h-4 rounded" style="accent-color: var(--color-primary);">
                                <span class="text-sm" style="color: var(--text-secondary);">{{ __('crud.is_active') }}</span>
                            </label>
                        </div>
                    </div>

                    {{-- Результат --}}
                    <div>
                        <label class="form-label">{{ __('crud.result') }}</label>
                        <input
                            type="number"
                            wire:model="result"
                            min="0"
                            class="form-input @error('result') is-invalid @enderror"
                            placeholder="{{ __('crud.result_placeholder') }}"
                        >
                        @error('result') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Связи --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Предыдущая операция --}}
                        <div>
                            <label class="form-label">{{ __('crud.previous_operation') }}</label>
                            <select wire:model="previous_id"
                                    class="form-input @error('previous_id') is-invalid @enderror">
                                <option value="">{{ __('crud.none') }}</option>
                                @foreach($this->getOperationOptions() as $id => $title)
                                    <option value="{{ $id }}">{{ $title }}</option>
                                @endforeach
                            </select>
                            @error('previous_id') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        {{-- Следующая операция --}}
                        <div>
                            <label class="form-label">{{ __('crud.next_operation') }}</label>
                            <select wire:model="next_id"
                                    class="form-input @error('next_id') is-invalid @enderror">
                                <option value="">{{ __('crud.none') }}</option>
                                @foreach($this->getOperationOptions() as $id => $title)
                                    <option value="{{ $id }}">{{ $title }}</option>
                                @endforeach
                            </select>
                            @error('next_id') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        {{-- Операция при отказе --}}
                        <div>
                            <label class="form-label">{{ __('crud.on_reject_operation') }}</label>
                            <select wire:model="on_reject_id"
                                    class="form-input @error('on_reject_id') is-invalid @enderror">
                                <option value="">{{ __('crud.none') }}</option>
                                @foreach($this->getRejectableOperationOptions() as $id => $title)
                                    <option value="{{ $id }}">{{ $title }}</option>
                                @endforeach
                            </select>
                            @error('on_reject_id') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-end gap-3 px-6 py-4 flex-shrink-0"
                 style="border-top: 1px solid var(--border-color);">
                <button wire:click="$set('showFormModal', false)" class="btn-secondary">
                    {{ __('crud.cancel') }}
                </button>
                <button wire:click="save" class="btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">{{ __('crud.save') }}</span>
                    <span wire:loading wire:target="save">{{ __('ui.loading') }}</span>
                </button>
            </div>
        </div>
    </div>

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
