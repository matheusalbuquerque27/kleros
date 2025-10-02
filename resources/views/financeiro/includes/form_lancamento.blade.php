<h1>Registrar lançamento</h1>
<form action="{{ route('financeiro.lancamentos.store') }}" method="post">
    @csrf
    <input type="hidden" name="caixa_id" value="{{ $caixa->id }}">
    <div class="form-control">
        <div class="form-item">
            <label>Caixa</label>
            <input type="text" value="{{ $caixa->nome }}" disabled>
        </div>
        <div class="form-item">
            <label for="tipo_lancamento_id">Tipo de lançamento</label>
            <select name="tipo_lancamento_id" id="tipo_lancamento_id">
                <option value="">Selecione (opcional)</option>
                @foreach($tiposLancamento as $tipo)
                    <option value="{{ $tipo->id }}" @selected(old('tipo_lancamento_id') == $tipo->id)>{{ $tipo->nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-item">
            <label for="valor">Valor</label>
            <input type="number" step="0.01" name="valor" id="valor" value="{{ old('valor') }}" placeholder="0,00" required>
        </div>
        <div class="form-item">
            <label>Tipo</label>
            <div class="form-square">
                <label><input type="radio" name="tipo" value="entrada" @checked(old('tipo', 'entrada') === 'entrada')> Entrada</label>
                <label><input type="radio" name="tipo" value="saida" @checked(old('tipo') === 'saida')> Saída</label>
            </div>
        </div>
        <div class="form-item">
            <label for="data_lancamento">Data</label>
            <input type="date" name="data_lancamento" id="data_lancamento" value="{{ old('data_lancamento', now()->toDateString()) }}">
        </div>
        <div class="form-item">
            <label for="descricao">Descrição</label>
            <textarea name="descricao" id="descricao" rows="3" placeholder="Observações do lançamento">{{ old('descricao') }}</textarea>
        </div>
        <div class="form-options">
            <button type="submit" class="btn"><i class="bi bi-plus-circle"></i> Registrar</button>
            <button type="button" class="btn" onclick="fecharJanelaModal()"><i class="bi bi-x-circle"></i> Cancelar</button>
        </div>
    </div>
</form>
