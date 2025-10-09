@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | Célula ' . $celula->identificacao)

@section('content')

<div class="container">
    <h1>Célula: {{ $celula->identificacao }}</h1>

    <div class="info">
        <h3>Integrantes</h3>

        <form action="{{ route('celulas.integrantes.adicionar') }}" method="post">
            @csrf
            <div class="search-panel">
                <div class="search-panel-item">
                    <label>Adicionar membros: </label>
                    <select name="membro" class="select2" data-placeholder="Selecione um membro" data-search-placeholder="Pesquise por membros">
                        <option value="">Selecione um membro</option>
                        @foreach ($membros as $item)
                            <option value="{{ $item->id }}" @selected(old('membro') == $item->id)>{{ $item->nome }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="celula" value="{{ $celula->id }}">
                </div>
                <div class="search-panel-item">
                    <button type="submit" id="btn_filtrar"><i class="bi bi-plus-circle"></i> Incluir</button>
                    <button type="button" id="btn_filtrar"><i class="bi bi-printer"></i> Imprimir</button>
                    <a href="/cadastros#celulas"><button type="button" id="btn_filtrar"><i class="bi bi-arrow-return-left"></i> Voltar</button></a>
                </div>
            </div>
        </form>

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
            </div><!--list-title-->

            @forelse ($integrantes as $participante)
                <div class="list-item">
                    <div class="item item-1">
                        <p>{{ $participante->nome }}</p>
                    </div>
                    <div class="item item-1">
                        <p>{{ $participante->telefone }}</p>
                    </div>
                    <div class="item item-2">
                        <p>
                            {{ $participante->endereco }},
                            {{ $participante->numero }} -
                            {{ $participante->bairro }}
                        </p>
                    </div>
                    <div class="item item-1">
                        <p>{{ optional($participante->ministerio)->titulo }}</p>
                    </div>
                </div><!--list-item-->
            @empty
                <div class="card">
                    <p><i class="bi bi-exclamation-triangle"></i> Nenhum integrante cadastrado para esta célula.</p>
                </div>
            @endforelse

            @if($integrantes->total() > $integrantes->perPage())
                <div class="pagination">
                    {{ $integrantes->links('pagination::default') }}
                </div>
            @endif
        </div>

        <div class="celula-detalhes mg-top-20">
            <div class="card">
                <p><strong>Líder:</strong> {{ optional($celula->lider)->nome ?? 'Não informado' }}</p>
                <p><strong>Co-líder:</strong> {{ optional($celula->colider)->nome ?? 'Não informado' }}</p>
                <p><strong>Anfitrião:</strong> {{ optional($celula->anfitriao)->nome ?? 'Não informado' }}</p>
                <p>
                    <strong>Encontro:</strong>
                    {{ $celula->dia_encontro ? diaSemana($celula->dia_encontro) : 'Dia não informado' }}
                    às {{ $celula->hora_encontro ? \Illuminate\Support\Carbon::parse($celula->hora_encontro)->format('H:i') : 'Horário não informado' }}
                </p>
                <button type="button" class="btn mg-top-10" onclick="abrirJanelaModal('{{ route('celulas.form_editar', $celula->id) }}')">
                    <i class="bi bi-pencil-square"></i> Editar célula
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
