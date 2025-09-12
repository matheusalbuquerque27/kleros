<h1>Criar Aviso</h1>
<form action="{{route('avisos.store')}}" method="post">
    @csrf
    <div class="form-control">
        <div class="form-item">
            <label for="titulo">Título: </label>
            <input type="text" name="titulo" id="titulo" placeholder="Título do aviso" required>
        </div>

        <div class="form-item">
            <label for="mensagem">Mensagem: </label>
            <textarea name="mensagem" id="mensagem" cols="30" rows="5" placeholder="Escreva sua mensagem"></textarea>
        </div>

        <div class="form-item">
            <label for="prioridade">Prioridade: </label>
            <select name="prioridade" id="prioridade">
                <option value="normal" selected>Normal (baixa)</option>
                <option value="importante">Importante (média)</option>
                <option value="urgente">Urgente (alta)</option>
            </select>
        </div>

        <div class="form-item">
            <label for="destinatarios">Quem recebe? </label>
            <select name="destinatarios" id="destinatarios">
                <option value="1">Todos</option>
                <option value="0">Destinatários selecionados</option>
            </select>
        </div>

        <!-- Multiselect de grupos e membros -->
        <div id="selecionados" style="display:none; margin-top:15px;">
            <div class="form-item">
                <label for="grupos">Grupos:</label>
                <select name="grupos[]" id="grupos" multiple>
                    @foreach($grupos as $grupo)
                        <option value="{{ $grupo->id }}">{{ $grupo->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-item">
                <label for="membros">Membros:</label>
                <select name="membros[]" id="membros" multiple>
                    @foreach($membros as $membro)
                        <option value="{{ $membro->id }}">{{ $membro->nome }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-item">
            <label for="data_fim">Exibir até: </label>
            <input type="date" name="data_fim" id="data_fim">
        </div>

        <div class="form-options">
            <button class="btn" type="submit"><i class="bi bi-send"></i> Enviar</button>
            <button type="button" class="btn" onclick="fecharJanelaModal()"><i class="bi bi-arrow-return-left"></i> Voltar</button>
        </div>
    </div>
</form>

<script>