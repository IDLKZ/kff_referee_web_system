<?php

namespace App\Livewire\Auth;

use App\Http\Middleware\RedirectByRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('auth.layout')]
#[Title('Login')]
class Login extends Component
{
    public string $login = '';
    public string $password = '';
    public bool $remember = false;
    public bool $showPassword = false;

    public function authenticate(): void
    {
        $this->validate([
            'login' => ['required', 'string', 'min:3', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $throttleKey = Str::transliterate(Str::lower($this->login) . '|' . request()->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('login', __('auth.too_many_attempts', ['seconds' => $seconds]));
            return;
        }

        $field = $this->detectLoginField($this->login);

        if (!Auth::attempt([$field => $this->login, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($throttleKey);
            $this->addError('login', __('auth.invalid_credentials'));
            return;
        }

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            $this->addError('login', __('auth.account_deactivated'));
            return;
        }

        RateLimiter::clear($throttleKey);

        session()->regenerate();

        toastr()->success(__('auth.welcome_back'));

        $group = $user->role?->group;
        $route = RedirectByRole::dashboardRouteForGroup($group);

        $this->redirect(route($route), navigate: true);
    }

    public function togglePasswordVisibility(): void
    {
        $this->showPassword = !$this->showPassword;
    }

    private function detectLoginField(string $login): string
    {
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }

        if (preg_match('/^\+?[0-9]{10,15}$/', $login)) {
            return 'phone';
        }

        return 'username';
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
