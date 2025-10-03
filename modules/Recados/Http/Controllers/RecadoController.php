<?php

namespace Modules\Recados\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Culto;
use App\Models\Recado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecadoController extends Controller
{

    public function historico(Request $request)
    {
        $congregacao = app('congregacao');

        $recadosQuery = Recado::query()
            ->with(['culto', 'membro']);

        if ($congregacao) {
            $recadosQuery->where('congregacao_id', $congregacao->id);
        }

        if ($request->filled('culto_id')) {
            $recadosQuery->where('culto_id', $request->integer('culto_id'));
        }

        if ($request->filled('mensagem')) {
            $recadosQuery->where('mensagem', 'like', '%' . $request->mensagem . '%');
        }

        $recados = $recadosQuery
            ->orderByDesc('data_recado')
            ->paginate(10)
            ->withQueryString();

        $cultos = Culto::query()
            ->when($congregacao, function ($query) use ($congregacao) {
                $query->where('congregacao_id', $congregacao->id);
            })
            ->orderByDesc('data_culto')
            ->get(['id', 'data_culto']);

        return view('recados::historico', [
            'recados' => $recados,
            'cultos' => $cultos,
            'congregacao' => $congregacao,
        ]);
    }
    public function create() {
        $congregacao = app('congregacao');
        return view('recados::cadastro', ['congregacao' => $congregacao]);
    }

    public function store(Request $request) {

        $recado = new Recado();

        $cultoDoDia = Culto::whereDate('data_culto', now()->toDateString())
            ->where('congregacao_id', app('congregacao')->id)
            ->first();

        if (!$cultoDoDia) {
            return redirect()->route('visitantes.adicionar')
                ->with('msg-error', 'Não enviado! Recados só podem ser enviados em dias de culto!');
        }

        $recado->congregacao_id = app('congregacao')->id;
        $recado->culto_id = $cultoDoDia->id;
        $recado->mensagem = $request->mensagem;
        $recado->data_recado = now()->toDateString();
        $recado->status = false;

        if (Auth::check()) {
            $usuario = Auth::user();
            $recado->membro_id = $usuario->membro_id ?? optional($usuario->membro)->id;
        }

        $recado->save();

        return redirect()->route('visitantes.adicionar')->with('msg', 'Um recado foi registrado!');
    }

    public function list($id) {

        $recados = Recado::where('culto_id', $id)->get();

        return view('recados::listar', ['recados' => $recados]);
    }

    public function destroy($id) {

        $recado = Recado::find($id);
        $recado->delete();

        return redirect('/');
    }
}
