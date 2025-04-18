<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Culto;
use App\Models\Evento;
use App\Models\Membro;
use App\Models\Recado;
use App\Models\Visitante;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {

        /*Esta parte pega informações do culto e verifica se existe um cadastro realizado para o dia atual

            Se não houver ele envia uma informação vazia, liberando uma mensagem e link para cadastro.
        */
        $culto_hoje = Culto::where('data_culto', date('Y/m/d'))->get();
        
        if($culto_hoje->isEmpty()) {
            $culto_hoje = '';
        }
        
        /*Esta parte verifica se há recados do dia de hoje

            Se não houver ele envia uma informação vazia, com mensagem sobre a ausencia de mensagens.
        */
        $recados = Recado::where('data_recado', date('Y/m/d'))->get();
        
        if($recados->isEmpty()) {
            $recados = '';
        }

        /*Esta parte verifica se há eventos cadastrados para os próximos dias

            Se não houver ele envia uma informação vazia, com mensagem sobre a ausencia de eventos.
        */
        
        $eventos = Evento::whereDate('data_evento', '>', date('Y/m/d'))->limit(4)->orderBy('data_evento', 'asc')->get();
        
        if($eventos->isEmpty()) {
            $eventos = '';
        }

        /*Esta parte verifica se há visitantes já cadastrados

            Se não houver ele envia uma informação vazia, com mensagem sobre a ausencia de visitantes.
        */

        $visitantes = Visitante::whereDate('data_visita', date("Y/m/d"))->get();

        if($visitantes->isEmpty()) {
            $visitantes = '';
        }

        /*Esta parte verifica se há membros fazendo aniversário neste mês

            Se não houver ele envia uma informação vazia, com mensagem sobre a ausencia de aniversariantes.
        */

        $membros = Membro::whereMonth('data_nascimento', Carbon::now()->month)->get();

        if($membros->isEmpty()) {
            $membros = '';
        }


        return view('home', ['visitantes' => $visitantes, 'culto_hoje' => $culto_hoje, 'recados' => $recados, 'eventos' => $eventos, 'membros' => $membros]);
    }
}
