<h1>Editar lançamento</h1>
<form action="{{ route('financeiro.lancamentos.update', $lancamento->id) }}" method="post">
    @csrf
    @method('PUT')
    <input type="hidden" name="caixa_id" value="{{ $lancamento->caixa_id }}">
    <div class="form-control">
        <div class="form-item">
            <label>Caixa</label>
            <input type="text" value="{{ $lancamento->caixa->nome }}" disabled>
        </div>
        <div class="form-item">
            <label for="tipo_lancamento_id">Tipo de lançamento</label>
            <select name="tipo_lancamento_id" id="tipo_lancamento_id">
                <option value="">Selecione (opcional)</option>
                @foreach($tiposLancamento as $tipo)
                    <option value="{{ $tipo->id }}" @selected($lancamento->tipo_lancamento_id == $tipo->id)>{{ $tipo->nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-item">
            <label for="valor">Valor</label>
            <input type="number" step="0.01" name="valor" id="valor" value="{{ $lancamento->valor }}" placeholder="0,00" required>
        </div>
        <div class="form-item">
            <label>Tipo</label>
            <div class="form-square">
                <label><input type="radio" name="tipo" value="entrada" @checked($lancamento->tipo === 'entrada')> Entrada</label>
                <label><input type="radio" name="tipo" value="saida" @checked($lancamento->tipo === 'saida')> Saída</label>
            </div>
        </div>
        <div class="form-item">
            <label for="data_lancamento">Data</label>
            <input type="date" name="data_lancamento" id="data_lancamento" value="{{ $lancamento->data_lancamento->format('Y-m-d') }}">
        </div>
        <div class="form-item">
            <label for="descricao">Descrição</label>
            <textarea name="descricao" id="descricao" rows="3" placeholder="Observações do lançamento">{{ $lancamento->descricao }}</textarea>
        </div>
        <div class="form-options">
            <button type="submit" class="btn"><i class="bi bi-check-circle"></i> Salvar alterações</button>
            <button type="button" class="btn" onclick="fecharJanelaModal()"><i class="bi bi-x-circle"></i> Cancelar</button>
        </div>
    </div>
</form>
