<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Culto;
use App\Models\Evento;
use Illuminate\Http\Request;

class CultoController extends Controller
{
    public function index() {

        $cultos = Culto::whereDate('data_culto', '<', date('Y/m/d'))->get();
        
        if($cultos->isEmpty()){
            $cultos = '';
        }

        return view('cultos/historico', ['cultos' => $cultos]);
    }

    public function create() {
        $congregacao = app('congregacao');

        $cultos = Culto::whereDate('data_culto', '>', date('Y/m/d'))->limit(4)->orderBy('data_culto', 'asc')->get();
        $cultos = $cultos->isEmpty() ? '' : $cultos;

        $eventos = Evento::all();

        return view('cultos/cadastro', ['cultos' => $cultos, 'eventos' => $eventos, 'congregacao' => $congregacao]);
    }

    public function agenda() {

        $cultos = Culto::whereDate('data_culto', '>=', date('Y/m/d'))->get();
        $cultos = $cultos->isEmpty() ? '' : $cultos;

        $eventos = Evento::whereDate('data_inicio', '>=', date('Y/m/d'))->distinct('titulo')->pluck('titulo');

        return view('cultos/agenda', ['cultos' => $cultos, 'eventos' => $eventos]);
    }

    public function store(Request $request) {

        $culto = new Culto;

        $culto->data_culto = $request->data_culto;
        $culto->preletor = $request->preletor;
        $culto->quant_visitantes = 0;
        $culto->evento_id = $request->evento_id;

        $culto->save();

        return redirect('/cadastros#cultos')->with('msg', 'Um novo culto foi agendado.');

    }

    public function search(Request $request) {

        $origin = $request->origin;

        if($origin == 'historico'){
            $data_inicial = $request->data_inicial;
            $data_final = $request->data_final;

            $cultos = Culto::whereDate('data_culto', '<=', date('Y/m/d'))->whereDate('data_culto', '>=', $data_inicial)->whereDate('data_culto', '<=', $data_final)->get();
            $cultos = $cultos->isEmpty() ? '' : $cultos;

        } else if($origin == 'agenda'){
            $preletor = $request->preletor;
            $evento = $request->evento;

            $cultos = Culto::whereDate('data_culto', '>=', date('Y/m/d'))->where('preletor', $preletor)->orWhere('evento_id', $evento)->get();
            $cultos = $cultos->isEmpty() ? '' : $cultos;
        }

        $view = view('cultos/cultos_search', ['cultos' => $cultos,  'origin' => $origin])->render();

        return response()->json(['view' => $view]);
    }
}
