@extends('layout.main')

@extends('name')

@section('content')

<h1>Configurações Gerais</h1>
<form action="" method="POST">
    @csrf
    <div>
        <label for="logo">Logo:</label>
        <input type="file" id="logo" name="logo" placeholder="Imagem para o logo do site">
    </div>
    <div>
        <label for="banner">Banner:</label>
        <input type="file" id="banner" name="banner" placeholder="Imagem para o banner do login">
    </div>
    <div>
        <label for="corprimaria">Cor Primária:</label>
        <input type="color" id="corprimaria" name="corprimaria" value="#000000">
    </div>
    <div>
        <label for="corsecundaria">Cor Secundária:</label>
        <input type="color" id="corsecundaria" name="corsecundaria" value="#FFFFFF">
    </div>
    <div>
        <label for="font_family">Fonte de Texto:</label>
        <select name="font_family" id="font_family">
            <option value="Arial, sans-serif">Arial</option>
            <option value="'Times New Roman', serif">Times New Roman</option>
            <option value="'Courier New', monospace">Courier New</option>
        </select>
    </div>
    <div>
        <label for="temalayout">Tema para o Layout:</label>
        <select name="temalayout" id="temalayout">
            <option value="layout1">Day</option>
            <option value="layout2">Night</option>
            <option value="layout3">Classic</option>
            <option value="layout4">Modern</option>
            <option value="layout5">Minimalist</option>
        </select>
    </div>
    <button type="submit">Salvar</button>
</form>

@endsection