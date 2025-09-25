@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')
<div class="container">
    <div class="nao-imprimir">
        <h1>Painel de Pesquisas</h1>
        <div class="info">
            <h3>Pesquisas Ativas</h3>
            <div class="form-options">
                <button type="button" class="btn" onclick="abrirJanelaModal('{{ route('pesquisas.form_criar') }}')">
                    <i class="bi bi-plus-circle"></i> Nova pesquisa
                </button>
                <button class="btn imprimir" type="button">
                    <i class="bi bi-printer"></i> Imprimir
                </button>
            </div>
        </div>
    </div>

    <div id="list" class="list">
        <div class="list-title">
            <div class="item-2">
                <b>Título</b>
            </div>
            <div class="item-1">
                <b>Período</b>
            </div>
            <div class="item-1">
                <b>Status</b>
            </div>
            <div class="item-1">
                <b>Criada por</b>
            </div>
        </div>
        <div id="content">
            @forelse ($pesquisas as $pesquisa)
                @php
                    $inicio = optional($pesquisa->data_inicio)?->format('d/m/Y');
                    $fim = optional($pesquisa->data_fim)?->format('d/m/Y');
                    $hoje = now();
                    $status = 'Sem período';

                    if ($pesquisa->data_inicio && $pesquisa->data_fim) {
                        if ($hoje->lt($pesquisa->data_inicio)) {
                            $status = 'Agendada';
                        } elseif ($hoje->between($pesquisa->data_inicio, $pesquisa->data_fim)) {
                            $status = 'Em andamento';
                        } else {
                            $status = 'Encerrada';
                        }
                    } elseif ($pesquisa->data_inicio) {
                        $status = $hoje->lt($pesquisa->data_inicio) ? 'Agendada' : 'Em andamento';
                    }
                @endphp
                <div class="list-item" title="{{ $pesquisa->descricao }}" onclick="abrirJanelaModal('{{ route('pesquisas.form_editar', $pesquisa->id) }}')">
                    <div class="item item-2">
                        <p class="with-description">
                            <span class="title"><i class="bi bi-bar-chart"></i> {{ $pesquisa->titulo }}</span>
                        </p>
                    </div>
                    <div class="item item-1">
                        <p>{{ $inicio ? $inicio : '—' }} @if($fim) até {{ $fim }} @endif</p>
                    </div>
                    <div class="item item-1">
                        <p>{{ $status }}</p>
                    </div>
                    <div class="item item-1">
                        <p>{{ optional($pesquisa->criador)->nome ?? 'N/D' }}</p>
                    </div>
                </div>
            @empty
                <div class="card">
                    <p><i class="bi bi-exclamation-triangle"></i> Nenhuma pesquisa cadastrada até o momento.</p>
                </div>
            @endforelse

            @if($pesquisas instanceof \Illuminate\Pagination\Paginator || $pesquisas instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="pagination">
                    {{ $pesquisas->links('pagination::default') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function(){
        $('.imprimir').click(function(event) {
            event.preventDefault();
            window.print();
        });
    });
</script>
@endpush
