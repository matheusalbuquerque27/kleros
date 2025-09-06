<div class="container">
    <h1>Novo Grupo</h1>
    <form action="/grupos" method="post">
        @csrf
        <div class="form-control">
            <div class="form-item">
                <label for="nome">Nome do grupo: </label>
                <input type="text" name="nome" placeholder="Nome do grupo">
            </div>
            <div class="form-item">
                <label for="descricao">Descrição do grupo: </label>
                <input type="text" name="descricao" placeholder="Descrição do grupo">
            </div>
            <div class="form-item">
                <label for="descricao">Líder: </label>
                <select name="lider_id" id="" required>
                    <option value="">Selecione um membro: </option>
                    @foreach ($membros as $item)
                        <option value="{{$item->id}}">{{$item->nome}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-item">
                <label for="descricao">Co-líder: </label>
                <select name="colider_id" id="" required>
                    <option value="">Selecione um membro: </option>
                    @foreach ($membros as $item)
                        <option value="{{$item->id}}">{{$item->nome}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-options">
                <button class="btn" type="submit"><i class="bi bi-plus-circle"></i> Adicionar Grupo</button>
                <a href="/cadastros#grupos"><button type="button" class="btn"><i class="bi bi-x-circle"></i> Cancelar</button></a>
            </div>
        </div>
    </form>
</div>