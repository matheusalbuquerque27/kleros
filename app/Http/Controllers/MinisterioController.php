<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Membro;
use App\Models\Ministerio;
use Illuminate\Http\Request;

class MinisterioController extends Controller
{
    public function form_criar() {
        return view('ministerios/includes/form_criar');
    }

    public function form_editar($id) {
        $ministerio = Ministerio::findOrFail($id);

        return view('ministerios/includes/form_editar', compact('ministerio'));
    }

    public function store(Request $request){

        $ministerio = new Ministerio;

        $ministerio->denominacao_id = app('congregacao')->denominacao_id;
        $ministerio->titulo = $request->titulo;
        $ministerio->sigla = $request->sigla;
        $ministerio->descricao = $request->descricao;

        $ministerio->save();
       
        return redirect('/cadastros')->with('msg', 'Novo ministério adicionado.');
    }

    public function update(Request $request, $id)
    {
        $ministerio = Ministerio::findOrFail($id);

        $ministerio->denominacao_id = app('congregacao')->denominacao_id;
        $ministerio->titulo = $request->titulo;
        $ministerio->sigla = $request->sigla;
        $ministerio->descricao = $request->descricao;

        $ministerio->save();

        return redirect('/cadastros')->with('msg', 'Ministério atualizado com sucesso.');
    }

    public function lista($id){

        $congregacao = app('congregacao');

        //Pesquisa no banco os membros desse ministério pelo id
        $membros = Membro::where('ministerio_id', $id)->paginate(10);
        $naoInclusos = Membro::where(function($query) use ($id) {
            $query->whereNull('ministerio_id')
                ->orWhere('ministerio_id', '<>', $id);
        })->get(); //Não pertencem ainda ao grupo;

        //Informa dados sobre o ministério
        $ministerio = Ministerio::find($id);
       
        return view('ministerios/lista', ['membros' => $membros, 'ministerio' => $ministerio, 'naoInclusos' => $naoInclusos, 'congregacao' => $congregacao]);
    }

    public function destroy($id){
        $ministerio = Ministerio::find($id);

        $ministerio->delete();        

        return response()->json(['success' => true, 'message' => 'Ministério excluído com sucesso!']);
    }

    public function print($data) {
        
        $ministerios = Ministerio::all();
        
        //Cria um objeto para armazenar os membros por ministério
        $membrosByMinisterio = new \stdClass();

        foreach($ministerios as $ministerio) {
            $membros = Membro::where('ministerio_id', $ministerio->id)->get();

            $membrosByMinisterio->{$ministerio->titulo} = $membros;
        }
        

        return view('impressoes/print-ministerios', compact('membrosByMinisterio'));
    }
}
