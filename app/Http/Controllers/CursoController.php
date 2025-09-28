<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;

class CursoController extends Controller
{
    public function index(){
        $congregacaoId = optional(app('congregacao'))->id;

        $cursos = Curso::query()
            ->with(['modulos' => fn ($query) => $query->ordenado()->where('ativo', true)])
            ->where('ativo', true)
            ->where(function ($query) use ($congregacaoId) {
                $query->where('publico', true);

                if ($congregacaoId) {
                    $query->orWhere('congregacao_id', $congregacaoId);
                } else {
                    $query->orWhereNull('congregacao_id');
                }
            })
            ->orderBy('titulo')
            ->get();

        return view('cursos.painel', compact('cursos'));
    }
    public function form_criar(){
       
        return view('cursos/includes/form_criar');
    }

    public function store(Request $request){

        // Validação dos dados do formulário
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'publico' => 'required|boolean',
            'ativo' => 'required|boolean',
            'icone' => 'nullable|image|max:2048', // max 2MB
        ]);

        // Preparar os dados
        $dadosCurso = [
            'titulo' => $validated['titulo'],
            'descricao' => $validated['descricao'] ?? null,
            'publico' => $validated['publico'],
            'ativo' => $validated['ativo'],
            'congregacao_id' => app('congregacao')->id ?? null,
        ];

        // Se um ícone foi enviado, salvar no storage e adicionar ao array
        if ($request->hasFile('icone')) {
            $icone = $request->file('icone');
            $path = $icone->store('cursos/icones', 'public');
            $dadosCurso['icone'] = $path;
        }

        // Criar o curso
        Curso::create($dadosCurso);

        // Redirecionar com mensagem de sucesso
        return redirect('/cadastros#cursos')->with('success', 'Curso cadastrado com sucesso!');

    }

    public function destroy(Request $request, $id)
    {
        $curso = Curso::findOrFail($id);

        $curso->delete();

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Curso excluído com sucesso!']);
        }

        return redirect('/cadastros#cursos')->with('msg', 'Curso excluído com sucesso!');
    }
}
