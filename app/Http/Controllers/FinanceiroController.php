<?php

namespace App\Http\Controllers;

use App\Models\Caixa;
use App\Models\LancamentoFinanceiro;
use App\Models\TipoContribuicao;
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
        return view('financeiro.includes.form_tipo_contribuicao');
    }

    public function formTipoEditar(int $id)
    {
        $tipo = TipoContribuicao::where('congregacao_id', app('congregacao')->id)->findOrFail($id);

        return view('financeiro.includes.form_tipo_contribuicao_editar', compact('tipo'));
    }

    public function formLancamento(int $caixaId)
    {
        $caixa = Caixa::where('congregacao_id', app('congregacao')->id)->with('lancamentos')->findOrFail($caixaId);
        $tiposContribuicao = TipoContribuicao::where('congregacao_id', app('congregacao')->id)
            ->orderBy('nome')
            ->get();

        return view('financeiro.includes.form_lancamento', compact('caixa', 'tiposContribuicao'));
    }

    public function storeTipo(Request $request): RedirectResponse
    {
        $congregacaoId = app('congregacao')->id;

        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $data['congregacao_id'] = $congregacaoId;

        TipoContribuicao::create($data);

        return redirect()->back()->with('success', 'Tipo de contribuição cadastrado com sucesso!');
    }

    public function updateTipo(Request $request, int $id): RedirectResponse
    {
        $tipo = TipoContribuicao::where('congregacao_id', app('congregacao')->id)->findOrFail($id);

        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $tipo->update($data);

        return redirect()->back()->with('success', 'Tipo de contribuição atualizado com sucesso!');
    }

    public function destroyTipo(int $id): RedirectResponse
    {
        $tipo = TipoContribuicao::where('congregacao_id', app('congregacao')->id)->findOrFail($id);
        $tipo->delete();

        return redirect()->back()->with('msg', 'Tipo de contribuição excluído com sucesso!');
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
            'tipo_contribuicao_id' => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail) use ($congregacaoId) {
                    if ($value && ! TipoContribuicao::where('congregacao_id', $congregacaoId)->where('id', $value)->exists()) {
                        $fail('Tipo de contribuição inválido.');
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
}
