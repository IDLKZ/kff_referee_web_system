{{--
    Выбор статуса (tinyInteger: -1=отклонён, 0=ожидание, 1=одобрен)
    @include('shared.common_form_fields.status_select', [
        'model'    => 'form.judge_response',
        'label'    => 'Ответ судьи',
        'options'  => null,            // optional: свой массив [value => label]
        'required' => false,           // optional
        'disabled' => false,           // optional
    ])
--}}
@php
    $required = $required ?? false;
    $disabled = $disabled ?? false;
    $fieldName = str_replace(['form.', '.'], ['', '_'], $model);

    $options = $options ?? [
        0  => 'Ожидание',
        1  => 'Одобрен',
        -1 => 'Отклонён',
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
