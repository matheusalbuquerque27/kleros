<h1>Novo tipo de contribuição</h1>
<form action="{{ route('financeiro.tipos.store') }}" method="post">
    @csrf
    <div class="form-control">
        <div class="form-item">
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" placeholder="Dízimo, Oferta, Doação" value="{{ old('nome') }}" required>
        </div>
        <div class="form-item">
            <label for="descricao">Descrição</label>
            <textarea name="descricao" id="descricao" rows="3" placeholder="Observações adicionais">{{ old('descricao') }}</textarea>
        </div>
        <div class="form-options">
            <button type="submit" class="btn"><i class="bi bi-plus-circle"></i> Cadastrar tipo</button>
            <button type="button" class="btn" onclick="fecharJanelaModal()"><i class="bi bi-x-circle"></i> Cancelar</button>
        </div>
    </div>
</form>
