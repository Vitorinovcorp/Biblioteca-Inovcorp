<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch($locale)
    {
        $availableLocales = ['pt', 'en', 'es', 'fr'];
        
        if (in_array($locale, $availableLocales)) {
            session(['locale' => $locale]);
            App::setLocale($locale);
        }
        
        return redirect()->back();
    }
}