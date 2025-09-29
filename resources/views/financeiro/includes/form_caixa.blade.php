<h1>Novo caixa</h1>
<form action="{{ route('financeiro.caixas.store') }}" method="post">
    @csrf
    <div class="form-control">
        <div class="form-item">
            <label for="nome">Nome do caixa</label>
            <input type="text" name="nome" id="nome" placeholder="Ex: Caixa principal" value="{{ old('nome') }}" required>
        </div>
        <div class="form-item">
            <label for="descricao">Descrição</label>
            <textarea name="descricao" id="descricao" rows="3" placeholder="Detalhes, finalidade ou observações">{{ old('descricao') }}</textarea>
        </div>
        <div class="form-options">
            <button type="submit" class="btn"><i class="bi bi-plus-circle"></i> Criar caixa</button>
            <button type="button" class="btn" onclick="fecharJanelaModal()"><i class="bi bi-x-circle"></i> Cancelar</button>
        </div>
    </div>
</form>
