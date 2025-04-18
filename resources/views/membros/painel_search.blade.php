@foreach ($membros as $item)
    <a href="/membros/exibir/{{$item->id}} class="content">
    <div class="list-item">
        
        <div class="item item-1">
            <p>{{$item->nome}}</p>
        </div>
        <div class="item item-1">
            <p>{{$item->telefone}}</p>
        </div>
        <div class="item item-2">
            <p>{{$item->endereco}}, {{$item->numero}} - {{$item->bairro}}</p>
        </div>
        <div class="item item-1">
            <p>{{$item->ministerio->titulo}}</p>
        </div>
        
    </div><!--list-item-->
    </a>
@endforeach   