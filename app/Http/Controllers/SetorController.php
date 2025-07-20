<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setor;

class SetorController extends Controller
{
    public function create()
    {
        // Lógica para listar os setores
        return view('setores.cadastro');
    }

    public function store(Request $request)
    {
        // Lógica para armazenar um novo setor
        $setor = new Setor();
        $setor->congregacao_id = session('congregacao_id');
        $setor->nome = $request->nome;
        $setor->save();

        return redirect()->route('index')->with('success', 'Setor criado com sucesso!');
    }

    public function edit($id)
    {
        // Lógica para editar um setor existente
        $setor = Setor::findOrFail($id);
        return view('setores.edit', ['setor' => $setor]);
    }

    public function update(Request $request, $id)
    {
        // Lógica para atualizar um setor existente
        $setor = Setor::findOrFail($id);
        $setor->nome = $request->nome;
        $setor->save();

        return redirect()->route('index')->with('success', 'Setor atualizado com sucesso!');
    }
    public function index()
    {
        // Lógica para listar os setores
        $setores = Setor::all();
        return view('setores.index', ['setores' => $setores]);
    }

    public function destroy($id)
    {
        // Lógica para excluir um setor existente
        $setor = Setor::findOrFail($id);
        $setor->delete();

        return redirect()->route('index')->with('success', 'Setor excluído com sucesso!');
    }

    public function show($id)
    {
        // Lógica para exibir um setor específico
        $setor = Setor::findOrFail($id);
        $departamentos = $setor->departamentos; // Assuming Setor has a relationship with Departamentos
        return view('setores.show', ['setor' => $setor, 'departamentos' => $departamentos]);
    }
    
}
