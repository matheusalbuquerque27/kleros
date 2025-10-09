@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')
@php
    use Illuminate\Support\Carbon;

    $visitors = trans('visitors');
    $common = $visitors['common'];
    $history = $visitors['historico'];
    $tooltipCopied = $common['tooltip']['copied'];

    $formatDate = function ($value) {
        return $value ? Carbon::parse($value)->format('d/m/Y') : '-';
    };
@endphp

<div class="container">
    <div class="nao-imprimir">
        <h1>{{ $history['title'] }}</h1>
        <div class="info">
            <h3>{{ $history['filter']['heading'] }}</h3>
            <div class="search-panel">
                <div class="search-panel-item">
                    <label for="nome">{{ $history['filter']['name_label'] }}:</label>
                    <input type="text" id="nome" placeholder="{{ $common['placeholders']['search_name'] }}">
                </div>
                <div class="search-panel-item">
                    <label for="data_visita">{{ $history['filter']['date_label'] }}:</label>
                    <input type="date" id="data_visita">
                </div>
                <div class="search-panel-item">
                    <button id="btn_filtrar" type="button"><i class="bi bi-search"></i> {{ $common['buttons']['search'] }}</button>
                    <button id="btn_exportar_visitantes" type="button" data-export-url="{{ route('visitantes.export') }}"><i class="bi bi-file-arrow-up"></i> {{ $common['buttons']['export'] }}</button>
                    <button class="options-menu__trigger" type="button" data-options-target="visitantesHistoricoOptions"><i class="bi bi-three-dots-vertical"></i> {{ $common['buttons']['options'] }}</button>
                </div>
            </div>
            <div class="options-menu" id="visitantesHistoricoOptions" hidden>
                <button type="button" class="btn" data-action="print"><i class="bi bi-printer"></i> {{ $common['buttons']['print'] }}</button>
                <button type="button" class="btn" data-action="back"><i class="bi bi-arrow-return-left"></i> {{ $common['buttons']['back'] }}</button>
            </div>
        </div>
    </div>

    <div class="list">
        <div class="list-title">
            <div class="item-1">
                <b>{{ $history['table']['name'] }}</b>
            </div>
            <div class="item-1">
                <b>{{ $history['table']['date'] }}</b>
            </div>
            <div class="item-1">
                <b>{{ $history['table']['phone'] }}</b>
            </div>
            <div class="item-1">
                <b>{{ $history['table']['status'] }}</b>
            </div>
        </div>
        <div id="content">
            @forelse ($visitantes as $item)
                <a href="{{ route('visitantes.exibir', $item->id) }}">
                    <div class="list-item">
                        <div class="item item-1">
                            <p><i class="bi bi-person-raised-hand"></i> {{ $item->nome }}</p>
                        </div>
                        <div class="item item-1">
                            <p>{{ $formatDate($item->data_visita) }}</p>
                        </div>
                        <div class="item item-1">
                            <p>
                                {{ $item->telefone }}
                                <span class="copy-helper" data-phone="{{ $item->telefone }}">
                                    <i class="bi bi-copy"></i>
                                    <span class="tooltip-copiar">{{ $tooltipCopied }}</span>
                                </span>
                            </p>
                        </div>
                        <div class="item item-1">
                            <p>{{ optional($item->sit_visitante)->titulo ?? $common['statuses']['not_informed'] }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="card">
                    <p><i class="bi bi-exclamation-triangle"></i> {{ $history['empty'] }}</p>
                </div>
            @endforelse
        </div>
        @if ($visitantes->total() > 10)
            <div class="pagination">
                {{ $visitantes->links('pagination::default') }}
            </div>
        @endif
    </div>
</div>

<style>
.tooltip-copiar {
    position: absolute;
    top: -25px;
    left: 0;
    background: #333;
    color: #fff;
    font-size: 0.8em;
    padding: 3px 6px;
    border-radius: 4px;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
}
.tooltip-copiar.show {
    opacity: 1;
}
.copy-helper {
    cursor: pointer;
    position: relative;
    padding-left: .2em;
}
</style>

@push('scripts')
<script>
    (function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const searchEmpty = @json($visitors['search']['empty']);
        const tooltipText = @json($tooltipCopied);
        const historyEmpty = @json($history['empty']);

        const nomeInput = document.getElementById('nome');
        const dataInput = document.getElementById('data_visita');
        const contentTarget = document.getElementById('content');

        function renderEmpty(message) {
            if (!contentTarget) return;
            contentTarget.innerHTML = `<div class="card"><p><i class="bi bi-exclamation-triangle"></i> ${message}</p></div>`;
        }

        function pesquisarVisitantes() {
            if (!csrfToken) {
                return;
            }
            const nome = nomeInput?.value ?? '';
            const data_visita = dataInput?.value ?? '';

            fetch('{{ route('visitantes.search') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ nome, data_visita }),
            })
                .then(response => response.json())
                .then(({ view }) => {
                    if (view && contentTarget) {
                        contentTarget.innerHTML = view;
                        attachCopyHandlers();
                    } else {
                        renderEmpty(searchEmpty);
                    }
                })
                .catch(() => renderEmpty(searchEmpty));
        }

        function attachCopyHandlers() {
            document.querySelectorAll('.copy-helper').forEach((element) => {
                element.addEventListener('click', function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    const phone = element.dataset.phone || '';
                    if (!phone) return;

                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(phone).then(() => showTooltip(element));
                    } else {
                        const textarea = document.createElement('textarea');
                        textarea.value = phone;
                        textarea.style.position = 'fixed';
                        textarea.style.opacity = '0';
                        document.body.appendChild(textarea);
                        textarea.select();
                        try {
                            document.execCommand('copy');
                            showTooltip(element);
                        } finally {
                            document.body.removeChild(textarea);
                        }
                    }
                });
            });
        }

        function showTooltip(element) {
            const tooltip = element.querySelector('.tooltip-copiar');
            if (!tooltip) return;
            tooltip.textContent = tooltipText;
            tooltip.classList.add('show');
            setTimeout(() => tooltip.classList.remove('show'), 1500);
        }

        document.getElementById('btn_filtrar')?.addEventListener('click', function (event) {
            event.preventDefault();
            pesquisarVisitantes();
        });

        document.getElementById('btn_exportar_visitantes')?.addEventListener('click', function (event) {
            event.preventDefault();
            const url = this.dataset.exportUrl;
            const params = new URLSearchParams();
            if (nomeInput?.value) params.append('nome', nomeInput.value);
            if (dataInput?.value) params.append('data_visita', dataInput.value);
            window.location.href = params.toString() ? `${url}?${params.toString()}` : url;
        });

        if (nomeInput) {
            nomeInput.addEventListener('keydown', pesquisarVisitantes);
        }

        attachCopyHandlers();
    })();
</script>
@endpush
@endsection
