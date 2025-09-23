<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Agrupamento;
use Illuminate\Http\Request;
use App\Models\Membro;

class DepartamentoController extends Controller
{
    public function painel()
    {
        $departamentos = Agrupamento::where('tipo', 'departamento')
            ->where('congregacao_id', app('congregacao')->id)
            ->paginate(10);
        // Lógica para listar os departamentos
        return view('departamentos.painel', compact('departamentos'));
    }

    public function create()
    {
        // Lógica para exibir o formulário de criação de departamento
        return view('departamentos.cadastro');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|unique:agrupamentos,nome,NULL,id,congregacao_id,' . app('congregacao')->id . '|max:255',
            'lider_id' => 'nullable|exists:membros,id',
        ], [
            'nome.required' => 'O nome do departamento é obrigatório.',
            'nome.unique' => 'Já existe um departamento com esse nome nesta congregação.',
            'nome.max' => 'O nome do departamento não pode exceder 255 caracteres.',
        ]);

        $departamento = new Agrupamento();
        $departamento->nome = $request->nome;
        $departamento->descricao = $request->descricao;
        $departamento->tipo = 'departamento';
        $departamento->congregacao_id = app('congregacao')->id;
        $departamento->lider_id = $request->lider_id;
        $departamento->colider_id = $request->colider_id;

        $departamento->save();

        return redirect()->back()->with('success', 'Departamento criado com sucesso!');
    }

    public function show($id)
    {
        // Lógica para exibir um departamento específico
        return view('departamentos.show', ['id' => $id]);
    }

    public function form_criar()
    {
        $membros = Membro::where('congregacao_id', app('congregacao')->id)
            ->orderBy('nome')
            ->get();
        return view('departamentos.includes.form_criar', compact('membros'));
    }

    public function form_editar($id)
    {
        $departamento = Agrupamento::where('id', $id)
            ->where('congregacao_id', app('congregacao')->id)
            ->where('tipo', 'departamento')
            ->firstOrFail();

        $membros = Membro::where('congregacao_id', app('congregacao')->id)
            ->orderBy('nome')
            ->get();
        return view('departamentos.includes.form_editar', compact('membros', 'departamento'));
    }

    public function update(Request $request, $id)
    {
        $departamento = Agrupamento::where('id', $id)
            ->where('tipo', 'departamento')
            ->where('congregacao_id', app('congregacao')->id)
            ->firstOrFail();

        $request->validate([
            'nome' => 'required|max:255|unique:agrupamentos,nome,' . $departamento->id . ',id,congregacao_id,' . app('congregacao')->id,
            'lider_id' => 'nullable|exists:membros,id',
            'colider_id' => 'nullable|exists:membros,id',
        ], [
            'nome.required' => 'O nome do departamento é obrigatório.',
            'nome.unique' => 'Já existe um departamento com esse nome nesta congregação.',
            'nome.max' => 'O nome do departamento não pode exceder 255 caracteres.',
        ]);

        $departamento->nome = $request->nome;
        $departamento->descricao = $request->descricao;
        $departamento->lider_id = $request->lider_id;
        $departamento->colider_id = $request->colider_id;

        $departamento->save();

        return redirect()->back()->with('success', 'Departamento atualizado com sucesso!');
    }
}
