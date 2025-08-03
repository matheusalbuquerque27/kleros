<div class="destaques-banner">
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            @foreach ($destaques as $item)
                <div class="swiper-slide">
                    <div class="slide-content">
                        @if (!empty($item['image']))
                            <img src="{{ $item['image'] }}" alt="Imagem" class="slide-image">
                        @endif
                        <div class="slide-text">
                            <a href="{{ $item['link'] }}" target="_blank" class="slide-title" title="{{ $item['title'] }}">
                                {{ strlen(strip_tags($item['title'])) > 80 ? substr(strip_tags($item['title']), 0, 77) . '...' : strip_tags($item['title']) }}                            </a>
                            <span class="slide-date">{{ $item['date'] }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>