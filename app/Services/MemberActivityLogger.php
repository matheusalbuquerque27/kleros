<?php

namespace App\Services;

use App\Models\MemberActivityLog;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class MemberActivityLogger
{
    /**
     * Registra uma atividade para o membro autenticado.
     */
    public function log(string $action, array $context = []): MemberActivityLog
    {
        /** @var Request|null $request */
        $request = request();
        $user = $this->resolveUser();

        $payload = $context['payload'] ?? [];

        if ($request instanceof Request && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            $payload = $this->mergeRequestPayload($request, $payload);
        }

        $subject = $context['subject'] ?? null;

        return MemberActivityLog::create([
            'user_id' => $user?->id,
            'membro_id' => $user?->membro_id,
            'action' => $action,
            'route' => $request?->route()?->getName(),
            'method' => $request?->getMethod(),
            'url' => $request?->fullUrl() ?? ($context['url'] ?? ''),
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
            'payload' => ! empty($payload) ? $payload : null,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),
            'logged_at' => now(),
        ]);
    }

    protected function resolveUser(): ?Authenticatable
    {
        try {
            return Auth::user();
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function mergeRequestPayload(Request $request, array $payload): array
    {
        if (! $request->isMethodSafe()) {
            $requestData = Arr::except(
                $request->all(),
                ['password', 'password_confirmation', '_token']
            );

            if (! empty($requestData)) {
                $payload = array_merge(['request' => $requestData], $payload);
            }
        }

        return $payload;
    }
}
