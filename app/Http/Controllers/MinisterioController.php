<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Membro;
use App\Models\Ministerio;
use Illuminate\Http\Request;

class MinisterioController extends Controller
{
    public function create() {
        return view('ministerios/cadastro');
    }

    public function store(Request $request){

        $ministerio = new Ministerio;

        $ministerio->denominacao_id = app('congregacao')->denominacao_id;
        $ministerio->titulo = $request->titulo;

        $ministerio->save();
       
        return redirect('/cadastros')->with('msg', 'Novo ministério adicionado.');
    }

    public function lista($id){

        //Pesquisa no banco os membros desse ministério pelo id
        $membros = Membro::where('ministerio_id', $id)->get();

        //Informa dados sobre o ministério
        $ministerio = Ministerio::find($id);
       
        return view('ministerios/lista', ['membros' => $membros, 'ministerio' => $ministerio]);
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
