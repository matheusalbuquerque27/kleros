<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\Grupo;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index() {

        $eventos = Evento::all();

        return view('eventos/historico', ['eventos' => $eventos]);
    }

    public function agenda() {

        $eventos = Evento::whereDate('data_evento', '>=', date('Y/m/d'))->get();
        
        $eventos = $eventos->isEmpty() ? '' : $eventos;

        $grupos = Grupo::all();

        return view('eventos/agenda', ['eventos' => $eventos, 'grupos' => $grupos]);
    }

    public function create() {

        $grupos = Grupo::all();

        return view('eventos/eventos', ['grupos' => $grupos]);
    }

    public function store(Request $request) {

        $evento = new Evento;

        $evento->titulo = $request->titulo;
        $evento->grupo_id = $request->grupo_id;
        $evento->descricao = $request->descricao;
        $evento->data_evento = $request->data_evento;
        $evento->created_at = date('Y/m/d');
        $evento->updated_at = date('Y/m/d');
        
        $evento->save();

        return redirect('/cadastros#eventos')->with('msg', 'Um novo evento foi agendado.');
    }

    public function search(Request $request) {

        $origin = $request->origin;

        if($origin == 'historico'){
            $data_inicial = $request->data_inicial;
            $data_final = $request->data_final;

            $eventos = Evento::whereDate('data_evento', '<=', date('Y/m/d'))->whereDate('data_evento', '>=', $data_inicial)->whereDate('data_evento', '<=', $data_final)->get();
            $eventos = $eventos->isEmpty() ? '' : $eventos;

        } else if($origin == 'agenda'){
            $titulo = $request->titulo;
            $grupo_id = $request->grupo;

            $eventos = Evento::whereDate('data_evento', '>=', date('Y/m/d'))->where('titulo', $titulo)->orWhere('grupo_id', $grupo_id)->get();
            $eventos = $eventos->isEmpty() ? '' : $eventos;
        }

        $view = view('eventos/eventos_search', ['eventos' => $eventos,  'origin' => $origin])->render();

        return response()->json(['view' => $view]);
    }
}
