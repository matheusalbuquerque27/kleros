@extends('layouts.main')

@section('title', 'Membros - AD Jerusalém')

@section('content')

<div class="container">
    <div class="nao-imprimir">
    <h1>Painel de Membros</h1>
    <div class="info">
        <h3>Pesquisar</h3>
        <form action="" method="POST">
            @csrf
            <div class="search-panel">
                <div class="search-panel-item">
                    <label>Filtrar por: </label>
                    <select name="" id="filtro">
                        <option value="nome">Nome</option>
                        <option value="telefone">Telefone</option>
                    </select>
                </div>
                <div class="search-panel-item">
                    <label>Nome/Telefone: </label>
                    <input type="text" name="" placeholder="Nome ou telefone" id="chave">
                </div>
                <div>
                    <button id="btn_filtrar"><i class="bi bi-search"></i> Procurar</button>
                    <a href="/membros/adicionar"><button type="button"><i class="bi bi-plus-circle"></i> Novo</button></a>
                    <button class="imprimir" type="button"><i class="bi bi-printer"></i> Imprimir</button>
                </div>
            </div>
        </form>
    </div>
    </div>{{-- nao-imprimir --}}
    
    <div id="list" class="list">
        <div class="list-title">
            <div class="item-2">
                <b>Nome</b>
            </div>
            <div class="item-1">
                <b>Telefone</b>
            </div>
            <div class="item-2">
                <b>Endereço</b>
            </div>
            <div class="item-1">
                <b>Ministério</b>
            </div>
        </div><!--list-item-->
        <div id="content">
            @foreach ($membros as $item)
            <a href="/membros/exibir/{{$item->id}}">
            <div class="list-item">
                <div class="item item-2">
                    <p><img src="{{ $item->foto ? asset('storage/' . $item->foto) : asset('storage/images/newuser.png') }}" class="avatar-icon" alt="Avatar">{{$item->nome}}</p>
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
        </div>{{-- content --}} 
    </div>
    <div class="pagination">
        {{ $membros->links('pagination::default') }}
    </div>
</div>

@endsection

@push('scripts')

<script>

    $(document).ready(function(){
        $('#btn_filtrar').click(function(event){

            event.preventDefault();
            
            const _token = $('meta[name="csrf-token"]').attr('content');
            let filtro = $('#filtro').val();
            let chave = $('#chave').val();

            $.post('/membros/search', { _token, filtro, chave }, function(response){
                
                var view = response.view

                $('#content').html(view);

            }).catch((err) => {console.log(err)})
            
        })

        $('.imprimir').click(function(event) {
            event.preventDefault();
            window.print();
        })
    })

</script>
    
@endpush