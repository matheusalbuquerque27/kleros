<h1>Novo Evento</h1>
<form action="/eventos" method="post">
    @csrf
    <div class="form-control">
        <div class="form-item">
            <label for="titulo">Título: </label>
            <input type="text" name="titulo" id="titulo" placeholder="Título do evento">
        </div>
        <div class="form-item">
            <label for="grupo_id">Grupo responsável: </label>
            <select name="grupo_id" id="grupo_id">
                <option value="">Grupo responsável</option>
                @foreach ($grupos as $item)
                <option value="{{$item->id}}">{{$item->nome}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-item">
            <label for="evento_recorrente">Natureza do evento: </label>
            <div class="form-square">
                <div>
                    <input type="radio" id="especifico" name="evento_recorrente" value="0" checked>
                    <label for="especifico">Específico (cadastro individual)</label>
                </div>
                <div>
                    <input type="radio" id="recorrente" name="evento_recorrente" value="1">
                    <label for="recorrente">Recorrente (cadastro único)</label>
                </div>
            </div>
        </div>
        <div class="form-item">
            <label for="data_inicio">Data de início: </label>
            <input type="date" name="data_inicio" id="data_inicio" value="{{ date('Y-m-d') }}">
        </div>
        <div class="form-item">
            <label for="data_encerramento">Data de encerramento: </label>
            <input type="date" name="data_encerramento" id="data_encerramento" value="{{ date('Y-m-d') }}">
        </div>
        <div class="form-item">
            <label for="descricao">Descrição: </label>
            <textarea name="descricao" placeholder="Descrição do evento"></textarea>
        </div>
        <div class="form-item">
            <label for="requer_inscricao">Tipo de Acesso: </label>
            <div class="form-square">
                <div>
                    <input type="radio" id="publico" name="requer_inscricao" value="0" checked>
                    <label for="publico">Público - Livre</label>
                </div>
                <div>
                    <input type="radio" id="privado" name="requer_inscricao" value="1">
                    <label for="privado">Privado - Requer confirmação</label>
                </div>
            </div>
        </div>
        <div class="form-item geracao_cultos">
            <label for="geracao_cultos">Geração de cultos: </label>
            <div class="form-square">
                <div>
                    <input type="radio" id="geracao_automatica" name="geracao_cultos" value="1" checked>
                    <label for="geracao_automatica">Automática</label>
                </div>
                <div>
                    <input type="radio" id="geracao_manual" name="geracao_cultos" value="0">
                    <label for="geracao_manual">Manual</label>
                </div>
            </div>
        </div>
        <div class="form-options">
            <button class="btn" type="submit"><i class="bi bi-plus-circle"></i> Adicionar Evento</button>
            <button onclick="fecharJanelaModal()" type="button" class="btn"><i class="bi bi-x-circle"></i> Cancelar</button>
        </div>
    </div>
</form>