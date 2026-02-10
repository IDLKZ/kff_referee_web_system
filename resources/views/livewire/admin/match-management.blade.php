@section('page-title', __('ui.matches'))

<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold" style="color: var(--text-primary);">
            {{ __('ui.matches') }}
        </h2>

        <div class="flex items-center gap-3">
            {{-- Search & Filter button --}}
            <button wire:click="toggleSearchModal" class="btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                {{ __('crud.search_filter') }}
                @if($search || $filter_tournament_id || $filter_season_id || $filter_is_active !== null || $filter_is_finished !== null || $filter_is_canceled !== null)
                    <span class="ml-1 w-2 h-2 rounded-full" style="background: var(--color-primary);"></span>
                @endif
            </button>

            {{-- Create button --}}
            @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::MATCHES_CREATE))
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
                        <th class="cursor-pointer hover:bg-opacity-80 transition-colors" wire:click="sortBy('tournament')">
                            <div class="flex items-center gap-1">
                                {{ __('crud.tournament') }}
                                @if($sortField === 'tournament')
                                    <svg class="w-3 h-3" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th>{{ __('crud.match') }}</th>
                        <th>{{ __('crud.score') }}</th>
                        <th>{{ __('crud.round') }}</th>
                        <th class="cursor-pointer hover:bg-opacity-80 transition-colors" wire:click="sortBy('start_at')">
                            <div class="flex items-center gap-1">
                                {{ __('crud.start_at') }}
                                @if($sortField === 'start_at')
                                    <svg class="w-3 h-3" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th>{{ __('crud.status') }}</th>
                        <th>{{ __('crud.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($matches as $match)
                        <tr wire:key="match-{{ $match->id }}" @if($match->trashed()) style="opacity: 0.5;" @endif>
                            <td style="color: var(--text-muted);">{{ $match->id }}</td>
                            <td>
                                @if($match->tournament)
                                    <span class="font-medium">{{ $match->tournament->{'title_' . app()->getLocale()} ?? $match->tournament->title_ru }}</span>
                                    @if($match->season)
                                        <div class="text-xs" style="color: var(--text-muted);">
                                            {{ $match->season->{'title_' . app()->getLocale()} ?? $match->season->title_ru }}
                                        </div>
                                    @endif
                                @else
                                    <span style="color: var(--text-muted);">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="font-medium">
                                    {{ $match->ownerClub->short_name_ru ?? '?' }}
                                    <span style="color: var(--text-muted);">{{ __('crud.vs') }}</span>
                                    {{ $match->guestClub->short_name_ru ?? '?' }}
                                </div>
                                @if($match->stadium)
                                    <div class="text-xs" style="color: var(--text-muted);">
                                        {{ $match->stadium->{'title_' . app()->getLocale()} ?? $match->stadium->title_ru }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($match->owner_point !== null && $match->guest_point !== null)
                                    <span class="font-bold">{{ $match->owner_point }} : {{ $match->guest_point }}</span>
                                @else
                                    <span style="color: var(--text-muted);">—</span>
                                @endif
                            </td>
                            <td style="color: var(--text-muted);">{{ $match->round ?? '—' }}</td>
                            <td>
                                @if($match->start_at)
                                    <div class="text-sm">{{ $match->start_at->format('d.m.Y') }}</div>
                                    <div class="text-xs" style="color: var(--text-muted);">{{ $match->start_at->format('H:i') }}</div>
                                @else
                                    <span style="color: var(--text-muted);">—</span>
                                @endif
                            </td>
                            <td>
                                @if($match->trashed())
                                    <span class="badge badge-danger">{{ __('crud.delete') }}</span>
                                @elseif($match->is_canceled)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                          style="background: var(--color-danger-light); color: var(--color-danger);">
                                        {{ __('crud.canceled') }}
                                    </span>
                                @elseif($match->is_finished)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                          style="background: var(--color-success-light); color: var(--color-success);">
                                        {{ __('crud.finished') }}
                                    </span>
                                @elseif($match->is_active)
                                    <span class="badge badge-success">{{ __('crud.active') }}</span>
                                @else
                                    <span class="badge badge-danger">{{ __('crud.inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::MATCHES_UPDATE))
                                        <button wire:click="openEditModal({{ $match->id }})"
                                                class="btn-icon btn-icon-edit"
                                                title="{{ __('crud.edit') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                    @endif

                                    @if(auth()->user()->hasPermission(\App\Constants\PermissionConstants::MATCHES_DELETE))
                                        <button wire:click="confirmDelete({{ $match->id }})"
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
        @if($matches->hasPages())
            <div class="px-6 py-4" style="border-top: 1px solid var(--border-color);">
                {{ $matches->links('vendor.pagination.tailwind') }}
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
                <select wire:model.live="filter_tournament_id" class="form-input">
                    <option value="">{{ __('crud.all_tournaments') }}</option>
                    @foreach($this->getTournamentOptions() as $t)
                        <option value="{{ $t->id }}">{{ $t->{'title_' . app()->getLocale()} ?? $t->title_ru }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter by Season --}}
            <div>
                <label class="form-label">{{ __('crud.season') }}</label>
                <select wire:model.live="filter_season_id" class="form-input">
                    <option value="">{{ __('crud.all_seasons') }}</option>
                    @foreach($this->getSeasonOptions() as $s)
                        <option value="{{ $s->id }}">{{ $s->{'title_' . app()->getLocale()} ?? $s->title_ru }}</option>
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

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">{{ __('crud.is_finished') }}</label>
                    <select wire:model.live="filter_is_finished" class="form-input">
                        <option value="">{{ __('crud.all') }}</option>
                        <option value="1">{{ __('crud.yes') }}</option>
                        <option value="0">{{ __('crud.no') }}</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">{{ __('crud.is_canceled') }}</label>
                    <select wire:model.live="filter_is_canceled" class="form-input">
                        <option value="">{{ __('crud.all') }}</option>
                        <option value="1">{{ __('crud.yes') }}</option>
                        <option value="0">{{ __('crud.no') }}</option>
                    </select>
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
    <x-modal wire:model="showFormModal" maxWidth="xl">
        <x-slot name="title">
            {{ $isEditing ? __('crud.edit_match') : __('crud.create_match') }}
        </x-slot>

        <form wire:submit="save">
            <div class="space-y-4 max-h-[70vh] overflow-y-auto pr-1">
                {{-- Tournament --}}
                <div>
                    <label class="form-label">{{ __('crud.tournament') }} <span style="color:var(--color-danger);">*</span></label>
                    <select wire:model="tournament_id"
                            class="form-input @error('tournament_id') is-invalid @enderror">
                        <option value="">{{ __('crud.select_tournament') }}</option>
                        @foreach($this->getTournamentOptions() as $t)
                            <option value="{{ $t->id }}">{{ $t->{'title_' . app()->getLocale()} ?? $t->title_ru }}</option>
                        @endforeach
                    </select>
                    @error('tournament_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Season --}}
                <div>
                    <label class="form-label">{{ __('crud.season') }} <span style="color:var(--color-danger);">*</span></label>
                    <select wire:model="season_id"
                            class="form-input @error('season_id') is-invalid @enderror">
                        <option value="">{{ __('crud.select_season') }}</option>
                        @foreach($this->getSeasonOptions() as $s)
                            <option value="{{ $s->id }}">{{ $s->{'title_' . app()->getLocale()} ?? $s->title_ru }}</option>
                        @endforeach
                    </select>
                    @error('season_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Stadium --}}
                <div>
                    <label class="form-label">{{ __('crud.stadium') }} <span style="color:var(--color-danger);">*</span></label>
                    <select wire:model.live="stadium_id"
                            class="form-input @error('stadium_id') is-invalid @enderror">
                        <option value="">{{ __('crud.select_stadium') }}</option>
                        @foreach($this->getStadiumOptions() as $st)
                            <option value="{{ $st->id }}">{{ $st->{'title_' . app()->getLocale()} ?? $st->title_ru }}</option>
                        @endforeach
                    </select>
                    @error('stadium_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- City (auto-filled) --}}
                <div>
                    <label class="form-label">{{ __('crud.city') }} <span style="color:var(--color-danger);">*</span></label>
                    <select wire:model="city_id"
                            class="form-input @error('city_id') is-invalid @enderror"
                            style="pointer-events: none; opacity: 0.7;">
                        <option value="">—</option>
                        @php
                            $cities = \App\Models\City::orderBy('title_ru')->get(['id', 'title_ru', 'title_kk', 'title_en']);
                        @endphp
                        @foreach($cities as $c)
                            <option value="{{ $c->id }}">{{ $c->{'title_' . app()->getLocale()} ?? $c->title_ru }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs mt-1" style="color: var(--text-muted);">{{ __('crud.auto_filled_from_stadium') }}</p>
                    @error('city_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Home Club --}}
                <div>
                    <label class="form-label">{{ __('crud.home_club') }} <span style="color:var(--color-danger);">*</span></label>
                    <select wire:model="owner_club_id"
                            class="form-input @error('owner_club_id') is-invalid @enderror">
                        <option value="">{{ __('crud.select_club') }}</option>
                        @foreach($this->getClubOptions() as $club)
                            <option value="{{ $club->id }}">{{ $club->short_name_ru }}</option>
                        @endforeach
                    </select>
                    @error('owner_club_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Guest Club --}}
                <div>
                    <label class="form-label">{{ __('crud.guest_club') }} <span style="color:var(--color-danger);">*</span></label>
                    <select wire:model="guest_club_id"
                            class="form-input @error('guest_club_id') is-invalid @enderror">
                        <option value="">{{ __('crud.select_club') }}</option>
                        @foreach($this->getClubOptions() as $club)
                            <option value="{{ $club->id }}">{{ $club->short_name_ru }}</option>
                        @endforeach
                    </select>
                    @error('guest_club_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Round --}}
                <div>
                    <label class="form-label">{{ __('crud.round') }}</label>
                    <input type="number" wire:model="round" min="1"
                           class="form-input @error('round') is-invalid @enderror">
                    @error('round') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Start Date --}}
                <div>
                    <label class="form-label">{{ __('crud.start_at') }} <span style="color:var(--color-danger);">*</span></label>
                    <input type="datetime-local" wire:model="start_at"
                           class="form-input @error('start_at') is-invalid @enderror">
                    @error('start_at') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- End Date --}}
                <div>
                    <label class="form-label">{{ __('crud.end_at') }}</label>
                    <input type="datetime-local" wire:model="end_at"
                           class="form-input @error('end_at') is-invalid @enderror">
                    @error('end_at') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Winner --}}
                <div>
                    <label class="form-label">{{ __('crud.winner') }}</label>
                    <select wire:model="winner_id"
                            class="form-input @error('winner_id') is-invalid @enderror">
                        <option value="">{{ __('crud.no_winner') }}</option>
                        @if($owner_club_id)
                            @php $ownerClub = \App\Models\Club::find($owner_club_id); @endphp
                            @if($ownerClub)
                                <option value="{{ $ownerClub->id }}">{{ $ownerClub->short_name_ru }}</option>
                            @endif
                        @endif
                        @if($guest_club_id)
                            @php $guestClub = \App\Models\Club::find($guest_club_id); @endphp
                            @if($guestClub)
                                <option value="{{ $guestClub->id }}">{{ $guestClub->short_name_ru }}</option>
                            @endif
                        @endif
                    </select>
                    @error('winner_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Home Score --}}
                <div>
                    <label class="form-label">{{ __('crud.home_score') }}</label>
                    <input type="number" wire:model="owner_point" min="0"
                           class="form-input @error('owner_point') is-invalid @enderror">
                    @error('owner_point') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Guest Score --}}
                <div>
                    <label class="form-label">{{ __('crud.guest_score') }}</label>
                    <input type="number" wire:model="guest_point" min="0"
                           class="form-input @error('guest_point') is-invalid @enderror">
                    @error('guest_point') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Status checkboxes --}}
                <div>
                    <div class="flex items-center gap-6 mb-3">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" wire:model="is_active"
                                   class="w-4 h-4 rounded" style="accent-color: var(--color-primary);">
                            <span class="text-sm" style="color: var(--text-secondary);">{{ __('crud.is_active') }}</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" wire:model="is_finished"
                                   class="w-4 h-4 rounded" style="accent-color: var(--color-primary);">
                            <span class="text-sm" style="color: var(--text-secondary);">{{ __('crud.is_finished') }}</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" wire:model.live="is_canceled"
                                   class="w-4 h-4 rounded" style="accent-color: var(--color-primary);">
                            <span class="text-sm" style="color: var(--text-secondary);">{{ __('crud.is_canceled') }}</span>
                        </label>
                    </div>
                </div>

                {{-- Cancel Reason (shown if is_canceled) --}}
                @if($is_canceled)
                    <div>
                        <label class="form-label">{{ __('crud.cancel_reason') }} <span style="color:var(--color-danger);">*</span></label>
                        <textarea wire:model="cancel_reason" rows="2"
                                  class="form-input @error('cancel_reason') is-invalid @enderror"></textarea>
                        @error('cancel_reason') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                @endif

                {{-- Info --}}
                <div>
                    <label class="form-label">{{ __('crud.info') }}</label>
                    <textarea wire:model="info" rows="2"
                              class="form-input @error('info') is-invalid @enderror"
                              placeholder="JSON"></textarea>
                    @error('info') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Current Operation --}}
                <div>
                    <label class="form-label">{{ __('crud.current_operation') }}</label>
                    <select wire:model="current_operation_id"
                            class="form-input @error('current_operation_id') is-invalid @enderror">
                        <option value="">{{ __('crud.select_operation') }}</option>
                        @foreach($this->getOperationOptions() as $op)
                            <option value="{{ $op->id }}">{{ $op->{'title_' . app()->getLocale()} ?? $op->title_ru }}</option>
                        @endforeach
                    </select>
                    @error('current_operation_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                {{-- Judge Requirements Section --}}
                <div style="border-top: 1px solid var(--border-color); padding-top: 1rem;">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-semibold" style="color: var(--text-primary);">
                            {{ __('crud.judge_requirements_section') }}
                        </h4>
                        <button type="button" wire:click="addJudgeRequirement" class="btn-secondary text-sm">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ __('crud.add_requirement') }}
                        </button>
                    </div>

                    @if(count($judgeRequirements) > 0)
                        <div class="space-y-2">
                            @foreach($judgeRequirements as $index => $req)
                                <div class="flex items-center gap-3 p-3 rounded-lg" style="background: var(--bg-hover);" wire:key="jr-{{ $index }}">
                                    <div class="flex-1">
                                        <select wire:model="judgeRequirements.{{ $index }}.judge_type_id" class="form-input text-sm">
                                            <option value="">{{ __('crud.select_judge_type') }}</option>
                                            @foreach($this->getJudgeTypeOptions() as $jt)
                                                <option value="{{ $jt->id }}">{{ $jt->{'title_' . app()->getLocale()} ?? $jt->title_ru }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="w-20">
                                        <input type="number" wire:model="judgeRequirements.{{ $index }}.qty"
                                               min="1" max="100" class="form-input text-sm" placeholder="{{ __('crud.qty') }}">
                                    </div>
                                    <label class="flex items-center gap-1 cursor-pointer select-none whitespace-nowrap">
                                        <input type="checkbox" wire:model="judgeRequirements.{{ $index }}.is_required"
                                               class="w-4 h-4 rounded" style="accent-color: var(--color-primary);">
                                        <span class="text-xs" style="color: var(--text-secondary);">{{ __('crud.is_required') }}</span>
                                    </label>
                                    <button type="button" wire:click="removeJudgeRequirement({{ $index }})"
                                            class="p-1 rounded transition-colors"
                                            style="color: var(--color-danger);">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm" style="color: var(--text-muted);">{{ __('crud.no_results') }}</p>
                    @endif
                </div>

                {{-- Match Logists Section --}}
                <div style="border-top: 1px solid var(--border-color); padding-top: 1rem;">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-semibold" style="color: var(--text-primary);">
                            {{ __('crud.match_logists_section') }}
                        </h4>
                    </div>

                    {{-- Add logist --}}
                    <div class="flex items-center gap-2 mb-3">
                        <select wire:model="newLogistId" class="form-input flex-1 text-sm">
                            <option value="">{{ __('crud.select_logist') }}</option>
                            @foreach($this->getLogistUsers() as $logist)
                                @if(!in_array($logist->id, $matchLogists))
                                    <option value="{{ $logist->id }}">{{ $logist->last_name }} {{ $logist->first_name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <button type="button" wire:click="addLogist" class="btn-secondary text-sm">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ __('crud.add_logist') }}
                        </button>
                    </div>

                    {{-- Logist list --}}
                    @if(count($matchLogists) > 0)
                        <div class="space-y-1">
                            @foreach($matchLogists as $index => $logistId)
                                @php $logistUser = \App\Models\User::find($logistId); @endphp
                                @if($logistUser)
                                    <div class="flex items-center justify-between p-2 rounded-lg" style="background: var(--bg-hover);" wire:key="ml-{{ $index }}">
                                        <span class="text-sm" style="color: var(--text-primary);">
                                            {{ $logistUser->last_name }} {{ $logistUser->first_name }}
                                            @if($logistUser->patronymic)
                                                {{ $logistUser->patronymic }}
                                            @endif
                                        </span>
                                        <button type="button" wire:click="removeLogist({{ $index }})"
                                                class="p-1 rounded transition-colors"
                                                style="color: var(--color-danger);">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm" style="color: var(--text-muted);">{{ __('crud.no_results') }}</p>
                    @endif
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
                @if($deletingIsTrashed)
                    {{ __('crud.confirm_force_delete_text') }}
                @else
                    {{ __('crud.confirm_soft_delete_text') }}
                @endif
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
                <span wire:loading.remove wire:target="delete">
                    {{ $deletingIsTrashed ? __('crud.yes_force_delete') : __('crud.yes_delete') }}
                </span>
                <span wire:loading wire:target="delete">{{ __('ui.loading') }}</span>
            </button>
        </x-slot>
    </x-modal>
</div>
