{{--
    Трёхъязычное текстовое поле (textarea) (ru / kk / en)
    @include('shared.common_form_fields.trilingual_textarea', [
        'modelPrefix' => 'form.description',  // -> form.description_ru, form.description_kk, form.description_en
        'label'       => 'Описание',
        'required'    => ['ru'],               // optional
        'rows'        => 3,                    // optional
        'disabled'    => false,                // optional
    ])
--}}
@php
    $required = $required ?? ['ru'];
    $disabled = $disabled ?? false;
    $rows = $rows ?? 3;

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

                <textarea
                    id="{{ $fieldName }}"
                    wire:model.live.debounce.300ms="{{ $fieldModel }}"
                    rows="{{ $rows }}"
                    placeholder="{{ $label }} ({{ $lang }})"
                    @if($isRequired) required @endif
                    @if($disabled) disabled @endif
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm
                           @error($fieldModel) border-red-500 @enderror
                           @if($disabled) bg-gray-100 cursor-not-allowed @endif"
                ></textarea>

                @error($fieldModel)
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        @endforeach
    </div>
</div>
