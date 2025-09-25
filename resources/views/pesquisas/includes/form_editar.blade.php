@php
    $activeTab = session('tab', request('tab', 'config'));
@endphp
<h1>Editar Pesquisa</h1>
<div class="info">
    <div class="tabs">
        <ul class="tab-menu">
            <li class="{{ $activeTab === 'config' ? 'active' : '' }}" data-tab="pesquisa-config"><i class="bi bi-gear"></i> Configurações</li>
            <li class="{{ $activeTab === 'perguntas' ? 'active' : '' }}" data-tab="pesquisa-perguntas"><i class="bi bi-list-check"></i> Perguntas</li>
        </ul>

        <div class="tab-content card">
            <div id="pesquisa-config" class="tab-pane form-control {{ $activeTab === 'config' ? 'active' : '' }}">
                <form action="{{ route('pesquisas.update', $pesquisa->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-item">
                        <label for="titulo">Título</label>
                        <input type="text" name="titulo" id="titulo" required value="{{ old('titulo', $pesquisa->titulo) }}">
                    </div>
                    <div class="form-item">
                        <label for="descricao">Descrição</label>
                        <textarea name="descricao" id="descricao" rows="4">{{ old('descricao', $pesquisa->descricao) }}</textarea>
                    </div>
                    <div class="form-item">
                        <label for="criada_por">Responsável</label>
                        <select name="criada_por" id="criada_por" required>
                            <option value="">Selecione um membro</option>
                            @foreach($membros as $membro)
                                <option value="{{ $membro->id }}" @selected(old('criada_por', $pesquisa->criada_por) == $membro->id)>{{ $membro->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-item">
                        <label for="data_inicio">Data de início</label>
                        <input type="date" name="data_inicio" id="data_inicio" value="{{ old('data_inicio', optional($pesquisa->data_inicio)->format('Y-m-d')) }}">
                    </div>
                    <div class="form-item">
                        <label for="data_fim">Data de encerramento</label>
                        <input type="date" name="data_fim" id="data_fim" value="{{ old('data_fim', optional($pesquisa->data_fim)->format('Y-m-d')) }}">
                    </div>
                    <div class="form-options">
                        <button type="submit" class="btn"><i class="bi bi-check-circle"></i> Salvar alterações</button>
                        <button type="button" class="btn danger" onclick="handleSubmit(event, document.getElementById('delete-pesquisa-{{ $pesquisa->id }}'), 'Deseja realmente excluir esta pesquisa?')"><i class="bi bi-trash"></i> Excluir</button>
                        <button type="button" class="btn" onclick="fecharJanelaModal()"><i class="bi bi-x-circle"></i> Cancelar</button>
                    </div>
                </form>
            </div>

            <div id="pesquisa-perguntas" class="tab-pane {{ $activeTab === 'perguntas' ? 'active' : '' }}">
                <div class="tab-pane-content">
                    <section class="pergunta-card">
                        <h4>Nova pergunta</h4>
                        <form action="{{ route('pesquisas.perguntas.store', $pesquisa->id) }}" method="POST">
                            @csrf
                            <div class="form-control">
                                <div class="form-item">
                                    <label for="texto-novo">Enunciado</label>
                                    <textarea name="texto" id="texto-novo" rows="3" required>{{ old('texto') }}</textarea>
                                    @error('texto')
                                        <small class="hint text-error">{{ $message }}</small>
                                    @enderror
                                </div>
                                @php
                                    $tipoNovo = old('tipo', 'texto');
                                @endphp
                                <div class="form-item">
                                    <label for="tipo-novo">Tipo de resposta</label>
                                    <select name="tipo" id="tipo-novo" data-toggle-options="#options-novo">
                                        <option value="texto" @selected($tipoNovo === 'texto')>Texto livre</option>
                                        <option value="radio" @selected($tipoNovo === 'radio')>Escolha única</option>
                                        <option value="checkbox" @selected($tipoNovo === 'checkbox')>Múltipla escolha</option>
                                    </select>
                                </div>
                                <div class="form-item options-box" id="options-novo" style="display: {{ in_array($tipoNovo, ['radio','checkbox']) ? 'block' : 'none' }};">
                                    <label for="options">Opções (uma por linha) <br> <small class="">As respostas serão criadas conforme as opções listadas.</small></label>
                                    <textarea name="options" id="options">{{ old('options') }}</textarea>
                                    @error('options')
                                        <small class="hint text-error">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-options">
                                    <button type="submit" class="btn"><i class="bi bi-plus-circle"></i> Adicionar pergunta</button>
                                </div>
                            </div>
                        </form>
                    </section>

                    <section class="pergunta-list">
                        <h4>Perguntas cadastradas</h4>
                        @php
                            $perguntas = $pesquisa->perguntas;
                            $perPage = 5;
                            $currentPage = max(1, (int) request('pergunta_page', 1));
                            $totalPerguntas = $perguntas->count();
                            $totalPages = max(1, (int) ceil($totalPerguntas / $perPage));
                            if ($currentPage > $totalPages) {
                                $currentPage = $totalPages;
                            }
                            $slice = $perguntas->slice(($currentPage - 1) * $perPage, $perPage)->values();
                        @endphp

                        @forelse($slice as $pergunta)
                            @php
                                $isCurrent = old('pergunta_id') == $pergunta->id;
                                $textoAnterior = $isCurrent ? old('texto') : $pergunta->texto;
                                $tipoAnterior = $isCurrent ? old('tipo') : $pergunta->tipo;
                                $optionsAnterior = $isCurrent ? old('options') : $pergunta->opcoes->pluck('texto')->implode("\n");
                                $tipoLabels = [
                                    'texto' => 'Texto livre',
                                    'radio' => 'Escolha única',
                                    'checkbox' => 'Múltipla escolha',
                                ];
                                $tipoDisplay = $tipoLabels[$tipoAnterior] ?? ucfirst($tipoAnterior);
                            @endphp
                            @php
                                $bodyId = 'pergunta-body-' . $pergunta->id;
                            @endphp
                            <div class="pergunta-card pergunta-accordion {{ $isCurrent ? 'open' : '' }}" data-accordion data-open="{{ $isCurrent ? 'true' : 'false' }}">
                                <button type="button" class="pergunta-toggle" data-accordion-toggle aria-expanded="{{ $isCurrent ? 'true' : 'false' }}" aria-controls="{{ $bodyId }}">
                                    <div>
                                        <span class="pergunta-title">{{ $pergunta->texto }}</span>
                                        <small class="pergunta-meta">
                                            <i class="bi bi-chat-left-dots"></i> {{ $tipoDisplay }}
                                            @if(in_array($tipoAnterior, ['radio','checkbox']))
                                                <span class="divider">•</span> {{ $pergunta->opcoes->count() }} Opções
                                            @endif
                                        </small>
                                    </div>
                                    <i class="bi bi-chevron-down"></i>
                                </button>
                                <div class="pergunta-body" id="{{ $bodyId }}" data-accordion-body>
                                    <form action="{{ route('pesquisas.perguntas.update', [$pesquisa->id, $pergunta->id]) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="pergunta_id" value="{{ $pergunta->id }}">
                                        <div class="form-control">
                                            <div class="form-item">
                                                <label for="texto-{{ $pergunta->id }}">Enunciado</label>
                                                <textarea name="texto" id="texto-{{ $pergunta->id }}" rows="3" required>{{ $textoAnterior }}</textarea>
                                                @if($isCurrent)
                                                    @error('texto')
                                                        <small class="hint text-error">{{ $message }}</small>
                                                    @enderror
                                                @endif
                                            </div>
                                            <div class="form-item">
                                                <label for="tipo-{{ $pergunta->id }}">Tipo de resposta</label>
                                                <select name="tipo" id="tipo-{{ $pergunta->id }}" data-toggle-options="#options-{{ $pergunta->id }}">
                                                    <option value="texto" @selected($tipoAnterior === 'texto')>Texto livre</option>
                                                    <option value="radio" @selected($tipoAnterior === 'radio')>Escolha única</option>
                                                    <option value="checkbox" @selected($tipoAnterior === 'checkbox')>Múltipla escolha</option>
                                                </select>
                                            </div>
                                            <div class="form-item options-box" id="options-{{ $pergunta->id }}" style="display: {{ in_array($tipoAnterior, ['radio','checkbox']) ? 'block' : 'none' }};">
                                                <label for="options">Opções (uma por linha) <br> <small class="">As respostas serão criadas conforme as opções listadas.</small></label>
                                                <textarea name="options" id="options-{{ $pergunta->id }}-textarea">{{ $optionsAnterior }}</textarea>
                                                @if($isCurrent)
                                                    @error('options')
                                                        <small class="hint text-error">{{ $message }}</small>
                                                    @enderror
                                                @endif
                                            </div>
                                            <div class="form-options">
                                                <button type="submit" class="btn"><i class="bi bi-floppy"></i> Salvar pergunta</button>
                                                <button type="button" class="btn danger" onclick="handleSubmit(event, document.getElementById('delete-pergunta-{{ $pergunta->id }}'), 'Deseja realmente excluir esta pergunta?')"><i class="bi bi-trash"></i> Excluir</button>
                                            </div>
                                        </div>
                                    </form>
                                    <form id="delete-pergunta-{{ $pergunta->id }}" action="{{ route('pesquisas.perguntas.destroy', [$pesquisa->id, $pergunta->id]) }}" method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="card">
                                <p><i class="bi bi-info-circle"></i> Ainda não há perguntas cadastradas para esta pesquisa.</p>
                            </div>
                        @endforelse

                        @if($totalPages > 1)
                            <div class="pagination">
                                @for($page = 1; $page <= $totalPages; $page++)
                                    <button type="button" class="page-btn {{ $page === $currentPage ? 'active' : '' }}" onclick="abrirJanelaModal('{{ route('pesquisas.form_editar', ['id' => $pesquisa->id, 'pergunta_page' => $page, 'tab' => 'perguntas']) }}')">{{ $page }}</button>
                                @endfor
                            </div>
                        @endif
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="delete-pesquisa-{{ $pesquisa->id }}" action="{{ route('pesquisas.destroy', $pesquisa->id) }}" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<style>
    .tabs {
        width: 100%;
    }
    .tab-menu {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0 0 1rem 0;
        border-bottom: 2px solid var(--secondary-color);
        gap: .25rem;
    }
    .tab-menu li {
        display: flex;
        align-items: center;
        gap: .35rem;
        cursor: pointer;
        background: rgba(15, 23, 42, .08);
        color: var(--secondary-color);
        padding: .5rem 1rem;
        border-radius: .75rem .75rem 0 0;
        font-weight: 500;
        transition: all .2s ease;
    }
    .tab-menu li i {
        font-size: 1rem;
    }
    .tab-menu li:hover,
    .tab-menu li.active {
        background: var(--secondary-color);
        color: var(--secondary-contrast);
    }
    .tab-content.card {
        border-radius: 0 .75rem .75rem .75rem;
    }
    .tab-pane {
        display: none;
        animation: fadeIn .3s ease-in-out;
    }
    .tab-pane.active {
        display: block;
    }
    .tab-pane-content {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    .pergunta-card {
        border: 1px solid rgba(15, 23, 42, .1);
        border-radius: 10px;
        padding: 1rem;
        background: #fff;
        box-shadow: 0 4px 12px rgba(15, 23, 42, .05);
    }
    .pergunta-accordion {
        padding: 0;
        overflow: hidden;
    }
    .pergunta-toggle {
        width: 100%;
        background: none;
        border: none;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 600;
        color: var(--secondary-color);
        transition: background .2s ease, color .2s ease;
    }
    .pergunta-toggle:hover,
    .pergunta-accordion.open .pergunta-toggle {
        background: rgba(15, 23, 42, .06);
    }
    .pergunta-toggle i {
        transition: transform .25s ease;
    }
    .pergunta-accordion.open .pergunta-toggle i {
        transform: rotate(180deg);
    }
    .pergunta-title {
        display: block;
        margin-bottom: .25rem;
    }
    .pergunta-meta {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        font-size: .85rem;
        color: rgba(15, 23, 42, .6);
    }
    .pergunta-meta .divider {
        opacity: .65;
    }
    .pergunta-body {
        display: none;
        padding: 0 1.25rem 1.25rem;
        border-top: 1px solid rgba(15, 23, 42, .08);
        animation: fadeIn .25s ease;
    }
    .pergunta-accordion.open .pergunta-body {
        display: block;
    }
    .pergunta-card h4,
    .pergunta-card h5 {
        margin-top: 0;
    }
    .pergunta-card .form-control {
        gap: 1rem;
    }
    .options-box textarea {
        min-height: 120px;
        resize: vertical;
    }
    .hint {
        font-size: .85rem;
        color: rgba(30,41,59,.7);
    }
    .hint.text-error {
        color: #dc2626;
    }
    .pergunta-card .form-options {
        gap: .5rem;
        flex-wrap: wrap;
    }
</style>
