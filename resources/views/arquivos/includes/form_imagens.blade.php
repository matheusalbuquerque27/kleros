<h1>Gestor de Imagens</h1>
<form action="/arquivos" enctype="multipart/form-data" method="post">
    @csrf
    <div class="acervo">
        @foreach ($arquivos as $item)
        <div class="card-arquivo">
            <img src="{{ asset('storage/'.$item->caminho) }}" alt="{{$item->nome}}">
            <div class="conteudo-arquivo">
                <span class="titulo">{{$item->nome}}</span>
                <div class="options">
                    <a href="#" class="botao delete-img" id="{{$item->id}}" title="Excluir"><i class="bi bi-trash"></i></a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="center w100">
        <button class="btn inactive" id="selected" type="button"><span for="selected"><i class="bi bi-check2"></i> Selecionar</span></button>
        <label class="btn" for="upload-img"><i class="bi bi-upload"></i> Upload</label>
        <input type="file" name="upload" id="upload-img" class="hidden">
    </div>
</form>

