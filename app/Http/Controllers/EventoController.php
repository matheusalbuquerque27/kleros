<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Culto;
use App\Models\Evento;
use App\Models\Agrupamento;
use Illuminate\Http\Request;
use DateTime;

class EventoController extends Controller
{
    private $congregacao;

    public function __construct()
    {
        $this->congregacao = app('congregacao');
    }


    public function index() {

        $eventos = Evento::whereDate('data_inicio', '<', date('Y-m-d'))->paginate(10);

        return view('eventos/historico', ['eventos' => $eventos]);
    }

    public function agenda() {

        $eventos = Evento::whereDate('data_inicio', '>=', date('Y-m-d'))->paginate(10);
        
        $eventos = $eventos->isEmpty() ? '' : $eventos;

        $grupos = Agrupamento::where('tipo', 'grupo')->get();
        $congregacao = app('congregacao');

        return view('eventos/agenda', ['eventos' => $eventos, 'grupos' => $grupos, 'congregacao' => $congregacao]);
    }

    public function create() {
        $grupos = Agrupamento::where('tipo', 'grupo')->get();

        return view('eventos/cadastro', ['grupos' => $grupos]);
    }

    public function store(Request $request) {

        $evento = new Evento;

        $request->validate([
            'titulo' => 'required',
            'data_inicio' => 'required'
        ],[
            '*.required' => 'Título e Data de início são obrigatórios'
        ]);

        $evento->congregacao_id = $this->congregacao->id;
        $evento->titulo = $request->titulo;
        $evento->agrupamento_id = $request->grupo_id;
        $evento->descricao = $request->descricao;
        $evento->recorrente = $request->evento_recorrente == "1" ? true : false;
        $geracao_cultos = $request->geracao_cultos == "1" ? true : false;
        $evento->local = $request->local;
        $evento->requer_inscricao = $request->requer_inscricao == "1" ? true : false;
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
            
        if($evento->save() && !$evento->recorrente) {
            if($geracao_cultos){
                $startDate = $evento->data_inicio;                
                $finalDate = $evento->data_encerramento;

                //Calcula quantos dias tem o evento
                $datas = pegarDiasDeIntervaloDatas($startDate, $finalDate);
                
                foreach ($datas as $dia) {
                    $culto = new Culto();
                    
                    $culto->congregacao_id = $this->congregacao->id;
                    $culto->data_culto = $dia;
                    $culto->preletor = "A definir";
                    $culto->quant_visitantes = 0;
                    $culto->evento_id = $evento->id;
                    
                    $culto->save();
                }
            }
        }
        
        return redirect()->back()->with('msg', 'Um novo evento foi agendado.');
    }

    public function search(Request $request) {

        $origin = $request->origin;

        if($origin == 'historico'){
            $data_inicial = $request->data_inicial;
            $data_final = $request->data_final;

            $eventos = Evento::where('recorrente', false)->whereDate('data_inicio', '<=', date('Y/m/d'))->whereDate('data_inicio', '>=', $data_inicial)->whereDate('data_inicio', '<=', $data_final)->get();
            $eventos = $eventos->isEmpty() ? '' : $eventos;

        } else if($origin == 'agenda'){
            $titulo = $request->titulo;
            $grupo_id = $request->grupo;

            $eventos = Evento::where('recorrente', false)->whereDate('data_inicio', '>=', date('Y/m/d'))->where('titulo', $titulo)->orWhere('grupo_id', $grupo_id)->get();
            $eventos = $eventos->isEmpty() ? '' : $eventos;
        }

        $view = view('eventos/eventos_search', ['eventos' => $eventos,  'origin' => $origin])->render();

        return response()->json(['view' => $view]);
    }

    public function form_criar(){
        $grupos = Agrupamento::where('congregacao_id', app('congregacao')->id)->where('tipo', 'grupo')->get();
        return view('eventos/includes/form_criar', ['grupos' => $grupos]);
    }

    public function form_editar($id){
        $evento = Evento::findOrFail($id);
        $grupos = Agrupamento::where('tipo', 'grupo')->get();
        return view('eventos/includes/form_editar', ['evento' => $evento, 'grupos' => $grupos]);
    }

    public function update(Request $request, $id)
    {
        $evento = Evento::where('congregacao_id', $this->congregacao->id)->findOrFail($id);

        $request->validate([
            'titulo' => 'required',
            'data_inicio' => 'required',
        ], [
            '*.required' => 'Título e Data de início são obrigatórios'
        ]);

        $evento->titulo = $request->titulo;
        $evento->agrupamento_id = $request->grupo_id ?: null;
        $evento->descricao = $request->descricao;
        $evento->recorrente = $request->evento_recorrente == "1" ? true : false;
        $evento->local = $request->local;
        $evento->requer_inscricao = $request->requer_inscricao == "1" ? true : false;
        $evento->data_inicio = $request->data_inicio;

        $dataInicioObj = new DateTime($request->data_inicio);
        $dataEncerramentoObj = ($request->data_encerramento != null)
            ? new DateTime($request->data_encerramento)
            : null;

        $evento->data_encerramento = ($request->data_encerramento != null
            && $dataEncerramentoObj > $dataInicioObj)
            ? $request->data_encerramento
            : $request->data_inicio;

        $evento->save();

        $geracaoCultos = $request->geracao_cultos == "1" ? true : false;

        if ($geracaoCultos && ! $evento->recorrente) {
            $datas = pegarDiasDeIntervaloDatas($evento->data_inicio, $evento->data_encerramento);

            foreach ($datas as $dia) {
                $evento->culto()->firstOrCreate(
                    ['data_culto' => $dia],
                    [
                        'congregacao_id' => $this->congregacao->id,
                        'preletor' => 'A definir',
                        'quant_visitantes' => 0,
                    ]
                );
            }
        }

        return redirect()->back()->with('msg', 'Evento atualizado com sucesso.');
    }
}
