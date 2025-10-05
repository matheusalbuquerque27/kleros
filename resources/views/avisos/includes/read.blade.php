@php
    $avisoSelecionado = $aviso;
@endphp

<div class="aviso-reader__inner {{ $avisoSelecionado ? '' : 'is-empty' }}" id="avisoReaderContent">
    @if ($avisoSelecionado)
        @php
            $criador = optional(optional($avisoSelecionado->criador)->membro);
            $ministerio = optional($criador->ministerio)->sigla;
            $autor = trim(collect([
                $ministerio,
                $criador && $criador->nome ? primeiroEUltimoNome($criador->nome) : null,
            ])->filter()->implode(' '));
        @endphp
        <header class="aviso-reader__header">
            <div>
                <h2>{{ $avisoSelecionado->titulo }}</h2>
                <div class="aviso-reader__meta">
                    <span>{{ $autor }}</span>
                    <span>&bull;</span>
                    <time datetime="{{ optional($avisoSelecionado->created_at)->toIso8601String() }}">
                        {{ optional($avisoSelecionado->created_at)->format('d/m/Y H:i') }}
                    </time>
                </div>
            </div>
            <span class="aviso-pill aviso-{{ $avisoSelecionado->prioridade }}">{{ ucfirst($avisoSelecionado->prioridade) }}</span>
        </header>
        <article class="aviso-reader__body">
            {!! nl2br(e($avisoSelecionado->mensagem)) !!}
        </article>
    @else
        <div class="aviso-reader__placeholder">
            <i class="bi bi-envelope-open"></i>
            <p>Selecione um aviso para visualizar o conteúdo.</p>
        </div>
    @endif
</div>
