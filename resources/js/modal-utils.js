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

    // --- gerenciador de participantes das células---
    const participanteTab = container.querySelector('#participantes[data-participantes]');
    if (participanteTab && !participanteTab.dataset.managerInitialized) {
        participanteTab.dataset.managerInitialized = 'true';

        const perPage = Number(participanteTab.dataset.pageSize || 6);
        const hiddenSelect = participanteTab.querySelector('#participantes-hidden');
        const select = participanteTab.querySelector('#participante-select');
        const addButton = participanteTab.querySelector('#btn-add-participante');
        const listContent = participanteTab.querySelector('.participantes-content');
        const pagination = participanteTab.querySelector('.participantes-pagination');
        const fallbackAvatar = participanteTab.dataset.fallbackAvatar || '';

        let currentPage = 1;
        let initialDataRaw = [];

        try {
            initialDataRaw = participanteTab.dataset.participantes ? JSON.parse(participanteTab.dataset.participantes) : [];
        } catch (error) {
            console.error('Falha ao interpretar os participantes iniciais:', error);
            initialDataRaw = [];
        }

        const state = new Map(initialDataRaw.map(item => [String(item.id), item]));

        function syncHiddenOptions() {
            if (!hiddenSelect) return;

            const stateIds = new Set(state.keys());

            Array.from(hiddenSelect.options).forEach(option => {
                if (!stateIds.has(option.value)) {
                    option.remove();
                } else {
                    option.selected = true;
                    stateIds.delete(option.value);
                }
            });

            stateIds.forEach(id => {
                const opt = document.createElement('option');
                opt.value = id;
                opt.selected = true;
                hiddenSelect.appendChild(opt);
            });
        }

        function setOptionAvailability() {
            if (!select) return;

            Array.from(select.options).forEach(option => {
                if (!option.value) {
                    return;
                }
                option.disabled = state.has(option.value);
            });
        }

        function renderPagination(totalItems) {
            if (!pagination) return;

            pagination.innerHTML = '';
            const totalPages = Math.max(1, Math.ceil(totalItems / perPage));
            if (currentPage > totalPages) {
                currentPage = totalPages;
            }

            if (totalPages <= 1) {
                return;
            }

            for (let page = 1; page <= totalPages; page++) {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'page-btn' + (page === currentPage ? ' active' : '');
                button.dataset.page = String(page);
                button.textContent = page;
                pagination.appendChild(button);
            }
        }

        function renderList() {
            if (!listContent) return;

            const items = Array.from(state.values()).sort((a, b) => a.nome.localeCompare(b.nome));
            const totalItems = items.length;
            const totalPages = Math.max(1, Math.ceil(totalItems / perPage));

            if (currentPage > totalPages) {
                currentPage = totalPages;
            }

            const start = (currentPage - 1) * perPage;
            const pageItems = items.slice(start, start + perPage);

            listContent.innerHTML = '';

            if (pageItems.length === 0) {
                const empty = document.createElement('div');
                empty.className = 'card empty-state';
                empty.textContent = 'Nenhum participante adicionado até o momento.';
                listContent.appendChild(empty);
            } else {
                pageItems.forEach(item => {
                    const row = document.createElement('div');
                    row.className = 'list-item participante-row';
                    row.dataset.id = item.id;
                    row.innerHTML = `
                        <div class="item item-2">
                            <p style="display:flex; align-items:center; gap:.5em">
                                <img src="${item.foto || fallbackAvatar}" class="avatar" alt="Avatar">
                                ${item.nome}
                            </p>
                        </div>
                        <div class="item item-1"><p>${item.telefone}</p></div>
                        <div class="item item-2"><p>${item.endereco}</p></div>
                        <div class="item item-1"><p>${item.ministerio}</p></div>
                        <div class="item item-1 participante-actions">
                            <button type="button" class="btn participante-remove" data-id="${item.id}"><i class="bi bi-person-dash"></i> Remover</button>
                        </div>
                    `;
                    listContent.appendChild(row);
                });
            }

            renderPagination(totalItems);
            syncHiddenOptions();
            setOptionAvailability();
        }

        if (addButton && select) {
            addButton.addEventListener('click', () => {
                const option = select.options[select.selectedIndex];
                if (!option || !option.value || option.disabled) {
                    return;
                }

                const id = option.value;
                if (state.has(id)) {
                    select.value = '';
                    return;
                }

                const item = {
                    id,
                    nome: option.dataset.nome || option.textContent.trim(),
                    telefone: option.dataset.telefone || 'Não informado',
                    endereco: option.dataset.endereco || 'Não informado',
                    ministerio: option.dataset.ministerio || 'Não informado',
                    foto: option.dataset.foto || fallbackAvatar,
                };

                state.set(id, item);
                select.value = '';
                currentPage = 1;
                renderList();
            });
        }

        if (listContent) {
            listContent.addEventListener('click', (event) => {
                const button = event.target.closest('.participante-remove');
                if (!button) {
                    return;
                }

                const id = button.dataset.id;
                if (!id) {
                    return;
                }

                state.delete(id);
                renderList();
            });
        }

        if (pagination) {
            pagination.addEventListener('click', (event) => {
                const button = event.target.closest('.page-btn');
                if (!button) {
                    return;
                }

                const page = Number(button.dataset.page) || 1;
                if (page === currentPage) {
                    return;
                }

                currentPage = page;
                renderList();
            });
        }

        renderList();
    }
}
