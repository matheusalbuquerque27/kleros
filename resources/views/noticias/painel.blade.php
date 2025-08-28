@extends('layouts.main')

@section('title', 'Notícias | ' . $appName)

@section('content')

<div class="container">
    <h1>Notícias Cristãs</h1>
    <div class="info">
        <h3>Cristianismo pelo mundo</h3>
        <div class="noticias-container">
            <div class="noticias-grid">
                @foreach ($noticias['guiame'] ?? [] as $noticia)
                    <div class="noticia-card">
                        @if (!empty($noticia['audio_url']))
                            <img src="{{ $noticia['audio_url'] }}" alt="" class="noticia-img">
                        @endif
                        <a href="{{ $noticia['link'] }}" target="_blank" class="noticia-title">
                            {{ $noticia['titulo'] }}
                        </a>
                        <div class="noticia-date">
                            {{ $noticia['publicado_em'] }}
                        </div>
                        <div class="noticia-desc">
                            {!! substr(strip_tags($noticia['descricao'] ?? ''), 0, 200) !!}...
                        </div>
                        <a href="{{ $noticia['link'] }}" target="_blank" class="noticia-link">
                            Ler mais
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        <h3>Atualidades</h3>
        <div class="noticias-container">
            <div class="noticias-grid">
                @foreach ($noticias['gospel+'] ?? [] as $noticia)
                    <div class="noticia-card">
                        @if (!empty($noticia['audio_url']))
                            <img src="{{ $noticia['audio_url'] }}" alt="" class="noticia-img">
                        @endif
                        <a href="{{ $noticia['link'] }}" target="_blank" class="noticia-title">
                            {{ $noticia['titulo'] }}
                        </a>
                        <div class="noticia-date">
                            {{ $noticia['publicado_em'] }}
                        </div>
                        <div class="noticia-desc">
                            {!! substr(strip_tags($noticia['descricao'] ?? ''), 0, 200) !!}...
                        </div>
                        <a href="{{ $noticia['link'] }}" target="_blank" class="noticia-link">
                            Ler mais
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        <h3>Missões Nacionais</h3>
        <div class="noticias-container">
            <div class="noticias-grid">
                @foreach ($noticias['missoesnacionais'] ?? [] as $noticia)
                    <div class="noticia-card">
                        @if (!empty($noticia['audio_url']))
                            <img src="{{ $noticia['audio_url'] }}" alt="" class="noticia-img">
                        @endif
                        <a href="{{ $noticia['link'] }}" target="_blank" class="noticia-title">
                            {{ $noticia['titulo'] }}
                        </a>
                        <div class="noticia-date">
                            {{ $noticia['publicado_em'] }}
                        </div>
                        <div class="noticia-desc">
                            {!! substr(strip_tags($noticia['descricao'] ?? ''), 0, 200) !!}...
                        </div>
                        <a href="{{ $noticia['link'] }}" target="_blank" class="noticia-link">
                            Ler mais
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection