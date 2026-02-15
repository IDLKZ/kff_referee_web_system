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
            background: #0a0a0f;
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
            background: radial-gradient(ellipse at 20% 20%, rgba(79, 70, 229, 0.15) 0%, transparent 50%),
                        radial-gradient(ellipse at 80% 80%, rgba(139, 92, 246, 0.1) 0%, transparent 50%),
                        radial-gradient(ellipse at 50% 50%, rgba(59, 130, 246, 0.05) 0%, transparent 70%);
            animation: gradientMove 15s ease-in-out infinite alternate;
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
            background-image:
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: gridMove 20s linear infinite;
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
            background: rgba(139, 92, 246, 0.6);
            border-radius: 50%;
            animation: particleFloat 15s ease-in-out infinite;
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
            background: rgba(17, 17, 27, 0.8);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow:
                0 25px 80px -20px rgba(79, 70, 229, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.05) inset;
            animation: cardEntrance 0.8s cubic-bezier(0.16, 1, 0.3, 1);
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
            background: linear-gradient(45deg, #6366f1, #8b5cf6, #ec4899, #6366f1);
            background-size: 300% 300%;
            animation: ringRotate 4s linear infinite;
            z-index: -1;
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
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 16px;
            color: white;
        }

        .logo svg {
            width: 32px;
            height: 32px;
        }

        .title {
            font-size: 26px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .subtitle {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.5);
        }

        /* Form */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.7);
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
            color: rgba(255, 255, 255, 0.4);
            transition: color 0.3s ease;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            height: 50px;
            padding: 0 50px 0 48px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            color: #ffffff;
            font-size: 15px;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        .form-input:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(99, 102, 241, 0.6);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .form-input:focus + .input-icon,
        .input-wrapper:focus-within .input-icon {
            color: #8b5cf6;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.4);
            cursor: pointer;
            transition: color 0.3s ease;
            background: none;
            border: none;
            padding: 4px;
        }

        .password-toggle:hover {
            color: rgba(255, 255, 255, 0.8);
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
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.04);
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            flex-shrink: 0;
        }

        .custom-checkbox:checked {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
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
            color: rgba(255, 255, 255, 0.6);
        }

        /* Submit button */
        .submit-btn {
            width: 100%;
            height: 52px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
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
            box-shadow: 0 10px 30px -10px rgba(99, 102, 241, 0.5);
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
            gap: 12px;
            z-index: 100;
        }

        .control-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 10px;
            color: rgba(255, 255, 255, 0.6);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .control-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 1);
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 32px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.3);
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
            const theme = localStorage.getItem('theme') || 'light';
            document.documentElement.classList.add('theme-' + theme);
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
