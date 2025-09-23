<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Models\Culto;
use App\Models\Evento;
use App\Models\Membro;
use App\Models\Recado;
use App\Models\Visitante;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    private $congregacao;

    public function __construct() {
        $this->middleware('auth')->except(['login', 'authenticate']);
        $this->congregacao = app('congregacao');
    }

    public function login() {

        // Verifica se a aplicação está rodando em modo admin
        if (app('modo_admin')) {
            return redirect()->route('admin.dashboard');
        }

        return view('login', ['congregacao' => $this->congregacao]);
    }

    public function authenticate(Request $request) {
        $credentials = $request->only('name', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->put('congregacao_id', Auth::user()->congregacao_id);
            $request->session()->put('user_id', Auth::user()->id);
            $request->session()->put('membro_id', Auth::user()->membro_id);
            $request->session()->regenerate();
            return redirect()->route('index');
        }

        return redirect()->back()->withErrors(['user' => 'Credenciais inválidas.']);
    }

    public function index() {

        if (app('modo_admin')) {
            // Painel geral da plataforma
            return view('admin.dashboard');
        }
        
        $congregacao = app('congregacao');
        $usuario = Auth::user();

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
        $recados = '';

        if (module_enabled('recados')) {
            $recadosCollection = Recado::where('data_recado', date('Y/m/d'))->get();

            if ($recadosCollection->isNotEmpty()) {
                $recados = $recadosCollection;
            }
        }

        /*Esta parte verifica se há eventos cadastrados para os próximos dias

            Se não houver ele envia uma informação vazia, com mensagem sobre a ausencia de eventos.
        */
        
        $eventos = Evento::where('recorrente', false)->whereDate('data_inicio', '>', date('Y/m/d'))->limit(4)->orderBy('data_inicio', 'asc')->get();
        
        if($eventos->isEmpty()) {
            $eventos = '';
        }

        /*Esta parte verifica se há visitantes já cadastrados

            Se não houver ele envia uma informação vazia, com mensagem sobre a ausencia de visitantes.
        */

        $visitantes = Visitante::where('congregacao_id', $congregacao->id)->whereDate('data_visita', date("Y/m/d"))->get();

        if($visitantes->isEmpty()) {
            $visitantes = '';
        }

        /*Esta parte verifica se há membros fazendo aniversário neste mês

            Se não houver ele envia uma informação vazia, com mensagem sobre a ausencia de aniversariantes.
        */

        $membros = Membro::whereMonth('data_nascimento', Carbon::now()->month)->orderBy('data_nascimento', 'asc')->get();

        if($membros->isEmpty()) {
            $membros = '';
        }


        return view('home', ['visitantes' => $visitantes, 'culto_hoje' => $culto_hoje, 'recados' => $recados, 'eventos' => $eventos, 'membros' => $membros, 'congregacao' => $congregacao, 'usuario' => $usuario]);
    }
}
