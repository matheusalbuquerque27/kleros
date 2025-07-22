<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $denominacoes = \App\Models\Denominacao::all();
        $congregacoes = \App\Models\Congregacao::all();
        $dominios = \App\Models\Dominio::all();

        return view('admin.dashboard', [
            'denominacoes' => $denominacoes,
            'congregacoes' => $congregacoes,
            'dominios' => $dominios
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
?>