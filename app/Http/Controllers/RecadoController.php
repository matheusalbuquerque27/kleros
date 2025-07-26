<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Culto;
use App\Models\Recado;
use Illuminate\Http\Request;

class RecadoController extends Controller
{
    public function create() {
        $congregacao = app('congregacao');
        return view('recados/cadastro', ['congregacao' => $congregacao]);
    }

    public function store(Request $request) {

        $recado = new Recado();

        $recado->mensagem = $request->mensagem;
        $recado->data_recado = date('Y/m/d');
        $recado->status = false;

        $confirma_culto = Culto::whereDate('data_culto', date("Y/m/d"))->get();

        if($confirma_culto->isEmpty()){

            return redirect()->route('visitantes.adicionar')->with('msg-error', 'Não enviado! Recados só podem ser enviados em dias de culto!');
            
        } else {

            $recado->save();

            return redirect()->route('visitantes.adicionar')->with('msg', 'Um recado foi registrado!');
        }
    }

    public function destroy($id) {

        $recado = Recado::find($id);
        $recado->delete();

        return redirect('/');
    }
}
