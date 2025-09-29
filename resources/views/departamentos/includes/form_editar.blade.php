<h1>Editar Departamento</h1>
<form action="{{ route('departamentos.update', $departamento->id) }}" method="post">
    @csrf
    @method('PUT')
    @php
        $setores = isset($setores) ? $setores : collect();
        $agrupamentoConfig = optional(optional($congregacao)->config)->agrupamentos;
        $mostrarSelecaoSetor = $agrupamentoConfig === 'setor';
        $selectedSetor = old('agrupamento_pai_id', $departamento->agrupamento_pai_id);
    @endphp

    <div class="form-control">
        <div class="form-item">
            <label for="nome">Nome do Departamento: </label>
            <input type="text" name="nome" id="nome" value="{{ old('nome', $departamento->nome) }}" required>
        </div>

        <div class="form-item">
            <label for="descricao">Descrição: </label>
            <textarea name="descricao" id="descricao" cols="30" rows="4" placeholder="Descreva a função do departamento">{{ old('descricao', $departamento->descricao) }}</textarea>
        </div>

        <div class="form-item">
            <label for="lider_id">Líder Responsável: </label>
            <select name="lider_id" id="lider_id" required>
                <option value="">Selecione um líder</option>
                @foreach($membros as $membro)
                    <option value="{{ $membro->id }}" @selected(old('lider_id', $departamento->lider_id) == $membro->id)>{{ $membro->nome }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-item">
            <label for="colider_id">Colíder: </label>
            <select name="colider_id" id="colider_id">
                <option value="">Selecione um colíder</option>
                @foreach($membros as $membro)
                    <option value="{{ $membro->id }}" @selected(old('colider_id', $departamento->colider_id) == $membro->id)>{{ $membro->nome }}</option>
                @endforeach
            </select>
        </div>

        @if($mostrarSelecaoSetor)
        <div class="form-item">
            <label for="agrupamento_pai_id">Setor: 
                @if($setores->isEmpty())
                <br><small class="hint">Cadastre um setor para vinculá-lo ao departamento.</small>
            @endif
            </label>
            <select name="agrupamento_pai_id" id="agrupamento_pai_id" @if($setores->isEmpty()) disabled @endif>
                <option value="">{{ $setores->isEmpty() ? 'Nenhum setor cadastrado' : 'Selecione um setor' }}</option>
                @foreach($setores as $setor)
                    <option value="{{ $setor->id }}" @selected($selectedSetor == $setor->id)>{{ $setor->nome }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div class="form-options">
            <button class="btn" type="submit"><i class="bi bi-arrow-clockwise"></i> Atualizar Departamento</button>
            <form action="{{ route('departamentos.destroy', $departamento->id) }}" method="POST" style="display:inline;" onsubmit="return handleSubmit(event, this, 'Deseja realmente excluir este departamento?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn danger"><i class="bi bi-trash"></i> Excluir</button>
            </form>
            <button type="button" class="btn" onclick="fecharJanelaModal()"><i class="bi bi-x-circle"></i> Cancelar</button>
        </div>
    </div>
</form>
