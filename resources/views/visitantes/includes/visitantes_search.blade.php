@if ($visitantes)
    @foreach ($visitantes as $item)
        <a href="{{route('visitantes.exibir', $item->id)}}"><div class="list-item">
            <div class="item item-1">
                <p>{{$item->data_visita}}</p>
            </div>
            <div class="item item-1">
                <p>{{$item->nome}}</p>
            </div>
            <div class="item item-1">
                <p>{{$item->telefone}}</p>
            </div>
            <div class="item item-1">
                <p>{{$item->sit_visitante->titulo}}</p>
            </div>
        </div></a><!--list-item-->
    @endforeach       
@else
    <div class="card">
        <p><i class="bi bi-exclamation-triangle"></i> Nenhum visitante retornado para esta pesquisa.</p>
    </div>
@endif