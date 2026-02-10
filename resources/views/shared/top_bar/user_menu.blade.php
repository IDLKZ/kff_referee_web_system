{{-- User menu (top bar) --}}
<div class="flex items-center gap-3">
    @include('shared.components.language_switcher')
    @include('shared.components.theme_toggle')

    @auth
        <span class="text-sm" style="color: var(--text-secondary);">
            {{ auth()->user()->last_name }} {{ auth()->user()->first_name }}
        </span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="text-sm font-medium transition-colors duration-150"
                    style="color: var(--color-danger);"
                    onmouseover="this.style.color='var(--color-danger-hover)'"
                    onmouseout="this.style.color='var(--color-danger)'"
            >
                {{ __('auth.logout') }}
            </button>
        </form>
    @endauth
</div>
