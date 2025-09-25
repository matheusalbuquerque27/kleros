<h1>Nova Pesquisa</h1>
<div class="info">
    <h3>Criar pesquisa para a congregação</h3>
    <form action="{{ route('pesquisas.store') }}" method="POST">
        @csrf
        <div class="form-control">
            <div class="form-item">
                <label for="titulo">Título</label>
                <input type="text" name="titulo" id="titulo" required value="{{ old('titulo') }}">
            </div>
            <div class="form-item">
                <label for="descricao">Descrição</label>
                <textarea name="descricao" id="descricao" rows="4">{{ old('descricao') }}</textarea>
            </div>
            <div class="form-item">
                <label for="criada_por">Responsável</label>
                <select name="criada_por" id="criada_por" required>
                    <option value="">Selecione um membro</option>
                    @foreach($membros as $membro)
                        <option value="{{ $membro->id }}" @selected(old('criada_por') == $membro->id)>{{ $membro->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-item">
                <label for="data_inicio">Data de início</label>
                <input type="date" name="data_inicio" id="data_inicio" value="{{ old('data_inicio') }}">
            </div>
            <div class="form-item">
                <label for="data_fim">Data de encerramento</label>
                <input type="date" name="data_fim" id="data_fim" value="{{ old('data_fim') }}">
            </div>
            <div class="form-options">
                <button type="submit" class="btn"><i class="bi bi-plus-circle"></i> Criar pesquisa</button>
                <button type="button" class="btn" onclick="fecharJanelaModal()"><i class="bi bi-x-circle"></i> Cancelar</button>
            </div>
        </div>
    </form>
</div>
