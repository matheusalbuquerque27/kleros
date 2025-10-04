<h1>Editar Evento</h1>
<form action="{{route('eventos.update', $evento->id)}}" method="post">
    @csrf
    @method('PUT')
    <div class="form-control">
        <div class="form-item">
            <label for="titulo">Título: </label>
            <input type="text" name="titulo" id="titulo" placeholder="Título do evento" value="{{ $evento->titulo }}">
        </div>
        <div class="form-item">
            <label for="grupo_id">Grupo responsável: </label>
            <select name="grupo_id" id="grupo_id">
                <option value="">Grupo responsável</option>
                @foreach ($grupos as $item)
                <option value="{{$item->id}}" {{ $evento->agrupamento_id == $item->id ? 'selected' : '' }}>{{$item->nome}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-item">
            <label for="evento_recorrente">Natureza do evento: </label>
            <div class="form-square">
                <div>
                    <input type="radio" id="especifico" name="evento_recorrente" value="0" {{ !$evento->recorrente ? 'checked' : '' }}>
                    <label for="especifico">Específico (cadastro individual)</label>
                </div>
                <div>
                    <input type="radio" id="recorrente" name="evento_recorrente" value="1" {{ $evento->recorrente ? 'checked' : '' }}>
                    <label for="recorrente">Recorrente (cadastro único)</label>
                </div>
            </div>
        </div>
        <div class="form-item">
            <label for="data_inicio">Data de início: </label>
            <input type="date" name="data_inicio" id="data_inicio" value="{{ \Carbon\Carbon::parse($evento->data_inicio)->format('Y-m-d') }}">
        </div>
        <div class="form-item">
            <label for="data_encerramento">Data de encerramento: </label>
            <input type="date" name="data_encerramento" id="data_encerramento" value="{{ \Carbon\Carbon::parse($evento->data_encerramento)->format('Y-m-d') }}">
        </div>
        <div class="form-item">
            <label for="descricao">Descrição: </label>
            <textarea name="descricao" placeholder="Descrição do evento">{{ $evento->descricao }}</textarea>
        </div>
        <div class="form-item">
            <label for="requer_inscricao">Tipo de Acesso: </label>
            <div class="form-square">
                <div>
                    <input type="radio" id="publico" name="requer_inscricao" value="0" {{ !$evento->requer_inscricao ? 'checked' : '' }}>
                    <label for="publico">Público - Livre</label>
                </div>
                <div>
                    <input type="radio" id="privado" name="requer_inscricao" value="1" {{ $evento->requer_inscricao ? 'checked' : '' }}>
                    <label for="privado">Privado - Requer confirmação</label>
                </div>
            </div>
        </div>
        <div class="form-options">
            <button class="btn" type="submit"><i class="bi bi-arrow-clockwise"></i> Atualizar</button>
            <button onclick="fecharJanelaModal()" type="button" class="btn"><i class="bi bi-x-circle"></i> Cancelar</button>
        </div>
    </div>
</form>

@push('scripts')
    
<script>
    (function() {
        function toggleGeracaoCultos() {
            // Se "Recorrente" (valor 1) estiver selecionado
            if ($('input[name="evento_recorrente"]:checked').val() === '1') {
                $('.geracao_cultos').hide();
                // Força a seleção de "Manual" (valor 0) para geracao_cultos
                $('input[name="geracao_cultos"][value="0"]').prop('checked', true);
            } else {
                // Caso contrário, exibe a opção
                $('.geracao_cultos').show();
            }
        }

        $('input[name="evento_recorrente"]').on('change', toggleGeracaoCultos);
        toggleGeracaoCultos(); // Executa ao carregar para definir o estado inicial
    })();
</script>

@endpush