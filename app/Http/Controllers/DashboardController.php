<?php

namespace App\Http\Controllers;

use App\Http\Middleware\RedirectByRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): RedirectResponse
    {
        $user = $request->user();
        $group = $user->role?->group;
        $route = RedirectByRole::dashboardRouteForGroup($group);

        return redirect()->route($route);
    }
}
