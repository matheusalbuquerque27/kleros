@php
    $members = trans('members');
    $common = $members['common'];
@endphp

@forelse ($membros as $item)
    <a href="{{ url('/membros/exibir/' . $item->id) }}" class="content">
        <div class="list-item">
            <div class="item item-2">
                <p style="display:flex; align-items: center; gap:.5em">
                    <img src="{{ $item->foto ? asset('storage/' . $item->foto) : asset('storage/images/newuser.png') }}" class="avatar" alt="Avatar">
                    {{ $item->nome }}
                </p>
            </div>
            <div class="item item-1">
                <p>{{ $item->telefone }}</p>
            </div>
            <div class="item item-2">
                <p>{{ $item->endereco }}, {{ $item->numero }} - {{ $item->bairro }}</p>
            </div>
            <div class="item item-1">
                <p>{{ optional($item->ministerio)->titulo ?? $common['statuses']['not_informed'] }}</p>
            </div>
        </div>
    </a>
@empty
    <div class="card">
        <p>{{ $members['search']['empty'] }}</p>
    </div>
@endforelse
