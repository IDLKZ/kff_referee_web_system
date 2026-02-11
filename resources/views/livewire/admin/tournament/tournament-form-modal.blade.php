<form wire:submit="save">
    {{-- Header --}}
    <div class="px-6 py-4" style="border-bottom: 1px solid var(--border-color);">
        <h3 class="text-lg font-semibold" style="color: var(--text-primary);">
            {{ $isEditing ? __('crud.edit_tournament') : __('crud.create_tournament') }}
        </h3>
    </div>

    {{-- Body --}}
    <div class="px-6 py-5 space-y-4">
        {{-- Image Upload --}}
        <div>
            <label class="form-label">{{ __('crud.tournament_image') }}</label>
            <div class="flex items-center gap-4">
                @if($image_id || $image)
                    <div class="relative">
                        @if($temporaryImageUrl)
                            <img src="{{ $temporaryImageUrl }}" class="w-16 h-16 rounded object-cover">
                        @elseif($existingImageUrl)
                            <img src="{{ $existingImageUrl }}" class="w-16 h-16 rounded object-cover">
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
                    <div class="w-16 h-16 rounded flex items-center justify-center"
                         style="background: var(--bg-hover); color: var(--text-muted);">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                @endif
                <div>
                    <input type="file" wire:model="image" class="hidden" id="tournament-image-upload">
                    <label for="tournament-image-upload" class="btn-secondary text-sm cursor-pointer">
                        {{ $image_id || $image ? __('crud.change_image') : __('crud.upload_image') }}
                    </label>
                    @error('image') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

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

        {{-- Short Title RU --}}
        <div>
            <label class="form-label">{{ __('crud.short_title_ru') }}</label>
            <input type="text" wire:model="short_title_ru"
                   class="form-input @error('short_title_ru') is-invalid @enderror">
            @error('short_title_ru') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        {{-- Short Title KK --}}
        <div>
            <label class="form-label">{{ __('crud.short_title_kk') }}</label>
            <input type="text" wire:model="short_title_kk"
                   class="form-input @error('short_title_kk') is-invalid @enderror">
            @error('short_title_kk') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        {{-- Short Title EN --}}
        <div>
            <label class="form-label">{{ __('crud.short_title_en') }}</label>
            <input type="text" wire:model="short_title_en"
                   class="form-input @error('short_title_en') is-invalid @enderror">
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
            @if(!$isEditing)
                <p class="text-xs mt-1" style="color: var(--text-muted);">
                    {{ __('crud.tournament_value_hint') }}
                </p>
            @endif
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
                   class="form-input @error('level') is-invalid @enderror">
            @error('level') <p class="form-error">{{ $message }}</p> @enderror
            <p class="text-xs mt-1" style="color: var(--text-muted);">
                {{ __('crud.level_hint') }}
            </p>
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
                      class="form-input @error('description_ru') is-invalid @enderror"></textarea>
            @error('description_ru') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        {{-- Description KK --}}
        <div>
            <label class="form-label">{{ __('crud.description_kk') }}</label>
            <textarea wire:model="description_kk" rows="3"
                      class="form-input @error('description_kk') is-invalid @enderror"></textarea>
            @error('description_kk') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        {{-- Description EN --}}
        <div>
            <label class="form-label">{{ __('crud.description_en') }}</label>
            <textarea wire:model="description_en" rows="3"
                      class="form-input @error('description_en') is-invalid @enderror"></textarea>
            @error('description_en') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        {{-- Is Active --}}
        <div class="flex items-center">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" wire:model="is_active"
                       class="w-4 h-4 rounded" style="accent-color: var(--color-primary);">
                <span class="text-sm" style="color: var(--text-secondary);">{{ __('crud.is_active') }}</span>
            </label>
        </div>
    </div>

    {{-- Footer --}}
    <div class="flex items-center justify-end gap-3 px-6 py-4"
         style="border-top: 1px solid var(--border-color);">
        <button type="button" wire:click="$dispatch('closeModal')" class="btn-secondary">
            {{ __('crud.cancel') }}
        </button>
        <button type="submit" class="btn-primary" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="save">{{ __('crud.save') }}</span>
            <span wire:loading wire:target="save">{{ __('ui.loading') }}</span>
        </button>
    </div>
</form>
