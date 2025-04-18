@if ($eventos)
    @if ($origin == 'historico')
        @foreach ($eventos as $item)
        <div class="list-item">
            <div class="item item-1">
                <p>{{$item->data_evento}}</p>
            </div>
            <div class="item item-1">
                <p>{{$item->titulo}}</p>
            </div>
            <div class="item item-1">
                <p>{{$item->grupo->nome}}</p>
            </div>
            <div class="item item-15">
                <p>{{$item->descricao}}</p>
            </div>
        </div><!--list-item-->
        @endforeach       
    @elseif($origin == 'agenda')
        @foreach ($eventos as $item)
        <div class="list-item">
            <div class="item item-1">
                <p>{{$item->data_evento}}</p>
            </div>
            <div class="item item-1">
                <p>{{$item->titulo}}</p>
            </div>
            <div class="item item-1">
                <p>{{$item->grupo->nome}}</p>
            </div>
            <div class="item item-15">
                <p>{{$item->descricao}}</p>
            </div>
        </div><!--list-item-->
        @endforeach
    @endif
@else
    <div class="card">
        <p><i class="bi bi-exclamation-triangle"></i> Nenhum evento retornado para esta pesquisa.</p>
    </div>
@endif