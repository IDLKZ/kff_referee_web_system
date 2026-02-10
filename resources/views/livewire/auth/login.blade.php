<div>
    <h2 class="text-xl font-semibold text-center mb-6" style="color: var(--text-primary);">
        {{ __('auth.login') }}
    </h2>

    <form wire:submit="authenticate">
        {{-- Login field (username / email / phone) --}}
        @include('shared.common_form_fields.icon_text_input', [
            'model'       => 'login',
            'label'       => __('auth.login_identifier'),
            'placeholder' => __('auth.login_identifier'),
            'icon'        => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>',
            'required'    => true,
            'autofocus'   => true,
        ])

        {{-- Password field --}}
        @include('shared.common_form_fields.icon_password_input', [
            'model'       => 'password',
            'label'       => __('auth.password'),
            'placeholder' => __('auth.password'),
            'required'    => true,
        ])

        {{-- Remember me --}}
        <div class="flex items-center justify-between mb-6">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input
                    type="checkbox"
                    wire:model="remember"
                    class="w-4 h-4 rounded"
                    style="accent-color: var(--color-primary);"
                >
                <span class="text-sm" style="color: var(--text-secondary);">
                    {{ __('auth.remember_me') }}
                </span>
            </label>
        </div>

        {{-- Submit --}}
        <button
            type="submit"
            class="btn-primary w-full"
            wire:loading.attr="disabled"
        >
            <span wire:loading.remove>
                {{ __('auth.login_button') }}
            </span>
            <span wire:loading class="flex items-center gap-2">
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                {{ __('ui.loading') }}
            </span>
        </button>
    </form>
</div>
