@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')

<div class="container">
    <h1>Histórico de Visitantes</h1>
    <div class="info nao-imprimir">
        <h3>Filtrar por período</h3>
        <div class="search-panel">
            <div class="search-panel-item">
                <label>Nome: </label>
                <input type="text" name="" id="nome" placeholder="Nome do visitante">
            </div>
            <div class="search-panel-item">
                <label>Data: </label>
                <input type="date" name="" id="data_visita">
            </div>
            <div class="search-panel-item">
                <button class="" id="btn_filtrar"><i class="bi bi-search"></i> Procurar</button>
                <button class="imprimir"><i class="bi bi-printer"></i> Imprimir</button>
                <button class="" onclick="window.history.back()"><i class="bi bi-arrow-return-left"></i> Voltar</button>
            </div>
        </div>
    </div>
    
    <div class="list">
        <div class="list-title">
            <div class="item-1">
                <b>Data da visita</b>
            </div>
            <div class="item-1">
                <b>Nome</b>
            </div>
            <div class="item-1">
                <b>Situação</b>
            </div>
            <div class="item-1">
                <b>Situação</b>
            </div>
            <div class="item-1">
                <b>Observações</b>
            </div>
        </div><!--list-item-->

        <div id="content">
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
        </div>
        @if ($visitantes->total() > 10)
            <div class="pagination">
                {{ $visitantes->links('pagination::default') }}
            </div>
        @endif
    </div>
    
    <h3>Últimos Visitantes</h3>

        <div class="form-control">
            <div class="card-container">
                    <div class="card">
                        <p class="card-title">Adalberto</p>
                        <p class="card-date">24/12</p>
                    </div>
                </div>
            <div class="form-item">
                <div class="form-options">
                    <button class="btn" id="btn_cadastrar"><i class="bi bi-plus-circle"></i> Cadastrar</button>
                </div>
            </div>
        </div>
    
</div>

@endsection

@push('scripts')

<script>
    $(document).ready(function(){

        $('#nome').keydown(function(){
            pesquisarVisitantes();
        });

        $('.imprimir').click(function(event) {
            event.preventDefault();
            window.print();
        });
    })
</script>
    
<script>
    function pesquisarVisitantes(){
        const _token = $('meta[name="csrf-token"]').attr('content');
        let data_visita = $('#data_visita').val();
        let nome = $('#nome').val();

        $.post('/visitantes/search', { _token, data_visita, nome }, function(response){
            var view = response.view

            $('#content').html(view)
        }).catch((err) => {console.log(err)})
    };
</script>
    
@endpush