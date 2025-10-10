<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssinaturaController extends Controller
{
    
    public function index() {
        $congregacao = app('congregacao');
        return view('assinaturas/index', ['congregacao' => $congregacao]);
    }

}
