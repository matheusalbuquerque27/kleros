<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Culto;
use App\Models\Evento;
use App\Models\Grupo;
use Illuminate\Http\Request;
use DateTime;

class EventoController extends Controller
{
    public function index() {

        $eventos = Evento::all();

        return view('eventos/historico', ['eventos' => $eventos]);
    }

    public function agenda() {

        $eventos = Evento::whereDate('data_inicio', '>=', date('Y/m/d'))->get();
        
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

        $request->validate([
            'titulo' => 'required',
            'data_inicio' => 'required'
        ],[
            '*.required' => 'Título e Data de início são obrigatórios'
        ]);

        $evento->titulo = $request->titulo;
        $evento->grupo_id = $request->grupo_id;
        $evento->descricao = $request->descricao;
        $geracao_cultos = $request->geracao_cultos == "1" ? true : false;
        $evento->data_inicio = $request->data_inicio;

        //Transformar string de datas em DateTime
        $dataInicioObj = new DateTime($request->data_inicio);
        $dataEncerramentoObj = ($request->data_encerramento != null)
            ? new DateTime($request->data_encerramento)
            : null;

        $evento->data_encerramento = ($request->data_encerramento != null 
            && $dataEncerramentoObj > $dataInicioObj) 
            ?  $request->data_encerramento 
            : $request->data_inicio;
            
        if($evento->save()){
            if($geracao_cultos){
                $startDate = $evento->data_inicio;                
                $finalDate = $evento->data_encerramento;

                //Calcula quantos dias tem o evento
                $datas = pegarDiasDeIntervaloDatas($startDate, $finalDate);
                
                foreach ($datas as $dia) {
                    $culto = new Culto();
                    
                    $culto->data_culto = $dia;
                    $culto->preletor = "A definir";
                    $culto->quant_visitantes = 0;
                    $culto->evento_id = $evento->id;
                    
                    $culto->save();
                }
            }
        }
        
        return redirect('/cadastros#eventos')->with('msg', 'Um novo evento foi agendado.');
    }

    public function search(Request $request) {

        $origin = $request->origin;

        if($origin == 'historico'){
            $data_inicial = $request->data_inicial;
            $data_final = $request->data_final;

            $eventos = Evento::whereDate('data_inicio', '<=', date('Y/m/d'))->whereDate('data_inicio', '>=', $data_inicial)->whereDate('data_inicio', '<=', $data_final)->get();
            $eventos = $eventos->isEmpty() ? '' : $eventos;

        } else if($origin == 'agenda'){
            $titulo = $request->titulo;
            $grupo_id = $request->grupo;

            $eventos = Evento::whereDate('data_inicio', '>=', date('Y/m/d'))->where('titulo', $titulo)->orWhere('grupo_id', $grupo_id)->get();
            $eventos = $eventos->isEmpty() ? '' : $eventos;
        }

        $view = view('eventos/eventos_search', ['eventos' => $eventos,  'origin' => $origin])->render();

        return response()->json(['view' => $view]);
    }
}
