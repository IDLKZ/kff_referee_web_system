<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? __('auth.login') . ' — ' . config('app.name', 'KFF') }}</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Toastr CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    {{-- Common Styles --}}
    @include('shared.common_styles.common_style')

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Livewire Styles --}}
    @livewireStyles

    @stack('styles')
</head>
<body class="min-h-screen flex items-center justify-center p-4"
      style="background: var(--bg-body); font-family: 'Inter', sans-serif;">

    {{-- Theme init from localStorage --}}
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            document.documentElement.classList.add('theme-' + theme);
        })();
    </script>

    {{-- Top-right controls --}}
    <div class="fixed top-4 right-4 flex items-center gap-2 z-50">
        @include('shared.components.language_switcher')
        @include('shared.components.theme_toggle')
    </div>

    {{-- Main Content --}}
    <div class="w-full max-w-md">
        {{-- Logo / Branding --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4"
                 style="background: var(--color-primary);">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold" style="color: var(--text-primary);">
                {{ __('auth.system_title') }}
            </h1>
            <p class="mt-1 text-sm" style="color: var(--text-secondary);">
                {{ __('auth.system_subtitle') }}
            </p>
        </div>

        {{-- Card --}}
        <div class="card p-8">
            {{ $slot ?? '' }}
            @yield('content')
        </div>

        {{-- Footer --}}
        <p class="text-center text-xs mt-6" style="color: var(--text-muted);">
            &copy; {{ date('Y') }} {{ __('auth.system_subtitle') }}
        </p>
    </div>

    {{-- Toastr JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 5000,
        };
    </script>

    {{-- Livewire Scripts --}}
    @livewireScripts

    {{-- Toastr flash messages from session --}}
    @if(session('toastr_success'))
        <script>toastr.success("{{ session('toastr_success') }}");</script>
    @endif
    @if(session('toastr_error'))
        <script>toastr.error("{{ session('toastr_error') }}");</script>
    @endif
    @if(session('toastr_info'))
        <script>toastr.info("{{ session('toastr_info') }}");</script>
    @endif

    @stack('scripts')
</body>
</html>
