<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Congregacao;
use App\Models\Escolaridade;
use App\Models\EstadoCiv;
use App\Models\Membro;
use App\Models\Ministerio;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MembroController extends Controller
{
    private $congregacao;

    public function __construct()
    {
        $this->congregacao = app('congregacao');
    }

    public function adicionar() {
        $escolaridade = Escolaridade::all();
        $ministerios = Ministerio::all();
        $estado_civil = EstadoCiv::all();

        return view('/membros/cadastro', ['escolaridade' => $escolaridade, 'ministerios' => $ministerios, 'estado_civil' => $estado_civil]);
    }

    public function store(Request $request) {

        $membro = new Membro;

        $request->validate([
            'nome' => 'required',
            'telefone' => 'required',
            'data_nascimento' => 'required'
        ], [
            '*.required' => 'Nome, Telefone e Data de nascimento são obrigatórios'
        ]);        

        $membro->congregacao_id = $this->congregacao->id;
        $membro->nome = $request->nome;
        $membro->rg = $request->rg;
        $membro->cpf = $request->cpf;
        $membro->data_nascimento = $request->data_nascimento;
        $membro->telefone = $request->telefone;
        $membro->email = $request->email;
        $membro->estado_civ_id = $request->estado_civil;
        $membro->escolaridade_id = $request->escolaridade;
        $membro->profissao = $request->profissao;
        $membro->endereco = $request->endereco;
        $membro->numero = $request->numero;
        $membro->bairro = $request->bairro;
        $membro->data_batismo= $request->data_batismo;
        $membro->denominacao_origem= $request->denominacao_origem;
        $membro->ministerio_id = $request->ministerio;
        $membro->nome_paterno = $request->nome_paterno;
        $membro->nome_materno = $request->nome_materno;
        $membro->created_at = date('Y-m-d H:i:s');
        $membro->updated_at = date('Y-m-d H:i:s');

        $msg = $request->nome.' se tornou membro da AD Jerusalém.';
        if($membro->save()){
            $user = new User;

            $partes = explode(' ', trim($request->nome));
            $user->name = strtolower($partes[0] . '.' . end($partes)) . $membro->id;            
            $user->email = $request->email;
            $user->password = bcrypt('1q2w3e4r');
            $user->congregacao_id = $this->congregacao->id;
            $user->membro_id = $membro->id;

            $user->save();
        }

        return redirect()->route('membros.adicionar')->with('msg', $msg);
    }

    public function painel() {

        $membros = Membro::paginate(10);
        $congregacao = app('congregacao');
        
        return view('/membros/painel', ['membros' => $membros, 'congregacao' => $congregacao]);
    }

    public function search(Request $request) {

        $filtro = $request->filtro;
        $chave = '%'. $request->chave .'%';
        
        $membros = Membro::where($filtro, 'LIKE', $chave)->get();

        // Renderiza a view com os resultados
        $view = view('membros/painel_search', ['membros' => $membros])->render();

        // Retorna a view renderizada como parte da resposta JSON
        return response()->json(['view' => $view]);
    }

    public function show($id) {

        $membro = Membro::findOrFail($id);
        
        return view('/membros/exibir', ['membro' => $membro]);
    }

    public function editar($id) {

        $membro = Membro::findOrFail($id);
        $estado_civil = EstadoCiv::all();;
        $escolaridade = Escolaridade::all();
        $ministerio = Ministerio::all();

        return view('/membros/edicao', ['membro' => $membro, 'estado_civil' => $estado_civil, 'escolaridade' => $escolaridade, 'ministerios' => $ministerio]);
    }

    public function update(Request $request, $id) {

        $membro = Membro::findOrFail($id);

        $request->validate([
            'nome' => 'required',
            'telefone' => 'required',
            'data_nascimento' => 'required'
        ], [
            'nome.required' => 'Nome do membro não informado',
            'telefone.required' => 'Número de telefone não informado',
            'data_nascimento.required' => 'Data de nascimento não informada'
        ]);

        $membro->nome = $request->nome;
        $membro->rg = $request->rg;
        $membro->cpf = $request->cpf;
        $membro->data_nascimento = $request->data_nascimento;
        $membro->telefone = $request->telefone;
        $membro->estado_civ_id = $request->estado_civil;
        $membro->escolaridade_id = $request->escolaridade;
        $membro->profissao = $request->profissao;
        $membro->endereco = $request->endereco;
        $membro->numero = $request->numero;
        $membro->bairro = $request->bairro;
        $membro->data_batismo= $request->data_batismo;
        $membro->denominacao_origem= $request->denominacao_origem;
        $membro->ministerio_id = $request->ministerio;
        $membro->nome_paterno = $request->nome_paterno;
        $membro->nome_materno = $request->nome_materno;

        // Atualiza os timestamps
        $membro->updated_at = date('Y-m-d H:i:s');

        // Salva as alterações
        if ($membro->save()) {
            return redirect()->route('membros.painel')->with('msg', 'Membro atualizado com sucesso!');
        } else {
            return redirect()->back()->withErrors(['msg' => 'Erro ao atualizar membro.']);
        }
    }
    
    public function destroy($id) {

       $membro = Membro::find($id);

        if (!$membro) {
            return redirect()->route('membros.excluir')->with('msg-error', 'Membro não encontrado.');
        }

        $membro->delete();

        return redirect()->route('membros.painel')->with('msg', 'Membro excluído com sucesso.');
    }

    public function perfil() {
        $membro = Auth::user()->membro;
        return view('/perfil/edicao', ['membro' => $membro]);
    }

    public function save_perfil($id) {
        $membro = Membro::findOrFail($id);

        $request = request();

        $request->validate([
            'nome' => 'required',
        ], [
            'nome.required' => 'O nome é obrigatório.',
        ]);

        $membro->nome = $request->nome;
        $membro->telefone = $request->telefone;
        $membro->email = $request->email;
        $membro->foto = $request->file('foto') ? $request->file('foto')->store('fotos', 'public') : $membro->foto;
        // $membro->estado_civ_id = $request->estado_civil;
        // $membro->escolaridade_id = $request->escolaridade;
        // $membro->profissao = $request->profissao;
        // $membro->endereco = $request->endereco;
        // $membro->numero = $request->numero;
        // $membro->bairro = $request->bairro;
        // $membro->data_batismo= $request->data_batismo;
        // $membro->denominacao_origem= $request->denominacao_origem;
        // $membro->ministerio_id = $request->ministerio;
        // $membro->nome_paterno = $request->nome_paterno;
        // $membro->nome_materno = $request->nome_materno;

        // Atualiza os timestamps
        $membro->updated_at = date('Y-m-d H:i:s');

        // Salva as alterações
        if ($membro->save()) {
            return redirect()->route('perfil')->with('msg', 'Perfil atualizado com sucesso!');
        } else {
            return redirect()->back()->withErrors(['msg' => 'Erro ao atualizar perfil.']);
        }
    }
}
