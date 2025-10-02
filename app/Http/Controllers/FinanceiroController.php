<?php

namespace App\Http\Controllers;

use App\Models\Caixa;
use App\Models\LancamentoFinanceiro;
use App\Models\TipoLancamento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class FinanceiroController extends Controller
{
    public function formCaixa()
    {
        return view('financeiro.includes.form_caixa');
    }

    public function formCaixaEditar(int $id)
    {
        $caixa = Caixa::where('congregacao_id', app('congregacao')->id)->findOrFail($id);

        return view('financeiro.includes.form_caixa_editar', compact('caixa'));
    }

    public function storeCaixa(Request $request): RedirectResponse
    {
        $congregacaoId = app('congregacao')->id;

        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $data['congregacao_id'] = $congregacaoId;

        Caixa::create($data);

        return redirect()->back()->with('success', 'Caixa criado com sucesso!');
    }

    public function updateCaixa(Request $request, int $id): RedirectResponse
    {
        $caixa = Caixa::where('congregacao_id', app('congregacao')->id)->findOrFail($id);

        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $caixa->update($data);

        return redirect()->back()->with('success', 'Caixa atualizado com sucesso!');
    }

    public function destroyCaixa(int $id): RedirectResponse
    {
        $caixa = Caixa::where('congregacao_id', app('congregacao')->id)->findOrFail($id);
        $caixa->delete();

        return redirect()->back()->with('msg', 'Caixa excluído com sucesso!');
    }

    public function formTipo()
    {
        return view('financeiro.includes.form_tipo_lancamento');
    }

    public function formTipoEditar(int $id)
    {
        $tipo = TipoLancamento::where('congregacao_id', app('congregacao')->id)->findOrFail($id);

        return view('financeiro.includes.form_tipo_lancamento_editar', compact('tipo'));
    }

    public function formLancamento(int $caixaId)
    {
        $caixa = Caixa::where('congregacao_id', app('congregacao')->id)->with('lancamentos')->findOrFail($caixaId);
        $tiposLancamento = TipoLancamento::where('congregacao_id', app('congregacao')->id)
            ->orderBy('nome')
            ->get();

        return view('financeiro.includes.form_lancamento', compact('caixa', 'tiposLancamento'));
    }

    public function storeTipo(Request $request): RedirectResponse
    {
        $congregacaoId = app('congregacao')->id;

        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $data['congregacao_id'] = $congregacaoId;

        TipoLancamento::create($data);

        return redirect()->back()->with('success', 'Tipo de lançamento cadastrado com sucesso!');
    }

    public function updateTipo(Request $request, int $id): RedirectResponse
    {
        $tipo = TipoLancamento::where('congregacao_id', app('congregacao')->id)->findOrFail($id);

        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $tipo->update($data);

        return redirect()->back()->with('success', 'Tipo de lançamento atualizado com sucesso!');
    }

    public function destroyTipo(int $id): RedirectResponse
    {
        $tipo = TipoLancamento::where('congregacao_id', app('congregacao')->id)->findOrFail($id);
        $tipo->delete();

        return redirect()->back()->with('msg', 'Tipo de lançamento excluído com sucesso!');
    }

    public function storeLancamento(Request $request): RedirectResponse
    {
        $congregacaoId = app('congregacao')->id;

        $data = $request->validate([
            'caixa_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($congregacaoId) {
                    if (! Caixa::where('congregacao_id', $congregacaoId)->where('id', $value)->exists()) {
                        $fail('Caixa inválido para esta congregação.');
                    }
                },
            ],
            'tipo_lancamento_id' => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail) use ($congregacaoId) {
                    if ($value && ! TipoLancamento::where('congregacao_id', $congregacaoId)->where('id', $value)->exists()) {
                        $fail('Tipo de lançamento inválido.');
                    }
                },
            ],
            'tipo' => 'required|in:entrada,saida',
            'valor' => 'required|numeric|min:0.01',
            'descricao' => 'nullable|string',
            'data_lancamento' => 'nullable|date',
        ]);

        if (blank(Arr::get($data, 'data_lancamento'))) {
            $data['data_lancamento'] = now()->toDateString();
        }

        LancamentoFinanceiro::create($data);

        return redirect()->back()->with('success', 'Lançamento registrado com sucesso!');
    }

    public function painel(Request $request)
    {
        $congregacaoId = app('congregacao')->id;

        $caixas = Caixa::where('congregacao_id', $congregacaoId)
            ->orderBy('nome')
            ->get();

        $tiposLancamento = TipoLancamento::where('congregacao_id', $congregacaoId)
            ->orderBy('nome')
            ->get();

        $lancamentos = $this->buildLancamentosQuery($request)
            ->orderByDesc('data_lancamento')
            ->orderByDesc('id')
            ->paginate(15);

        return view('financeiro.painel', compact('caixas', 'tiposLancamento', 'lancamentos'));
    }

    protected function buildLancamentosQuery(Request $request)
    {
        $congregacaoId = app('congregacao')->id;

        $query = LancamentoFinanceiro::whereHas('caixa', function ($q) use ($congregacaoId) {
            $q->where('congregacao_id', $congregacaoId);
        });

        if ($request->filled('caixa')) {
            $query->where('caixa_id', $request->input('caixa'));
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->input('tipo'));
        }

        if ($request->filled('tipo_lancamento_id')) {
            $query->where('tipo_lancamento_id', $request->input('tipo_lancamento_id'));
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('data_lancamento', '>=', $request->input('data_inicio'));
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('data_lancamento', '<=', $request->input('data_fim'));
        }

        return $query;
    }

    public function formLancamentoEditar(int $id)
    {
        $congregacaoId = app('congregacao')->id;
        $lancamento = LancamentoFinanceiro::whereHas('caixa', function ($q) use ($congregacaoId) {
            $q->where('congregacao_id', $congregacaoId);
        })->findOrFail($id);

        $tiposLancamento = TipoLancamento::where('congregacao_id', $congregacaoId)
            ->orderBy('nome')
            ->get();

        return view('financeiro.includes.form_lancamento_editar', compact('lancamento', 'tiposLancamento'));
    }

    public function updateLancamento(Request $request, int $id): RedirectResponse
    {
        $congregacaoId = app('congregacao')->id;
        $lancamento = LancamentoFinanceiro::whereHas('caixa', function ($q) use ($congregacaoId) {
            $q->where('congregacao_id', $congregacaoId);
        })->findOrFail($id);

        $data = $request->validate([
            'caixa_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) use ($congregacaoId) {
                    if (! Caixa::where('congregacao_id', $congregacaoId)->where('id', $value)->exists()) {
                        $fail('Caixa inválido para esta congregação.');
                    }
                },
            ],
            'tipo_lancamento_id' => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail) use ($congregacaoId) {
                    if ($value && ! TipoLancamento::where('congregacao_id', $congregacaoId)->where('id', $value)->exists()) {
                        $fail('Tipo de lançamento inválido.');
                    }
                },
            ],
            'tipo' => 'required|in:entrada,saida',
            'valor' => 'required|numeric|min:0.01',
            'descricao' => 'nullable|string',
            'data_lancamento' => 'nullable|date',
        ]);

        if (blank(Arr::get($data, 'data_lancamento'))) {
            $data['data_lancamento'] = now()->toDateString();
        }

        $lancamento->update($data);

        return redirect()->back()->with('success', 'Lançamento atualizado com sucesso!');
    }
}
