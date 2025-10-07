<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Denominacao;
use App\Models\Congregacao;
use App\Models\Dominio;

class AdminController extends Controller
{
    public function login()
    {
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('name', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['login' => 'Credenciais inválidas.']);
    }

    public function dashboard()
    {

        $denominacoes = Denominacao::withCount('congregacoes')->orderBy('nome')->get();
        $congregacoes = Congregacao::with(['denominacao', 'cidade', 'estado'])
            ->withCount('membros')
            ->orderByDesc('created_at')
            ->get();
        $dominios = Dominio::all();

        $membersByDenomination = Denominacao::leftJoin('congregacoes', 'congregacoes.denominacao_id', '=', 'denominacoes.id')
            ->leftJoin('membros', 'membros.congregacao_id', '=', 'congregacoes.id')
            ->select('denominacoes.id', 'denominacoes.nome')
            ->selectRaw('COUNT(membros.id) as total_membros')
            ->groupBy('denominacoes.id', 'denominacoes.nome')
            ->orderByDesc('total_membros')
            ->get();

        $stats = [
            'denominacoes' => $denominacoes->count(),
            'congregacoes' => $congregacoes->count(),
            'dominios' => $dominios->count(),
            'membros' => $membersByDenomination->sum('total_membros'),
        ];

        return view('admin.dashboard', [
            'denominacoes' => $denominacoes,
            'congregacoes' => $congregacoes,
            'dominios' => $dominios,
            'membersByDenomination' => $membersByDenomination,
            'stats' => $stats,
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
?>
