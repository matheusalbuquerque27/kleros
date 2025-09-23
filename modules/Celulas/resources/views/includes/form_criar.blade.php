<h1>Criar Célula</h1>
<form action="{{ route('celulas.store') }}" method="post">
    @csrf
    <div class="form-control">

        <div class="form-item">
            <label for="identificacao">Identificação da Célula:</label>
            <input type="text" name="identificacao" id="identificacao" placeholder="Ex: Célula da Família Silva" required>
        </div>

        <div class="form-item">
            <label for="cor_borda">Cor da Borda:</label>
            <input type="color" name="cor_borda" id="cor_borda" value="{{ old('cor_borda', '#ffffff') }}">
        </div>

        <div class="form-item">
            <label for="lider_id">Líder:</label>
            <select name="lider_id" id="lider_id">
                <option value="">Selecione um líder</option>
                @foreach($membros as $membro)
                    <option value="{{ $membro->id }}">{{ $membro->nome }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-item">
            <label for="colider_id">Colíder:</label>
            <select name="colider_id" id="colider_id">
                <option value="">Selecione um colíder</option>
                @foreach($membros as $membro)
                    <option value="{{ $membro->id }}">{{ $membro->nome }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-item">
            <label for="anfitriao_id">Anfitrião:</label>
            <select name="anfitriao_id" id="anfitriao_id">
                <option value="">Selecione um anfitrião</option>
                @foreach($membros as $membro)
                    <option value="{{ $membro->id }}">{{ $membro->nome }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-item">
            <label for="endereco">Endereço:</label>
            <input type="text" name="endereco" id="endereco" placeholder="Rua, avenida...">
        </div>

        <div class="form-item">
            <label for="numero">Número:</label>
            <input type="text" name="numero" id="numero" placeholder="Nº">
        </div>

        <div class="form-item">
            <label for="bairro">Bairro:</label>
            <input type="text" name="bairro" id="bairro" placeholder="Ex: Centro">
        </div>

        <div class="form-item">
            <label for="cep">CEP:</label>
            <input type="text" name="cep" id="cep" placeholder="00000-000">
        </div>

        <div class="form-item">
            <label for="dia_encontro">Dia do Encontro:</label>
            <select name="dia_encontro" id="dia_encontro">
                <option value="">Selecione o dia</option>
                <option value="1">Segunda-feira</option>
                <option value="2">Terça-feira</option>
                <option value="3">Quarta-feira</option>
                <option value="4">Quinta-feira</option>
                <option value="5">Sexta-feira</option>
                <option value="6">Sábado</option>
                <option value="7">Domingo</option>
            </select>
        </div>

        <div class="form-item">
            <label for="hora_encontro">Hora do Encontro:</label>
            <input type="time" name="hora_encontro" id="hora_encontro">
        </div>

        <div class="form-item">
            <label for="ativa">Status da Célula:</label>
            <div class="form-square">
                <div>
                    <input type="radio" id="ativa_sim" name="ativa" value="1" checked>
                    <label for="ativa_sim">Ativa</label>
                </div>
                <div>
                    <input type="radio" id="ativa_nao" name="ativa" value="0">
                    <label for="ativa_nao">Inativa</label>
                </div>
            </div>
        </div>

        <div class="form-item">
            <label for="descricao">Descrição:</label>
            <textarea name="descricao" id="descricao" cols="30" rows="4" placeholder="Detalhes adicionais sobre a célula"></textarea>
        </div>

        <div class="form-options">
            <button class="btn" type="submit"><i class="bi bi-plus-circle"></i> Adicionar</button>
            <button type="button" class="btn" onclick="fecharJanelaModal()"><i class="bi bi-x-circle"></i> Cancelar</button>
        </div>
    </div>
</form>
