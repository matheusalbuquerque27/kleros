<h1>Editar Reunião</h1>
<form action="/reunioes" method="post">
    @csrf
    @method('PUT')
    <div class="form-control">
        <div class="form-item">
            <label for="titulo">Assunto: </label>
            <input type="text" name="assunto" id="assunto" value="{{$reuniao->assunto}}">
        </div>
        <div class="form-item">
            <label for="grupo_id">Participantes: </label>
            <select name="grupo_id" id="grupo_id">
                <option value="">Grupo responsável</option>
                @foreach ($grupos as $item)
                <option value="{{$item->id}}" @selected($reuniao->grupo_id == $item->id)>{{$item->nome}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-item">
            <label for="tipo_reuniao">Tipo de reunião: </label>
            <div class="form-square">
                <div>
                <input type="radio" id="lideranca" name="tipo_reuniao" value="lideranca"
                        @checked(old('tipo', $reuniao->tipo ?? 'geral') === 'lideranca')>
                <label for="lideranca">Liderança</label>
                </div>
                <div>
                <input type="radio" id="grupo" name="tipo_reuniao" value="grupo"
                        @checked(old('tipo', $reuniao->tipo ?? 'geral') === 'grupo')>
                <label for="grupo">Grupo</label>
                </div>
                <div>
                <input type="radio" id="geral" name="tipo_reuniao" value="geral"
                        @checked(old('tipo', $reuniao->tipo ?? 'geral') === 'geral')>
                <label for="geral">Geral</label>
                </div>
                <div>
                <input type="radio" id="outro" name="tipo_reuniao" value="outro"
                        @checked(old('tipo', $reuniao->tipo ?? 'geral') === 'outro')>
                <label for="outro">Outro</label>
                </div>
            </div>
        </div>
        <div class="form-item">
            <label for="data_inicio">Data agendada: </label>
            <input type="date" name="data_inicio" id="data_inicio" value="{{ $reuniao->data_inicio?->format('Y-m-d') }}">
        </div>
        <div class="form-item">
            <label for="horario_inicio">Horário de início: </label>
            <input type="time" name="horario_inicio" id="horario_inicio" value="{{ $reuniao->data_inicio?->format('H:i') }}">
        </div>
        <div class="form-item">
            <label for="descricao">Descrição: </label>
            <textarea name="descricao" placeholder="Descrição">{{$reuniao->descricao}}</textarea>
        </div>
        <div class="form-item">
            <label for="requer_inscricao">Tipo de Acesso: </label>
            <div class="form-square">
                <div>
                    <input type="radio" id="acesso_publico" name="tipo" value="0"
                        @checked($reuniao->tipo === '0') required>
                    <label for="acesso_publico">Pública</label>
                </div>
                <div>
                    <input type="radio" id="acesso_privado" name="tipo" value="1"
                        @checked($reuniao->tipo === '1')>
                    <label for="acesso_privado">Privada</label>
                </div>
            </div>
        </div>
        <div class="form-options">
            <button class="btn" type="submit"><i class="bi bi-plus-circle"></i> Alterar Reunião</button>
            <button type="button" class="btn" onclick="fecharJanelaModal()"><i class="bi bi-x-circle"></i> Cancelar</button>
        </div>
    </div>
</form>