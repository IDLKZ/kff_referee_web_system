{{--
    File upload с поддержкой Livewire
    @include('shared.common_form_fields.file_upload', [
        'model'    => 'form.image',
        'label'    => 'Изображение',
        'accept'   => 'image/*',          // optional: 'image/*', '.pdf,.doc', etc.
        'required' => false,               // optional
        'disabled' => false,               // optional
        'preview'  => $previewUrl ?? null, // optional: URL текущего файла
    ])
--}}
@php
    $required = $required ?? false;
    $disabled = $disabled ?? false;
    $accept = $accept ?? '*/*';
    $preview = $preview ?? null;
    $fieldName = str_replace(['form.', '.'], ['', '_'], $model);
@endphp

<div class="mb-4">
    <label for="{{ $fieldName }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
        @if($required) <span class="text-red-500">*</span> @endif
    </label>

    @if($preview)
        <div class="mb-2">
            @if(str_starts_with($accept, 'image'))
                <img src="{{ $preview }}" alt="preview" class="h-20 w-20 object-cover rounded border">
            @else
                <a href="{{ $preview }}" target="_blank" class="text-sm text-blue-600 hover:underline">Текущий файл</a>
            @endif
        </div>
    @endif

    <input
        id="{{ $fieldName }}"
        type="file"
        wire:model="{{ $model }}"
        accept="{{ $accept }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
               file:rounded-md file:border-0 file:text-sm file:font-medium
               file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100
               @error($model) border-red-500 @enderror"
    >

    <div wire:loading wire:target="{{ $model }}" class="mt-1 text-sm text-blue-600">
        Загрузка...
    </div>

    @error($model)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
