<h1>Editar Encontro</h1>
<div class="info">
    @php
        $localCelula = collect([
            optional($encontro->celula)->endereco,
            optional($encontro->celula)->numero,
            optional($encontro->celula)->bairro,
        ])->filter()->implode(', ');
    @endphp

    @if ($localCelula)
        <div class="card" style="margin-bottom: 12px;">
            <p><i class="bi bi-geo-alt"></i> <strong>Local:</strong> {{ $localCelula }}</p>
        </div>
    @endif
    <form action="{{ route('celulas.encontros.update', $encontro->id) }}" method="post">
        @csrf
        @method('PUT')

        <div class="tab-content card">
            <div class="form-control">
                <div class="form-item">
                    <label for="ec-celula">Célula</label>
                    <select name="celula_id" id="ec-celula">
                        @foreach ($celulas as $celula)
                            <option value="{{ $celula->id }}" @selected(old('celula_id', $encontro->celula_id) == $celula->id)>
                                {{ $celula->identificacao }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-item">
                    <label for="ec-data">Data do encontro</label>
                    <input type="date" name="data_encontro" id="ec-data"
                        value="{{ old('data_encontro', optional($encontro->data_encontro instanceof \Carbon\Carbon ? $encontro->data_encontro : \Carbon\Carbon::parse($encontro->data_encontro))->format('Y-m-d')) }}"
                        required>
                </div>

                <div class="form-item">
                    <label for="ec-hora">Hora do encontro</label>
                    <input type="time" name="hora_encontro" id="ec-hora"
                        value="{{ old('hora_encontro', $encontro->hora_encontro ? \Carbon\Carbon::parse($encontro->hora_encontro)->format('H:i') : '') }}">
                </div>

                <div class="form-item">
                    <label for="ec-status">Status</label>
                    <select name="status" id="ec-status">
                        <option value="pendente"   @selected(old('status', $encontro->status) === 'pendente')>Pendente</option>
                        <option value="confirmado" @selected(old('status', $encontro->status) === 'confirmado')>Confirmado</option>
                        <option value="cancelado"  @selected(old('status', $encontro->status) === 'cancelado')>Cancelado</option>
                    </select>
                </div>

                <div class="form-item">
                    <label for="ec-preletor">Responsável</label>
                    <select name="preletor_id" id="ec-preletor" class="select2">
                        <option value="">Nenhum</option>
                        @foreach ($membros as $membro)
                            <option value="{{ $membro->id }}" @selected(old('preletor_id', $encontro->preletor_id) == $membro->id)>
                                {{ $membro->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-item">
                    <label for="ec-tema">Tema</label>
                    <input type="text" name="tema" id="ec-tema"
                        value="{{ old('tema', $encontro->tema) }}"
                        placeholder="Tema do encontro">
                </div>

                <div class="form-item">
                    <label for="ec-quantidade">Quantidade de presentes</label>
                    <input type="number" name="quantidade_presentes" id="ec-quantidade" min="0"
                        value="{{ old('quantidade_presentes', $encontro->quantidade_presentes) }}"
                        placeholder="0">
                </div>

                <div class="form-item">
                    <label for="ec-observacoes">Observações</label>
                    <textarea name="observacoes" id="ec-observacoes" rows="3"
                        placeholder="Informações adicionais sobre o encontro">{{ old('observacoes', $encontro->observacoes) }}</textarea>
                </div>
            </div>

            <div class="form-options center">
                <button class="btn" type="submit">
                    <i class="bi bi-arrow-clockwise"></i> Atualizar encontro
                </button>
                <button type="button" class="btn danger"
                    onclick="handleSubmit(event, document.getElementById('delete-encontro-{{ $encontro->id }}'), 'Deseja realmente excluir este encontro?')">
                    <i class="bi bi-trash"></i> Excluir
                </button>
                <button type="button" class="btn" onclick="fecharJanelaModal()">
                    <i class="bi bi-arrow-return-left"></i> Voltar
                </button>
            </div>
        </div>
    </form>
</div>

<form id="delete-encontro-{{ $encontro->id }}" action="{{ route('celulas.encontros.destroy', $encontro->id) }}" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
