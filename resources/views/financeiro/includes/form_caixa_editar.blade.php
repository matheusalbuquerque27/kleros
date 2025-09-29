<h1>Editar caixa</h1>
<form action="{{ route('financeiro.caixas.update', $caixa->id) }}" method="post">
    @csrf
    @method('PUT')
    <div class="form-control">
        <div class="form-item">
            <label for="nome">Nome do caixa</label>
            <input type="text" name="nome" id="nome" value="{{ old('nome', $caixa->nome) }}" required>
        </div>
        <div class="form-item">
            <label for="descricao">Descrição</label>
            <textarea name="descricao" id="descricao" rows="3">{{ old('descricao', $caixa->descricao) }}</textarea>
        </div>
        <div class="form-options">
            <button type="submit" class="btn"><i class="bi bi-arrow-clockwise"></i> Atualizar caixa</button>
            <button type="button" class="btn" onclick="fecharJanelaModal()"><i class="bi bi-x-circle"></i> Cancelar</button>
        </div>
    </div>
</form>
