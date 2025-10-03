<h1>Cadastrar Visitante</h1>
<div class="info">
    <h3>Registro</h3>
    <form action="/visitantes" method="post">
        @csrf
        <div class="form-control">
            <div class="form-item">
                <label for="visitante-nome">Nome completo</label>
                <input type="text" id="visitante-nome" name="nome" value="{{ old('nome') }}" placeholder="Nome do visitante" required>
            </div>
            <div class="form-item">
                <label for="visitante-telefone">Telefone/Celular</label>
                <input type="tel" id="visitante-telefone" name="telefone" value="{{ old('telefone') }}" placeholder="(00) 00000-0000" required>
            </div>
            <div class="form-item">
                <label for="visitante-data">Data da visita</label>
                <input type="date" id="visitante-data" name="data_visita" value="{{ old('data_visita', now()->format('Y-m-d')) }}" required>
            </div>
            <div class="form-item">
                <label for="visitante-situacao">Situação</label>
                <select name="situacao" id="visitante-situacao" required>
                    <option value="">Selecione a situação do visitante</option>
                    @foreach ($situacao_visitante as $item)
                        <option value="{{ $item->id }}" @selected(old('situacao') == $item->id)>{{ $item->titulo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-item">
                <label for="visitante-observacoes">Observações</label>
                <textarea id="visitante-observacoes" name="observacoes" placeholder="Observações importantes">{{ old('observacoes') }}</textarea>
            </div>
            <div class="form-options">
                <button class="btn" type="submit"><i class="bi bi-plus-circle"></i> Salvar Dados</button>
                <a href="/visitantes/historico" class="btn"><i class="bi bi-card-list"></i> Histórico</a>
                <button type="button" class="btn" onclick="fecharJanelaModal()"><i class="bi bi-x-circle"></i> Cancelar</button>
            </div>
        </div>
    </form>

    @if(module_enabled('recados'))
        <a href="/recados/adicionar" class="float-btn" title="Enviar recado"><i class="bi bi-chat-left-dots"></i></a>
    @endif

    <div class="clear"></div>
</div>
