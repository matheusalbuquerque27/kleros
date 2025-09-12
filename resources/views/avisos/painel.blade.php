@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')

<div class="container">
    <h1>Painel de Avisos</h1>
    <div class="info nao-imprimir">
        <h3>Edição geral</h3>
        <div class="search-panel">
            <div class="search-panel-item">
                <button class="" onclick="abrirJanelaModal('{{ route('avisos.form_criar') }}')"><i class="bi bi-plus-circle"></i> Criar Aviso</button>
                <button class="" onclick="window.history.back()"><i class="bi bi-arrow-return-left"></i> Voltar</button>
            </div>
        </div>
    </div>
    <div class="avisos">
    @foreach ($avisos as $item)
        <div class="aviso-card aviso-{{ $item->prioridade }}">
            <div class="aviso-header">
                <h2>{{ $item->titulo }}</h2>
                <span class="badge {{ $item->prioridade }}">{{ ucfirst($item->prioridade) }}</span>
            </div>
            <p>{{ $item->mensagem }}</p>
            <div class="aviso-footer">
                <span>Enviado por: {{ $item->criador->membro->ministerio->sigla.' '. primeiroEUltimoNome($item->criador->membro->nome) }}</span>
            </div>
        </div>
    @endforeach
    </div>
    @include('noticias.includes.destaques')
</div>

@endsection

@push('scripts')

<script>
    $(document).ready(function(){
        $('#btn_filtrar').click(function(){
            const _token = $('meta[name="csrf-token"]').attr('content');
            const origin = 'agenda';
            let preletor = $('#preletor').val();
            let evento = $('#evento').val();
            
            $.post('/cultos/search', { _token, origin, preletor, evento }, function(response){
                
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