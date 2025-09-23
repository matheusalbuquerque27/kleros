<h1>Criar Departamento</h1>
<form action="{{ route('departamentos.store') }}" method="post">
    @csrf

    <div class="form-control">
        <div class="form-item">
            <label for="nome">Nome do Departamento: </label>
            <input type="text" name="nome" id="nome" value="{{ old('nome') }}" placeholder="Ex: Tesouraria" required>
        </div>

        <div class="form-item">
            <label for="descricao">Descrição: </label>
            <textarea name="descricao" id="descricao" cols="30" rows="4" placeholder="Descreva a função do departamento">{{ old('descricao') }}</textarea>
        </div>

        <div class="form-item">
            <label for="lider_id">Líder Responsável: </label>
            <select name="lider_id" id="lider_id" required>
                <option value="">Selecione um líder</option>
                @foreach($membros as $membro)
                    <option value="{{ $membro->id }}" @selected(old('lider_id') == $membro->id)>{{ $membro->nome }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-item">
            <label for="colider_id">Colíder: </label>
            <select name="colider_id" id="colider_id">
                <option value="">Selecione um colíder</option>
                @foreach($membros as $membro)
                    <option value="{{ $membro->id }}" @selected(old('colider_id') == $membro->id)>{{ $membro->nome }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-options">
            <button class="btn" type="submit"><i class="bi bi-plus-circle"></i> Adicionar</button>
            <button type="button" class="btn" onclick="fecharJanelaModal()"><i class="bi bi-x-circle"></i> Cancelar</button>
        </div>
    </div>
</form>
