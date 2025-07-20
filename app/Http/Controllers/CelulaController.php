<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CelulaController extends Controller
{
    public function index()
    {
        // Lógica para listar as células
        return view('celulas.index');
    }

    public function create()
    {
        // Lógica para exibir o formulário de criação de célula
        return view('celulas.cadastro');
    }
}
