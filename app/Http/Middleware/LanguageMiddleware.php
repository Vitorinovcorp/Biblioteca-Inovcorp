<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        } elseif ($request->has('lang')) {
            $lang = $request->get('lang');
            if (in_array($lang, ['pt', 'en', 'es', 'fr'])) {
                App::setLocale($lang);
                Session::put('locale', $lang);
            }
        } else {
            App::setLocale(config('app.locale'));
        }

        return $next($request);
    }
}