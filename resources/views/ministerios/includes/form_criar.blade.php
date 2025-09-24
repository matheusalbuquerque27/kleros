<h1>Adicionar Ministério</h1>
<div class="info">
    <h3>Registrar</h3>
    <form action="/ministerios" method="post">
        @csrf
        <div class="form-control">
            <div class="form-item">
                <label for="nome">Nome do ministério: </label>
                <input type="text" name="titulo" placeholder="Nome do grupo">
            </div>
            <div class="form-item">
                <label for="nome">Sigla/Abreviação: </label>
                <input type="text" name="sigla" placeholder="Abreviação (opcional)">
            </div>
            <div class="form-item">
                <label for="nome">Nome do ministério: </label>
                <input type="text" name="descricao" placeholder="Descrição do grupo">
            </div>
            <div class="form-options">
                <button class="btn" type="submit"><i class="bi bi-plus-circle"></i> Registrar Ministério</button>
                <button type="button" onclick="fecharJanelaModal()" class="btn"><i class="bi bi-x-circle"></i> Cancelar</button>
            </div>
        </div>
    </form>
</div>