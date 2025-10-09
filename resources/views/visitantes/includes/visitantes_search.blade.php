@php
    use Illuminate\Support\Carbon;

    $visitorsData = trans('visitors');
    $common = $visitorsData['common'];
    $history = $visitorsData['historico'];
    $tooltipCopied = $common['tooltip']['copied'];
    $formatDate = fn ($value) => $value ? Carbon::parse($value)->format('d/m/Y') : '-';
@endphp

@forelse ($visitantes as $item)
    <a href="{{ route('visitantes.exibir', $item->id) }}">
        <div class="list-item">
            <div class="item item-1">
                <p><i class="bi bi-person-raised-hand"></i> {{ $item->nome }}</p>
            </div>
            <div class="item item-1">
                <p>{{ $formatDate($item->data_visita) }}</p>
            </div>
            <div class="item item-1">
                <p>
                    {{ $item->telefone }}
                    <span class="copy-helper" data-phone="{{ $item->telefone }}">
                        <i class="bi bi-copy"></i>
                        <span class="tooltip-copiar">{{ $tooltipCopied }}</span>
                    </span>
                </p>
            </div>
            <div class="item item-1">
                <p>{{ optional($item->sit_visitante)->titulo ?? $common['statuses']['not_informed'] }}</p>
            </div>
        </div>
    </a>
@empty
    <div class="card">
        <p><i class="bi bi-exclamation-triangle"></i> {{ $history['empty'] }}</p>
    </div>
@endforelse
