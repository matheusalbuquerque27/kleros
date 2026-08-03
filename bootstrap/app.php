<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Em produção a aplicação roda atrás do nginx do host (proxy_pass para o
        // NodePort do k3s). Sem isso o Laravel enxerga http e gera URLs erradas,
        // além de quebrar o SESSION_SECURE_COOKIE.
        $middleware->trustProxies(
            at: '*',
            headers: Request::HEADER_X_FORWARDED_FOR
                | Request::HEADER_X_FORWARDED_HOST
                | Request::HEADER_X_FORWARDED_PORT
                | Request::HEADER_X_FORWARDED_PROTO
        );

        $middleware->alias([
            'dominio' => \App\Http\Middleware\AcessarCongregacaoPeloDominio::class,
            'check.session' => \App\Http\Middleware\CheckSession::class,
            'member.activity' => \App\Http\Middleware\LogMemberActivity::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'gestor' => \App\Http\Middleware\CheckGestorRole::class,
            'setlocale' => \App\Http\Middleware\SetLocale::class,
            'congregacao' => \App\Http\Middleware\IdentifyCongregacao::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
