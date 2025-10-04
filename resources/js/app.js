import './bootstrap';
import { initModalScripts } from './modal-utils.js';
import { initOptionsMenus } from './options-menu.js';

window.initModalScripts = initModalScripts;

document.addEventListener('DOMContentLoaded', () => {
    initModalScripts(document);
    initOptionsMenus(document);
});
