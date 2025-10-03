<h1>Criar Projeto</h1>
<div class="info">
    <h3>Registro</h3>
    <form action="{{ route('projetos.store') }}" method="post">
        @csrf
        <div class="form-control">
            <div class="form-item">
                <label>Nome do projeto</label>
                <input type="text" name="nome" required maxlength="255" placeholder="Digite o nome do projeto">
            </div>
            <div class="form-item">
                <label>Cor</label>
                <input type="color" name="cor" value="#1f2937">
            </div>
            <div class="form-item">
                <label for="projeto-para-todos">Visibilidade</label>
                <label class="checkbox-pill" for="projeto-para-todos">
                    <input type="checkbox" id="projeto-para-todos" name="para_todos" value="1">
                    <span>Todos os membros</span>
                </label>
            </div>
            <div class="form-options">
                <button type="submit" class="btn"><i class="bi bi-save"></i> Criar</button>
                <button class="btn"><i class="bi bi-x-circle"></i> Cancelar</button>
            </div>
        </div>
    </form>
</div>

<style>
    .checkbox-pill {
        display: inline-flex;
        align-items: center;
        justify-content: right;
        gap: 0.6rem;
        padding: 0.45rem 0.9rem;
        cursor: pointer;
        font-size: 1rem;
        user-select: none;
        border-radius: var(--border-style);

    }

    .checkbox-pill input[type="checkbox"] {
        width: 2rem;
        height: 1.2rem;
        accent-color: var(--secondary-color);
    }
</style>
