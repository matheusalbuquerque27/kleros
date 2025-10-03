@extends('layouts.main')

@section('title', ($congregacao->nome_curto ?? 'Congregação') . ' | ' . $appName)

@section('content')
@php
    use Illuminate\Support\Str;
@endphp
<div class="container">
    <h1>Histórico de Recados</h1>

    <div class="info nao-imprimir">
        <h3>Filtrar</h3>
        <div class="search-panel">
            <div class="search-panel-item">
                <label for="filtro_culto">Culto:</label>
                <select id="filtro_culto" name="culto_id">
                    <option value="">Todos os cultos</option>
                    @foreach ($cultos as $culto)
                        <option value="{{ $culto->id }}" @selected(request('culto_id') == $culto->id)>
                            {{ optional($culto->data_culto)->format('d/m/Y') ?? 'Sem data' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="search-panel-item">
                <label for="filtro_mensagem">Mensagem:</label>
                <input type="text" id="filtro_mensagem" name="mensagem" value="{{ request('mensagem') }}" placeholder="Buscar recado...">
            </div>
            <div class="search-panel-item">
                <button class="" id="btn_filtrar"><i class="bi bi-search"></i> Procurar</button>
                <button class="" id="btn_limpar"><i class="bi bi-eraser"></i> Limpar</button>
            </div>
        </div>
    </div>

    <div class="list">
        <div class="list-title">
            <div class="item-1"><b>Data</b></div>
            <div class="item-1"><b>Data do Culto</b></div>
            <div class="item-2"><b>Mensagem</b></div>
            <div class="item-1"><b>Remetente</b></div>
        </div>
        <div id="content">
            @forelse ($recados as $recado)
                <div class="list-item">
                    <div class="item item-1">
                        <p>{{ optional($recado->data_recado)->format('d/m/Y') ?? '—' }}</p>
                    </div>
                    <div class="item item-1">
                        <p>{{ optional(optional($recado->culto)->data_culto)->format('d/m/Y') ?? '—' }}</p>
                    </div>
                    <div class="item item-2">
                        <p class="mensagem">{!! nl2br(e(Str::limit($recado->mensagem, 250))) !!}</p>
                    </div>
                    <div class="item item-1">
                        <p>{{ optional($recado->membro)->nome ?? 'Visitante' }}</p>
                    </div>
                </div>
            @empty
                <div class="card">
                    <p><i class="bi bi-exclamation-triangle"></i> Nenhum recado encontrado para os filtros selecionados.</p>
                </div>
            @endforelse
        </div>
        @if ($recados->hasPages())
            <div class="pagination">
                {{ $recados->links('pagination::default') }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        function aplicarFiltros() {
            const cultoId = $('#filtro_culto').val();
            const mensagem = $('#filtro_mensagem').val().trim();
            const params = new URLSearchParams();

            if (cultoId) {
                params.set('culto_id', cultoId);
            }

            if (mensagem) {
                params.set('mensagem', mensagem);
            }

            const query = params.toString();
            const destino = query ? `${window.location.pathname}?${query}` : window.location.pathname;
            window.location.href = destino;
        }

        $('#btn_filtrar').on('click', function (event) {
            event.preventDefault();
            aplicarFiltros();
        });

        $('#btn_limpar').on('click', function (event) {
            event.preventDefault();
            window.location.href = window.location.pathname;
        });

        $('#filtro_culto').on('change', aplicarFiltros);

        $('#filtro_mensagem').on('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                aplicarFiltros();
            }
        });
    });
</script>
@endpush
