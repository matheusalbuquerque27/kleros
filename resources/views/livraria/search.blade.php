@if ($livros == null || empty($livros) || count($livros) == 0) 
    <div class="card">
        <p><i class="bi bi-exclamation-triangle"></i> Nenhum livro foi encontrado.</p>  
    </div>  
@endif
@foreach ($livros as $item)
    <div class="card-livro">
        <img src="{{$item['imagem']}}" alt="{{$item['titulo']}}">
        <div class="conteudo-livro">
            <div>
            <h3 class="titulo-livro">{{$item['titulo']}}</h3>
            <p class="autor-livro">{{$item['autor']}}</p>
            </div>
            <div class="botao-comprar">
            <a href="{{$item['description']}}" target="_blank"><i class="bi bi-search"></i> Ver mais</a>
            </div>
        </div>
    </div>
@endforeach