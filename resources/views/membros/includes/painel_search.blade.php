@foreach ($membros as $item)
    <a href="/membros/exibir/{{$item->id}}" class="content">
    <div class="list-item">
        <div class="item item-2">
            <p style="display:flex; align-items: center; gap:.5em"><img src="{{ $item->foto ? asset('storage/' . $item->foto) : asset('storage/images/newuser.png') }}" class="avatar" alt="Avatar">{{$item->nome}}</p>
        </div>
        <div class="item item-1">
            <p>{{$item->telefone}}</p>
        </div>
        <div class="item item-2">
            <p>{{$item->endereco}}, {{$item->numero}} - {{$item->bairro}}</p>
        </div>
        <div class="item item-1">
            <p>{{ optional($item->ministerio)->titulo ?? 'Não informado'}}</p>
        </div>
        
    </div><!--list-item-->
    </a>
@endforeach   