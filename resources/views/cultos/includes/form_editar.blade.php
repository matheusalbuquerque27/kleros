<h1>Editar Culto</h1>
<form action="{{route('cultos.update', $culto->id)}}" method="post">
    @csrf
    @method('PUT')
    <div class="form-control">
        <div class="form-item">
            <label for="data_culto">Data do culto: </label>
            <input type="date" name="data_culto" id="" value="{{$culto->data_culto}}" required>
        </div>
        <div class="form-item">
            <label for="preletor">Preletor: </label>
            <input type="text" name="preletor" id="" value="{{$culto->preletor}}" required>
        </div>
        <div class="form-item">
            <label for="evento">Evento: </label>
            <select name="evento_id" id="">
                <option value="">Selecione um evento cadastrado</option>
                <option value="">Nenhum</option>
                @if($eventos)
                    @foreach ($eventos as $item)
                    <option value="{{$item->id}}" @selected($culto->evento_id == $item->id)>{{$item->titulo}}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="form-item">
            <div class="card">
                <p>Não encontrou o evento? <a onclick="abrirJanelaModal('{{route('eventos.form_criar')}}')" class="link-standard">Cadastrar aqui</a></p>
            </div>
        </div>
        <div class="form-options">
            <button class="btn" type="submit"><i class="bi bi-arrow-clockwise"></i> Atualizar Culto</button>
            <button type="button" class="btn" onclick="fecharJanelaModal()"><i class="bi bi-arrow-return-left"></i> Voltar</button>
        </div>
    </div>
</form>
