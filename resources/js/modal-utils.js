export function initModalScripts(container) {
                
    // --- controle de destinatários ---
    const selectDest = container.querySelector('#destinatarios');
    const divSelecionados = container.querySelector('#selecionados');

    if (selectDest && divSelecionados) {
        selectDest.addEventListener('change', function () {
            divSelecionados.style.display = (this.value === "0") ? 'block' : 'none';
        });
        selectDest.dispatchEvent(new Event('change'));
    }

    // --- inicialização do Select2 ---
    if (typeof $ !== 'undefined' && $.fn.select2) {
        const $grupos = $(container).find('#grupos');
        const $membros = $(container).find('#membros');

        if ($membros.length) {
            $membros.select2({
                placeholder: "Selecione os membros",
                allowClear: true,
                width: '100%',
                dropdownParent: $(container)
            });

            // força o placeholder no input interno
            $membros.data('select2').$selection.find('input.select2-search__field')
                .attr('placeholder', 'Selecione os membros');
        }

        if ($grupos.length) {
            $grupos.select2({
                placeholder: "Selecione os grupos",
                allowClear: true,
                width: '100%',
                dropdownParent: $(container)
            });

            $grupos.data('select2').$selection.find('input.select2-search__field')
                .attr('placeholder', 'Selecione os grupos');
        }
    }
}
