<div class="destaques-banner">
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            @foreach ($destaques as $item)
                <div class="swiper-slide">
                    <div class="slide-content">
                        @if (!empty($item['media_url']))
                            <img src="{{ $item['media_url'] }}" alt="Imagem" class="slide-image">
                        @endif
                        <div class="slide-text">
                            <a href="{{ $item['link'] }}" target="_blank" class="slide-title" title="{{ $item['title'] }}">
                                {{ strlen(strip_tags($item['titulo'])) > 80 ? substr(strip_tags($item['titulo']), 0, 77) . '...' : strip_tags($item['titulo']) }}                            </a>
                            <span class="slide-date">{{ $item['publicado_em'] }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>