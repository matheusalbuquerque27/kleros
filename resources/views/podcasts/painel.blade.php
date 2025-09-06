@extends('layouts.main')

@section('title', 'Podcasts | ' . $appName)

@section('content')

<div class="container">
    <h1>Podcasts</h1>
    <div class="info">
        <h3 id="teologia">Aprender ouvindo</h3>
        <div class="noticias-container">
            <div class="noticias-grid">
                @foreach ($podcasts['btcast'] ?? [] as $item)
                    <div class="noticia-card">
                        <img src="{{ $item['imagem_capa'] ?? asset('images/podcast.png')}}" alt="" class="noticia-img">
                         {{-- Se o enclosure for áudio --}}
                        @if(Str::endsWith($item['media_url'], ['.mpeg', '.mp3', '.ogg', '.wav']))
                            <button class="play-central" data-audio="{{ $item['media_url'] }}" data-title="{{ $item['titulo'] }}">
                                <i class="bi bi-play-circle"></i> Ouvir no player
                            </button>
                        @endif
                        <a href="{{ $item['link'] }}" target="_blank" class="noticia-title">
                            {{ $item['titulo'] }}
                        </a>
                        <div class="noticia-date">
                            {{ $item['publicado_em'] }}
                        </div>
                        <div class="noticia-desc">
                            {!! substr(strip_tags($item['descricao'] ?? ''), 0, 200) !!}...
                        </div>
                        <a href="{{ $item['link'] }}" target="_blank" class="noticia-link">
                            Ler mais
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<div class="podcast-menu-bar">
  <div class="podcast-player-container">
    <audio id="podcast-player" class="js-podcast-player" controls></audio>
    <span id="podcast-title" class="podcast-title">Selecione um episódio...</span>
  </div>

  <!-- Menu -->
  <ul class="podcast-menu">
    <li><a href="#teologia" class="play-btn" data-audio="URL_PODCAST_1" data-title="BTCast #101"><i class="bi bi-share"></i></a></li>
    <li><a href="#teologia" class="play-btn" data-audio="URL_PODCAST_1" data-title="BTCast #101"><i class="bi bi-heart"></i></a></li>
  </ul>
</div>
@endsection

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', () => {
  const player = new Plyr('#podcast-player');
  const titleEl = document.getElementById('podcast-title');

  document.querySelectorAll('[data-audio]').forEach(btn => {
    btn.addEventListener('click', e => {
      e.preventDefault();
      const audioUrl = btn.getAttribute('data-audio');
      const audioTitle = btn.getAttribute('data-title') ?? 'Tocando...';

      player.source = {
        type: 'audio',
        sources: [{ src: audioUrl, type: 'audio/mp3' }]
      };

      titleEl.textContent = audioTitle;
      player.play();
    });
  });
});
</script>

@endpush