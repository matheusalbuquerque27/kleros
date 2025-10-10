const menuRegistry = new Map();
let listenersBound = false;

const DEFAULT_ACTIONS = {
    print: () => window.print(),
    back: () => window.history.back(),
    redirect: (button) => {
        const url = button?.dataset?.url;
        if (!url) {
            console.error('options-menu: atributo data-url não encontrado para a ação "redirect".', button);
            return;
        }
        window.location.href = url;
    },
};

const escapeSelector = (value) => {
    if (typeof CSS !== 'undefined' && typeof CSS.escape === 'function') {
        return CSS.escape(value);
    }
    return String(value);
};

function resolveMenuId(trigger) {
    return trigger.dataset.optionsTarget || trigger.dataset.optionsTrigger || null;
}

function getMenuElement(menuId, root) {
    if (!menuId) {
        return null;
    }

    if (root && typeof root.querySelector === 'function') {
        const scoped = root.querySelector(`#${escapeSelector(menuId)}`);
        if (scoped) {
            return scoped;
        }
    }

    return document.getElementById(menuId);
}

function toggleMenu(menu, trigger, open) {
    if (!menu || !trigger) {
        return;
    }

    if (open) {
        menu.hidden = false;
        menu.classList.add('is-open');
        trigger.setAttribute('aria-expanded', 'true');
    } else {
        menu.hidden = true;
        menu.classList.remove('is-open');
        trigger.setAttribute('aria-expanded', 'false');
    }
}

function closeAll(exceptId = null) {
    menuRegistry.forEach((entry, id) => {
        if (id === exceptId) {
            return;
        }
        toggleMenu(entry.menu, entry.trigger, false);
    });
}

function handleAction(button) {
    if (!button) {
        return;
    }

    const action = button.dataset.action;
    const handler = DEFAULT_ACTIONS[action];
    if (handler) {
        handler(button);
        return;
    }

    const event = new CustomEvent('options-menu:action', {
        detail: { action, trigger: button },
    });
    document.dispatchEvent(event);
}

function bindGlobalListeners() {
    if (listenersBound) {
        return;
    }
    listenersBound = true;

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-options-target], [data-options-trigger]');
        if (trigger) {
            const menuId = resolveMenuId(trigger);
            const entry = menuRegistry.get(menuId);
            if (!entry) {
                return;
            }

            event.preventDefault();
            const willOpen = entry.menu.hidden;
            closeAll(menuId);
            toggleMenu(entry.menu, entry.trigger, willOpen);
            return;
        }

        const menu = event.target.closest('.options-menu');
        if (menu) {
            const actionButton = event.target.closest('[data-action]');
            if (actionButton) {
                event.preventDefault();
                const entry = menuRegistry.get(menu.id);
                toggleMenu(entry?.menu, entry?.trigger, false);
                handleAction(actionButton);
            }
            return;
        }

        closeAll();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeAll();
        }
    });
}

function registerTrigger(trigger, root) {
    if (trigger.dataset.optionsInitialized) {
        return;
    }

    const menuId = resolveMenuId(trigger);
    if (!menuId) {
        return;
    }

    const menu = getMenuElement(menuId, root);
    if (!menu) {
        console.warn(`options-menu: elemento com id "${menuId}" não encontrado.`);
        return;
    }

    trigger.dataset.optionsInitialized = 'true';
    trigger.setAttribute('aria-haspopup', 'true');
    trigger.setAttribute('aria-expanded', 'false');

    if (typeof menu.hidden === 'undefined') {
        menu.hidden = true;
    }

    menu.hidden = menu.hidden !== false;

    menuRegistry.set(menuId, { menu, trigger });
}

export function initOptionsMenus(root = document) {
    if (typeof document === 'undefined') {
        return;
    }

    const scope = root || document;
    const triggers = scope.querySelectorAll('[data-options-target], [data-options-trigger]');
    triggers.forEach((trigger) => registerTrigger(trigger, scope));

    bindGlobalListeners();
}

if (typeof window !== 'undefined') {
    window.initOptionsMenus = initOptionsMenus;
}
