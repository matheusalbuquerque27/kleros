<h1>Agendar Culto</h1>
<div class="info">
    <form action="{{ route('cultos.store') }}" method="post">
        @csrf

        <div class="tabs">
            <ul class="tab-menu">
                <li class="active" data-tab="culto-registro"><i class="bi bi-journal-text"></i> Registro</li>
                <li data-tab="culto-escalas"><i class="bi bi-diagram-3"></i> Escalas</li>
            </ul>

            <div class="tab-content card">
                <div id="culto-registro" class="tab-pane form-control active">
                    <div class="form-item">
                        <label for="data_culto">Data do culto: </label>
                        <input type="date" name="data_culto" id="data_culto" value="{{ old('data_culto') }}" required>
                    </div>

                    <div class="form-item">
                        <label for="preletor">Preletor: </label>
                        <input type="text" name="preletor" id="preletor" value="{{ old('preletor') }}" required>
                    </div>

                    <div class="form-item">
                        <label for="evento_id">Evento: </label>
                        <select name="evento_id" id="evento_id">
                            <option value="">Selecione um evento cadastrado</option>
                            <option value="">Nenhum</option>
                            @if($eventos)
                                @foreach ($eventos as $item)
                                    <option value="{{ $item->id }}" @selected(old('evento_id') == $item->id)>{{ $item->titulo }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-item">
                        <div class="card">
                            <p>Não encontrou o evento? <a onclick="abrirJanelaModal('{{ route('eventos.form_criar') }}')" class="link-standard">Cadastrar aqui</a></p>
                        </div>
                    </div>
                </div>

                <div id="culto-escalas" class="tab-pane form-control">
                    <div class="card">
                        <p><i class="bi bi-info-circle"></i> Salve o culto para adicionar escalas específicas.</p>
                    </div>
                </div>
            </div>

            <div class="form-options">
                <button class="btn" type="submit"><i class="bi bi-plus-circle"></i> Registrar Culto</button>
                <button type="button" class="btn" onclick="window.history.back()"><i class="bi bi-arrow-return-left"></i> Voltar</button>
            </div>
        </div>
    </form>
</div>
