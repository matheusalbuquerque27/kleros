<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    public function index()
    {
        // Lógica para listar os departamentos
        return view('departamentos.index');
    }

    public function create()
    {
        // Lógica para exibir o formulário de criação de departamento
        return view('departamentos.cadastro');
    }

    public function store(Request $request)
    {
        // Lógica para armazenar um novo departamento
        return redirect()->route('departamentos.index')->with('success', 'Departamento criado com sucesso!');
    }

    public function show($id)
    {
        // Lógica para exibir um departamento específico
        return view('departamentos.show', ['id' => $id]);
    }
}
