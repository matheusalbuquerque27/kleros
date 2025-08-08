@foreach ($arquivos as $item)
<div class="card-arquivo">
    <img src="{{ asset('storage/'.$item->caminho) }}" alt="{{$item->nome}}">
    <div class="conteudo-arquivo">
        <span class="titulo">{{$item->nome}}</span>
        <div class="options">
            <a href="#" class="botao delete-img" id="{{$item->id}}" title="Excluir"><i class="bi bi-trash"></i></a>
        </div>
    </div>
</div>
@endforeach