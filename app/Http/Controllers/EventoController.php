<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesCongregacaoLogoDataUri;
use App\Http\Controllers\Controller;
use App\Models\Agrupamento;
use App\Models\Congregacao;
use App\Models\Culto;
use App\Models\Evento;
use App\Models\EventoOcorrencia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\LaravelPdf\Facades\Pdf;

class EventoController extends Controller
{
    use ResolvesCongregacaoLogoDataUri;

    private $congregacao;

    public function __construct()
    {
        $this->congregacao = app()->bound('congregacao') ? app('congregacao') : null;
    }


    public function index() {
        $eventos = $this->buildHistoricoEventosQuery()->paginate(10);

        return view('eventos/historico', [
            'eventos' => $eventos,
            'congregacao' => $this->congregacaoAtual(),
        ]);
    }

    public function agenda() {
        $congregacao = app('congregacao');
        $congregacaoId = $congregacao->id;

        $eventos = $this->buildAgendaEventosQuery()->paginate(10);

        $titulosFiltro = Evento::where('congregacao_id', $congregacaoId)
            ->where('recorrente', false)
            ->whereDate('data_inicio', '>=', date('Y-m-d'))
            ->orderBy('titulo')
            ->distinct()
            ->pluck('titulo');

        $grupos = Agrupamento::where('congregacao_id', $congregacaoId)
            ->where('tipo', 'grupo')
            ->orderBy('nome')
            ->get();

        return view('eventos/agenda', [
            'eventos' => $eventos,
            'titulosFiltro' => $titulosFiltro,
            'grupos' => $grupos,
            'congregacao' => $congregacao,
        ]);
    }

    public function create() {
        $grupos = Agrupamento::where('tipo', 'grupo')->get();

        return view('eventos/cadastro', ['grupos' => $grupos]);
    }

