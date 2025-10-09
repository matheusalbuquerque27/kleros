<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $supported = Config::get('locales.supported', []);
        $default = Config::get('locales.default', Config::get('app.locale', 'en'));

        $localeFromRequest = $request->input('lang');
        $congregacaoLocale = null;

        if (app()->bound('congregacao') && app('congregacao')) {
            $congregacaoLocale = app('congregacao')->language ?? null;
        }

        $sessionLocale = $request->session()->get('app_locale');

        if ($localeFromRequest && in_array($localeFromRequest, $supported, true)) {
            $locale = $localeFromRequest;
            $request->session()->put('app_locale', $locale);
        } elseif ($congregacaoLocale && in_array($congregacaoLocale, $supported, true)) {
            $locale = $congregacaoLocale;
            $request->session()->put('app_locale', $locale);
        } elseif ($sessionLocale && in_array($sessionLocale, $supported, true)) {
            $locale = $sessionLocale;
        } else {
            $locale = $request->getPreferredLanguage($supported) ?? $default;
            $request->session()->put('app_locale', $locale);
        }

        App::setLocale($locale);

        return $next($request);
    }
}
