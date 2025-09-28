@php
    $selectedAgrupamentos = (array) old('agrupamentos', []);
    $selectedMembros = (array) old('membros', []);
@endphp

<h1>Nova Reunião</h1>
<div class="info">
    <form action="{{ route('reunioes.store') }}" method="post">
        @csrf

        <div class="tabs">
            <ul class="tab-menu">
                <li class="active" data-tab="reuniao-detalhes"><i class="bi bi-journal-text"></i> Detalhes</li>
                <li data-tab="reuniao-participantes"><i class="bi bi-people"></i> Participantes</li>
            </ul>

            <div class="tab-content card">
                <div id="reuniao-detalhes" class="tab-pane form-control active">
                    <div class="form-item">
                        <label for="assunto">Assunto: </label>
                        <input type="text" name="assunto" id="assunto" value="{{ old('assunto') }}" placeholder="Assunto principal" required>
                    </div>
                    <div class="form-item">
                        <label for="tipo">Tipo de reunião: </label>
                        <div class="form-square">
                            <div>
                                <input type="radio" id="lideranca" name="tipo" value="lideranca" @checked(old('tipo', 'geral') === 'lideranca')>
                                <label for="lideranca">Liderança</label>
                            </div>
                            <div>
                                <input type="radio" id="grupo" name="tipo" value="grupo" @checked(old('tipo', 'geral') === 'grupo')>
                                <label for="grupo">Grupo</label>
                            </div>
                            <div>
                                <input type="radio" id="geral" name="tipo" value="geral" @checked(old('tipo', 'geral') === 'geral')>
                                <label for="geral">Geral</label>
                            </div>
                            <div>
                                <input type="radio" id="outro" name="tipo" value="outro" @checked(old('tipo') === 'outro')>
                                <label for="outro">Outro</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-item">
                        <label for="data_inicio">Data agendada: </label>
                        <input type="date" name="data_inicio" id="data_inicio" value="{{ old('data_inicio', now()->format('Y-m-d')) }}" required>
                    </div>
                    <div class="form-item">
                        <label for="horario_inicio">Horário de início: </label>
                        <input type="time" name="horario_inicio" id="horario_inicio" value="{{ old('horario_inicio', now()->format('H:i')) }}">
                    </div>
                    <div class="form-item">
                        <label for="data_fim">Data de encerramento (opcional): </label>
                        <input type="date" name="data_fim" id="data_fim" value="{{ old('data_fim') }}">
                    </div>
                    <div class="form-item">
                        <label for="descricao">Descrição: </label>
                        <textarea name="descricao" id="descricao" placeholder="Descrição">{{ old('descricao') }}</textarea>
                    </div>
                    <div class="form-item">
                        <label for="privado">Tipo de Acesso: </label>
                        <div class="form-square">
                            <div>
                                <input type="radio" id="acesso_publico" name="privado" value="0" @checked(! old('privado'))>
                                <label for="acesso_publico">Pública</label>
                            </div>
                            <div>
                                <input type="radio" id="acesso_privado" name="privado" value="1" @checked(old('privado') == '1')>
                                <label for="acesso_privado">Privada</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-item">
                        <label for="online">Ocorrência: </label>
                        <div class="form-square">
                            <div>
                                <input type="radio" id="presencial" name="online" value="0" @checked(! old('online'))>
                                <label for="presencial">Presencial</label>
                            </div>
                            <div>
                                <input type="radio" id="online" name="online" value="1" @checked(old('online') == '1')>
                                <label for="online">Online</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-item">
                        <label for="link_online">Link da reunião (opcional): </label>
                        <input type="url" name="link_online" id="link_online" value="{{ old('link_online') }}" placeholder="https://">
                    </div>
                </div>

                <div id="reuniao-participantes" class="tab-pane form-control">
                        @php
                            $agrupamentoLabels = [
                                'grupo' => 'Grupos',
                                'departamento' => 'Departamentos',
                                'setor' => 'Setores',
                                'ministerio' => 'Ministérios',
                            ];
                        @endphp
                    <div class="form-item">
                        <label for="grupos">Participação por agrupamentos <br> <small class="hint">Clique para abrir e marque os agrupamentos desejados.</small></label>
                        @if($agrupamentos->isEmpty())
                            <p class="hint">Nenhum agrupamento cadastrado até o momento.</p>
                        @else
                        <select name="agrupamentos[]" id="grupos" multiple class="select2" data-placeholder="Selecione os agrupamentos" data-search-placeholder="Pesquise por agrupamentos">
                                <option></option>
                                @foreach($agrupamentos as $tipo => $lista)
                                    <optgroup label="{{ $agrupamentoLabels[$tipo] ?? ucfirst($tipo) }}">
                                        @foreach ($lista as $item)
                                            <option value="{{ $item->id }}" @selected(in_array($item->id, $selectedAgrupamentos))>{{ $item->nome }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        @endif
                    </div>
                    <div class="form-item">
                        <label for="membros">Selecionar membros individuais <br> <small class="hint">Clique para abrir e marque os membros desejados.</small></label>
                    <select name="membros[]" id="membros" multiple class="select2" data-placeholder="Selecione os membros" data-search-placeholder="Pesquise por membros">
                            <option></option>
                            @foreach ($membros as $membro)
                                <option value="{{ $membro->id }}" @selected(in_array($membro->id, $selectedMembros))>{{ $membro->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-options center">
                <button class="btn" type="submit"><i class="bi bi-plus-circle"></i> Confirmar Reunião</button>
                <button type="button" onclick="if (typeof fecharJanelaModal === 'function') { fecharJanelaModal(); } else { window.history.back(); }" class="btn"><i class="bi bi-x-circle"></i> Cancelar</button>
            </div>
        </div>
    </form>
</div>
