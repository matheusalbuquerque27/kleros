<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckSession
{
    public function handle($request, Closure $next)
    {
        // Se já está na tela de login ou recuperação de senha, não redireciona
        if ($request->is('login') || $request->is('recuperar-senha*')) {
            return $next($request);
        }

        // Se não autenticado, manda pro login
        if (!Auth::check()) {
            return redirect('/login')
                ->with('error', 'Sua sessão expirou, faça login novamente.');
        }

        return $next($request);
    }
}