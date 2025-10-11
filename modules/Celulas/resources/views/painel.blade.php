@extends('layouts.main')

@section('title', 'Células')

@section('content')

<div class="container">
    <h1>Células</h1>
    <div class="info">
        <h3>Visão Geral</h3>
        <div class="search-panel">
            <div class="search-panel-item">
                <label>Membro: </label>
                <input type="text" name="" placeholder="Nome" id="membro">
            </div>
            <div class="search-panel-item">
                <button class="" id="btn_filtrar"><i class="bi bi-search"></i> Procurar</button>
                <button class="" onclick="abrirJanelaModal('{{route('celulas.form_criar')}}')"><i class="bi bi-plus-circle"></i> Adicionar</button>
                <button class="" onclick="window.history.back()"><i class="bi bi-arrow-return-left"></i> Voltar</button>
            </div>
        </div>

        <div id="list" class="list">
            <div class="list-title">
                <div class="item-1">
                    <b>Identificação</b>
                </div>
                <div class="item-1">
                    <b>Liderança</b>
                </div>
                <div class="item-1">
                    <b>Anfitrião</b>
                </div>
                <div class="item-1">
                    <b>Local</b>
                </div>
            </div><!--list-title-->
            <div id="content">
                @include('celulas::includes.lista', ['celulas' => $celulas])
            </div>
        </div>

        <br>
        
        <h3>Mapa das Células</h3>
        <div class="celulas-map-wrapper">
            <p class="map-feedback">Carregando localização das células…</p>
            <div
                id="celulas-map"
                data-celulas="{{ json_encode($celulasMapa) }}"
                data-map-id="{{ $googleMapsMapId }}"
            ></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const trigger = document.getElementById('btn_filtrar');
        const inputMembro = document.getElementById('membro');
        const contentTarget = document.getElementById('content');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const endpoint = @json(route('celulas.search'));

        function aplicarResultado(html) {
            if (!contentTarget) {
                return;
            }

            contentTarget.innerHTML = html;

            if (typeof initModalScripts === 'function') {
                try {
                    initModalScripts(contentTarget);
                } catch (error) {
                    console.error('Falha ao reinicializar scripts do modal em células.', error);
                }
            }
        }

        function pesquisarCelulas() {
            if (!csrfToken || !endpoint) {
                return;
            }

            const payload = {
                membro: inputMembro?.value || '',
            };

            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify(payload),
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Não foi possível carregar a lista de células.');
                    }
                    return response.json();
                })
                .then((data) => {
                    aplicarResultado(data.view || '');
                })
                .catch((error) => {
                    console.error(error);
                });
        }

        trigger?.addEventListener('click', function (event) {
            event.preventDefault();
            pesquisarCelulas();
        });
    })();
</script>
@endpush

