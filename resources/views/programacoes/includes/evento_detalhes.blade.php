@php
    use Illuminate\Support\Carbon;

    $inicio = $evento->data_inicio ? Carbon::parse($evento->data_inicio) : null;
    $fim = $evento->data_encerramento ? Carbon::parse($evento->data_encerramento) : null;
@endphp

<div class="modal-header">
    <h1 class="modal-title"><i class="bi bi-calendar-event"></i> {{ $evento->titulo }}</h1>
</div>

<div class="info">
    <div class="card">
        <dl class="modal-details">
            @if ($inicio)
                <div>
                    <dt><i class="bi bi-stopwatch"></i> Início</dt>
                    <dd>{{ $inicio->translatedFormat('d \\d\\e F \\à\\s H\\hi') }}</dd>
                </div>
            @endif

            @if ($fim && $fim->greaterThan($inicio))
                <div>
                    <dt><i class="bi bi-flag"></i> Encerramento</dt>
                    <dd>{{ $fim->translatedFormat('d \\d\\e F \\à\\s H\\hi') }}</dd>
                </div>
            @endif

            @if (!empty($evento->local))
                <div>
                    <dt><i class="bi bi-geo-alt"></i> Local</dt>
                    <dd>{{ $evento->local }}</dd>
                </div>
            @endif

            @if (!empty($evento->agrupamento_id) && optional($evento->grupo)->nome)
                <div>
                    <dt><i class="bi bi-people"></i> Grupo responsável</dt>
                    <dd>{{ $evento->grupo->nome }}</dd>
                </div>
            @endif

            <div>
                <dt><i class="bi bi-ticket-perforated"></i> Acesso</dt>
                <dd>{{ $evento->requer_inscricao ? 'Necessita inscrição' : 'Aberto ao público' }}</dd>
            </div>
        </dl>
    </div>

    @if (!empty($evento->descricao))
        <div class="card">
            <h2 class="modal-section-title"><i class="bi bi-card-text"></i> Descrição</h2>
            <p class="modal-description">{{ $evento->descricao }}</p>
        </div>
    @endif

    <div class="modal-actions">
        <button type="button" class="btn" onclick="fecharJanelaModal()">
            <i class="bi bi-x-circle"></i> Fechar
        </button>
    </div>
</div>

<style>
    .modal-header {
        margin-bottom: 16px;
    }
    .modal-title {
        font-size: 1.6rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .modal-title i {
        color: var(--terciary-color);
    }
    .modal-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin: 0;
    }
    .modal-details dt {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        color: var(--secondary-color);
        margin-bottom: 6px;
    }
    .modal-details dd {
        margin: 0;
        font-size: 0.95rem;
        color: var(--text-color);
    }
    .modal-section-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 1rem;
        margin-bottom: 10px;
    }
    .modal-description {
        margin: 0;
        font-size: 0.95rem;
        line-height: 1.6;
        color: var(--text-color);
    }
    .modal-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
    }
</style>
