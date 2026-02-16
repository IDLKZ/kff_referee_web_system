<div>
    <!-- Logo Section -->
    <div class="logo-section">
        <div class="logo-wrapper">
            <div class="logo-ring"></div>
            <div class="logo">
                <img src="{{asset("logo_kff.png")}}">
            </div>
        </div>
        <h1 class="title">{{ __('auth.login') }}</h1>
        <p class="subtitle">{{ __('auth.enter_credentials') }}</p>
    </div>

    <!-- Form -->
    <form wire:submit="authenticate">
        <!-- Login field -->
        <div class="form-group">
            <div class="input-wrapper">
                <input
                    type="text"
                    wire:model="login"
                    class="form-input"
                    placeholder="{{ __('auth.login_identifier') }}"
                    required
                    autofocus
                >
                <div class="input-icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
            @error('login')
                <p class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password field -->
        <div class="form-group">
            <div class="input-wrapper" x-data="{ show: @entangle('showPassword') }">
                <input
                    :type="show ? 'text' : 'password'"
                    wire:model="password"
                    class="form-input"
                    placeholder="{{ __('auth.password') }}"
                    required
                >
                <div class="input-icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <button type="button" @click="show = !show" class="password-toggle">
                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember me -->
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" wire:model="remember" class="custom-checkbox">
                <span class="checkbox-text">{{ __('auth.remember_me') }}</span>
            </label>
        </div>

        <!-- Submit button -->
        <button type="submit" class="submit-btn" wire:loading.attr="disabled">
            <span wire:loading.remove class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                {{ __('auth.login_button') }}
            </span>
            <span wire:loading class="flex items-center gap-2">
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                {{ __('ui.loading') }}...
            </span>
        </button>
    </form>
</div>
