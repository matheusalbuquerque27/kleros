<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class LocaleController extends Controller
{
    /**
     * Update the current locale and persist it in the session.
     */
    public function update(Request $request): RedirectResponse
    {
        $supported = Config::get('locales.supported', []);
        $labels = Config::get('locales.labels', []);

        $locale = $request->string('locale')->toString();

        if (!in_array($locale, $supported, true)) {
            return back()->with('locale_error', __('site.locale.unsupported'));
        }

        $request->session()->put('app_locale', $locale);
        App::setLocale($locale);

        $languageName = $labels[$locale] ?? $locale;

        return back()->with('locale_changed', __('site.locale.updated', ['language' => $languageName]));
    }
}
