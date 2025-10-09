<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Dominio;
use Illuminate\Support\Facades\Auth;

class AcessarCongregacaoPeloDominio
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Obtém o host da requisição
        $host = $request->getHost();

        // Se for o domínio principal, não carregar congregação
         if (in_array($host, ['kleros.local', 'admin.local'])) {
            app()->instance('modo_admin', $host === 'admin.local');
            app()->instance('site_publico', $host === 'kleros.local');
            app()->instance('congregacao', null);
            Auth::shouldUse('web'); // garante sessão padrão
            return $next($request);
        }

        // Verifica se o host é um domínio válido
        $dominio = Dominio::with('congregacao.config')->where('dominio', $host)
            ->where('ativo', true)
            ->first();

        if (!$dominio) {
            // 🔁 Redireciona para site principal se o domínio não existir
            return redirect()->away('http://kleros.local');
        }

        app()->instance('congregacao', $dominio->congregacao);
        app()->instance('modo_admin', false);
        app()->instance('site_publico', false);

        return $next($request);
    }
}
