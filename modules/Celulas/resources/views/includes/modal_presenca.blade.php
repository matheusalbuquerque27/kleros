<h1>Adicionar Presente</h1>
<div class="info">
    <h3>Registro de presença do encontro</h3>
    <form
        class="form-control modal-presenca-form nao-imprimir"
        x-data="{
            tipo: 'membro',
            carregando: false,
            async enviar(event) {
                event.preventDefault();
                const form = event.target;

                if (this.tipo === 'membro') {
                    const membro = form.querySelector('#modal-presenca-membro')?.value;
                    if (!membro) { alert('Selecione um membro.'); return; }
                } else {
                    const nome = form.querySelector('#modal-presenca-nome')?.value?.trim();
                    if (!nome) { alert('Informe o nome do visitante.'); return; }
                }

                this.carregando = true;
                const csrfToken = form.querySelector('input[name=_token]')?.value
                    || document.querySelector('meta[name=csrf-token]')?.content;

                try {
                    const resp = await fetch('{{ route('celulas.encontros.presentes.registrar') }}', {
                        method: 'POST',
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        body: new FormData(form),
                    });
                    const json = await resp.json();
                    if (resp.ok && json.success) {
                        fecharJanelaModal();
                        window.location.reload();
                    } else {
                        const erros = json.errors
                            ? Object.values(json.errors).flat().join('\n')
                            : (json.message || 'Erro ao registrar presença.');
                        alert(erros);
                    }
                } catch (err) {
                    console.error(err);
                    alert('Falha na comunicação com o servidor.');
                } finally {
                    this.carregando = false;
                }
            }
        }"
        @submit.prevent="enviar($event)"
    >
        @csrf
        <input type="hidden" name="celula_id" value="{{ $celulaId }}">
        <input type="hidden" name="data_encontro" value="{{ $dataEncontro }}">
        <input type="hidden" name="return_to" value="{{ $panelUrl }}">
        <input type="hidden" name="tipo_participante" :value="tipo">

        <div class="form-item">
            <label>Tipo de participante</label>
            <div class="form-square modal-presenca-switch">
                <label>
                    <input type="radio" name="_tipo_participante_radio" value="membro" x-model="tipo">
                    <span>Membro</span>
                </label>
                <label>
                    <input type="radio" name="_tipo_participante_radio" value="visitante" x-model="tipo">
                    <span>Visitante</span>
                </label>
            </div>
        </div>

        <div x-show="tipo === 'membro'">
            <div class="form-item">
                <label for="modal-presenca-membro">Selecione o membro</label>
                <select id="modal-presenca-membro" name="membro_id" class="painel-select2" data-placeholder="Buscar por nome">
                    <option value=""></option>
                    @foreach ($membros as $membro)
                        <option value="{{ $membro->id }}">{{ $membro->nome }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div x-show="tipo === 'visitante'">
            <div class="form-item">
                <label for="modal-presenca-nome">Nome do visitante <span style="color:red">*</span></label>
                <input type="text" id="modal-presenca-nome" name="visitante_nome" placeholder="Informe o nome completo">
            </div>

            <div class="form-item">
                <label for="modal-presenca-telefone">Telefone</label>
                <input type="tel" id="modal-presenca-telefone" name="visitante_telefone" placeholder="(00) 00000-0000">
            </div>

            <div class="form-item">
                <label for="modal-presenca-data">Data da visita</label>
                <input type="date" id="modal-presenca-data" name="visitante_data" value="{{ $dataEncontro }}">
            </div>

            <div class="form-item">
                <label for="modal-presenca-situacao">Situação</label>
                <select id="modal-presenca-situacao" name="visitante_situacao">
                    <option value="">Selecione a situação</option>
                    @foreach ($situacoesVisitante as $situacao)
                        <option value="{{ $situacao->id }}">{{ $situacao->titulo }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-item">
                <label for="modal-presenca-observacoes">Observações</label>
                <textarea id="modal-presenca-observacoes" name="visitante_observacoes" rows="3" placeholder="Informações adicionais"></textarea>
            </div>
        </div>

        <div class="form-options">
            <button class="btn" type="submit" :disabled="carregando">
                <i class="bi" :class="carregando ? 'bi-hourglass-split' : 'bi-person-plus'"></i>
                <span x-text="carregando ? 'Registrando...' : 'Registrar presença'"></span>
            </button>
            <button class="btn" type="button" onclick="fecharJanelaModal()">
                <i class="bi bi-x-circle"></i> Cancelar
            </button>
        </div>
    </form>

    <div class="modal-presenca-info card">
        <p><i class="bi bi-people"></i>
            @if ($celula)
                Registro de presença para a célula <strong>{{ $celula->identificacao }}</strong> em {{ \Carbon\Carbon::parse($dataEncontro)->format('d/m/Y') }}.
            @else
                Selecione uma célula antes de adicionar participantes ao encontro.
            @endif
        </p>
    </div>
</div>

<style>
    .modal-presenca-form .form-item {
        margin-bottom: 1rem;
    }

    .modal-presenca-switch {
        display: flex;
        gap: 1.5rem;
    }

    .modal-presenca-info {
        margin-top: 1rem;
    }
</style>
