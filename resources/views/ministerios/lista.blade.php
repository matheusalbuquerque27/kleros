@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')

<div class="container">
    <h1>Ministério de: {{$ministerio->titulo}}</h1>
    <div class="info">
        <h3>Membros</h3>
        <div class="search-panel">
            <div class="search-panel-item">
                <label for="chave">Buscar: </label>
                <input type="text" id="chave" name="chave" placeholder="Nome">
                <button type="button" id="btnProcurar">
                    <i class="bi bi-search"></i> Procurar
                </button>
            </div>

            <div class="search-panel-item">
                <label for="nome">Outros membros: </label>
                <select name="membro" id="nome" class="select2" data-placeholder="Selecione um membro" data-search-placeholder="Pesquise por membros">
                    <option value="">Selecione um membro</option>
                    @foreach ($naoInclusos ?? [] as $item)
                        <option value="{{ $item->id }}">{{ $item->nome }}</option>
                    @endforeach
                </select>
            </div>

            <form id="formIncluir" action="{{ url('/ministerios/incluir/' . $ministerio->id) }}" method="POST" style="display:none;">
                @csrf
                @method('PUT')
                <input type="hidden" name="membro_id" id="membro_id">
            </form>

            <div class="search-panel-item">
                <button type="button" id="btnIncluir">
                    <i class="bi bi-plus-circle"></i> Incluir
                </button>
                <button type="button">
                    <i class="bi bi-printer"></i> Imprimir
                </button>
                <button type="button" onclick="window.location.href='/cadastros#ministerios'"><i class="bi bi-arrow-return-left"></i> Voltar</button>
            </div>
        </div>
    </div>
    <div class="list">
        <div class="list-title">
            <div class="item-1">
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
        @foreach ($membros as $item)
        <div class="list-item taggable-item">
            <div class="item item-1">
                <p>{{$item->nome}}</p>
            </div>
            <div class="item item-1">
                <p>{{$item->telefone}}</p>
            </div>
            <div class="item item-2">
                <p>{{$item->endereco}}, {{$item->numero}} - {{$item->bairro}}</p>
            </div>
            <div class="item item-1">
                <p>{{$item->ministerio->titulo}}</p>
            </div>
            <div class="taggable-actions">
                <form action="{{ route('ministerios.membros.remover', [$ministerio->id, $item->id]) }}" method="POST" onsubmit="return handleSubmit(event, this, 'Remover {{$item->nome}} deste ministério?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" title="Remover integrante do ministério">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div><!--list-item-->
        @endforeach
        @if($membros->total() > 10)
            <div class="pagination">
                {{ $membros->links('pagination::default') }}
            </div>
        @endif
</div>

@endsection

@push('scripts')
    
<script>
    document.getElementById('btnProcurar').addEventListener('click', function() {
        let chave = document.getElementById('chave').value;
        if (chave) {
            window.location.href = "/ministerios/buscar?chave=" + encodeURIComponent(chave);
        }
    });

    document.getElementById('btnIncluir').addEventListener('click', function() {
        let membroId = document.getElementById('nome').value;
        if (membroId) {
            document.getElementById('membro_id').value = membroId;
            document.getElementById('formIncluir').submit();
        } else {
            alert("Selecione um membro primeiro!");
        }
    });
</script>

@endpush
