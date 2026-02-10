<?php

namespace App\Http\Middleware;

use App\Constants\RoleConstants;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectByRole
{
    public function handle(Request $request, Closure $next, string $allowedGroup): Response
    {
        $user = $request->user();

        if (!$user || !$user->role) {
            return redirect()->route('login');
        }

        $userGroup = $user->role->group;

        if ($userGroup !== $allowedGroup) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }

    public static function dashboardRouteForGroup(?string $group): string
    {
        return match ($group) {
            RoleConstants::ADMINISTRATOR_GROUP => 'admin.dashboard',
            RoleConstants::KFF_PFLK_GROUP => 'kff.dashboard',
            RoleConstants::REFEREE_GROUP => 'referee.dashboard',
            default => 'login',
        };
    }
}
