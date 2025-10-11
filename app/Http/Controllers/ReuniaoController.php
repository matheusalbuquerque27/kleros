<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Agrupamento;
use App\Models\Membro;
use App\Models\Reuniao;
use App\Services\AvisoService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReuniaoController extends Controller
{
    public function index() {
        $reunioes = Reuniao::where('congregacao_id', app('congregacao')->id)
            ->orderBy('data_inicio', 'asc')
            ->paginate(10);
        $congregacao = app('congregacao');
        return view('reunioes.painel', ['congregacao' => $congregacao ,'reunioes' => $reunioes]);
    }

    public function create(){

        $congregacao = app('congregacao');
        $congregacaoId = $congregacao->id;

        $agrupamentos = Agrupamento::where('congregacao_id', $congregacaoId)
            ->orderBy('nome')
            ->get()
            ->groupBy('tipo');

        $membros = Membro::DaCongregacao()->orderBy('nome')->get();

        return view('reunioes.cadastro', [
            'agrupamentos' => $agrupamentos,
            'membros' => $membros,
            'congregacao' => $congregacao,
        ]);
    }

    public function store(Request $request){

        $reuniao = new Reuniao;

        $reuniao->congregacao_id = app('congregacao')->id;
        $reuniao->assunto = $request->input('assunto', 'Reunião');
        $reuniao->descricao = $request->input('descricao');
        $reuniao->tipo = $request->input('tipo', 'geral');
        $reuniao->privado = (bool) $request->input('privado', false);
        $reuniao->online = (bool) $request->input('online', false);
        $reuniao->local = $request->input('local');
        $reuniao->link_online = $request->input('link_online');

        $dataInicio = $request->input('data_inicio');
        $horaInicio = $request->input('horario_inicio');
        if ($dataInicio) {
            $reuniao->data_inicio = $dataInicio . ' ' . ($horaInicio ?? '00:00:00');
        } elseif ($horaInicio) {
            $reuniao->data_inicio = now()->format('Y-m-d') . ' ' . $horaInicio;
        } else {
            $reuniao->data_inicio = now();
        }

        if ($request->filled('data_fim')) {
            $reuniao->data_fim = $request->input('data_fim');
        }

        $reuniao->save();

        $agrupamentos = collect((array) $request->input('agrupamentos'))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $membros = collect((array) $request->input('membros'))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $reuniao->agrupamentos()->sync($agrupamentos);
        $reuniao->membros()->sync($membros);

        $reuniao->load('agrupamentos', 'membros');

        $dataInicio = Carbon::parse($reuniao->data_inicio);
        $dataFim = $reuniao->data_fim ? Carbon::parse($reuniao->data_fim) : null;

        $detalhes = [
            'Data: ' . $dataInicio->format('d/m/Y'),
            'Horário: ' . $dataInicio->format('H:i'),
            'Tipo: ' . ucfirst($reuniao->tipo),
            'Acesso: ' . ($reuniao->privado ? 'Reservada aos convidados' : 'Aberta aos membros da congregação'),
            'Formato: ' . ($reuniao->online ? 'Online' : 'Presencial'),
        ];

        if ($dataFim && $dataFim->greaterThan($dataInicio)) {
            $detalhes[] = 'Término previsto: ' . $dataFim->format('d/m/Y H:i');
        }

        if (!$reuniao->online && $reuniao->local) {
            $detalhes[] = 'Local: ' . $reuniao->local;
        }

        if ($reuniao->online && $reuniao->link_online) {
            $detalhes[] = 'Link da reunião: ' . $reuniao->link_online;
        }

        if (!empty($agrupamentos)) {
            $nomesGrupos = $reuniao->agrupamentos->pluck('nome')->all();
            if (!empty($nomesGrupos)) {
                $detalhes[] = 'Grupos convidados: ' . implode(', ', $nomesGrupos);
            }
        }

        if (!empty($membros)) {
            $detalhes[] = 'Convites individuais: ' . count($membros) . ' membro(s)';
        }

        if ($reuniao->descricao) {
            $detalhes[] = 'Pauta: ' . $reuniao->descricao;
        }

        $mensagemReuniao = 'Uma nova reunião foi agendada: ' . $reuniao->assunto . "\n\n"
            . implode("\n", array_filter($detalhes))
            . "\n\nConfirme sua presença e organize-se para participar.";

        $paraTodos = !$reuniao->privado && empty($agrupamentos) && empty($membros);

        AvisoService::enviar([
            'titulo' => 'Reunião: ' . $reuniao->assunto,
            'mensagem' => $mensagemReuniao,
            'prioridade' => 'importante',
            'para_todos' => $paraTodos,
            'grupos' => $agrupamentos,
            'membros' => $membros,
            'criado_por' => Auth::id(),
        ]);

        return redirect('/cadastros#reunioes')->with('msg', 'Reunião agendada com sucesso.');
    }


    public function update(Request $request, Reuniao $reuniao)
    {
        $dataInicio = $request->input('data_inicio');
        $horaInicio = $request->input('horario_inicio');

        if ($dataInicio) {
            $reuniao->data_inicio = $dataInicio . ' ' . ($horaInicio ?? $reuniao->data_inicio?->format('H:i'));
        } elseif ($horaInicio && $reuniao->data_inicio) {
            $reuniao->data_inicio = $reuniao->data_inicio->format('Y-m-d') . ' ' . $horaInicio;
        }

        if ($request->filled('data_fim')) {
            $reuniao->data_fim = $request->input('data_fim');
        } elseif ($request->has('data_fim')) {
            $reuniao->data_fim = null;
        }

        $reuniao->assunto = $request->input('assunto', $reuniao->assunto);
        $reuniao->tipo = $request->input('tipo', $reuniao->tipo);
        $reuniao->descricao = $request->input('descricao', $reuniao->descricao);
        $reuniao->privado = (bool) $request->input('privado', $reuniao->privado);
        $reuniao->online = (bool) $request->input('online', $reuniao->online);
        $reuniao->local = $request->input('local', $reuniao->local);
        if ($request->has('link_online')) {
            $reuniao->link_online = $request->input('link_online');
        }

        $reuniao->save();

        $agrupamentos = collect((array) $request->input('agrupamentos'))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $membros = collect((array) $request->input('membros'))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        $reuniao->agrupamentos()->sync($agrupamentos);
        $reuniao->membros()->sync($membros);

        return redirect('/cadastros#reunioes')->with('msg', 'Reunião atualizada com sucesso.');
    }

    public function form_criar(){
        $congregacaoId = app('congregacao')->id;

        $agrupamentos = Agrupamento::where('congregacao_id', $congregacaoId)
            ->orderBy('nome')
            ->get()
            ->groupBy('tipo');

        $membros = Membro::DaCongregacao()->orderBy('nome')->get();

        return view('reunioes.includes.form_criar', [
            'agrupamentos' => $agrupamentos,
            'membros' => $membros,
        ]);
    }

    public function form_editar($id){
        $congregacaoId = app('congregacao')->id;

        $agrupamentos = Agrupamento::where('congregacao_id', $congregacaoId)
            ->orderBy('nome')
            ->get()
            ->groupBy('tipo');

        $membros = Membro::DaCongregacao()->orderBy('nome')->get();
        $reuniao = Reuniao::with(['membros', 'agrupamentos'])->findOrFail($id);

        return view('reunioes.includes.form_editar', [
            'reuniao' => $reuniao,
            'agrupamentos' => $agrupamentos,
            'membros' => $membros,
        ]);
    }

    public function destroy(Reuniao $reuniao)
    {
        $reuniao->delete();

        return redirect('/cadastros#reunioes')->with('msg', 'Reunião excluída com sucesso.');
    }

    public function search(Request $request)
    {
        $congregacaoId = app('congregacao')->id;

        $query = Reuniao::query()
            ->where('congregacao_id', $congregacaoId)
            ->orderBy('data_inicio', 'asc');

        if ($request->filled('assunto')) {
            $termo = $request->input('assunto');
            $query->where(function ($q) use ($termo) {
                $q->where('assunto', 'like', '%' . $termo . '%')
                    ->orWhere('descricao', 'like', '%' . $termo . '%');
            });
        }

        if ($request->filled('data_inicial')) {
            $query->whereDate('data_inicio', '>=', $request->input('data_inicial'));
        }

        if ($request->filled('data_final')) {
            $query->whereDate('data_inicio', '<=', $request->input('data_final'));
        }

        $reunioes = $query->get();

        $view = view('reunioes.includes.lista', [
            'reunioes' => $reunioes,
            'mostrarPaginacao' => false,
        ])->render();

        return response()->json(['view' => $view]);
    }
}
