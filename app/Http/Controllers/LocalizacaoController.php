<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estado;
use App\Models\Cidade;

class LocalizacaoController extends Controller
{
    public function getEstados($pais_id)
    {
        return response()->json(
            Estado::where('pais_id', $pais_id)->get()
        );
    }

    public function getCidades($estado_id)
    {
        $uf = Estado::where('id', $estado_id)->first()->uf;
        return response()->json(
            Cidade::where('uf', $uf)->get()
        );
    }
}
