<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Culto;
use App\Models\Evento;
use App\Models\SituacaoVisitante;
use App\Models\Visitante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $msg = $request->nome.' foi cadastrado(a) como visitante!';

        $request->validate([
            'nome' => 'required',
            'telefone' => 'required',
            'data_visita' => 'required'
        ], [
            '*.required' => 'Nome do visitante, Telefone e Data de visita são obrigatórios',
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

        return redirect()->route('visitantes.adicionar')->with('msg', $msg);    
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
        $visitantesForView = $visitantes->isEmpty() ? '' : $visitantes;

        $view = view('visitantes/includes/visitantes_search', ['visitantes' => $visitantesForView])->render();

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

        $filename = 'visitantes_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $callback = function () use ($visitantes) {
            $handle = fopen('php://output', 'w');
            if ($handle === false) {
                return;
            }

            fwrite($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, ['Nome', 'Data da visita', 'Telefone', 'Situação', 'Observações'], ';');

            foreach ($visitantes as $visitante) {
                $dataVisita = $visitante->data_visita
                    ? Carbon::parse($visitante->data_visita)->format('Y-m-d')
                    : '';

                fputcsv($handle, [
                    $visitante->nome,
                    $dataVisita,
                    $visitante->telefone,
                    optional($visitante->sit_visitante)->titulo ?? 'Não informado',
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
        $msg = $visitante->nome.' foi atualizado(a) com sucesso!';

        $request->validate([
            'nome' => 'required',
            'telefone' => 'required',
            'data_visita' => 'required'
        ], [
            '*.required' => 'Nome, Telefone e Data de visita são obrigatórios',
        ]);    

        $visitante->nome = $request->nome;
        $visitante->telefone = $request->telefone;
        $visitante->data_visita = $request->data_visita;
        $visitante->sit_visitante_id = $request->sit_visitante;
        $visitante->observacoes = $request->observacoes;
        $visitante->updated_at = date('Y-m-d H:i:s');

        $visitante->save();

        return redirect()->route('visitantes.historico')->with('msg', $msg);
    }

    public function destroy($id) {
        $visitante = Visitante::findOrFail($id);
        $visitante->delete();
        return redirect()->route('visitantes.historico')->with('msg', 'Visitante excluído com sucesso.');
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


        return redirect()->route('membros.editar', $membro->id)->with('msg', $membro->nome.' agora é um membro! Complete os dados cadastrais.');
    }

}
