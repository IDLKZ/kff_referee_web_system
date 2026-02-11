<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'KFF') }}</title>

    {{-- Common Styles --}}
    @include('shared.common_styles.common_style')

    {{-- Tailwind / Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Livewire Styles --}}
    @livewireStyles

    {{-- Extra Head --}}
    @stack('styles')
</head>
<body class="min-h-screen flex" style="background: var(--bg-body); color: var(--text-primary); font-family: 'Inter', sans-serif;">

    {{-- Theme init from localStorage --}}
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            document.documentElement.classList.add('theme-' + theme);
        })();
    </script>

    {{-- Sidebar --}}
    @hasSection('sidebar')
        <aside class="w-64 min-h-screen flex-shrink-0 flex flex-col"
               style="background: var(--bg-sidebar); border-right: 1px solid var(--border-color);">
            @yield('sidebar')
        </aside>
    @endif

    {{-- Main wrapper --}}
    <div class="flex-1 flex flex-col min-h-screen">

        {{-- Top bar --}}
        @hasSection('topbar')
            <header class="px-6 py-3 flex items-center justify-between" style="background: var(--bg-card); box-shadow: var(--shadow-sm);">
                @yield('topbar')
            </header>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 p-6">
            {{ $slot ?? '' }}
            @yield('content')
        </main>

        {{-- Footer --}}
        @hasSection('footer')
            <footer class="px-6 py-3 text-sm" style="background: var(--bg-card); border-top: 1px solid var(--border-color); color: var(--text-muted);">
                @yield('footer')
            </footer>
        @endif
    </div>

    {{-- jQuery + Toastr --}}
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

    {{-- Wire Elements Modal --}}
    @livewire('wire-elements-modal')

    {{-- Livewire Scripts --}}
    @livewireScripts

    {{-- Toastr flash messages --}}
    @if(session('toastr_success'))
        <script>toastr.success("{{ session('toastr_success') }}");</script>
    @endif
    @if(session('toastr_error'))
        <script>toastr.error("{{ session('toastr_error') }}");</script>
    @endif
    @if(session('toastr_info'))
        <script>toastr.info("{{ session('toastr_info') }}");</script>
    @endif

    {{-- Extra Scripts --}}
    @stack('scripts')
</body>
</html>
