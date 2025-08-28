<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aviso;

class AvisoController extends Controller
{
    public function index() {
        $congregacao = app('congregacao');
        $avisos = Aviso::where('congregacao_id', $congregacao->id)->get();

        return view('avisos.painel', compact('avisos', 'congregacao'));
    }
}
