<h1>Editar Grupo</h1>
<form action="{{route('grupos.update', $grupo->id)}}" method="post">
    @csrf
    @method('PUT')
    <div class="form-control">
        <div class="form-item">
            <label for="nome">Nome do grupo: </label>
            <input type="text" name="nome" value="{{$grupo->nome}}">
        </div>
        <div class="form-item">
            <label for="descricao">Descrição do grupo: </label>
            <input type="text" name="descricao" value="{{$grupo->descricao}}">
        </div>
        <div class="form-item">
            <label for="descricao">Líder: </label>
            <select name="lider_id" id="" required>
                <option value="">Selecione um membro: </option>
                @foreach ($membros as $item)
                    <option value="{{$item->id}}" @selected($item->id == $grupo->lider_id)>{{$item->nome}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-item">
                <label for="descricao">Co-líder: </label>
                <select name="colider_id" id="" required>
                    <option value="">Selecione um membro: </option>
                    @foreach ($membros as $item)
                        <option value="{{$item->id}}" @selected($item->id == $grupo->colider_id)>{{$item->nome}}</option>
                    @endforeach
                </select>
            </div>
        <div class="form-options">
            <button class="btn" type="submit"><i class="bi bi-check"></i> Atualizar Grupo</button>
            <button type="button" class="btn" onclick="fecharJanelaModal()"><i class="bi bi-x-circle"></i> Cancelar</button>
        </div>
    </div>
</form>