{{--
    Password input с поддержкой Livewire
    @include('shared.common_form_fields.password_input', [
        'model'       => 'form.password',
        'label'       => 'Пароль',
        'placeholder' => '',           // optional
        'required'    => true,         // optional
    ])
--}}
@php
    $required = $required ?? false;
    $placeholder = $placeholder ?? '';
    $fieldName = str_replace(['form.', '.'], ['', '_'], $model);
@endphp

<div class="mb-4">
    <label for="{{ $fieldName }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
        @if($required) <span class="text-red-500">*</span> @endif
    </label>

    <input
        id="{{ $fieldName }}"
        type="password"
        wire:model.live.debounce.300ms="{{ $model }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm
               @error($model) border-red-500 @enderror"
    >

    @error($model)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
