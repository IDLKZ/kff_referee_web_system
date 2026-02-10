{{--
    DateTime input с поддержкой Livewire
    @include('shared.common_form_fields.datetime_input', [
        'model'    => 'form.start_at',
        'label'    => 'Начало матча',
        'required' => true,            // optional
        'disabled' => false,           // optional
    ])
--}}
@php
    $required = $required ?? false;
    $disabled = $disabled ?? false;
    $fieldName = str_replace(['form.', '.'], ['', '_'], $model);
@endphp

<div class="mb-4">
    <label for="{{ $fieldName }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
        @if($required) <span class="text-red-500">*</span> @endif
    </label>

    <input
        id="{{ $fieldName }}"
        type="datetime-local"
        wire:model.live="{{ $model }}"
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
