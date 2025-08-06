<h1>Nova Reunião</h1>
<form action="/reunioes" method="post">
    @csrf
    <div class="form-control">
        <div class="form-item">
            <label for="titulo">Título: </label>
            <input type="text" name="titulo" id="titulo" placeholder="Título da reunião">
        </div>
        <div class="form-item">
            <label for="grupo_id">Participantes: </label>
            <select name="grupo_id" id="grupo_id">
                <option value="">Grupo responsável</option>
                @foreach ($grupos as $item)
                <option value="{{$item->id}}">{{$item->nome}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-item">
            <label for="tipo_reuniao">Tipo de reunião: </label>
            <div class="form-square">
                <div>
                    <input type="radio" id="lideranca" name="tipo_reuniao" value="lideranca" checked>
                    <label for="lideranca">Liderança</label>
                </div>
                <div>
                    <input type="radio" id="grupo" name="tipo_reuniao" value="grupo" checked>
                    <label for="grupo">Grupo</label>
                </div>
                <div>
                    <input type="radio" id="geral" name="tipo_reuniao" value="geral" checked>
                    <label for="regular">Geral</label>
                </div>
                <div>
                    <input type="radio" id="outro" name="tipo_reuniao" value="outro">
                    <label for="especifico">Outro</label>
                </div>
            </div>
        </div>
        <div class="form-item">
            <label for="data_inicio">Data agendada: </label>
            <input type="date" name="data_inicio" id="data_inicio" value="{{ date('Y-m-d') }}">
        </div>
        <div class="form-item">
            <label for="horario_inicio">Horário de início: </label>
            <input type="time" name="horario_inicio" id="horario_inicio" value="{{ date('H:i') }}">
        </div>
        <div class="form-item">
            <label for="descricao">Descrição: </label>
            <textarea name="descricao" placeholder="Descrição"></textarea>
        </div>
        <div class="form-item">
            <label for="requer_inscricao">Tipo de Acesso: </label>
            <div class="form-square">
                <div>
                    <input type="radio" id="automatica" name="tipo_acesso" value="0" checked>
                    <label for="automatica">Pública</label>
                </div>
                <div>
                    <input type="radio" id="manual" name="tipo_acesso" value="1">
                    <label for="manual">Privada</label>
                </div>
            </div>
        </div>
        <div class="form-options">
            <button class="btn" type="submit"><i class="bi bi-plus-circle"></i> Confirmar Reunião</button>
            <a href="/cadastros#reunioes"><button type="button" class="btn"><i class="bi bi-x-circle"></i> Cancelar</button></a>
        </div>
    </div>
</form>