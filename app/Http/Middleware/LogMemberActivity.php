<?php

namespace App\Http\Middleware;

use App\Services\MemberActivityLogger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogMemberActivity
{
    protected MemberActivityLogger $logger;

    public function __construct(MemberActivityLogger $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (! Auth::check()) {
            return $response;
        }

        $user = Auth::user();

        if (! $user?->membro_id) {
            return $response;
        }

        if ($this->shouldSkip($request)) {
            return $response;
        }

        $route = $request->route();

        $action = $route?->getName()
            ?? sprintf('%s %s', $request->getMethod(), '/' . ltrim($request->path(), '/'));

        $payload = [
            'response_status' => $response->getStatusCode(),
        ];

        if (! empty($route?->parameters())) {
            $payload['route_parameters'] = $route->parameters();
        }

        $this->logger->log($action, ['payload' => $payload]);

        return $response;
    }

    protected function shouldSkip(Request $request): bool
    {
        if (! in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return true;
        }

        if ($request->isMethodSafe() && $request->is('up', 'health', 'debugbar*')) {
            return true;
        }

        if ($request->expectsJson() && $request->ajax() && $request->route()?->getName() === null) {
            return true;
        }

        return false;
    }
}
