@php
    $funcao = $item['funcao'] ?? '';
    $membroSelecionado = $item['membro_id'] ?? null;
    $responsavelExterno = $item['responsavel_externo'] ?? '';
    $numeroItem = $index + 1;
    $membroEncontrado = $membros->firstWhere('id', $membroSelecionado);
    $textoResponsavel = $membroEncontrado?->nome ?? ($responsavelExterno !== '' ? $responsavelExterno : 'Responsável não definido');
@endphp

<div class="escala-item" data-index="{{ $index }}">
    <details class="escala-accordion">
        <summary>
            <div class="escala-summary-content">
                <span class="escala-summary-title" data-escala-funcao>{{ $funcao !== '' ? $funcao : 'Definir função' }}</span>
                <span class="escala-summary-funcao" data-escala-responsavel>{{ $textoResponsavel }}</span>
            </div>
            <span class="escala-summary-icon"><i class="bi bi-chevron-down"></i></span>
        </summary>
        <div class="escala-accordion-body">
            <p class="hint"><span data-escala-order>Item {{ $numeroItem }}</span> - Defina a função e o responsável por esta escala.</p>

            <div class="form-item">
                <label for="funcao-{{ $index }}" data-escala-label="funcao">Função:</label>
                <input type="text" name="itens[{{ $index }}][funcao]" id="funcao-{{ $index }}" value="{{ $funcao }}" placeholder="Ex: Vocal, Recepção" data-escala-field="funcao" required>
            </div>

            <div class="form-item">
                <label for="membro-{{ $index }}" data-escala-label="membro_id">Membro designado:</label>
                <select name="itens[{{ $index }}][membro_id]" id="membro-{{ $index }}" class="select2" data-placeholder="Selecione um membro" data-escala-field="membro_id">
                    <option value="">Sem membro designado</option>
                    @foreach($membros as $membro)
                        <option value="{{ $membro->id }}" @selected($membroSelecionado == $membro->id)>{{ $membro->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-item">
                <label for="responsavel-{{ $index }}" data-escala-label="responsavel_externo">Responsável externo:</label>
                <input type="text" name="itens[{{ $index }}][responsavel_externo]" id="responsavel-{{ $index }}" value="{{ $responsavelExterno }}" placeholder="Informe caso não seja membro" data-escala-field="responsavel_externo">
            </div>

            <div class="form-options">
                <button type="button" class="btn danger btn-remover-item"><i class="bi bi-trash"></i> Remover item</button>
            </div>
        </div>
    </details>
</div>
