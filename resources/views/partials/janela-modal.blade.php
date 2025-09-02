<div id="janelaModal" class="modal-overlay" style="display: none;">
    <div class="modal-box">
        <div class="scroll-container">
            <button id="fecharModal" onclick="fecharJanelaModal()" class="fechar-btn" title="Fechar"><i class="bi bi-x-circle-fill"></i></button>
            <div id="conteudoModal">
                <!-- Aqui entra o conteúdo dos includes -->
                @yield('modal-content')
            </div>
        </div>
    </div>
</div>