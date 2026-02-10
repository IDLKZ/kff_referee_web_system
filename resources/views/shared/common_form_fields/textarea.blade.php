{{--
    Textarea с поддержкой Livewire
    @include('shared.common_form_fields.textarea', [
        'model'       => 'form.comment',
        'label'       => 'Комментарий',
        'placeholder' => '',           // optional
        'rows'        => 3,            // optional
        'required'    => false,        // optional
        'disabled'    => false,        // optional
    ])
--}}
@php
    $required = $required ?? false;
    $disabled = $disabled ?? false;
    $rows = $rows ?? 3;
    $placeholder = $placeholder ?? '';
    $fieldName = str_replace(['form.', '.'], ['', '_'], $model);
@endphp

<div class="mb-4">
    <label for="{{ $fieldName }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
        @if($required) <span class="text-red-500">*</span> @endif
    </label>

    <textarea
        id="{{ $fieldName }}"
        wire:model.live.debounce.300ms="{{ $model }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm
               @error($model) border-red-500 @enderror
               @if($disabled) bg-gray-100 cursor-not-allowed @endif"
    ></textarea>

    @error($model)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
