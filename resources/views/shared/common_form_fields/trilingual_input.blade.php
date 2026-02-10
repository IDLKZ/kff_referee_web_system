{{--
    Трёхъязычное текстовое поле (ru / kk / en)
    @include('shared.common_form_fields.trilingual_input', [
        'modelPrefix' => 'form.title',      // -> form.title_ru, form.title_kk, form.title_en
        'label'       => 'Название',
        'required'    => ['ru'],             // optional: какие языки обязательны
        'maxlength'   => 255,               // optional
        'disabled'    => false,              // optional
    ])
--}}
@php
    $required = $required ?? ['ru'];
    $disabled = $disabled ?? false;
    $maxlength = $maxlength ?? 255;

    $languages = [
        'ru' => 'Русский',
        'kk' => 'Қазақша',
        'en' => 'English',
    ];
@endphp

<div class="mb-4">
    <span class="block text-sm font-medium text-gray-700 mb-2">
        {{ $label }}
    </span>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        @foreach($languages as $lang => $langLabel)
            @php
                $fieldModel = "{$modelPrefix}_{$lang}";
                $fieldName = str_replace(['form.', '.'], ['', '_'], $fieldModel);
                $isRequired = in_array($lang, $required);
            @endphp

            <div>
                <label for="{{ $fieldName }}" class="block text-xs text-gray-500 mb-1">
                    {{ $langLabel }}
                    @if($isRequired) <span class="text-red-500">*</span> @endif
                </label>

                <input
                    id="{{ $fieldName }}"
                    type="text"
                    wire:model.live.debounce.300ms="{{ $fieldModel }}"
                    placeholder="{{ $label }} ({{ $lang }})"
                    maxlength="{{ $maxlength }}"
                    @if($isRequired) required @endif
                    @if($disabled) disabled @endif
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm
                           @error($fieldModel) border-red-500 @enderror
                           @if($disabled) bg-gray-100 cursor-not-allowed @endif"
                >

                @error($fieldModel)
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        @endforeach
    </div>
</div>
