{{--
    Text input with left icon and Livewire support
    @include('shared.common_form_fields.icon_text_input', [
        'model'       => 'login',
        'label'       => 'Login',
        'icon'        => '<svg>...</svg>',
        'placeholder' => '',           // optional
        'type'        => 'text',       // optional
        'required'    => true,         // optional
        'disabled'    => false,        // optional
        'autofocus'   => false,        // optional
    ])
--}}
@php
    $type = $type ?? 'text';
    $required = $required ?? false;
    $disabled = $disabled ?? false;
    $autofocus = $autofocus ?? false;
    $placeholder = $placeholder ?? '';
    $icon = $icon ?? '';
    $fieldName = str_replace(['form.', '.'], ['', '_'], $model);
@endphp

<div class="mb-4">
    @if(!empty($label))
        <label for="{{ $fieldName }}" class="form-label">
            {{ $label }}
            @if($required) <span style="color: var(--color-danger);">*</span> @endif
        </label>
    @endif

    <div class="relative">
        @if(!empty($icon))
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                 style="color: var(--text-muted);">
                {!! $icon !!}
            </div>
        @endif

        <input
            id="{{ $fieldName }}"
            type="{{ $type }}"
            wire:model.live.debounce.300ms="{{ $model }}"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($autofocus) autofocus @endif
            class="form-input {{ !empty($icon) ? 'form-input-icon' : '' }}
                   @error($model) is-invalid @enderror"
            @if($disabled) style="background: var(--bg-input-disabled); cursor: not-allowed;" @endif
        >
    </div>

    @error($model)
        <p class="form-error">{{ $message }}</p>
    @enderror
</div>
