<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Culto;
use App\Models\Evento;
use App\Models\SituacaoVisitante;
use App\Models\Visitante;
use Illuminate\Http\Request;
use App\Models\Membro;
use Illuminate\Support\Carbon;

class VisitanteController extends Controller
{
    public function create() {

        $situacao_visitante = SituacaoVisitante::all();
        $congregacao = app('congregacao');

        return view('visitantes/cadastro', ['situacao_visitante' => $situacao_visitante, 'congregacao' => $congregacao]);
    }

    public function store(Request $request) {

        $visitante = new Visitante;

        $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'telefone' => ['required', 'string', 'max:100'],
            'data_visita' => ['required', 'date'],
        ], [
            'nome.required' => __('visitors.validation.name_required'),
            'telefone.required' => __('visitors.validation.phone_required'),
            'data_visita.required' => __('visitors.validation.date_required'),
        ]);

        $visitante->nome = $request->nome;
        $visitante->telefone = $request->telefone;
        $visitante->data_visita = $request->data_visita;
        $visitante->sit_visitante_id = $request->situacao;
        $visitante->observacoes = $request->observacoes;
        $visitante->congregacao_id = app('congregacao')->id;
        $visitante->created_at = date('Y-m-d H:i:s');
        $visitante->updated_at = date('Y-m-d H:i:s');

        $visitante->save();

        return redirect()
            ->route('visitantes.adicionar')
            ->with('msg', __('visitors.flash.created', ['name' => $visitante->nome]));
    }

    public function historico() {

        $visitantes = Visitante::where('congregacao_id', app('congregacao')->id)
            ->orderByDesc('data_visita')
                ->paginate(10);

        return view('visitantes/historico', ['visitantes' => $visitantes]);
    }

    public function search(Request $request) {
        
        $query = Visitante::where('congregacao_id', app('congregacao')->id);

        if ($request->filled('data_visita')) {
            $query->whereDate('data_visita', $request->data_visita);
        }

        if ($request->filled('nome')) {
            $query->where('nome', 'LIKE', '%' . $request->nome . '%');
        }

        $visitantes = $query->orderByDesc('data_visita')->get();

        $view = view('visitantes/includes/visitantes_search', ['visitantes' => $visitantes])->render();

        return response()->json(['view' => $view]);

    }

    public function export(Request $request)
    {
        $query = Visitante::where('congregacao_id', app('congregacao')->id)
            ->with('sit_visitante')
            ->orderByDesc('data_visita');

        if ($request->filled('data_visita')) {
            $query->whereDate('data_visita', $request->input('data_visita'));
        }

        if ($request->filled('nome')) {
            $query->where('nome', 'LIKE', '%' . $request->input('nome') . '%');
        }

        $visitantes = $query->get();

        $filename = __('visitors.export.filename_prefix') . now()->format('Y-m-d_H-i-s') . '.csv';

        $callback = function () use ($visitantes) {
            $handle = fopen('php://output', 'w');
            if ($handle === false) {
                return;
            }

            fwrite($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            $headers = __('visitors.export.headers');
            if (!is_array($headers)) {
                $headers = ['Nome', 'Data da visita', 'Telefone', 'Situação', 'Observações'];
            }

            fputcsv($handle, $headers, ';');

            foreach ($visitantes as $visitante) {
                $dataVisita = $visitante->data_visita
                    ? Carbon::parse($visitante->data_visita)->format('Y-m-d')
                    : '';

                fputcsv($handle, [
                    $visitante->nome,
                    $dataVisita,
                    $visitante->telefone,
                    optional($visitante->sit_visitante)->titulo ?? __('visitors.common.statuses.not_informed'),
                    $visitante->observacoes,
                ], ';');
            }

            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function exibir($id) {

        $visitante = Visitante::findOrFail($id);

        return view('visitantes/exibir', ['visitante' => $visitante]);
    }

    public function form_editar($id) {

        $visitante = Visitante::findOrFail($id);
        $situacao_visitante = SituacaoVisitante::all();

        return view('visitantes/includes/form_editar', ['visitante' => $visitante, 'situacao_visitante' => $situacao_visitante]);
    }

    public function update(Request $request, $id) {

        $visitante = Visitante::findOrFail($id);

        $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'telefone' => ['required', 'string', 'max:100'],
            'data_visita' => ['required', 'date'],
        ], [
            'nome.required' => __('visitors.validation.name_required'),
            'telefone.required' => __('visitors.validation.phone_required'),
            'data_visita.required' => __('visitors.validation.date_required'),
        ]);

        $visitante->nome = $request->nome;
        $visitante->telefone = $request->telefone;
        $visitante->data_visita = $request->data_visita;
        $visitante->sit_visitante_id = $request->sit_visitante;
        $visitante->observacoes = $request->observacoes;
        $visitante->updated_at = date('Y-m-d H:i:s');

        $visitante->save();

        return redirect()
            ->route('visitantes.historico')
            ->with('msg', __('visitors.flash.updated', ['name' => $visitante->nome]));
    }

    public function destroy($id) {
        $visitante = Visitante::findOrFail($id);
        $visitante->delete();

        return redirect()
            ->route('visitantes.historico')
            ->with('msg', __('visitors.flash.deleted'));
    }

    public function tornarMembro(Request $request) {
        
        $membro = new Membro;
        $membro->nome = $request->nome;
        $membro->telefone = $request->telefone;
        $membro->data_nascimento = null;
        $membro->congregacao_id = app('congregacao')->id;
        $membro->created_at = date('Y-m-d H:i:s');
        $membro->updated_at = date('Y-m-d H:i:s');
        $membro->save();

        return redirect()
            ->route('membros.editar', $membro->id)
            ->with('msg', __('visitors.flash.converted', ['name' => $membro->nome]));
    }

}
