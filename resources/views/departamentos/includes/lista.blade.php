@forelse ($departamentos as $item)
    <div class="list-item" onclick="abrirJanelaModal('{{ route('departamentos.form_editar', $item->id) }}')">
        <div class="item item-1">
            <p><i class="bi bi-intersect"></i> {{ $item->nome }}</p>
        </div>
        <div class="item item-2">
            <p>{{ $item->descricao }}</p>
        </div>
        <div class="item item-1">
            <p>
                {{ optional($item->lider)->nome ?? 'Sem líder' }}
                @if($item->colider)
                    {{ ' / ' . $item->colider->nome }}
                @endif
            </p>
        </div>
    </div>
@empty
    <div class="card">
        <p><i class="bi bi-exclamation-triangle"></i> Ainda não há departamentos para exibição.</p>
    </div>
@endforelse

@if($departamentos instanceof \Illuminate\Contracts\Pagination\Paginator || $departamentos instanceof \Illuminate\Pagination\LengthAwarePaginator)
    @if($departamentos->total() > $departamentos->perPage())
        <div class="pagination">
            {{ $departamentos->links('pagination::default') }}
        </div>
    @endif
@endif