@if(!empty($googleMapsKey)) 
    <style>
        .celulas-map-wrapper {
            margin: 28px auto 0;
            width: 95%;
        }

        #celulas-map {
            width: 100%;
            height: 520px;
            border-radius: 12px;
            border: 1px solid var(--secondary-color, #ccc);
            overflow: hidden;
        }

        .map-feedback {
            margin-top: 10px;
            color: #555;
            font-size: 0.95rem;
        }

        .map-basic-marker {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #d93025;
            border: 2px solid #fff;
            box-shadow: 0 0 6px rgba(0, 0, 0, 0.4);
            position: absolute;
            transform: translate(-50%, -50%);
            cursor: pointer;
        }

        .map-basic-marker:hover {
            transform: translate(-50%, -50%) scale(1.1);
        }
    </style>

    <script>
        window.initCelulasMap = function initCelulasMap() {
            const mapEl = document.getElementById('celulas-map');
            const feedback = document.querySelector('.map-feedback');

            if (!mapEl || !(window.google && google.maps)) {
                return;
            }

            let celulasData = [];
            try {
                celulasData = JSON.parse(mapEl.dataset.celulas || '[]');
            } catch (error) {
                console.warn('Falha ao interpretar os dados do mapa:', error);
            }

            const mapOptions = {
                zoom: 5,
                center: { lat: -14.235004, lng: -51.92528 },
                mapTypeControl: false,
                fullscreenControl: false,
                streetViewControl: false,
            };

            const mapId = mapEl.dataset.mapId;
            if (mapId) {
                mapOptions.mapId = mapId;
            }

            const map = new google.maps.Map(mapEl, mapOptions);

            const AdvancedMarkerElement = mapId && google.maps.marker?.AdvancedMarkerElement
                ? google.maps.marker.AdvancedMarkerElement
                : null;

            const sharedInfoWindow = new google.maps.InfoWindow();

            class BasicMarker extends google.maps.OverlayView {
                constructor(position, title, content, color) {
                    super();
                    this.position = position;
                    this.title = title;
                    this.content = content;
                    this.color = color;
                    this.div = null;
                    this.setMap(map);
                }

                onAdd() {
                    this.div = document.createElement('div');
                    this.div.className = 'map-basic-marker';
                    if (this.title) {
                        this.div.title = this.title;
                    }
                    if (this.color) {
                        this.div.style.backgroundColor = this.color;
                    }
                    if (this.content) {
                        this.div.addEventListener('click', () => {
                            sharedInfoWindow.setContent(this.content);
                            sharedInfoWindow.setPosition(this.position);
                            sharedInfoWindow.open({ map });
                        });
                    }

                    const panes = this.getPanes();
                    panes.overlayMouseTarget.appendChild(this.div);
                }

                draw() {
                    if (!this.div) return;
                    const projection = this.getProjection();
                    if (!projection) return;
                    const point = projection.fromLatLngToDivPixel(this.position);
                    this.div.style.left = `${point.x}px`;
                    this.div.style.top = `${point.y}px`;
                }

                onRemove() {
                    if (this.div && this.div.parentNode) {
                        this.div.parentNode.removeChild(this.div);
                    }
                    this.div = null;
                }
            }

            const addMarker = (position, title, content = null, color = '#4285F4') => {
                if (AdvancedMarkerElement) {
                    const marker = new AdvancedMarkerElement({ map, position, title });
                    if (content) {
                        const infoWindow = new google.maps.InfoWindow({ content });
                        marker.addListener('gmp-click', () => infoWindow.open({ map, anchor: marker }));
                    }
                    return;
                }

                new BasicMarker(position, title, content, color);
            };

            if (!Array.isArray(celulasData) || celulasData.length === 0) {
                if (feedback) {
                    feedback.textContent = 'Nenhuma coordenada foi informada para as células.';
                }
                addMarker(mapOptions.center, 'Brasil');
                map.setZoom(4);
                return;
            }

            const bounds = new google.maps.LatLngBounds();

            celulasData.forEach(celula => {
                const lat = Number(celula.latitude);
                const lng = Number(celula.longitude);

                if (Number.isFinite(lat) && Number.isFinite(lng)) {
                    const position = { lat, lng };
                    const content = celula.endereco
                        ? `<strong>${celula.nome}</strong><br>${celula.endereco}`
                        : `<strong>${celula.nome}</strong>`;

                    addMarker(position, celula.nome, content, celula.cor ?? '#4285F4');
                    bounds.extend(position);
                } else {
                    console.warn('Coordenadas inválidas para a célula', celula);
                }
            });

            if (bounds.isEmpty()) {
                if (feedback) {
                    feedback.textContent = 'Nenhuma coordenada válida encontrada. O mapa está centralizado no Brasil.';
                }
                addMarker(mapOptions.center, 'Brasil');
                map.setZoom(4);
                return;
            }

            if (feedback) {
                feedback.textContent = 'Clique nos marcadores para ver os detalhes da célula.';
            }

            map.fitBounds(bounds, { top: 40, right: 40, bottom: 40, left: 40 });
        };
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsKey }}&loading=async&libraries=marker&callback=initCelulasMap"></script>
@endif

@endsection
