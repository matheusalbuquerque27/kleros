@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

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
                        <option value="email">Email</option>
                    </select>
                </div>
                <div class="search-panel-item">
                    <label>Palavra-chave: </label>
                    <input type="text" name="" placeholder="Palavra-chave" id="chave">
                </div>
                <div class="search-panel-item">
                    <button id="btn_filtrar"><i class="bi bi-search"></i> Procurar</button>
                    <a href="/membros/adicionar"><button type="button"><i class="bi bi-plus-circle"></i> Adicionar</button></a>
                    <button id="btn_exportar" type="button" data-export-url="{{ route('membros.export') }}"><i class="bi bi-file-arrow-up"></i> Exportar</button>
                    <button class="options-menu__trigger" type="button" data-options-target="membrosPainelOptions"><i class="bi bi-three-dots-vertical"></i> Opções</button>
                </div>
            </div>
            <div class="options-menu" id="membrosPainelOptions" hidden>
                <button type="button" class="btn" data-action="print"><i class="bi bi-printer"></i> Imprimir</button>
                <button type="button" class="btn" data-action="back"><i class="bi bi-arrow-return-left"></i> Voltar</button>
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
            @if($membros->total() > 10)
                <div class="pagination">
                    {{ $membros->links('pagination::default') }}
                </div>
            @endif
        </div>{{-- content --}} 
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

        $('#btn_exportar').on('click', function(event) {
            event.preventDefault();

            const url = $(this).data('exportUrl');
            const filtro = $('#filtro').val();
            const chave = $('#chave').val();

            const params = new URLSearchParams();
            if (filtro) {
                params.append('filtro', filtro);
            }
            if (chave) {
                params.append('chave', chave);
            }

            const finalUrl = params.toString() ? `${url}?${params.toString()}` : url;
            window.location.href = finalUrl;
        });
    })

</script>
    
@endpush