    public function store(Request $request) {

        $evento = new Evento;

        $request->validate([
            'titulo' => 'required',
            'ocorrencias' => 'required|array|min:1',
            'ocorrencias.*.data_ocorrencia' => 'required|date'
        ],[
            'titulo.required' => 'O título é obrigatório',
            'ocorrencias.required' => 'É necessário adicionar ao menos uma ocorrência',
            'ocorrencias.*.data_ocorrencia.required' => 'Cada ocorrência deve ter uma data'
        ]);

        // Calcula data_inicio e data_encerramento a partir das ocorrências
        $datas = collect($request->input('ocorrencias', []))
            ->pluck('data_ocorrencia')
            ->filter()
            ->sort();

        if ($datas->isEmpty()) {
            return back()->with('msg-error', 'É necessário adicionar ao menos uma ocorrência ao evento.');
        }

        $evento->congregacao_id = $this->congregacao->id;
        $evento->titulo = $request->titulo;
        $evento->agrupamento_id = $request->grupo_id;
        $evento->descricao = $request->descricao;
        $evento->recorrente = $request->evento_recorrente == "1" ? true : false;
        $evento->local = $request->local;
        $evento->requer_inscricao = $request->requer_inscricao == "1" ? true : false;
        
        // Define data_inicio como a menor data e data_encerramento como a maior
        $evento->data_inicio = $datas->first();
        $evento->data_encerramento = $datas->last();
            
        if($evento->save()) {
            // Salva ocorrências do cronograma
            $ocorrencias = collect($request->input('ocorrencias', []))
                ->filter(fn ($item) => !empty($item['data_ocorrencia']))
                ->map(fn ($item) => [
                    'data_ocorrencia' => $item['data_ocorrencia'],
                    'horario_inicio' => $item['horario_inicio'] ?? null,
                    'descricao' => $item['descricao'] ?? null,
                    'local' => $item['local'] ?? null,
                    'culto_id' => null,
                ]);

            $createdOcorrencias = $ocorrencias->isNotEmpty()
                ? collect($evento->ocorrencias()->createMany($ocorrencias->toArray()))
                : collect();

            if (!$evento->recorrente) {
                $ocorrenciasParaCulto = collect($request->input('ocorrencias', []))
                    ->filter(fn ($item) => !empty($item['data_ocorrencia']) && !empty($item['gerar_culto']))
                    ->values();

                foreach ($ocorrenciasParaCulto as $index => $item) {
                    $horario = $item['horario_inicio'] ?? null;
                    $dataHora = $horario
                        ? Carbon::parse($item['data_ocorrencia'] . ' ' . $horario)
                        : Carbon::parse($item['data_ocorrencia']);

                    $culto = new Culto();
                    $culto->congregacao_id = $this->congregacao->id;
                    $culto->data_culto = $dataHora;
                    $culto->preletor_id = null;
                    $culto->preletor_externo = 'A definir';
                    $culto->quant_visitantes = 0;
                    $culto->evento_id = $evento->id;
                    $culto->save();

                    if (isset($createdOcorrencias[$index])) {
                        $createdOcorrencias[$index]->update(['culto_id' => $culto->id]);
                    }
                }
            }
        }
        
        // Se for requisição AJAX, retorna JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Evento criado com sucesso!',
                'evento' => [
                    'id' => $evento->id,
                    'titulo' => $evento->titulo,
                    'data_inicio' => $evento->data_inicio,
                    'data_encerramento' => $evento->data_encerramento,
                ],
                'data' => [
                    'id' => $evento->id,
                    'titulo' => $evento->titulo,
                    'data_inicio' => $evento->data_inicio,
                    'data_encerramento' => $evento->data_encerramento,
                ]
            ]);
        }
        
        return redirect()->back()->with('msg', 'Um novo evento foi agendado.');
    }

    public function search(Request $request) {

        $origin = $request->input('origin');

        if ($origin === 'historico') {
            $query = $this->buildHistoricoEventosQuery(
                $request->input('data_inicial'),
                $request->input('data_final')
            );
        } else {
            $query = $this->buildAgendaEventosQuery(
                $request->input('titulo'),
                $request->input('grupo')
            );
        }

        $eventosCollection = $query->get();
        $eventos = $eventosCollection->isEmpty() ? '' : $eventosCollection;

        $view = view('eventos/eventos_search', ['eventos' => $eventos, 'origin' => $origin])->render();

        return response()->json(['view' => $view]);
    }

    public function imprimirHistorico(Request $request)
    {
        $congregacao = $this->congregacaoAtual()->loadMissing('config');
        $dataInicial = $request->input('data_inicial');
        $dataFinal = $request->input('data_final');

        $eventos = $this->buildHistoricoEventosQuery($dataInicial, $dataFinal)
            ->get()
            ->map(fn (Evento $evento) => $this->prepareEventoForReport($evento));

        return Pdf::view('eventos.relatorios.historico_pdf', [
            'congregacao' => $congregacao,
            'eventos' => $eventos,
            'periodo' => $this->formatPeriodo($dataInicial, $dataFinal, 'Todo o histórico de eventos'),
            'filtros' => [],
            'resumo' => $this->buildEventosResumo($eventos),
            'logoDataUri' => $this->resolveCongregacaoLogoDataUri($congregacao),
            'geradoEm' => now(),
        ])
            ->format('A4')
            ->name('relatorio-eventos-historico.pdf');
    }

    public function imprimirAgenda(Request $request)
    {
        $congregacao = $this->congregacaoAtual()->loadMissing('config');
        $titulo = $request->input('titulo');
        $grupoId = $request->input('grupo');
        $grupo = null;

        if ($grupoId) {
            $grupo = Agrupamento::where('congregacao_id', $this->congregacaoAtual()->id)->find($grupoId);
        }

        $eventos = $this->buildAgendaEventosQuery($titulo, $grupoId)
            ->get()
            ->map(fn (Evento $evento) => $this->prepareEventoForReport($evento));

        $filtros = [];
        if (filled($titulo)) {
            $filtros['Título'] = $titulo;
        }
        if ($grupo) {
            $filtros['Grupo'] = $grupo->nome;
        }

        return Pdf::view('eventos.relatorios.agenda_pdf', [
            'congregacao' => $congregacao,
            'eventos' => $eventos,
            'periodo' => 'Próximos eventos',
            'filtros' => $filtros,
            'resumo' => $this->buildEventosResumo($eventos),
            'logoDataUri' => $this->resolveCongregacaoLogoDataUri($congregacao),
            'geradoEm' => now(),
        ])
            ->format('A4')
            ->name('relatorio-eventos-agenda.pdf');
    }

    public function form_criar(){
        $grupos = Agrupamento::where('congregacao_id', app('congregacao')->id)->where('tipo', 'grupo')->get();
        return view('eventos/includes/form_criar', ['grupos' => $grupos]);
    }

    public function form_editar($id){
        $evento = Evento::with('ocorrencias')->findOrFail($id);
        $grupos = Agrupamento::where('congregacao_id', $this->congregacao->id)
            ->where('tipo', 'grupo')
            ->orderBy('nome')
            ->get();
        return view('eventos/includes/form_editar', ['evento' => $evento, 'grupos' => $grupos]);
    }

    public function update(Request $request, $id)
    {
        $evento = Evento::where('congregacao_id', $this->congregacao->id)->findOrFail($id);

        $request->validate([
            'titulo' => 'required',
            'ocorrencias' => 'required|array|min:1',
            'ocorrencias.*.data_ocorrencia' => 'required|date'
        ], [
            'titulo.required' => 'O título é obrigatório',
            'ocorrencias.required' => 'É necessário ter ao menos uma ocorrência',
            'ocorrencias.*.data_ocorrencia.required' => 'Cada ocorrência deve ter uma data'
        ]);

        $evento->titulo = $request->titulo;
        $evento->agrupamento_id = $request->grupo_id ?: null;
        $evento->descricao = $request->descricao;
        $evento->local = $request->local;
        $evento->requer_inscricao = $request->requer_inscricao == "1" ? true : false;

        // Coleta os IDs das ocorrências enviadas no request
        $idsEnviados = collect($request->input('ocorrencias', []))
            ->filter(fn ($item) => !empty($item['id']))
            ->pluck('id')
            ->toArray();

        // Remove ocorrências que não estão mais no request
        EventoOcorrencia::where('evento_id', $evento->id)
            ->whereNotIn('id', $idsEnviados)
            ->delete();

        // Atualiza/insere ocorrências do cronograma
        collect($request->input('ocorrencias', []))
            ->filter(fn ($item) => !empty($item['data_ocorrencia']))
            ->each(function ($item) use ($evento) {
                $payload = [
                    'data_ocorrencia' => $item['data_ocorrencia'],
                    'horario_inicio' => $item['horario_inicio'] ?? null,
                    'descricao' => $item['descricao'] ?? null,
                    'local' => $item['local'] ?? null,
                ];

                if (!empty($item['id'])) {
                    EventoOcorrencia::where('evento_id', $evento->id)
                        ->where('id', $item['id'])
                        ->update($payload);
                } else {
                    $evento->ocorrencias()->create($payload);
                }
            });

        // Recalcula data_inicio e data_encerramento baseado nas ocorrências
        $datas = $evento->ocorrencias()
            ->pluck('data_ocorrencia')
            ->filter()
            ->sort();

        if ($datas->isNotEmpty()) {
            $evento->data_inicio = $datas->first();
            $evento->data_encerramento = $datas->last();
        }

        $evento->save();

        // Geração de cultos a partir das ocorrências marcadas
        if (!$evento->recorrente) {
            $evento->load('ocorrencias');
            $ocorrenciasColecao = $evento->ocorrencias;
            $ocorrenciaUsadas = [];

            $ocorrenciasRequest = collect($request->input('ocorrencias', []))
                ->filter(fn ($item) => !empty($item['data_ocorrencia']) && !empty($item['gerar_culto']))
                ->values();

            foreach ($ocorrenciasRequest as $item) {
                $horario = $item['horario_inicio'] ?? null;
                $data = $item['data_ocorrencia'];
                $dataHora = $horario
                    ? Carbon::parse($data . ' ' . $horario)
                    : Carbon::parse($data);

                // Tenta achar a ocorrência correspondente
                $ocorrencia = null;
                if (!empty($item['id'])) {
                    $ocorrencia = $ocorrenciasColecao->firstWhere('id', $item['id']);
                }

                if (!$ocorrencia) {
                    $ocorrencia = $ocorrenciasColecao->first(function ($oc) use ($data, $horario, &$ocorrenciaUsadas) {
                        $key = $oc->id;
                        if (in_array($key, $ocorrenciaUsadas, true)) {
                            return false;
                        }
                        return $oc->data_ocorrencia === $data && $oc->horario_inicio === ($horario ?? null);
                    });
                }

                if ($ocorrencia && $ocorrencia->culto_id) {
                    continue; // Já vinculado
                }

                $culto = new Culto();
                $culto->congregacao_id = $this->congregacao->id;
                $culto->data_culto = $dataHora;
                $culto->preletor_id = null;
                $culto->preletor_externo = 'A definir';
                $culto->quant_visitantes = 0;
                $culto->evento_id = $evento->id;
                $culto->save();

                if ($ocorrencia) {
                    $ocorrencia->update(['culto_id' => $culto->id]);
                    $ocorrenciaUsadas[] = $ocorrencia->id;
                }
            }
        }

        return redirect()->back()->with('msg', 'Evento atualizado com sucesso.');
    }

    public function destroy($id)
    {
        try {
            $evento = Evento::findOrFail($id);
            
            // Remove todas as ocorrências associadas
            $evento->ocorrencias()->delete();
            
            // Remove o evento
            $evento->delete();
            
            return redirect()->back()->with('msg', 'Evento excluído com sucesso.');
        } catch (\Exception $e) {
            return redirect()->back()->with('msg-error', 'Erro ao excluir evento: ' . $e->getMessage());
        }
    }

    private function buildHistoricoEventosQuery(?string $dataInicial = null, ?string $dataFinal = null)
    {
        $congregacao = $this->congregacaoAtual();

        $query = Evento::with(['grupo', 'ocorrencias'])
            ->where('congregacao_id', $congregacao->id)
            ->where('recorrente', false)
            ->whereDate('data_inicio', '<', now()->toDateString());

        if ($dataInicial) {
            $query->whereDate('data_inicio', '>=', $dataInicial);
        }

        if ($dataFinal) {
            $query->whereDate('data_inicio', '<=', $dataFinal);
        }

        return $query->orderByDesc('data_inicio');
    }

    private function buildAgendaEventosQuery(?string $titulo = null, $grupoId = null)
    {
        $congregacao = $this->congregacaoAtual();

        $query = Evento::with(['grupo', 'ocorrencias'])
            ->where('congregacao_id', $congregacao->id)
            ->where('recorrente', false)
            ->whereDate('data_inicio', '>=', now()->toDateString());

        if (filled($titulo)) {
            $query->where('titulo', $titulo);
        }

        if (filled($grupoId)) {
            $query->where('agrupamento_id', $grupoId);
        }

        return $query->orderBy('data_inicio');
    }

    private function prepareEventoForReport(Evento $evento): Evento
    {
        $evento->setRelation(
            'ocorrencias',
            $evento->ocorrencias
                ->sortBy([
                    ['data_ocorrencia', 'asc'],
                    ['horario_inicio', 'asc'],
                ])
                ->values()
        );

        $evento->grupo_label = optional($evento->grupo)->nome ?? 'Geral';
        $evento->ocorrencias_total = $evento->ocorrencias->count();
        $evento->inscricoes_label = $evento->requer_inscricao ? 'Sim' : 'Não';

        return $evento;
    }

    private function buildEventosResumo($eventos): array
    {
        return [
            'total_eventos' => $eventos->count(),
            'total_ocorrencias' => $eventos->sum(fn (Evento $evento) => (int) ($evento->ocorrencias_total ?? $evento->ocorrencias->count())),
            'com_inscricao' => $eventos->filter(fn (Evento $evento) => (bool) $evento->requer_inscricao)->count(),
            'com_grupo' => $eventos->filter(fn (Evento $evento) => ! empty($evento->agrupamento_id))->count(),
        ];
    }

    private function formatPeriodo(?string $dataInicial, ?string $dataFinal, string $fallback): string
    {
        return match (true) {
            $dataInicial && $dataFinal => 'De ' . Carbon::parse($dataInicial)->format('d/m/Y') . ' até ' . Carbon::parse($dataFinal)->format('d/m/Y'),
            $dataInicial => 'A partir de ' . Carbon::parse($dataInicial)->format('d/m/Y'),
            $dataFinal => 'Até ' . Carbon::parse($dataFinal)->format('d/m/Y'),
            default => $fallback,
        };
    }

    private function congregacaoAtual()
    {
        $congregacao = app()->bound('congregacao') ? app('congregacao') : null;

        if ($congregacao) {
            return $congregacao;
        }

        $congregacaoId = optional(Auth::user())->congregacao_id;

        if ($congregacaoId) {
            $this->congregacao = Congregacao::find($congregacaoId);

            return $this->congregacao;
        }

        return $this->congregacao;
    }
}
