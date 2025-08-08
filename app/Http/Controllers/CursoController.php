<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;

class CursoController extends Controller
{
    public function index(){
    }
    public function create(){
       
        return view('cursos.cadastro');

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
}
