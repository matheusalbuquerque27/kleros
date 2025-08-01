@extends('layouts.main')

@section('title', 'Agenda de Eventos - AD Jerusalém')

@section('content')

<div class="container">
    <h1>Próximos Eventos</h1>
    <div class="info nao-imprimir">
        <h3>Pesquisar: </h3>
        <div class="search-panel">
            <div class="search-panel-item">
                <label>Nome do evento: </label>
                <select name="" id="titulo">
                    @if ($eventos)
                        @foreach ($eventos as $item)
                        <option value="{{$item->titulo}}">{{$item->titulo}}</option>
                        @endforeach
                    @else
                        <option value="">Nenhum evento cadastrado.</option>
                    @endif
                </select>
            </div>
            <div class="search-panel-item">
                <label>Grupo responsável: </label>
                <select name="" id="grupo">
                    @if ($grupos)
                        @foreach ($grupos as $item)
                        <option value="{{$item->id}}">{{$item->nome}}</option>
                        @endforeach
                    @else
                        <option value="">Nenhum evento cadastrado</option>
                    @endif
                </select>
            </div>
            <div class="form-control">
                <button class="" id="btn_filtrar"><i class="bi bi-search"></i> Procurar</button>
                <button class="imprimir"><i class="bi bi-printer"></i> Imprimir</button>
                <a href="/cadastros#eventos"><button class=""><i class="bi bi-arrow-return-left"></i> Voltar</button></a>
            </div>
        </div>
    </div>
    <div class="list">
        @if ($eventos)
        <div class="list-title">
            <div class="item-1">
                <b>Início do Evento</b>
            </div>
            <div class="item-1">
                <b>Título</b>
            </div>
            <div class="item-1">
                <b>Grupo responsável</b>
            </div>
            <div class="item-15">
                <b>Descrição</b>
            </div>
        </div><!--list-item-->
        <div id="content">
            @foreach ($eventos as $item)
            <div class="list-item">
                <div class="item item-1">
                    <p>{{$item->data_inicio}}</p>
                </div>
                <div class="item item-1">
                    <p>{{$item->titulo}}</p>
                </div>
                <div class="item item-1">
                    <p>{{$item->grupo->nome ?? 'Geral'}}</p>
                </div>
                <div class="item item-15">
                    <p>{{$item->descricao}}</p>
                </div>
            </div><!--list-item-->
            @endforeach       
        </div>                   
        @else
            <div class="card">
                <p><i class="bi bi-exclamation-triangle"></i> Ainda não há eventos previstos para exibição.</p>
            </div>
        @endif
    </div> 
    
</div>

@endsection

@push('scripts')

<script>

    $(document).ready(function(){
        $('#btn_filtrar').click(function(){
            const origin = 'agenda';
            const _token = $('meta[name="csrf-token"]').attr('content');
            let titulo = $('#titulo').val();
            let grupo = $('#grupo').val();

            //$.post(url, data, success, dataType);

            $.post('/eventos/search', { _token, origin, titulo, grupo }, function(response){
                
                var view = response.view

                $('#content').html(view);

            }).catch((err) => {console.log(err)})
            
        });

        $('.imprimir').click(function(event) {
            event.preventDefault();
            window.print();
        });
    })

</script>
    
@endpush