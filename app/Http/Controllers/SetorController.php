<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Agrupamento;
use App\Models\Membro;
use App\Models\Setor;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class SetorController extends Controller
{
    public function form_criar()
    {
        $congregacao = app('congregacao');
        $membros = Membro::where('congregacao_id', $congregacao->id)
            ->orderBy('nome')
            ->get();
        $departamentos = Agrupamento::where('congregacao_id', $congregacao->id)
            ->where('tipo', 'departamento')
            ->orderBy('nome')
            ->get();
        $grupos = Agrupamento::where('congregacao_id', $congregacao->id)
            ->where('tipo', 'grupo')
            ->orderBy('nome')
            ->get();

        return view('setores.includes.form_criar', compact('membros', 'departamentos', 'grupos'));
    }

    public function store(Request $request)
    {
        $congregacao = app('congregacao');
        $congregacaoId = $congregacao->id;

        $request->validate([
            'nome' => 'required|max:255|unique:agrupamentos,nome,NULL,id,congregacao_id,' . $congregacaoId,
            'descricao' => 'nullable|string',
            'lider_id' => 'nullable|exists:membros,id',
            'colider_id' => 'nullable|exists:membros,id',
            'departamentos' => 'array',
            'departamentos.*' => [
                'integer',
                Rule::exists('agrupamentos', 'id')->where(function ($query) use ($congregacaoId) {
                    $query->where('tipo', 'departamento')
                        ->where('congregacao_id', $congregacaoId);
                }),
            ],
            'grupos' => 'array',
            'grupos.*' => [
                'integer',
                Rule::exists('agrupamentos', 'id')->where(function ($query) use ($congregacaoId) {
                    $query->where('tipo', 'grupo')
                        ->where('congregacao_id', $congregacaoId);
                }),
            ],
        ], [
            'nome.required' => 'O nome do setor é obrigatório.',
            'nome.unique' => 'Já existe um setor com esse nome nesta congregação.',
            'nome.max' => 'O nome do setor não pode exceder 255 caracteres.',
        ]);

        $setor = new Setor();
        $setor->nome = $request->nome;
        $setor->descricao = $request->descricao;
        $setor->congregacao_id = $congregacaoId;
        $setor->lider_id = $request->lider_id;
        $setor->colider_id = $request->colider_id;
        $setor->save();

        $this->sincronizarAgrupamentos($setor->id, $congregacaoId, Arr::wrap($request->input('departamentos', [])), 'departamento');
        $this->sincronizarAgrupamentos($setor->id, $congregacaoId, Arr::wrap($request->input('grupos', [])), 'grupo');

        return redirect()->back()->with('success', 'Setor criado com sucesso!');
    }

    public function form_editar(int $id)
    {
        $congregacao = app('congregacao');
        $congregacaoId = $congregacao->id;

        $setor = Setor::where('congregacao_id', $congregacaoId)
            ->findOrFail($id);

        $membros = Membro::where('congregacao_id', $congregacaoId)
            ->orderBy('nome')
            ->get();

        $departamentos = Agrupamento::where('congregacao_id', $congregacaoId)
            ->where('tipo', 'departamento')
            ->orderBy('nome')
            ->get();
        $grupos = Agrupamento::where('congregacao_id', $congregacaoId)
            ->where('tipo', 'grupo')
            ->orderBy('nome')
            ->get();

        $departamentosSelecionados = Agrupamento::where('congregacao_id', $congregacaoId)
            ->where('tipo', 'departamento')
            ->where('agrupamento_pai_id', $setor->id)
            ->pluck('id')
            ->all();

        $gruposSelecionados = Agrupamento::where('congregacao_id', $congregacaoId)
            ->where('tipo', 'grupo')
            ->where('agrupamento_pai_id', $setor->id)
            ->pluck('id')
            ->all();

        return view('setores.includes.form_editar', compact('setor', 'membros', 'departamentos', 'departamentosSelecionados', 'grupos', 'gruposSelecionados'));
    }

    public function update(Request $request, int $id)
    {
        $congregacao = app('congregacao');
        $congregacaoId = $congregacao->id;

        $setor = Setor::where('congregacao_id', $congregacaoId)
            ->findOrFail($id);

        $request->validate([
            'nome' => 'required|max:255|unique:agrupamentos,nome,' . $setor->id . ',id,congregacao_id,' . $congregacaoId,
            'descricao' => 'nullable|string',
            'lider_id' => 'nullable|exists:membros,id',
            'colider_id' => 'nullable|exists:membros,id',
            'departamentos' => 'array',
            'departamentos.*' => [
                'integer',
                Rule::exists('agrupamentos', 'id')->where(function ($query) use ($congregacaoId) {
                    $query->where('tipo', 'departamento')
                        ->where('congregacao_id', $congregacaoId);
                }),
            ],
            'grupos' => 'array',
            'grupos.*' => [
                'integer',
                Rule::exists('agrupamentos', 'id')->where(function ($query) use ($congregacaoId) {
                    $query->where('tipo', 'grupo')
                        ->where('congregacao_id', $congregacaoId);
                }),
            ],
        ], [
            'nome.required' => 'O nome do setor é obrigatório.',
            'nome.unique' => 'Já existe um setor com esse nome nesta congregação.',
            'nome.max' => 'O nome do setor não pode exceder 255 caracteres.',
        ]);

        $setor->nome = $request->nome;
        $setor->descricao = $request->descricao;
        $setor->lider_id = $request->lider_id;
        $setor->colider_id = $request->colider_id;
        $setor->save();

        $this->sincronizarAgrupamentos($setor->id, $congregacaoId, Arr::wrap($request->input('departamentos', [])), 'departamento');
        $this->sincronizarAgrupamentos($setor->id, $congregacaoId, Arr::wrap($request->input('grupos', [])), 'grupo');

        return redirect()->back()->with('success', 'Setor atualizado com sucesso!');
    }

    public function destroy(Request $request, int $id)
    {
        $congregacaoId = app('congregacao')->id;

        $setor = Setor::where('congregacao_id', $congregacaoId)
            ->findOrFail($id);

        foreach (['departamento', 'grupo'] as $tipo) {
            Agrupamento::where('congregacao_id', $congregacaoId)
                ->where('tipo', $tipo)
                ->where('agrupamento_pai_id', $setor->id)
                ->update(['agrupamento_pai_id' => null]);
        }

        $setor->delete();

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Setor excluído com sucesso!']);
        }

        return redirect('/cadastros#setores')->with('msg', 'Setor excluído com sucesso!');
    }

    protected function sincronizarAgrupamentos(int $setorId, int $congregacaoId, array $selecionados, string $tipo): void
    {
        $ids = collect($selecionados)
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        Agrupamento::where('congregacao_id', $congregacaoId)
            ->where('tipo', $tipo)
            ->where('agrupamento_pai_id', $setorId)
            ->whereNotIn('id', $ids)
            ->update(['agrupamento_pai_id' => null]);

        if ($ids->isNotEmpty()) {
            Agrupamento::where('congregacao_id', $congregacaoId)
                ->where('tipo', $tipo)
                ->whereIn('id', $ids)
                ->update(['agrupamento_pai_id' => $setorId]);
        }
    }
}
