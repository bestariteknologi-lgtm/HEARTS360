<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch($lang)
    {
        if (in_array($lang, ['id', 'en'])) {
            Session::put('applocale', $lang);
        }
        return redirect()->back();
    }
}
