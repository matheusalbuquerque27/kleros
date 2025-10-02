<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacaoController extends Controller
{
    /**
     * Fetch user notifications.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = Auth::user();

        // Ensure user is authenticated and has a member profile
        if (!$user || !$user->membro) {
            return response()->json([
                'count' => 0,
                'avisos' => [],
            ]);
        }

        $membro = $user->membro;

        // Fetch unread notices for the member, filtered by the member's congregation
        $avisos = $membro->avisos()
            ->where('congregacao_id', $membro->congregacao_id)
            ->wherePivot('lido', false)
            ->latest()
            ->get();

        return response()->json([
            'count' => $avisos->count(),
            'avisos' => $avisos,
        ]);
    }
}