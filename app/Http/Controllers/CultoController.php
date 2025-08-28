<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Culto;
use App\Models\Evento;
use Illuminate\Http\Request;

class CultoController extends Controller
{
    public function index() {

        $cultos = Culto::whereDate('data_culto', '<', date('Y-m-d'))->paginate(10);
        
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

        return view('cultos/checkin', ['cultos' => $cultos, 'eventos' => $eventos, 'congregacao' => $congregacao]);
    }

    public function agenda() {

        $congregacao = app('congregacao');
        $cultos = Culto::whereDate('data_culto', '>=', date('Y-m-d'))->paginate(10);
        $cultos = $cultos->isEmpty() ? '' : $cultos;

        $eventos = Evento::whereDate('data_inicio', '>=', date('Y/m/d'))->distinct('titulo')->pluck('titulo');

        return view('cultos/agenda', ['cultos' => $cultos, 'eventos' => $eventos, 'congregacao' => $congregacao]);
    }

    public function store(Request $request) {

        $culto = new Culto;

        $culto->data_culto = $request->data_culto . ' ' . $request->horario_inicio;
        $culto->preletor = $request->preletor;
        $culto->quant_visitantes = $request->quantidade_visitantes ?? 0;
        $culto->evento_id = $request->evento_id;
        $culto->tema_sermao = $request->tema_sermao ?? null;
        $culto->texto_base = $request->texto_base ?? null;
        $culto->quant_adultos = $request->quantidade_adultos ?? 0;
        $culto->quant_criancas = $request->quantidade_criancas ?? 0;

        $culto->save();

        return redirect()->to(url()->previous())->with('msg', 'Um novo culto foi agendado.');

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

    public function complete($id) {
        

        if($id == 'adicionar'){
            $culto = null;
        } else {
            $culto = Culto::findOrFail($id);
        }

        $congregacao = app('congregacao');
        $eventos = Evento::all();

        return view('cultos/checkout', ['culto' => $culto, 'eventos' => $eventos, 'congregacao' => $congregacao]);
    }

    public function update(Request $request, $id){

        $culto = Culto::findOrFail($id);
        
        $culto->congregacao_id = app('congregacao')->id;
        $culto->data_culto = $request->data_culto . ' ' . $request->horario_inicio;
        $culto->preletor = $request->preletor;
        $culto->quant_visitantes = $request->quantidade_visitantes ?? 0;
        $culto->evento_id = $request->evento_id;
        $culto->tema_sermao = $request->tema_sermao ?? null;
        $culto->texto_base = $request->texto_base ?? null;
        $culto->quant_adultos = $request->quantidade_adultos ?? 0;
        $culto->quant_criancas = $request->quantidade_criancas ?? 0;

        $culto->save();

        return redirect()->to(url()->previous())->with('msg', 'Registro de culto atualizado com sucesso.');

    }

    public function form_criar(){
        $eventos = Evento::all();
        return view('cultos/includes/form_criar', ['eventos' => $eventos]);
    }

    public function form_editar($id){
        $culto = Culto::findOrFail($id);
        $eventos = Evento::all();
        return view('cultos/includes/form_editar', ['culto' => $culto, 'eventos' => $eventos]);
    }
}
