{{--
    Reusable modal component (Alpine.js)
    Usage in Livewire:
        wire:model for show state
        $title — modal header
        $maxWidth — sm|md|lg|xl (default md)
        $slot — body content
--}}
@props(['id' => 'modal', 'maxWidth' => 'md'])

@php
    $widthClass = match($maxWidth) {
        'sm' => 'max-w-sm',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        default => 'max-w-md',
    };
@endphp

<div
    x-data="{ show: @entangle($attributes->wire('model')) }"
    x-show="show"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="display: none;"
>
    {{-- Backdrop --}}
    <div
        x-show="show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="show = false"
        class="fixed inset-0"
        style="background: rgba(0,0,0,0.5);"
    ></div>

    {{-- Dialog --}}
    <div
        x-show="show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative w-full {{ $widthClass }} rounded-xl overflow-hidden"
        style="background: var(--bg-card); box-shadow: var(--shadow-lg);"
        @click.outside="show = false"
        @keydown.escape.window="show = false"
    >
        {{-- Header --}}
        @if(isset($title))
            <div class="flex items-center justify-between px-6 py-4"
                 style="border-bottom: 1px solid var(--border-color);">
                <h3 class="text-lg font-semibold" style="color: var(--text-primary);">
                    {{ $title }}
                </h3>
                <button @click="show = false" class="p-1 rounded-md transition-colors"
                        style="color: var(--text-muted);"
                        onmouseover="this.style.color='var(--text-primary)'"
                        onmouseout="this.style.color='var(--text-muted)'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- Body --}}
        <div class="px-6 py-5">
            {{ $slot }}
        </div>

        {{-- Footer --}}
        @if(isset($footer))
            <div class="flex items-center justify-end gap-3 px-6 py-4"
                 style="border-top: 1px solid var(--border-color);">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
