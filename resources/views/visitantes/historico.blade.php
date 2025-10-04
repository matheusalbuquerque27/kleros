@extends('layouts.main')

@section('title', $congregacao->nome_reduzido . ' | ' . $appName)

@section('content')

<div class="container">
    <h1>Histórico de Visitantes</h1>
    <div class="info nao-imprimir">
        <h3>Filtrar por período</h3>
        <div class="search-panel">
            <div class="search-panel-item">
                <label>Nome: </label>
                <input type="text" name="" id="nome" placeholder="Nome do visitante">
            </div>
            <div class="search-panel-item">
                <label>Data: </label>
                <input type="date" name="" id="data_visita">
            </div>
            <div class="search-panel-item">
                <button class="" id="btn_filtrar"><i class="bi bi-search"></i> Procurar</button>
                <button class="" type="button" id="btn_exportar_visitantes" data-export-url="{{ route('visitantes.export') }}"><i class="bi bi-file-arrow-up"></i> Exportar</button>
                <button class="options-menu__trigger" type="button" data-options-target="visitantesHistoricoOptions"><i class="bi bi-three-dots-vertical"></i> Opções</button>
            </div>
        </div>
        <div class="options-menu" id="visitantesHistoricoOptions" hidden>
            <button type="button" class="btn" data-action="print"><i class="bi bi-printer"></i> Imprimir</button>
            <button type="button" class="btn" data-action="back"><i class="bi bi-arrow-return-left"></i> Voltar</button>
        </div>
    </div>
    
    <div class="list">
        <div class="list-title">
            <div class="item-1">
                <b>Nome</b>
            </div>
            <div class="item-1">
                <b>Data da visita</b>
            </div>
            <div class="item-1">
                <b>Telefone</b>
            </div>
            <div class="item-1">
                <b>Situação</b>
            </div>
        </div><!--list-item-->

        <div id="content">
            @foreach ($visitantes as $item)
            <a href="{{route('visitantes.exibir', $item->id)}}"><div class="list-item">
                <div class="item item-1">
                    <p><i class="bi bi-person-raised-hand"></i> {{$item->nome}}</p>
                </div>
                <div class="item item-1">
                    <p>{{$item->data_visita}}</p>
                </div>
                <div class="item item-1">
                    <p>{{ $item->telefone }}
                        <span onclick="copiarTexto(event, '{{ $item->telefone }}')" 
                            style="cursor:pointer; position:relative; padding-left: .2em;">
                            <i class="bi bi-copy"></i>
                            <span class="tooltip-copiar">Copiado!</span>
                        </span>
                    </p>
                </div>
                <div class="item item-1">
                    <p>{{$item->sit_visitante->titulo}}</p>
                </div>
            </div></a><!--list-item-->
            @endforeach       
        </div>
        @if ($visitantes->total() > 10)
            <div class="pagination">
                {{ $visitantes->links('pagination::default') }}
            </div>
        @endif
    </div>
    
    <h3>Últimos Visitantes</h3>

        <div class="form-control">
            <div class="card-container">
                    <div class="card">
                        <p class="card-title">Adalberto</p>
                        <p class="card-date">24/12</p>
                    </div>
                </div>
            <div class="form-item">
                <div class="form-options">
                    <button class="btn" id="btn_cadastrar"><i class="bi bi-plus-circle"></i> Cadastrar</button>
                </div>
            </div>
        </div>
    
</div>

<style>
.tooltip-copiar {
    position: absolute;
    top: -25px;
    left: 0;
    background: #333;
    color: #fff;
    font-size: 0.8em;
    padding: 3px 6px;
    border-radius: 4px;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
}
.tooltip-copiar.show {
    opacity: 1;
}
</style>

@endsection

@push('scripts')

<script>
    $(document).ready(function(){

        $('#nome').on('keydown', function(){
            pesquisarVisitantes();
        });

        $('#btn_filtrar').on('click', function(event) {
            event.preventDefault();
            pesquisarVisitantes();
        });

        $('#btn_exportar_visitantes').on('click', function(event) {
            event.preventDefault();

            const url = $(this).data('exportUrl');
            const params = new URLSearchParams();
            const nome = $('#nome').val();
            const dataVisita = $('#data_visita').val();

            if (nome) {
                params.append('nome', nome);
            }
            if (dataVisita) {
                params.append('data_visita', dataVisita);
            }

            const finalUrl = params.toString() ? `${url}?${params.toString()}` : url;
            window.location.href = finalUrl;
        });
    })
</script>
    
<script>
    function pesquisarVisitantes(){
        const _token = $('meta[name="csrf-token"]').attr('content');
        let data_visita = $('#data_visita').val();
        let nome = $('#nome').val();

        $.post('/visitantes/search', { _token, data_visita, nome }, function(response){
            var view = response.view

            $('#content').html(view)
        }).catch((err) => {console.log(err)})
    };
</script>

<script>
    function copiarTexto(event, texto) {
        event.preventDefault();
        event.stopPropagation();

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(texto).then(() => {
                mostrarTooltip(event);
            }).catch(err => {
                console.error("Erro ao copiar:", err);
            });
        } else {
            // fallback
            let textArea = document.createElement("textarea");
            textArea.value = texto;
            textArea.style.position = "fixed";
            textArea.style.opacity = 0;
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                document.execCommand('copy');
                mostrarTooltip(event);
            } catch (err) {
                console.error("Erro no fallback de cópia:", err);
            }
            document.body.removeChild(textArea);
        }
    }

    function mostrarTooltip(event) {
        const tooltip = event.currentTarget.querySelector(".tooltip-copiar");
        tooltip.classList.add("show");
        setTimeout(() => {
            tooltip.classList.remove("show");
        }, 1500);
    }
</script>
    
@endpush
