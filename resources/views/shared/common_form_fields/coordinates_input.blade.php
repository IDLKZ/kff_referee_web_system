{{--
    Координаты (lat / lon)
    @include('shared.common_form_fields.coordinates_input', [
        'latModel' => 'form.lat',
        'lonModel' => 'form.lon',
        'label'    => 'Координаты',
        'required' => false,           // optional
        'disabled' => false,           // optional
    ])
--}}
@php
    $required = $required ?? false;
    $disabled = $disabled ?? false;
    $latName = str_replace(['form.', '.'], ['', '_'], $latModel);
    $lonName = str_replace(['form.', '.'], ['', '_'], $lonModel);
@endphp

<div class="mb-4">
    <span class="block text-sm font-medium text-gray-700 mb-2">
        {{ $label }}
        @if($required) <span class="text-red-500">*</span> @endif
    </span>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <label for="{{ $latName }}" class="block text-xs text-gray-500 mb-1">Широта (lat)</label>
            <input
                id="{{ $latName }}"
                type="number"
                wire:model.live.debounce.300ms="{{ $latModel }}"
                step="0.0000001"
                min="-90" max="90"
                placeholder="43.2380000"
                @if($required) required @endif
                @if($disabled) disabled @endif
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm
                       @error($latModel) border-red-500 @enderror
                       @if($disabled) bg-gray-100 cursor-not-allowed @endif"
            >
            @error($latModel)
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="{{ $lonName }}" class="block text-xs text-gray-500 mb-1">Долгота (lon)</label>
            <input
                id="{{ $lonName }}"
                type="number"
                wire:model.live.debounce.300ms="{{ $lonModel }}"
                step="0.0000001"
                min="-180" max="180"
                placeholder="76.9450000"
                @if($required) required @endif
                @if($disabled) disabled @endif
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm
                       @error($lonModel) border-red-500 @enderror
                       @if($disabled) bg-gray-100 cursor-not-allowed @endif"
            >
            @error($lonModel)
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
