@extends('layouts.main')

@section('title', $congregacao->nome_curto . ' | ' . $appName)

@section('content')

<div class="container">

    <h1>Agenda Integrada</h1>
    <div class="info">
        <h3>Visão Geral</h3>
        <div class="control-btn">
            <div class="control-btn-group">
                <h4>Alterar visão</h4>
                <button class="btn" id="btnVisaoAnual">Visão Anual</button>
                <button class="btn" id="btnVisaoMensal">Visão Mensal</button>
            </div>
            
            <div class="control-btn-group">
                <h4>Agendar</h4>
                <button onclick="abrirJanelaModal('{{route('eventos.form_criar')}}')" class="btn" id="evento"><i class="bi bi-calendar-event"></i> Evento</button>
                <button onclick="abrirJanelaModal('{{route('cultos.form_criar')}}')" class="btn" id="culto"><i class="bi bi-bell"></i> Culto</button>
                <button onclick="abrirJanelaModal('{{route('reunioes.form_criar')}}')" class="btn" id="reuniao"><i class="bi bi-people"></i> Reunião</button>
            </div>
        </div>
        <div id="calendar"></div>
    </div>
    </div>

@endsection

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');


        const calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'pt-br',
            timeZone: 'local',
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
             buttonText: {
                today: 'Hoje',
                month: 'Mês',
                week: 'Semana',
                day: 'Dia'
            },
            views: {
                multiMonthYear: {
                    type: 'multiMonth',
                    duration: { months: 12 },
                    buttonText: 'Ano'
                }
            },
            events: "{{ route('agenda.eventos.json') }}",
            eventDidMount: function(info) {
                const titleEl = info.el.querySelector('.fc-event-title');
                if (!titleEl) {
                    return;
                }

                const type = info.event.extendedProps ? info.event.extendedProps.type : null;
                const iconMap = {
                    culto: 'bi-bell',
                    evento: 'bi-calendar-event',
                    reuniao: 'bi-people',
                };

                let titleHtml = info.event.title;
                const iconClass = iconMap[type];

                if (iconClass && !/^<i\b/i.test(titleHtml.trim())) {
                    titleHtml = '<i class="bi ' + iconClass + '"></i> ' + titleHtml;
                }

                titleEl.innerHTML = titleHtml;
            },
            eventClick: function(info) {
                info.jsEvent.preventDefault();

                const { editUrl } = info.event.extendedProps || {};

                if (editUrl) {
                    abrirJanelaModal(editUrl);
                    return;
                }

                const plainTitle = info.event.title.replace(/<[^>]+>/g, '');
                alert('Evento: ' + plainTitle + '\nInício: ' + info.event.start.toLocaleString());
            }
        });

        calendar.render();

        // Trocar para visão mensal
        document.getElementById('btnVisaoMensal').addEventListener('click', function () {
            calendar.changeView('dayGridMonth');
        });

        // Trocar para visão anual
        document.getElementById('btnVisaoAnual').addEventListener('click', function () {
            calendar.changeView('multiMonthYear');
        });
    });
</script>

@endpush
