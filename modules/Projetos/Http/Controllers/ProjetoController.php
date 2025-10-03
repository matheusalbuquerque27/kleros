<?php

namespace Modules\Projetos\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Projetos\Models\Projeto;
use Modules\Projetos\Models\ProjetoLista;

class ProjetoController extends Controller
{
    public function index(Request $request): View
    {
        $congregacao = app('congregacao');
        abort_if(! $congregacao, 404);

        $query = Projeto::query()
            ->where('congregacao_id', $congregacao->id)
            ->orderByDesc('updated_at');

        $filter = $request->input('filtro', 'titulo');
        $term = trim((string) $request->input('termo'));

        if ($term !== '') {
            if ($filter === 'responsavel') {
                $query->whereHas('membros', fn ($sub) => $sub->where('nome', 'like', "%{$term}%"));
            } else {
                $query->where('nome', 'like', "%{$term}%");
            }
        }

        $projects = $query->paginate(12)->withQueryString();
        return view('projetos::painel', [
            'projects' => $projects,
            'appName' => config('app.name'),
            'congregacao' => $congregacao,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $congregacao = app('congregacao');
        abort_if(! $congregacao, 404);

        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'cor' => ['nullable', 'string', 'max:20'],
            'para_todos' => ['nullable', 'boolean'],
        ]);

        $projeto = Projeto::create([
            'congregacao_id' => $congregacao->id,
            'nome' => $validated['nome'],
            'cor' => $validated['cor'] ?? null,
            'para_todos' => $validated['para_todos'] ?? false,
        ]);

        $projeto->statuses()->createMany([
            ['nome' => 'Planejado', 'ordem' => 1],
            ['nome' => 'Em andamento', 'ordem' => 2],
            ['nome' => 'Concluído', 'ordem' => 3],
        ]);

        return redirect()->route('projetos.exibir', $projeto)
            ->with('success', 'Projeto criado com sucesso.');
    }

    public function formCriar(): View
    {
        $congregacao = app('congregacao');
        abort_if(! $congregacao, 404);

        return view('projetos::form_criar', [
            'appName' => config('app.name'),
            'congregacao' => $congregacao,
        ]);
    }

    public function show(Projeto $projeto): View
    {
        $congregacao = app('congregacao');
        abort_if(! $congregacao || $projeto->congregacao_id !== $congregacao->id, 403);

        $projeto->load([
            'statuses' => fn ($q) => $q->orderBy('ordem'),
            'listas.cards.status',
        ]);

        return view('projetos::exibir', [
            'projeto' => $projeto,
            'appName' => config('app.name'),
            'congregacao' => $congregacao,
        ]);
    }

    public function formEditar(Projeto $projeto): View
    {
        $congregacao = app('congregacao');
        abort_if(! $congregacao || $projeto->congregacao_id !== $congregacao->id, 403);

        return view('projetos::form_editar', [
            'projeto' => $projeto,
            'appName' => config('app.name'),
            'congregacao' => $congregacao,
        ]);
    }

    public function update(Request $request, Projeto $projeto): RedirectResponse
    {
        $congregacao = app('congregacao');
        abort_if(! $congregacao || $projeto->congregacao_id !== $congregacao->id, 403);

        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'cor' => ['nullable', 'string', 'max:20'],
            'para_todos' => ['nullable', 'boolean'],
        ]);

        $projeto->update([
            'nome' => $validated['nome'],
            'cor' => $validated['cor'] ?? null,
            'para_todos' => $validated['para_todos'] ?? false,
        ]);

        return redirect()->route('projetos.exibir', $projeto)
            ->with('success', 'Projeto atualizado com sucesso.');
    }

    public function storeLista(Request $request, Projeto $projeto): RedirectResponse
    {
        $congregacao = app('congregacao');
        abort_if(! $congregacao || $projeto->congregacao_id !== $congregacao->id, 403);

        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
        ]);

        $projeto->listas()->create($validated);

        return back()->with('success', 'Lista adicionada com sucesso.');
    }

    public function storeCard(Request $request, Projeto $projeto, ProjetoLista $lista): RedirectResponse
    {
        $congregacao = app('congregacao');
        abort_if(! $congregacao || $projeto->congregacao_id !== $congregacao->id || $lista->projeto_id !== $projeto->id, 403);

        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string', 'max:1000'],
        ]);

        $lista->cards()->create([
            'titulo' => $validated['titulo'],
            'descricao' => $validated['descricao'] ?? null,
        ]);

        return back()->with('success', 'Card adicionado com sucesso.');
    }

    public function destroy(Projeto $projeto): RedirectResponse
    {
        $congregacao = app('congregacao');
        abort_if(! $congregacao || $projeto->congregacao_id !== $congregacao->id, 403);

        $projeto->delete();

        return redirect()->route('projetos.painel')
            ->with('success', 'Projeto removido com sucesso.');
    }
}
