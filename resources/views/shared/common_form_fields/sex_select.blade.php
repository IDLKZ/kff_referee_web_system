{{--
    Выбор пола (tinyInteger: 0=не указан, 1=мужской, 2=женский)
    @include('shared.common_form_fields.sex_select', [
        'model'    => 'form.sex',
        'label'    => 'Пол',
        'required' => false,           // optional
        'disabled' => false,           // optional
    ])
--}}
@php
    $required = $required ?? false;
    $disabled = $disabled ?? false;
    $fieldName = str_replace(['form.', '.'], ['', '_'], $model);

    $options = [
        0 => 'Не указан',
        1 => 'Мужской',
        2 => 'Женский',
    ];
@endphp

<div class="mb-4">
    <label for="{{ $fieldName }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
        @if($required) <span class="text-red-500">*</span> @endif
    </label>

    <select
        id="{{ $fieldName }}"
        wire:model.live="{{ $model }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm
               @error($model) border-red-500 @enderror
               @if($disabled) bg-gray-100 cursor-not-allowed @endif"
    >
        @foreach($options as $val => $lbl)
            <option value="{{ $val }}">{{ $lbl }}</option>
        @endforeach
    </select>

    @error($model)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
