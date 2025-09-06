<div class="container">
    <h1>Adicionar Ministério</h1>
    <form action="{{route('ministerios.update', $ministerio->id)}}" method="post">
        @csrf
        @method('PUT')
        <div class="form-control">
            <div class="form-item">
                <label for="nome">Nome do ministério: </label>
                <input type="text" name="titulo" value="{{$ministerio->titulo}}">
            </div>
            <div class="form-item">
                <label for="nome">Sigla/Abreviação: </label>
                <input type="text" name="sigla" value="{{$ministerio->sigla}}">
            </div>
            <div class="form-item">
                <label for="nome">Descrição do ministério: </label>
                <input type="text" name="descricao" value="{{$ministerio->descricao}}">
            </div>
            <div class="form-options">
                <button class="btn" type="submit"><i class="bi bi-arrow-clockwise"></i> Atualizar Ministério</button>
                <button type="button" onclick="fecharJanelaModal()" class="btn"><i class="bi bi-x-circle"></i> Cancelar</button>
            </div>
        </div>
    </form>
</div>