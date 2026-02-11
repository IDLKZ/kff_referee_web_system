<div x-data="{
    activeTab: 'main',
    tabs: ['main', 'descriptions']
}">
    <form wire:submit="save" class="flex flex-col h-[70vh]">
        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 shrink-0" style="border-bottom: 1px solid var(--border-color);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: var(--color-primary-light);">
                    <svg class="w-5 h-5" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold" style="color: var(--text-primary);">
                        {{ $isEditing ? __('crud.edit_tournament') : __('crud.create_tournament') }}
                    </h3>
                    <p class="text-xs" style="color: var(--text-muted);">
                        {{ $isEditing ? __('crud.edit_tournament_hint') : __('crud.create_tournament_hint') }}
                    </p>
                </div>
            </div>
            <button type="button" wire:click="$dispatch('closeModal')" class="p-2 rounded-lg transition-colors hover:bg-opacity-80"
                    style="color: var(--text-muted);" onmouseover="this.style.backgroundColor='var(--bg-hover)'" onmouseout="this.style.backgroundColor='transparent'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Tabs --}}
        <div class="flex items-center gap-1 px-6 pt-4 shrink-0">
            <button type="button" @click="activeTab = 'main'"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-all"
                    :class="activeTab === 'main' ? 'bg-opacity-100' : 'bg-opacity-0 hover:bg-opacity-50'"
                    :style="activeTab === 'main' ? 'background: var(--color-primary-light); color: var(--color-primary);' : 'color: var(--text-secondary);'">
                {{ __('crud.main_info') }}
            </button>
            <button type="button" @click="activeTab = 'descriptions'"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-all"
                    :class="activeTab === 'descriptions' ? 'bg-opacity-100' : 'bg-opacity-0 hover:bg-opacity-50'"
                    :style="activeTab === 'descriptions' ? 'background: var(--color-primary-light); color: var(--color-primary);' : 'color: var(--text-secondary);'">
                {{ __('crud.descriptions') }}
            </button>
        </div>

        {{-- Body with Scroll --}}
        <div class="flex-1 overflow-y-auto px-6 py-5">
            {{-- Main Info Tab --}}
            <div x-show="activeTab === 'main'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                    {{-- Image Upload - spans full width --}}
                    <div class="lg:col-span-2">
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
                                <input type="file" wire:model="image" class="hidden" id="tournament-image-upload" accept="image/*">
                                <label for="tournament-image-upload" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium cursor-pointer transition-colors"
                                       style="background: var(--bg-hover); color: var(--text-secondary); border: 1px solid var(--border-color);"
                                       onmouseover="this.style.backgroundColor='var(--border-color)'"
                                       onmouseout="this.style.backgroundColor='var(--bg-hover)'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    {{ $image_id || $image ? __('crud.change_image') : __('crud.upload_image') }}
                                </label>
                                @error('image') <p class="form-error mt-2">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Title RU --}}
                    <div>
                        <label class="form-label flex items-center gap-1">
                            {{ __('crud.title_ru') }}
                            <span class="w-1 h-1 rounded-full" style="background: var(--color-danger);"></span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold" style="color: var(--text-muted);">RU</span>
                            <input type="text" wire:model="title_ru"
                                   class="form-input pl-9 @error('title_ru') is-invalid @enderror"
                                   placeholder="{{ __('crud.enter_title_ru') }}">
                        </div>
                        @error('title_ru') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Title KK --}}
                    <div>
                        <label class="form-label">{{ __('crud.title_kk') }}</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold" style="color: var(--text-muted);">KK</span>
                            <input type="text" wire:model="title_kk"
                                   class="form-input pl-9 @error('title_kk') is-invalid @enderror"
                                   placeholder="{{ __('crud.enter_title_kk') }}">
                        </div>
                        @error('title_kk') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Title EN --}}
                    <div class="lg:col-span-2">
                        <label class="form-label">{{ __('crud.title_en') }}</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold" style="color: var(--text-muted);">EN</span>
                            <input type="text" wire:model="title_en"
                                   class="form-input pl-9 @error('title_en') is-invalid @enderror"
                                   placeholder="{{ __('crud.enter_title_en') }}">
                        </div>
                        @error('title_en') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Short Title RU --}}
                    <div>
                        <label class="form-label">{{ __('crud.short_title_ru') }}</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold" style="color: var(--text-muted);">RU</span>
                            <input type="text" wire:model="short_title_ru"
                                   class="form-input pl-9 @error('short_title_ru') is-invalid @enderror"
                                   placeholder="{{ __('crud.enter_short_title_ru') }}">
                        </div>
                        @error('short_title_ru') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Short Title KK --}}
                    <div>
                        <label class="form-label">{{ __('crud.short_title_kk') }}</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold" style="color: var(--text-muted);">KK</span>
                            <input type="text" wire:model="short_title_kk"
                                   class="form-input pl-9 @error('short_title_kk') is-invalid @enderror"
                                   placeholder="{{ __('crud.enter_short_title_kk') }}">
                        </div>
                        @error('short_title_kk') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Short Title EN --}}
                    <div>
                        <label class="form-label">{{ __('crud.short_title_en') }}</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold" style="color: var(--text-muted);">EN</span>
                            <input type="text" wire:model="short_title_en"
                                   class="form-input pl-9 @error('short_title_en') is-invalid @enderror"
                                   placeholder="{{ __('crud.enter_short_title_en') }}">
                        </div>
                        @error('short_title_en') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Value --}}
                    <div>
                        <label class="form-label flex items-center gap-1">
                            {{ __('crud.value') }}
                            <span class="w-1 h-1 rounded-full" style="background: var(--color-danger);"></span>
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                            </svg>
                            <input type="text" wire:model="value"
                                   class="form-input pl-10 @error('value') is-invalid @enderror"
                                   {{ $isEditing ? 'disabled' : '' }}
                                   placeholder="{{ __('crud.tournament_value_placeholder') }}">
                        </div>
                        @error('value') <p class="form-error">{{ $message }}</p> @enderror
                        @if(!$isEditing)
                            <p class="text-xs mt-1.5 flex items-center gap-1" style="color: var(--text-muted);">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ __('crud.tournament_value_hint') }}
                            </p>
                        @endif
                    </div>

                    {{-- Country --}}
                    <div>
                        <label class="form-label">{{ __('crud.country') }}</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <select wire:model="country_id"
                                    class="form-input pl-10 appearance-none @error('country_id') is-invalid @enderror">
                                <option value="">{{ __('crud.select_country') }}</option>
                                @foreach($this->getCountryOptions() as $id => $title)
                                    <option value="{{ $id }}">{{ $title }}</option>
                                @endforeach
                            </select>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                        @error('country_id') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Level --}}
                    <div>
                        <label class="form-label flex items-center gap-1">
                            {{ __('crud.level') }}
                            <span class="w-1 h-1 rounded-full" style="background: var(--color-danger);"></span>
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <input type="number" wire:model="level" min="1"
                                   class="form-input pl-10 @error('level') is-invalid @enderror"
                                   placeholder="{{ __('crud.enter_level') }}">
                        </div>
                        @error('level') <p class="form-error">{{ $message }}</p> @enderror
                        <p class="text-xs mt-1.5 flex items-center gap-1" style="color: var(--text-muted);">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('crud.level_hint') }}
                        </p>
                    </div>

                    {{-- Sex --}}
                    <div>
                        <label class="form-label flex items-center gap-1">
                            {{ __('crud.sex') }}
                            <span class="w-1 h-1 rounded-full" style="background: var(--color-danger);"></span>
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <select wire:model="sex"
                                    class="form-input pl-10 appearance-none @error('sex') is-invalid @enderror">
                                @foreach($this->getSexOptions() as $val => $label)
                                    <option value="{{ $val }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none" style="color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                        @error('sex') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Is Active - spans full width --}}
                    <div class="lg:col-span-2 flex items-center justify-between p-4 rounded-xl" style="background: var(--bg-hover);">
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
                                 style="background: var(--border-color);"
                                 ondblclick="this.style.background='var(--border-color)'"></div>
                            <div class="absolute left-0.5 top-0.5 w-5 h-5 rounded-full shadow transition-all peer-checked:translate-x-5"
                                 style="background: white;"></div>
                            <style>
                                input:checked + div { background: var(--color-primary) !important; }
                            </style>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Descriptions Tab --}}
            <div x-show="activeTab === 'descriptions'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" style="display: none;">
                <div class="space-y-5">
                    {{-- Description RU --}}
                    <div>
                        <label class="form-label flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-xs font-bold" style="background: var(--bg-hover); color: var(--text-muted);">RU</span>
                            {{ __('crud.description_ru') }}
                        </label>
                        <textarea wire:model="description_ru" rows="4"
                                  class="form-input @error('description_ru') is-invalid @enderror resize-none"
                                  placeholder="{{ __('crud.enter_description_ru') }}"></textarea>
                        @error('description_ru') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Description KK --}}
                    <div>
                        <label class="form-label flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-xs font-bold" style="background: var(--bg-hover); color: var(--text-muted);">KK</span>
                            {{ __('crud.description_kk') }}
                        </label>
                        <textarea wire:model="description_kk" rows="4"
                                  class="form-input @error('description_kk') is-invalid @enderror resize-none"
                                  placeholder="{{ __('crud.enter_description_kk') }}"></textarea>
                        @error('description_kk') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    {{-- Description EN --}}
                    <div>
                        <label class="form-label flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-xs font-bold" style="background: var(--bg-hover); color: var(--text-muted);">EN</span>
                            {{ __('crud.description_en') }}
                        </label>
                        <textarea wire:model="description_en" rows="4"
                                  class="form-input @error('description_en') is-invalid @enderror resize-none"
                                  placeholder="{{ __('crud.enter_description_en') }}"></textarea>
                        @error('description_en') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-between px-6 py-4 shrink-0" style="border-top: 1px solid var(--border-color);">
            <p class="text-xs" style="color: var(--text-muted);">
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('crud.required_fields_hint') }}
                </span>
            </p>
            <div class="flex items-center gap-3">
                <button type="button" wire:click="$dispatch('closeModal')" class="btn-secondary">
                    {{ __('crud.cancel') }}
                </button>
                <button type="submit" class="btn-primary" wire:loading.attr="disabled">
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
            </div>
        </div>
    </form>
</div>
