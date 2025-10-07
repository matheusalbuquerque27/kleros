<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function home()
    {
        // aqui você pode injetar métricas reais, depoimentos, etc.
        return view('site.home');
    }
}
