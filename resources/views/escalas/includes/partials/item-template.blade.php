<div class="escala-item" data-index="__INDEX__">
    <details class="escala-accordion">
        <summary>
            <div class="escala-summary-content">
                <span class="escala-summary-title" data-escala-funcao>Definir função</span>
                <span class="escala-summary-funcao" data-escala-responsavel>Responsável não definido</span>
            </div>
            <span class="escala-summary-icon"><i class="bi bi-chevron-down"></i></span>
        </summary>
        <div class="escala-accordion-body">
            <p class="hint"><span data-escala-order>Item</span> - Defina a função e o responsável por esta escala.</p>

            <div class="form-item">
                <label for="funcao-__INDEX__" data-escala-label="funcao">Função:</label>
                <input type="text" name="itens[__INDEX__][funcao]" id="funcao-__INDEX__" placeholder="Ex: Vocal, Recepção" data-escala-field="funcao" required>
            </div>

            <div class="form-item">
                <label for="membro-__INDEX__" data-escala-label="membro_id">Membro designado:</label>
                <select name="itens[__INDEX__][membro_id]" id="membro-__INDEX__" class="select2" data-placeholder="Selecione um membro" data-escala-field="membro_id">
                    <option value="">Sem membro designado</option>
                    @foreach($membros as $membro)
                        <option value="{{ $membro->id }}">{{ $membro->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-item">
                <label for="responsavel-__INDEX__" data-escala-label="responsavel_externo">Responsável externo:</label>
                <input type="text" name="itens[__INDEX__][responsavel_externo]" id="responsavel-__INDEX__" placeholder="Informe caso não seja membro" data-escala-field="responsavel_externo">
            </div>

            <div class="form-options">
                <button type="button" class="btn danger btn-remover-item"><i class="bi bi-trash"></i> Remover item</button>
            </div>
        </div>
    </details>
</div>
