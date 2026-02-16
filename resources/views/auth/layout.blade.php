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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Toastr CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    {{-- Common Styles --}}
    @include('shared.common_styles.common_style')

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Livewire Styles --}}
    @livewireStyles

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: var(--bg-body, #0a0a0f);
        }

        /* Dark Theme (default) */
        :root {
            --bg-body: #0a0a0f;
            --bg-card: rgba(17, 17, 27, 0.85);
            --bg-input: rgba(255, 255, 255, 0.04);
            --bg-hover: rgba(255, 255, 255, 0.1);
            --text-primary: #ffffff;
            --text-secondary: rgba(255, 255, 255, 0.5);
            --text-tertiary: rgba(255, 255, 255, 0.3);
            --border-color: rgba(255, 255, 255, 0.08);
            --accent-primary: #6366f1;
            --accent-secondary: #8b5cf6;
        }

        /* Light Theme */
        .theme-light {
            --bg-body: #f8fafc;
            --bg-card: rgba(255, 255, 255, 0.9);
            --bg-input: rgba(0, 0, 0, 0.04);
            --bg-hover: rgba(0, 0, 0, 0.06);
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-tertiary: #94a3b8;
            --border-color: rgba(0, 0, 0, 0.1);
            --accent-primary: #4f46e5;
            --accent-secondary: #7c3aed;
        }

        /* Animated background */
        .bg-animation {
            position: fixed;
            inset: 0;
            z-index: -2;
        }

        .bg-gradient {
            position: absolute;
            inset: 0;
            background: var(--bg-gradient);
            animation: gradientMove 15s ease-in-out infinite alternate;
        }

        /* Default gradient (dark) */
        :root {
            --bg-gradient:
                radial-gradient(ellipse at 20% 20%, rgba(79, 70, 229, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 80%, rgba(139, 92, 246, 0.1) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 50%, rgba(59, 130, 246, 0.05) 0%, transparent 70%);
        }

        /* Light theme gradient */
        .theme-light {
            --bg-gradient:
                radial-gradient(ellipse at 20% 20%, rgba(79, 70, 229, 0.08) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 80%, rgba(139, 92, 246, 0.06) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 50%, rgba(59, 130, 246, 0.04) 0%, transparent 70%);
        }

        @keyframes gradientMove {
            0% {
                transform: scale(1) rotate(0deg);
            }
            100% {
                transform: scale(1.1) rotate(5deg);
            }
        }

        /* Grid pattern */
        .grid-pattern {
            position: absolute;
            inset: 0;
            background-image: var(--grid-pattern);
            background-size: 50px 50px;
            animation: gridMove 20s linear infinite;
        }

        :root {
            --grid-pattern:
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
        }

        .theme-light {
            --grid-pattern:
                linear-gradient(rgba(0,0,0,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,0,0,0.03) 1px, transparent 1px);
        }

        @keyframes gridMove {
            0% {
                transform: perspective(500px) rotateX(60deg) translateY(0);
            }
            100% {
                transform: perspective(500px) rotateX(60deg) translateY(50px);
            }
        }

        /* Floating particles */
        .particles {
            position: fixed;
            inset: 0;
            overflow: hidden;
            z-index: -1;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--particle-color, rgba(139, 92, 246, 0.6));
            border-radius: 50%;
            animation: particleFloat 15s ease-in-out infinite;
        }

        :root {
            --particle-color: rgba(139, 92, 246, 0.6);
        }

        .theme-light {
            --particle-color: rgba(99, 102, 241, 0.4);
        }

        @keyframes particleFloat {
            0%, 100% {
                transform: translateY(100vh) translateX(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) translateX(100px);
                opacity: 0;
            }
        }

        /* Main container */
        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            padding: 20px;
        }

        /* Card */
        .login-card {
            background: var(--bg-card);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow: var(--card-shadow);
            animation: cardEntrance 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        :root {
            --card-shadow:
                0 25px 80px -20px rgba(79, 70, 229, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.05) inset;
        }

        .theme-light {
            --card-shadow:
                0 25px 80px -20px rgba(0, 0, 0, 0.1),
                0 0 0 1px rgba(255, 255, 255, 0.8) inset;
        }

        @keyframes cardEntrance {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Logo */
        .logo-section {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-wrapper {
            position: relative;
            display: inline-block;
            margin-bottom: 20px;
        }

        .logo-ring {
            position: absolute;
            inset: -8px;
            border: 2px solid transparent;
            border-radius: 20px;
            background: var(--logo-ring-gradient);
            background-size: 300% 300%;
            animation: ringRotate 4s linear infinite;
            z-index: -1;
        }

        :root {
            --logo-ring-gradient: linear-gradient(45deg, #6366f1, #8b5cf6, #ec4899, #6366f1);
        }

        .theme-light {
            --logo-ring-gradient: linear-gradient(45deg, #4f46e5, #7c3aed, #db2777, #4f46e5);
        }

        @keyframes ringRotate {
            0% { background-position: 0% 50%; }
            100% { background-position: 100% 50%; }
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            background: var(--logo-gradient);
            border-radius: 16px;
            color: white;
        }

        :root {
            --logo-gradient: linear-gradient(135deg, #6366f1, #8b5cf6);
        }

        .theme-light {
            --logo-gradient: linear-gradient(135deg, #4f46e5, #7c3aed);
        }

        .logo svg {
            width: 32px;
            height: 32px;
        }

        .title {
            font-size: 26px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .subtitle {
            font-size: 14px;
            color: var(--text-secondary);
        }

        /* Form */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--input-icon-color);
            transition: color 0.3s ease;
            pointer-events: none;
        }

        :root {
            --input-icon-color: rgba(255, 255, 255, 0.4);
        }

        .theme-light {
            --input-icon-color: rgba(0, 0, 0, 0.3);
        }

        .form-input {
            width: 100%;
            height: 50px;
            padding: 0 50px 0 48px;
            background: var(--bg-input);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            color: var(--text-primary);
            font-size: 15px;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-input::placeholder {
            color: var(--text-tertiary);
        }

        .form-input:focus {
            background: var(--input-focus-bg);
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 4px var(--input-focus-shadow);
        }

        :root {
            --input-focus-bg: rgba(255, 255, 255, 0.08);
            --input-focus-shadow: rgba(99, 102, 241, 0.1);
        }

        .theme-light {
            --input-focus-bg: rgba(0, 0, 0, 0.02);
            --input-focus-shadow: rgba(99, 102, 241, 0.08);
        }

        .form-input:focus + .input-icon,
        .input-wrapper:focus-within .input-icon {
            color: var(--accent-secondary);
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--input-icon-color);
            cursor: pointer;
            transition: color 0.3s ease;
            background: none;
            border: none;
            padding: 4px;
        }

        .password-toggle:hover {
            color: var(--text-primary);
        }

        .error-text {
            margin-top: 8px;
            font-size: 12px;
            color: #ef4444;
        }

        /* Checkbox */
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            user-select: none;
        }

        .custom-checkbox {
            width: 18px;
            height: 18px;
            border: 2px solid var(--checkbox-border);
            border-radius: 5px;
            background: var(--bg-input);
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            flex-shrink: 0;
        }

        :root {
            --checkbox-border: rgba(255, 255, 255, 0.2);
        }

        .theme-light {
            --checkbox-border: rgba(0, 0, 0, 0.15);
        }

        .custom-checkbox:checked {
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            border-color: transparent;
        }

        .custom-checkbox:checked::after {
            content: '';
            position: absolute;
            left: 5px;
            top: 2px;
            width: 4px;
            height: 8px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .checkbox-text {
            font-size: 14px;
            color: var(--text-secondary);
        }

        /* Submit button */
        .submit-btn {
            width: 100%;
            height: 52px;
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transform: translateX(-100%);
            transition: transform 0.5s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--btn-shadow);
        }

        :root {
            --btn-shadow: 0 10px 30px -10px rgba(99, 102, 241, 0.5);
        }

        .theme-light {
            --btn-shadow: 0 10px 30px -10px rgba(79, 70, 229, 0.4);
        }

        .submit-btn:hover::before {
            transform: translateX(100%);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Top controls */
        .top-controls {
            position: fixed;
            top: 24px;
            right: 24px;
            display: flex;
            gap: 10px;
            z-index: 100;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 32px;
            font-size: 13px;
            color: var(--text-tertiary);
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-card {
                padding: 32px 24px;
                border-radius: 20px;
            }

            .title {
                font-size: 22px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="bg-animation">
        <div class="bg-gradient"></div>
        <div class="grid-pattern"></div>
    </div>

    <div class="particles" id="particles"></div>

    <script>
        // Create floating particles
        const particlesContainer = document.getElementById('particles');
        for (let i = 0; i < 30; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 15 + 's';
            particle.style.animationDuration = (15 + Math.random() * 10) + 's';
            particle.style.opacity = 0.3 + Math.random() * 0.5;
            particlesContainer.appendChild(particle);
        }

        // Theme init from localStorage
        (function() {
            const storedTheme = localStorage.getItem('theme');
            if (storedTheme === 'light') {
                document.documentElement.classList.add('theme-light');
            } else {
                // Default to dark if not set or explicitly set to dark
                document.documentElement.classList.add('theme-dark');
            }
        })();
    </script>

    <div class="top-controls">
        @include('shared.components.language_switcher')
        @include('shared.components.theme_toggle')
    </div>

    <div class="login-wrapper">
        <div class="login-card">
            {{ $slot ?? '' }}
            @yield('content')
        </div>

        <p class="footer">
            &copy; {{ date('Y') }} {{ __('auth.system_subtitle') }}
        </p>
    </div>

    <!-- Toastr JS -->
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

    @livewireScripts

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
