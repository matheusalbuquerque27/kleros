<?php 

namespace App\Http\Controllers;

use FeedIo\FeedIo;
use FeedIo\Adapter\Http\Client;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle7\Client as GuzzleAdapter;
use Psr\Log\NullLogger;
use Illuminate\Support\Facades\Cache;
use App\Models\Feed;

class FeedController extends Controller
{
    // Lista todas as notícias
    public function noticias()
    {
        $noticias = Feed::where('categoria', 'noticia')
            ->orderBy('publicado_em', 'desc')
            ->get() // pega todos
            ->groupBy('fonte')
            ->map(function ($grupo) {
                return $grupo->take(15); // pega só 10 de cada
            });

        return view('noticias.painel', compact('noticias'));
    }

    public function podcasts()
    {
        $podcasts = Feed::where('categoria', 'podcast')
        ->orderBy('publicado_em', 'desc')
        ->get() // pega todos
        ->groupBy('fonte')
        ->map(function ($grupo) {
            return $grupo->take(9); // pega só 10 de cada
        });

        return view('podcasts.painel', compact('podcasts'));
    }

    // Destaques (ex: só fonte "guiame")
    public function destaques()
    {
        $destaques = Feed::where('categoria', 'noticia')
            ->where('fonte', 'guiame')
            ->orderBy('publicado_em', 'desc')
            ->limit(9)
            ->get();

        return view('noticias.includes.destaques', compact('destaques'));
    }
    
}
?>