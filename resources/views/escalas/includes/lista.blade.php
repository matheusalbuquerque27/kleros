@forelse ($escalas as $escala)
    @php
        $dataEscala = $escala->data_hora?->format('d/m/Y H:i');
        if (! $dataEscala) {
            $dataEscala = optional(optional($escala->culto)->data_culto)?->format('d/m/Y H:i');
        }
        $local = $escala->local ?? optional($escala->culto)->local;
    @endphp
    <div class="list-item clickable" onclick="abrirJanelaModal('{{ route('escalas.form_editar', $escala->id) }}')">
        <div class="item item-1">
            <p><i class="bi bi-diagram-3"></i> {{ optional($escala->tipo)->nome ?? 'Sem tipo' }}</p>
        </div>
        <div class="item item-1">
            <p>{{ $dataEscala ?? 'Data não informada' }}</p>
        </div>
        <div class="item item-2">
            <p>
                {{ $local ?? 'Local não informado' }}
                @if($escala->observacoes)
                    <br><small class="hint">{{ \Illuminate\Support\Str::limit($escala->observacoes, 120) }}</small>
                @endif
            </p>
        </div>
    </div>
@empty
    <div class="card">
        <p><i class="bi bi-exclamation-triangle"></i> Nenhuma escala encontrada para os filtros selecionados.</p>
    </div>
@endforelse

@if($escalas instanceof \Illuminate\Contracts\Pagination\Paginator || $escalas instanceof \Illuminate\Pagination\LengthAwarePaginator)
    @if($escalas->total() > $escalas->perPage())
        <div class="pagination">
            {{ $escalas->withQueryString()->links('pagination::default') }}
        </div>
    @endif
@endif
