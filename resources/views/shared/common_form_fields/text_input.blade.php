{{--
    Text input с поддержкой Livewire
    @include('shared.common_form_fields.text_input', [
        'model'       => 'form.value',        // wire:model
        'label'       => 'Системный код',
        'placeholder' => 'Введите значение',  // optional
        'type'        => 'text',               // optional: text, email, tel, url
        'required'    => true,                 // optional
        'disabled'    => false,                // optional
        'maxlength'   => 280,                 // optional
    ])
--}}
@php
    $type = $type ?? 'text';
    $required = $required ?? false;
    $disabled = $disabled ?? false;
    $placeholder = $placeholder ?? '';
    $maxlength = $maxlength ?? null;
    $fieldName = str_replace(['form.', '.'], ['', '_'], $model);
@endphp

<div class="mb-4">
    <label for="{{ $fieldName }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
        @if($required) <span class="text-red-500">*</span> @endif
    </label>

    <input
        id="{{ $fieldName }}"
        type="{{ $type }}"
        wire:model.live.debounce.300ms="{{ $model }}"
        placeholder="{{ $placeholder }}"
        @if($maxlength) maxlength="{{ $maxlength }}" @endif
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
