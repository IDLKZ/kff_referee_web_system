{{--
    Toggle (boolean switch) с поддержкой Livewire
    @include('shared.common_form_fields.toggle', [
        'model'    => 'form.is_active',
        'label'    => 'Активен',
        'disabled' => false,           // optional
    ])
--}}
@php
    $disabled = $disabled ?? false;
    $fieldName = str_replace(['form.', '.'], ['', '_'], $model);
@endphp

<div class="mb-4 flex items-center gap-3">
    <label for="{{ $fieldName }}" class="relative inline-flex items-center cursor-pointer">
        <input
            id="{{ $fieldName }}"
            type="checkbox"
            wire:model.live="{{ $model }}"
            @if($disabled) disabled @endif
            class="sr-only peer"
        >
        <div class="w-11 h-6 bg-gray-200 rounded-full peer
                    peer-checked:bg-blue-600 peer-focus:ring-2 peer-focus:ring-blue-300
                    after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                    after:bg-white after:border after:border-gray-300 after:rounded-full
                    after:h-5 after:w-5 after:transition-all
                    peer-checked:after:translate-x-full peer-checked:after:border-white
                    @if($disabled) opacity-50 cursor-not-allowed @endif"></div>
    </label>

    <span class="text-sm font-medium text-gray-700">{{ $label }}</span>

    @error($model)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
