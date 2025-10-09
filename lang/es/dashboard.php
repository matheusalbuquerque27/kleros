<?php

return [
    'title' => 'Control General',
    'general' => [
        'heading' => 'Datos Generales',
        'cards' => [
            'today' => [
                'label' => 'Hoy',
            ],
            'service' => [
                'label' => 'Culto del día',
                'unknown_preacher' => 'Predicador no informado',
                'no_event' => 'Culto sin evento asociado',
                'no_service' => 'No hay culto registrado',
                'cta' => 'Agendar ahora',
            ],
            'members_active' => [
                'label' => 'Miembros activos',
                'small' => 'Total registrado: :total',
            ],
            'new_month' => [
                'label' => 'Nuevos en el mes',
                'small' => 'Ingreso en :period',
                'period_format' => 'MMM/Y',
            ],
            'members_groups' => [
                'label' => 'Miembros en grupos',
                'small' => ':count sin vínculo',
            ],
            'visitors' => [
                'label' => 'Visitantes',
                'small' => ':count desde el inicio',
            ],
            'structure' => [
                'label' => 'Estructura',
                'small' => ':groups grupos · :cells células',
            ],
            'organization' => [
                'label' => 'Organización interna',
                'small' => ':departments departamentos · :sectors sectores',
            ],
        ],
    ],
    'chart' => [
        'title' => 'Miembros por grupos',
        'subtitle' => 'Próximos cultos: :services · Eventos programados: :events',
        'empty' => 'Aún no hay miembros vinculados a los grupos.',
    ],
    'recados' => [
        'heading' => 'Recados',
        'empty' => 'No hay nuevos recados.',
        'sent_by' => 'Enviado por :name',
    ],
    'events' => [
        'heading' => 'Eventos',
        'empty' => 'No hay eventos previstos para los próximos días.',
        'general_owner' => 'General',
    ],
    'birthdays' => [
        'heading' => 'Cumpleaños',
        'empty' => 'No hay cumpleaños este mes.',
        'no_ministry' => 'Sin asignar',
    ],
    'visitors' => [
        'heading' => 'Visitantes',
        'empty' => 'Aún no recibimos visitantes.',
    ],
    'days' => [
        0 => 'Domingo',
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado',
    ],
    'numbers' => [
        'decimal' => ',',
        'thousand' => '.',
    ],
    'intl_locale' => 'es-ES',
];
