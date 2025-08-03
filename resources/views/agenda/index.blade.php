@extends('layouts.kleros')

@section('title', 'Agenda Integrada')

@section('content')

<div class="container">

    <h1>Agenda Integrada</h1>
    <div class="info">
        <h3>Visão Geral</h3>
        <div id="calendar"></div>
    </div>
    </div>

@endsection

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'pt-br',
            timeZone: 'local',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: "{{ route('agenda.eventos.json') }}",
            eventClick: function(info) {
                alert('Evento: ' + info.event.title + '\nInício: ' + info.event.start.toLocaleString());
            }
        });

        calendar.render();
    });
</script>
@endpush