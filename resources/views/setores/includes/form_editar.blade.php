<h1>Editar Setor</h1>
<form action="{{ route('setores.update', $setor->id) }}" method="post">
    @csrf
    @method('PUT')
    @php
        $membros = isset($membros) ? $membros : collect();
        $departamentos = isset($departamentos) ? $departamentos : collect();
        $departamentosSelecionados = collect(old('departamentos', isset($departamentosSelecionados) ? $departamentosSelecionados : []))
            ->map(fn ($id) => (int) $id)
            ->all();
        $grupos = isset($grupos) ? $grupos : collect();
        $gruposSelecionados = collect(old('grupos', isset($gruposSelecionados) ? $gruposSelecionados : []))
            ->map(fn ($id) => (int) $id)
            ->all();
    @endphp

    <div class="form-control">
        <div class="form-item">
            <label for="nome">Nome do Setor: </label>
            <input type="text" name="nome" id="nome" value="{{ old('nome', $setor->nome) }}" required>
        </div>

        <div class="form-item">
            <label for="descricao">Descrição: </label>
            <textarea name="descricao" id="descricao" cols="30" rows="4" placeholder="Descreva a atuação do setor">{{ old('descricao', $setor->descricao) }}</textarea>
        </div>

        <div class="form-item">
            <label for="lider_id">Líder Responsável: </label>
            <select name="lider_id" id="lider_id">
                <option value="">Selecione um líder</option>
                @foreach($membros as $membro)
                    <option value="{{ $membro->id }}" @selected(old('lider_id', $setor->lider_id) == $membro->id)>{{ $membro->nome }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-item">
            <label for="colider_id">Colíder: </label>
            <select name="colider_id" id="colider_id">
                <option value="">Selecione um colíder</option>
                @foreach($membros as $membro)
                    <option value="{{ $membro->id }}" @selected(old('colider_id', $setor->colider_id) == $membro->id)>{{ $membro->nome }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-item">
            <label for="departamentos">Departamentos vinculados <br> <small class="hint">Selecione os departamentos que fazem parte deste setor.</small></label>
            <select name="departamentos[]" id="departamentos" multiple class="select2" data-placeholder="Selecione os departamentos" data-search-placeholder="Pesquise por departamentos">
                <option></option>
                @foreach($departamentos as $departamento)
                    <option value="{{ $departamento->id }}" @selected(in_array($departamento->id, $departamentosSelecionados))>{{ $departamento->nome }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-item">
            <label for="grupos">Grupos vinculados <br> <small class="hint">Selecione os grupos que pertencem a este setor.</small></label>
            <select name="grupos[]" id="grupos" multiple class="select2" data-placeholder="Selecione os grupos" data-search-placeholder="Pesquise por grupos">
                <option></option>
                @foreach($grupos as $grupo)
                    <option value="{{ $grupo->id }}" @selected(in_array($grupo->id, $gruposSelecionados))>{{ $grupo->nome }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-options">
            <button class="btn" type="submit"><i class="bi bi-arrow-clockwise"></i> Atualizar Setor</button>
            <button type="button" class="btn" onclick="fecharJanelaModal()"><i class="bi bi-x-circle"></i> Cancelar</button>
        </div>
    </div>
</form>
