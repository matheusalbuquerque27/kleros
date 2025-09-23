<h1>Editar Célula</h1>
<form action="{{ route('celulas.update', $celula->id) }}" method="post">
    @csrf
    @method('PUT')

    <div class="tabs">
        <!-- Menu de abas -->
        <ul class="tab-menu">
            <li class="active" data-tab="principal"><i class="bi bi-house"></i> Principal</li>
            <li data-tab="local"><i class="bi bi-pin-map"></i> Local</li>
            <li data-tab="encontros"><i class="bi bi-calendar-event"></i> Encontros</li>
            <li data-tab="participantes"><i class="bi bi-people"></i> Participantes</li>
        </ul>

        <!-- Conteúdo das abas -->
        <div class="tab-content card">

            <!-- Aba 1 -->
            <div id="principal" class="tab-pane active form-control">
                <div class="form-item">
                    <label for="identificacao">Identificação da Célula:</label>
                    <input type="text" name="identificacao" id="identificacao"
                        value="{{ old('identificacao', $celula->identificacao) }}" required>
                </div>

                <div class="form-item">
                    <label for="cor_borda">Cor da Borda:</label>
                    <input type="color" name="cor_borda" id="cor_borda"
                        value="{{ old('cor_borda', $celula->cor_borda ?? '#ffffff') }}">
                </div>

                <div class="form-item">
                    <label for="lider_id">Líder:</label>
                    <select name="lider_id" id="lider_id">
                        <option value="">Selecione um líder</option>
                        @foreach($membros as $membro)
                            <option value="{{ $membro->id }}"
                                @selected(old('lider_id', $celula->lider_id) == $membro->id)>
                                {{ $membro->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-item">
                    <label for="colider_id">Colíder:</label>
                    <select name="colider_id" id="colider_id">
                        <option value="">Selecione um colíder</option>
                        @foreach($membros as $membro)
                            <option value="{{ $membro->id }}"
                                @selected(old('colider_id', $celula->colider_id) == $membro->id)>
                                {{ $membro->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-item">
                    <label for="anfitriao_id">Anfitrião:</label>
                    <select name="anfitriao_id" id="anfitriao_id">
                        <option value="">Selecione um anfitrião</option>
                        @foreach($membros as $membro)
                            <option value="{{ $membro->id }}"
                                @selected(old('anfitriao_id', $celula->anfitriao_id) == $membro->id)>
                                {{ $membro->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-item">
                    <label for="ativa">Status da Célula:</label>
                    <div class="form-square">
                        <div>
                            <input type="radio" id="ativa_sim" name="ativa" value="1"
                                @checked(old('ativa', $celula->ativa) == 1)>
                            <label for="ativa_sim">Ativa</label>
                        </div>
                        <div>
                            <input type="radio" id="ativa_nao" name="ativa" value="0"
                                @checked(old('ativa', $celula->ativa) == 0)>
                            <label for="ativa_nao">Inativa</label>
                        </div>
                    </div>
                </div>

                <div class="form-item">
                    <label for="descricao">Descrição:</label>
                    <textarea name="descricao" id="descricao" rows="4"
                        placeholder="Detalhes adicionais sobre a célula">{{ old('descricao', $celula->descricao) }}</textarea>
                </div>
            </div>

            <!-- Aba 2 -->
            <div id="local" class="tab-pane form-control">
                <div class="form-item">
                    <label for="endereco">Endereço:</label>
                    <input type="text" name="endereco" id="endereco"
                        value="{{ old('endereco', $celula->endereco) }}">
                </div>

                <div class="form-item">
                    <label for="numero">Número:</label>
                    <input type="text" name="numero" id="numero"
                        value="{{ old('numero', $celula->numero) }}">
                </div>

                <div class="form-item">
                    <label for="bairro">Bairro:</label>
                    <input type="text" name="bairro" id="bairro"
                        value="{{ old('bairro', $celula->bairro) }}">
                </div>

                <div class="form-item">
                    <label for="cep">CEP:</label>
                    <input type="text" name="cep" id="cep"
                        value="{{ old('cep', $celula->cep) }}">
                </div>

            </div>

            <!-- Aba 3 -->
            <div id="encontros" class="tab-pane form-control">
                <div class="form-item">
                    <label for="dia_encontro">Dia do Encontro:</label>
                    <select name="dia_encontro" id="dia_encontro">
                        <option value="">Selecione o dia</option>
                        <option value="1" @selected(old('dia_encontro', $celula->dia_encontro) == 1)>Segunda-feira</option>
                        <option value="2" @selected(old('dia_encontro', $celula->dia_encontro) == 2)>Terça-feira</option>
                        <option value="3" @selected(old('dia_encontro', $celula->dia_encontro) == 3)>Quarta-feira</option>
                        <option value="4" @selected(old('dia_encontro', $celula->dia_encontro) == 4)>Quinta-feira</option>
                        <option value="5" @selected(old('dia_encontro', $celula->dia_encontro) == 5)>Sexta-feira</option>
                        <option value="6" @selected(old('dia_encontro', $celula->dia_encontro) == 6)>Sábado</option>
                        <option value="7" @selected(old('dia_encontro', $celula->dia_encontro) == 7)>Domingo</option>
                    </select>
                </div>

                <div class="form-item">
                    <label for="hora_encontro">Hora do Encontro:</label>
                    <input type="time" name="hora_encontro" id="hora_encontro"
                        value="{{ old('hora_encontro', $celula->hora_encontro) }}">
                </div>
            </div>

            <!-- Aba 4 -->
            @php
                $participantesSelecionados = collect(old('participantes', $celula->participantes->pluck('id')->all()))
                    ->filter()
                    ->unique()
                    ->values();

                $membrosPorId = $membros->keyBy('id');

                $participantesData = $participantesSelecionados->map(function ($participanteId) use ($membrosPorId) {
                    $membro = $membrosPorId->get($participanteId);
                    if (!$membro) {
                        return null;
                    }

                    $partesEndereco = array_filter([$membro->endereco, $membro->numero]);
                    $endereco = implode(', ', $partesEndereco);
                    if ($membro->bairro) {
                        $endereco = $endereco ? $endereco . ' - ' . $membro->bairro : $membro->bairro;
                    }
                    $endereco = $endereco ?: 'Não informado';

                    return [
                        'id' => (string) $membro->id,
                        'nome' => $membro->nome,
                        'telefone' => $membro->telefone ?? 'Não informado',
                        'endereco' => $endereco,
                        'ministerio' => optional($membro->ministerio)->titulo ?? 'Não informado',
                        'foto' => $membro->foto ? asset('storage/' . $membro->foto) : asset('storage/images/newuser.png'),
                    ];
                })->filter()->values();
            @endphp

            <div id="participantes" class="tab-pane form-control" data-participantes='@json($participantesData)' data-page-size="6" data-fallback-avatar="{{ asset('storage/images/newuser.png') }}">
                <div class="form-item">
                    <p>Gerencie os participantes desta célula. Use o seletor abaixo para adicionar novos membros.</p>
                </div>

                <select id="participantes-hidden" name="participantes[]" multiple hidden>
                    @foreach($participantesSelecionados as $participanteId)
                        <option value="{{ $participanteId }}" selected></option>
                    @endforeach
                </select>

                <div class="form-item participante-add">
                    <label for="participante-select">Adicionar participante</label>
                    <div class="participante-add-row">
                        <select id="participante-select">
                            <option value="">Selecione um membro</option>
                            @foreach($membros as $membro)
                                @php
                                    $partesEndereco = array_filter([$membro->endereco, $membro->numero]);
                                    $enderecoOption = implode(', ', $partesEndereco);
                                    if ($membro->bairro) {
                                        $enderecoOption = $enderecoOption ? $enderecoOption . ' - ' . $membro->bairro : $membro->bairro;
                                    }
                                    $enderecoOption = $enderecoOption ?: 'Não informado';
                                    $telefoneOption = $membro->telefone ?? 'Não informado';
                                    $ministerioOption = optional($membro->ministerio)->titulo ?? 'Não informado';
                                    $fotoOption = $membro->foto ? asset('storage/' . $membro->foto) : asset('storage/images/newuser.png');
                                @endphp
                                <option value="{{ $membro->id }}"
                                    data-nome="{{ e($membro->nome) }}"
                                    data-telefone="{{ e($telefoneOption) }}"
                                    data-endereco="{{ e($enderecoOption) }}"
                                    data-ministerio="{{ e($ministerioOption) }}"
                                    data-foto="{{ $fotoOption }}"
                                    @disabled($participantesSelecionados->contains($membro->id))
                                >
                                    {{ $membro->nome }}
                                </option>
                            @endforeach
                        </select>
                        <button type="button" class="btn" id="btn-add-participante"><i class="bi bi-person-plus"></i> Adicionar</button>
                    </div>
                </div>

                <div class="participantes-list-wrapper">
                    <div class="list participantes-list">
                        <div class="list-title">
                            <div class="item-2"><b>Nome</b></div>
                            <div class="item-1"><b>Telefone</b></div>
                            <div class="item-2"><b>Endereço</b></div>
                            <div class="item-1"><b>Ministério</b></div>
                            <div class="item-1"><b>Ações</b></div>
                        </div>
                        <div class="participantes-content"></div>
                    </div>
                    <div class="participantes-pagination"></div>
                </div>
            </div>
        </div>

        @php
            $congregacao = optional($celula)->congregacao;
            $cidade = optional(optional($congregacao)->cidade)->nome;
            $estado = optional(optional($congregacao)->estado)->nome;
            $cidadeEstado = $cidade && $estado ? $cidade . ' - ' . $estado : ($cidade ?: $estado);

            $logradouro = trim(implode(' ', array_filter([$celula->endereco, $celula->numero])));
            $enderecoPartes = array_filter([$logradouro, $celula->bairro, $cidadeEstado]);

            $enderecoCompleto = implode(', ', $enderecoPartes);
        @endphp

        <div class="form-options">
            <button class="btn" type="submit"><i class="bi bi-arrow-clockwise"></i> Atualizar</button>
            @if($enderecoCompleto)
                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($enderecoCompleto) }}" target="_blank">
                    <button class="btn" type="button"><i class="bi bi-geo-alt"></i> Ver no Mapa</button>
                </a>
            @endif
            <button type="button" class="btn" onclick="fecharJanelaModal()"><i class="bi bi-x-circle"></i> Cancelar</button>
        </div>
    </div>
</form>

<!-- Estilo (pode extrair para CSS separado) -->
<style>
.tab-menu {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0 0 15px 0;
    border-bottom: 2px solid var(--secondary-color);
}
.tab-menu li {
    padding: 6px 12px;
    cursor: pointer;
    margin-right: 4px;
    border-radius: 8px 8px 0 0;
    transition: all .3s;
    font-weight: 500;
}
.tab-menu li:hover {
    background: var(--secondary-color);
    color: #fff;
}
.tab-menu li.active {
    background: var(--secondary-color);
    color: #fff;
    font-weight: bold;
}
.card {
    background: #fff;
    border-radius: 0 8px 8px 8px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.tab-pane {
    display: none;
}
.tab-pane.active {
    display: block;
}
.form-options {
    text-align: center;
    margin-top: 20px;
}
.participante-add-row {
    display: flex;
    align-items: center;
    gap: 12px;
}

#participante-select {
    flex: 1;
}

.participantes-list-wrapper {
    margin-top: 16px;
}

.participantes-content {
    max-height: 260px;
    overflow-y: auto;
}

.participantes-content .list-item {
    cursor: default;
}

.participante-actions {
    display: flex;
    justify-content: center;
    align-items: center;
}

.participante-actions .btn {
    font-size: 0.85rem;
    padding: 6px 12px;
    border-radius: 8px;
}

.participantes-pagination {
    display: flex;
    gap: 6px;
    justify-content: center;
    margin-top: 12px;
}

.participantes-pagination .page-btn {
    border: 1px solid var(--secondary-color);
    background: #fff;
    color: var(--secondary-color);
    border-radius: 6px;
    padding: 4px 10px;
    cursor: pointer;
    transition: all .2s;
}

.participantes-pagination .page-btn.active,
.participantes-pagination .page-btn:hover {
    background: var(--secondary-color);
    color: #fff;
}

.participantes-content .empty-state {
    margin: 12px 0;
    padding: 16px;
    text-align: center;
    color: #666;
}
</style>
