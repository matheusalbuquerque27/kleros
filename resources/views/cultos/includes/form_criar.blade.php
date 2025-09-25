<h1>Agendar Culto</h1>
<div class="info">
    <h3>Agendamento de Culto</h3>
    <form action="/cultos" method="post">
        @csrf
        <div class="form-control">
            <div class="form-item">
                <label for="data_culto">Data do culto: </label>
                <input type="date" name="data_culto" id="" required>
            </div>
            <div class="form-item">
                <label for="preletor">Preletor: </label>
                <input type="text" name="preletor" id="" required>
            </div>
            <div class="form-item">
                <label for="evento">Evento: </label>
                <select name="evento_id" id="">
                    <option value="">Selecione um evento cadastrado</option>
                    <option value="">Nenhum</option>
                    @if($eventos)
                        @foreach ($eventos as $item)
                        <option value="{{$item->id}}">{{$item->titulo}}</option>
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
                <button class="btn" type="submit"><i class="bi bi-plus-circle"></i> Registrar Culto</button>
                <button type="button" class="btn" onclick="window.history.back()"><i class="bi bi-arrow-return-left"></i> Voltar</button>
            </div>
        </div>
    </form>
</div>
