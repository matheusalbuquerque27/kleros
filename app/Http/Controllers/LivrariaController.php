<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class LivrariaController extends Controller
{
    public function index() {

        $livros = $this->livrosGoogle();
        //Para o banner de noticias
        $noticias = Cache::get('noticias_feed') ?? [];
        $destaques = array_slice($noticias['guiame'] ?? [], 0, 9);
        
        return view('livraria.index', ['livros' => $livros, 'destaques' => $destaques]);
    }

    public function livrosJson() {

        $livros = [
            [
                'titulo' => 'O Nome do Vento',
                'autor' => 'Patrick Rothfuss',
                'imagem' => 'https://via.placeholder.com/220x320?text=Livro+1',
                'link' => '#'
            ],
            [
                'titulo' => 'Sapiens',
                'autor' => 'Yuval Noah Harari',
                'imagem' => 'https://via.placeholder.com/220x320?text=Livro+2',
                'link' => '#'
            ],
            [
                'titulo' => '1984',
                'autor' => 'George Orwell',
                'imagem' => 'https://via.placeholder.com/220x320?text=Livro+3',
                'link' => '#'
            ],
        ];

        return $livros;
    }

    public function livrosGoogle($titulo = null, $autor = null)
    {
        $filtro = '"cristianismo" OR "vida com propósito" OR "missões cristãs" OR "teologia cristã" OR "bíblia"';
        
        if($autor != null){
            $filtro .= ' inauthor:"' . $autor . '"';
        }
        if($titulo != null) {
            $filtro .= ' '. $titulo. '"';
        }

        $query = request('q', $filtro);

        $response = Http::get('https://www.googleapis.com/books/v1/volumes', [
            'q' => $query,
            'printType' => 'books',
            'maxResults' => 12
        ]);

        $items = $response->json('items');

        $livros = collect($items)->map(function ($item) {
            $volumeInfo = $item['volumeInfo'] ?? [];
            return [
                'titulo' => $volumeInfo['title'] ?? 'Título desconhecido',
                'autor' => $volumeInfo['authors'][0] ?? 'Autor desconhecido',
                'imagem' => $volumeInfo['imageLinks']['extraLarge'] ??
                    $volumeInfo['imageLinks']['large'] ??
                    $volumeInfo['imageLinks']['medium'] ??
                    $volumeInfo['imageLinks']['small'] ??
                    $volumeInfo['imageLinks']['thumbnail'] 
                    ?? asset('storage/images/book.gif'),
                'link' => $volumeInfo['infoLink'] ?? '#',
                'description' => $volumeInfo['infoLink'] ?? 'Sem descrição',
            ];
        });

        return $livros;
    }

    public function search(Request $request) {

        $filtro = $request->filtro;
        $chave = $request->chave;

        $titulo = ($filtro == "titulo") ?  $chave : null;
        $autor = ($filtro == "autor") ? $chave : null;

        $livros = $this->livrosGoogle($titulo, $autor);

        // Renderiza a view com os resultados
        $view = view('livraria/search', ['livros' => $livros])->render();

        // Retorna a view renderizada como parte da resposta JSON
        return response()->json(['view' => $view]);
    }
}
