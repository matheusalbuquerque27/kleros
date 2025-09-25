<?php

namespace App\Http\Controllers;

use App\Models\Pesquisa;
use App\Models\Membro;
use App\Models\Pergunta;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PesquisaController extends Controller
{
    public function painel(): View
    {
        $congregacao = app('congregacao');

        $pesquisas = Pesquisa::with('criador')
            ->forCongregacao($congregacao->id)
            ->latest()
            ->paginate(10);

        return view('pesquisas.painel', compact('pesquisas'));
    }

    public function form_criar(): View
    {
        $membros = Membro::where('congregacao_id', app('congregacao')->id)
            ->orderBy('nome')
            ->get();

        return view('pesquisas.includes.form_criar', compact('membros'));
    }

    public function form_editar(int $id): View
    {
        $congregacaoId = app('congregacao')->id;

        $pesquisa = Pesquisa::forCongregacao($congregacaoId)
            ->with(['perguntas' => fn ($query) => $query->with('opcoes')->orderBy('created_at')])
            ->findOrFail($id);

        $membros = Membro::where('congregacao_id', $congregacaoId)
            ->orderBy('nome')
            ->get();

        return view('pesquisas.includes.form_editar', compact('membros', 'pesquisa'));
    }

    public function store(Request $request): RedirectResponse
    {
        $congregacao = app('congregacao');

        $data = $this->validatedData($request);
        $data['congregacao_id'] = $congregacao->id;

        Pesquisa::create($data);

        return redirect()->route('pesquisas.painel')
            ->with('msg', 'Pesquisa criada com sucesso!');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $congregacaoId = app('congregacao')->id;

        $pesquisa = Pesquisa::forCongregacao($congregacaoId)->findOrFail($id);

        $data = $this->validatedData($request, $pesquisa->id);

        $pesquisa->update($data);

        return redirect()->route('pesquisas.painel')
            ->with('msg', 'Pesquisa atualizada com sucesso!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $congregacaoId = app('congregacao')->id;

        $pesquisa = Pesquisa::forCongregacao($congregacaoId)->findOrFail($id);

        DB::transaction(function () use ($pesquisa) {
            $pesquisa->perguntas()->each(function ($pergunta) {
                $pergunta->opcoes()->delete();
                $pergunta->respostas()->delete();
                $pergunta->delete();
            });

            $pesquisa->respostas()->delete();
            $pesquisa->delete();
        });

        return redirect()->route('pesquisas.painel')
            ->with('msg', 'Pesquisa excluída com sucesso!');
    }

    public function storePergunta(Request $request, int $pesquisaId): RedirectResponse
    {
        $pesquisa = $this->resolvePesquisa($pesquisaId);

        $validator = Validator::make($request->all(), [
            'texto' => 'required|string|max:255',
            'tipo' => ['required', Rule::in(['texto', 'radio', 'checkbox'])],
            'options' => 'nullable|string',
        ]);

        $options = $this->normalizeOptions($request->input('options'));

        $validator->after(function ($validator) use ($request, $options) {
            if (in_array($request->input('tipo'), ['radio', 'checkbox']) && empty($options)) {
                $validator->errors()->add('options', 'Informe pelo menos uma opção para esse tipo de pergunta.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('tab', 'perguntas');
        }

        DB::transaction(function () use ($pesquisa, $request, $options) {
            $pergunta = $pesquisa->perguntas()->create([
                'texto' => $request->input('texto'),
                'tipo' => $request->input('tipo'),
            ]);

            $this->syncOptions($pergunta, $options);
        });

        return redirect()->back()->with('msg', 'Pergunta adicionada com sucesso!')->with('tab', 'perguntas');
    }

    public function updatePergunta(Request $request, int $pesquisaId, int $perguntaId): RedirectResponse
    {
        $pesquisa = $this->resolvePesquisa($pesquisaId);
        $pergunta = $pesquisa->perguntas()->whereKey($perguntaId)->with('opcoes')->firstOrFail();

        $validator = Validator::make($request->all(), [
            'texto' => 'required|string|max:255',
            'tipo' => ['required', Rule::in(['texto', 'radio', 'checkbox'])],
            'options' => 'nullable|string',
            'pergunta_id' => 'nullable|integer',
        ]);

        $options = $this->normalizeOptions($request->input('options'));

        $validator->after(function ($validator) use ($request, $options) {
            if (in_array($request->input('tipo'), ['radio', 'checkbox']) && empty($options)) {
                $validator->errors()->add('options', 'Informe pelo menos uma opção para esse tipo de pergunta.');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('tab', 'perguntas');
        }

        DB::transaction(function () use ($pergunta, $request, $options) {
            $pergunta->update([
                'texto' => $request->input('texto'),
                'tipo' => $request->input('tipo'),
            ]);

            $this->syncOptions($pergunta, $options);
        });

        return redirect()->back()->with('msg', 'Pergunta atualizada com sucesso!')->with('tab', 'perguntas');
    }

    public function destroyPergunta(int $pesquisaId, int $perguntaId): RedirectResponse
    {
        $pesquisa = $this->resolvePesquisa($pesquisaId);
        $pergunta = $pesquisa->perguntas()->whereKey($perguntaId)->firstOrFail();

        DB::transaction(function () use ($pergunta) {
            $pergunta->opcoes()->delete();
            $pergunta->respostas()->delete();
            $pergunta->delete();
        });

        return redirect()->back()->with('msg', 'Pergunta removida com sucesso!')->with('tab', 'perguntas');
    }

    protected function resolvePesquisa(int $pesquisaId): Pesquisa
    {
        return Pesquisa::forCongregacao(app('congregacao')->id)
            ->findOrFail($pesquisaId);
    }

    protected function normalizeOptions(?string $raw): array
    {
        if ($raw === null) {
            return [];
        }

        return collect(preg_split('/\r\n|[\r\n]/', $raw))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    protected function syncOptions(Pergunta $pergunta, array $options): void
    {
        if ($pergunta->tipo === 'texto') {
            $pergunta->opcoes()->delete();
            return;
        }

        $pergunta->opcoes()->delete();

        foreach ($options as $texto) {
            $pergunta->opcoes()->create(['texto' => $texto]);
        }
    }

    protected function validatedData(Request $request, ?int $ignoreId = null): array
    {
        $rules = [
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'criada_por' => [
                'required',
                Rule::exists('membros', 'id')->where(fn ($query) => $query->where('congregacao_id', app('congregacao')->id)),
            ],
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
        ];

        return $request->validate($rules);
    }
}
