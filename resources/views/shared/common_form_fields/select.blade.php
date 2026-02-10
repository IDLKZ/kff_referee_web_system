{{--
    Select (dropdown) с поддержкой Livewire
    @include('shared.common_form_fields.select', [
        'model'       => 'form.role_id',
        'label'       => 'Роль',
        'options'     => $roles,             // коллекция или массив
        'optionValue' => 'id',               // optional, default: 'id'
        'optionLabel' => 'title_ru',         // optional, default: 'title_ru'
        'placeholder' => '-- Выберите --',   // optional
        'required'    => true,               // optional
        'disabled'    => false,              // optional
    ])
--}}
@php
    $required = $required ?? false;
    $disabled = $disabled ?? false;
    $placeholder = $placeholder ?? '-- Выберите --';
    $optionValue = $optionValue ?? 'id';
    $optionLabel = $optionLabel ?? 'title_ru';
    $fieldName = str_replace(['form.', '.'], ['', '_'], $model);
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
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $option)
            @php
                $val = is_array($option) ? ($option[$optionValue] ?? '') : ($option->$optionValue ?? '');
                $lbl = is_array($option) ? ($option[$optionLabel] ?? '') : ($option->$optionLabel ?? '');
            @endphp
            <option value="{{ $val }}">{{ $lbl }}</option>
        @endforeach
    </select>

    @error($model)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
