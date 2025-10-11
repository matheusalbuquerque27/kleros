@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')

<div class="container">
    <h1>Quadro de Reuniões</h1>
    <div class="info">
        <h3>Agendadas</h3>
        <div class="nao-imprimir">
            <div class="search-panel">
                <div class="search-panel-item">
                    <label>Assunto: </label>
                    <input type="text" id="assunto" placeholder="Assunto">
                </div>
                <div class="search-panel-item">
                    <label>Data inicial: </label>
                    <input type="date" name="" id="data_inicial">
                </div>
                <div class="search-panel-item">
                    <label>Data final: </label>
                    <input type="date" name="" id="data_final">
                </div>
                <div class="search-panel-item">
                    <button class="" id="btn_filtrar"><i class="bi bi-search"></i> Procurar</button>
                    <button class="" onclick="abrirJanelaModal('{{route('reunioes.form_criar')}}')"><i class="bi bi-plus-circle"></i> Adicionar</button>
                    <button class="" onclick="window.history.back()"><i class="bi bi-arrow-return-left"></i> Voltar</button>
                </div>
            </div>
        </div>
        <div class="list">
            <div class="list-title">
                <div class="item-1">
                    <b>Data</b>
                </div>
                <div class="item-1">
                    <b>Horário</b>
                </div>
                <div class="item-15">
                    <b>Descrição</b>
                </div>
                <div class="item-1">
                    <b>Local</b>
                </div>
                <div class="item-1">
                    <b>Ambiente</b>
                </div>
            </div><!--list-item-->
            <div id="content">
                @include('reunioes.includes.lista', ['reunioes' => $reunioes, 'mostrarPaginacao' => true])
            </div><!--content-->
        </div><!--list-->
    </div>
</div>

@endsection

@push('scripts')
<script>
    (function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const endpoint = @json(route('reunioes.search'));

        function coletarFiltros() {
            return {
                assunto: document.getElementById('assunto')?.value || '',
                data_inicial: document.getElementById('data_inicial')?.value || '',
                data_final: document.getElementById('data_final')?.value || '',
            };
        }

        function aplicarResultado(viewHtml) {
            const container = document.getElementById('content');
            if (container) {
                container.innerHTML = viewHtml;
                if (typeof initModalScripts === 'function') {
                    try {
                        initModalScripts(container);
                    } catch (error) {
                        console.error('Falha ao reinicializar scripts do modal após busca.', error);
                    }
                }
                if (typeof initOptionsMenus === 'function') {
                    try {
                        initOptionsMenus(container);
                    } catch (error) {
                        console.error('Falha ao reinicializar menus após busca.', error);
                    }
                }
            }
        }

        function executarBusca() {
            const filtros = coletarFiltros();

            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify(filtros),
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Não foi possível carregar a lista de reuniões.');
                    }
                    return response.json();
                })
                .then((data) => {
                    aplicarResultado(data.view || '');
                })
                .catch((error) => {
                    console.error(error);
                });
        }

        document.getElementById('btn_filtrar')?.addEventListener('click', executarBusca);
        document.getElementById('assunto')?.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                executarBusca();
            }
        });
    })();
</script>
@endpush
