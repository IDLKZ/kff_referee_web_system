{{--
    JSON / tag-like input с поддержкой Livewire
    Для полей типа phone (json), extensions (json), etc.
    @include('shared.common_form_fields.json_input', [
        'model'       => 'form.phone',
        'label'       => 'Телефоны',
        'placeholder' => '+7 (XXX) XXX-XX-XX',  // optional
        'addMethod'   => 'addPhone',             // Livewire method для добавления элемента
        'removeMethod'=> 'removePhone',          // Livewire method для удаления элемента
        'disabled'    => false,                   // optional
    ])
--}}
@php
    $disabled = $disabled ?? false;
    $placeholder = $placeholder ?? '';
    $fieldName = str_replace(['form.', '.'], ['', '_'], $model);
    $items = $items ?? [];
@endphp

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>

    {{-- Список текущих элементов --}}
    <div class="flex flex-wrap gap-2 mb-2">
        @foreach($items as $index => $item)
            <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">
                {{ $item }}
                @unless($disabled)
                    <button type="button" wire:click="{{ $removeMethod }}({{ $index }})" class="text-blue-600 hover:text-red-600">&times;</button>
                @endunless
            </span>
        @endforeach
    </div>

    {{-- Поле для добавления --}}
    @unless($disabled)
        <div class="flex gap-2">
            <input
                id="{{ $fieldName }}_new"
                type="text"
                wire:model="{{ $model }}_new"
                wire:keydown.enter.prevent="{{ $addMethod }}"
                placeholder="{{ $placeholder }}"
                class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
            >
            <button
                type="button"
                wire:click="{{ $addMethod }}"
                class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700"
            >+</button>
        </div>
    @endunless

    @error($model)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
