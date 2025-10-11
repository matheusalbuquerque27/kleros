@php
    use Illuminate\Support\Carbon;

    $isPaginator = $reunioes instanceof \Illuminate\Contracts\Pagination\Paginator;
    $lista = $isPaginator ? $reunioes : collect($reunioes);
@endphp

@if ($lista->count() > 0)
    @foreach ($lista as $reuniao)
        @php
            $inicio = $reuniao->data_inicio ? Carbon::parse($reuniao->data_inicio) : null;
            $dataFormatada = $inicio ? $inicio->format('d/m/Y') : 'Sem data';
            $horaFormatada = $inicio ? $inicio->format('H:i') : '--:--';
        @endphp
        <div class="list-item" onclick="abrirJanelaModal('{{ route('reunioes.form_editar', $reuniao->id) }}')">
            <div class="item item-1">
                <p><i class="bi bi-people-fill"></i> {{ $dataFormatada }}</p>
            </div>
            <div class="item item-1">
                <p class="tag">{{ $horaFormatada }}</p>
            </div>
            <div class="item item-15">
                <p>{{ $reuniao->descricao ?: $reuniao->assunto }}</p>
            </div>
            <div class="item item-1">
                <p>{{ $reuniao->local ?: 'A definir' }}</p>
            </div>
            <div class="item item-1">
                <p>{{ $reuniao->online ? 'Online' : 'Presencial' }}</p>
            </div>
        </div><!--list-item-->
    @endforeach

    @if (($mostrarPaginacao ?? false) && $isPaginator && $reunioes->hasPages())
        <div class="pagination">
            {{ $reunioes->links('pagination::default') }}
        </div>
    @endif
@else
    <div class="card">
        <p><i class="bi bi-exclamation-triangle"></i> Nenhuma reunião encontrada para os filtros informados.</p>
    </div>
@endif

