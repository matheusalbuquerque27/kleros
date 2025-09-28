<h1>Criar Aviso</h1>
<div class="info">
    <form action="{{ route('avisos.store') }}" method="post">
        @csrf

        <div class="tabs">
            <ul class="tab-menu">
                <li class="active" data-tab="aviso-detalhes"><i class="bi bi-journal-text"></i> Detalhes</li>
                <li data-tab="aviso-destinatarios"><i class="bi bi-people"></i> Destinatários</li>
            </ul>

            <div class="tab-content card">
                <div id="aviso-detalhes" class="tab-pane form-control active">
                    <div class="form-item">
                        <label for="titulo">Título: </label>
                        <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" placeholder="Título do aviso" required>
                    </div>

                    <div class="form-item">
                        <label for="mensagem">Mensagem: </label>
                        <textarea name="mensagem" id="mensagem" cols="30" rows="5" placeholder="Escreva sua mensagem">{{ old('mensagem') }}</textarea>
                    </div>

                    <div class="form-item">
                        <label for="prioridade">Prioridade: </label>
                        <select name="prioridade" id="prioridade">
                            <option value="normal" @selected(old('prioridade', 'normal') === 'normal')>Normal (baixa)</option>
                            <option value="importante" @selected(old('prioridade') === 'importante')>Importante (média)</option>
                            <option value="urgente" @selected(old('prioridade') === 'urgente')>Urgente (alta)</option>
                        </select>
                    </div>

                    <div class="form-item">
                        <label for="data_fim">Exibir até: </label>
                        <input type="date" name="data_fim" id="data_fim" value="{{ old('data_fim') }}">
                    </div>
                </div>

                @php
                    $mostrarSelecionados = old('destinatarios', 1) == 0;
                @endphp
                <div id="aviso-destinatarios" class="tab-pane form-control">
                    <div class="form-item">
                        <label for="destinatarios">Quem recebe? </label>
                        <select name="destinatarios" id="destinatarios">
                            <option value="1" @selected(old('destinatarios', 1) == 1)>Todos</option>
                            <option value="0" @selected(old('destinatarios') == 0)>Destinatários selecionados</option>
                        </select>
                    </div>

                    <div id="selecionados" style="{{ $mostrarSelecionados ? 'margin-top:15px;' : 'display:none; margin-top:15px;' }}">
                        <div class="form-item">
                            <label for="grupos">Grupos:</label>
                            <select name="grupos[]" id="grupos" multiple class="select2" data-placeholder="Selecione os grupos" data-search-placeholder="Pesquise por grupos">
                                <option></option>
                                @foreach($grupos as $grupo)
                                    <option value="{{ $grupo->id }}" @selected(collect(old('grupos', []))->contains($grupo->id))>{{ $grupo->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-item">
                            <label for="membros">Membros:</label>
                            <select name="membros[]" id="membros" multiple class="select2" data-placeholder="Selecione os membros" data-search-placeholder="Pesquise por membros">
                                <option></option>
                                @foreach($membros as $membro)
                                    <option value="{{ $membro->id }}" @selected(collect(old('membros', []))->contains($membro->id))>{{ $membro->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-options center">
                <button class="btn" type="submit"><i class="bi bi-send"></i> Enviar</button>
                <button type="button" class="btn" onclick="if (typeof fecharJanelaModal === 'function') { fecharJanelaModal(); } else { window.history.back(); }"><i class="bi bi-arrow-return-left"></i> Voltar</button>
            </div>
        </div>
    </form>
</div>
