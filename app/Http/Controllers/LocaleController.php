<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    private const SUPPORTED_LOCALES = ['ru', 'kk', 'en'];

    public function switch(Request $request, string $locale): RedirectResponse
    {
        if (!in_array($locale, self::SUPPORTED_LOCALES)) {
            $locale = config('app.locale', 'en');
        }

        $request->session()->put('locale', $locale);

        return redirect()->back();
    }
}
