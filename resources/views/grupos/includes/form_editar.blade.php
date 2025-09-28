@php
    $setores = isset($setores) ? $setores : collect();
    $agrupamentoConfig = optional(optional($congregacao)->config)->agrupamentos;
    $mostrarSelecaoSetor = $agrupamentoConfig === 'setor';
    $setorSelecionado = old('setor_id', $grupo->agrupamento_pai_id);
@endphp

<h1>Editar Grupo</h1>
<form action="{{route('grupos.update', $grupo->id)}}" method="post">
    @csrf
    @method('PUT')
    <div class="form-control">
        <div class="form-item">
            <label for="nome">Nome do grupo: </label>
            <input type="text" name="nome" value="{{ old('nome', $grupo->nome) }}" required>
        </div>
        <div class="form-item">
            <label for="descricao">Descrição do grupo: </label>
            <input type="text" name="descricao" value="{{ old('descricao', $grupo->descricao) }}">
        </div>
        <div class="form-item">
            <label for="descricao">Líder: </label>
            <select name="lider_id" id="" required>
                <option value="">Selecione um membro: </option>
                @foreach ($membros as $item)
                    <option value="{{$item->id}}" @selected(old('lider_id', $grupo->lider_id) == $item->id)>{{$item->nome}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-item">
            <label for="descricao">Co-líder: </label>
            <select name="colider_id" id="" required>
                <option value="">Selecione um membro: </option>
                @foreach ($membros as $item)
                    <option value="{{$item->id}}" @selected(old('colider_id', $grupo->colider_id) == $item->id)>{{$item->nome}}</option>
                @endforeach
            </select>
        </div>

        @if($mostrarSelecaoSetor)
        <div class="form-item">
            <label for="setor_id">Setor vinculado:</label>
            <select name="setor_id" id="setor_id" class="select2" data-placeholder="Selecione um setor">
                <option value="">Nenhum setor</option>
                @foreach($setores as $setor)
                    <option value="{{ $setor->id }}" @selected($setorSelecionado == $setor->id)>{{ $setor->nome }}</option>
                @endforeach
            </select>
        </div>
        @endif
        
        <div class="form-options">
            <button class="btn" type="submit"><i class="bi bi-arrow-clockwise"></i> Atualizar Grupo</button>
            <button type="button" class="btn danger" onclick="handleSubmit(event, document.getElementById('delete-grupo-{{ $grupo->id }}'), 'Deseja realmente excluir este grupo?')"><i class="bi bi-trash"></i> Excluir</button>
            <button type="button" class="btn" onclick="fecharJanelaModal()"><i class="bi bi-x-circle"></i> Cancelar</button>
        </div>
    </div>
</form>

<form id="delete-grupo-{{ $grupo->id }}" action="{{ route('grupos.destroy', $grupo->id) }}" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
