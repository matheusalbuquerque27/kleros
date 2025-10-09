@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')

<div class="container">
    <h1>Grupo: {{$grupo->nome}}</h1>
    <div class="info">
        <h3>Integrantes</h3>
        <form action="/grupos/integrantes" method="post">
            @csrf
            <div class="search-panel">
                <div class="search-panel-item">
                    <label>Adicionar membros: </label>
                    <select name="membro" id="nome" class="select2" data-placeholder="Selecione um membro" data-search-placeholder="Pesquise por membros">
                        <option value="">Selecione um membro</option>
                        @if ($membros != null)
                            @foreach ($membros as $item)
                                <option value="{{$item->id}}" @selected(old('membro') == $item->id)>{{$item->nome}}</option>
                            @endforeach
                        @endif
                    </select>
                    <input type="hidden" name="grupo" value="{{$grupo->id}}">
                </div>
                <div class="search-panel-item">
                    <button type="submit" id="btn_filtrar"><i class="bi bi-plus-circle"></i> Incluir</button>
                    <button type="button" id="btn_filtrar"><i class="bi bi-printer"></i> Imprimir</button>
                    <a href="/cadastros#grupos"><button type="button" id="btn_filtrar"><i class="bi bi-arrow-return-left"></i> Voltar</button></a>
                </div>
            </div>
        </form>
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
        @foreach ($integrantes as $item)
            <div class="list-item taggable-item">
                <div class="item item-1 integrante-info">
                    <p>{{$item->nome}}</p>
                </div>
                <div class="item item-1 integrante-info">
                    <p>{{$item->telefone}}</p>
                </div>
                <div class="item item-2 integrante-info">
                    <p>{{$item->endereco}}, {{$item->numero}} - {{$item->bairro}}</p>
                </div>
                <div class="item item-1 integrante-info">
                    <p>{{$item->ministerio?->titulo}}</p>
                </div>
                <div class="taggable-actions">
                    <form action="{{ route('grupos.integrantes.remover', [$grupo->id, $item->id]) }}" method="POST" onsubmit="return handleSubmit(event, this, 'Remover {{$item->nome}} do grupo?') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="Remover integrante">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div><!--list-item-->
        @endforeach
        @if($integrantes->total() > 10)
            <div class="pagination">
                {{ $integrantes->links('pagination::default') }}
            </div>
        @endif  
    </div>
</div>

@endsection
