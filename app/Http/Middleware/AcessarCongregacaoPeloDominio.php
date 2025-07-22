<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Dominio;

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
        if ($host === 'kleros.local') {
            app()->instance('modo_admin', true);
            return $next($request);
        }

        // Verifica se o host é um domínio válido
        $dominio = Dominio::with('congregacao.config')->where('dominio', $host)
            ->where('ativo', true)
            ->first();

        if (!$dominio) {
            abort(404, 'Congregação não encontrada para este domínio.');
        }

        app()->instance('congregacao', $dominio->congregacao);
        app()->instance('modo_admin', false);

        return $next($request);
    }
}
