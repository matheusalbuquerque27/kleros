@extends('layouts.main')

@section('title', 'Histórico de Cultos - AD Jerusalém')

@section('content')

<div class="container">
    <h1>Histórico de Cultos</h1>
    <div class="info nao-imprimir">
        <h3>Filtrar por período</h3>
        <div class="search-panel">
            <div class="search-panel-item">
                <label>Data inicial: </label>
                <input type="date" name="" id="data_inicial">
            </div>
            <div class="search-panel-item">
                <label>Data final: </label>
                <input type="date" name="" id="data_final">
            </div>
            <div class="form-control">
                <button class="" id="btn_filtrar"><i class="bi bi-search"></i> Procurar</button>
                <button class="imprimir" type="button"><i class="bi bi-printer"></i> Imprimir</button>
                <a href="/cadastros#cultos"><button class=""><i class="bi bi-arrow-return-left"></i> Voltar</button></a>
            </div>
        </div>
    </div>
    
    <div class="list">
        @if ($cultos)
            <div class="list-title">
                <div class="item-1">
                    <b>Data do Culto</b>
                </div>
                <div class="item-15">
                    <b>Preletor</b>
                </div>
                <div class="item-1">
                    <b>Nº de visitantes</b>
                </div>
                <div class="item-1">
                    <b>Evento associado</b>
                </div>
            </div><!--list-item-->

            <div id="content">
                @foreach ($cultos as $item)
                    <a href="{{route('cultos.complete', $item->id)}}">
                    <div class="list-item">
                        <div class="item item-1">
                            <p>{{$item->data_culto}}</p>
                        </div>
                        <div class="item item-15">
                            <p>{{$item->preletor}}</p>
                        </div>
                        <div class="item item-1">
                            <p>{{$item->quant_visitantes}}</p>
                        </div>
                        <div class="item item-1">
                            <p>@if ($item->evento)
                                    {{$item->evento->titulo}}
                                @else Nenhum @endif
                            </p>
                        </div>
                    </div><!--list-item-->
                    </a>
                @endforeach
            </div>
            
        @else
            <div class="card">
                <p><i class="bi bi-exclamation-triangle"></i> Ainda não há histórico de cultos para exibição.</p>
            </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')

<script>
    $(document).ready(function(){
        $('#btn_filtrar').click(function(){
            const _token = $('meta[name="csrf-token"]').attr('content');
            const origin = 'historico';
            let data_inicial = $('#data_inicial').val();
            let data_final = $('#data_final').val();
            
            $.post('/cultos/search', { _token, origin, data_inicial, data_final }, function(response){
                
                var view = response.view
                
                $('#content').html(view)

            }).catch((err) => {console.log(err)})

        });

        $('.imprimir').click(function(event) {
            event.preventDefault();
            window.print();
        });
    })
</script>

    
@endpush