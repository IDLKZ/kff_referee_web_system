{{--
    Date input с поддержкой Livewire
    @include('shared.common_form_fields.date_input', [
        'model'    => 'form.birth_date',
        'label'    => 'Дата рождения',
        'required' => false,           // optional
        'disabled' => false,           // optional
        'min'      => '1950-01-01',    // optional
        'max'      => '2030-12-31',    // optional
    ])
--}}
@php
    $required = $required ?? false;
    $disabled = $disabled ?? false;
    $min = $min ?? null;
    $max = $max ?? null;
    $fieldName = str_replace(['form.', '.'], ['', '_'], $model);
@endphp

<div class="mb-4">
    <label for="{{ $fieldName }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
        @if($required) <span class="text-red-500">*</span> @endif
    </label>

    <input
        id="{{ $fieldName }}"
        type="date"
        wire:model.live="{{ $model }}"
        @if($min) min="{{ $min }}" @endif
        @if($max) max="{{ $max }}" @endif
        @if($required) required @endif
        @if($disabled) disabled @endif
        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm
               @error($model) border-red-500 @enderror
               @if($disabled) bg-gray-100 cursor-not-allowed @endif"
    >

    @error($model)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
