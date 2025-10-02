<h1>Editar tipo de contribuição</h1>
<form action="{{ route('financeiro.tipos.update', $tipo->id) }}" method="post">
    @csrf
    @method('PUT')
    <div class="form-control">
        <div class="form-item">
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" value="{{ old('nome', $tipo->nome) }}" required>
        </div>
        <div class="form-item">
            <label for="descricao">Descrição</label>
            <textarea name="descricao" id="descricao" rows="3">{{ old('descricao', $tipo->descricao) }}</textarea>
        </div>
        <div class="form-options">
            <button type="submit" class="btn"><i class="bi bi-arrow-clockwise"></i> Atualizar tipo</button>
            <button type="button" class="btn" onclick="fecharJanelaModal()"><i class="bi bi-x-circle"></i> Cancelar</button>
        </div>
    </div>
</form>
