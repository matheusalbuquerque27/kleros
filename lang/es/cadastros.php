<?php

return [
    'title' => 'Registros',
    'common' => [
        'view' => 'Ver',
        'edit' => 'Editar',
        'delete' => 'Eliminar',
        'manage' => 'Gestionar',
        'history' => 'Historial',
        'new' => 'Nuevo',
        'print' => 'Imprimir',
        'print_list' => 'Imprimir lista',
        'print_report' => 'Imprimir informe',
        'status_label' => 'Estado:',
        'status' => [
            'active' => 'Activo',
            'inactive' => 'Inactivo',
        ],
        'leader' => 'Liderazgo',
        'team' => 'Equipo',
        'no_description' => 'Sin descripción',
        'no_linked' => 'Ningún agrupamiento vinculado.',
        'related' => 'Relacionados',
        'see_more' => 'Ver',
        'until' => 'Hasta',
        'editor' => 'Editor',
        'general' => 'General',
        'open' => 'Abrir',
    ],
    'confirmations' => [
        'delete_scale_type' => '¿Realmente deseas eliminar este tipo de escala?',
        'delete_group' => '¿Realmente deseas eliminar este grupo?',
        'delete_department' => '¿Realmente deseas eliminar este departamento?',
        'delete_sector' => '¿Realmente deseas eliminar este sector?',
        'delete_course' => '¿Realmente deseas eliminar este curso?',
        'delete_cell' => '¿Realmente deseas eliminar esta célula?',
        'delete_cashier' => '¿Eliminar la caja :name? Todos los registros serán removidos.',
    ],
    'sections' => [
        'cults' => [
            'title' => 'Cultos',
            'subtitle' => 'Próximos cultos previstos:',
            'labels' => [
                'preacher' => 'Predicador',
                'event' => 'Evento',
                'none' => 'Ninguno',
            ],
            'messages' => [
                'no_upcoming' => 'No hay cultos previstos para los próximos días.',
            ],
            'buttons' => [
                'schedule' => 'Agendar culto',
                'agenda' => 'Próximos cultos',
                'register' => 'Registrar',
                'history' => 'Historial',
            ],
        ],
        'scales' => [
            'title' => 'Escalas',
            'subtitle' => 'Tipos de escala registrados:',
            'messages' => [
                'empty' => 'Ningún tipo de escala ha sido registrado aún.',
            ],
            'buttons' => [
                'view' => 'Ver escalas',
                'new_type' => 'Nuevo tipo de escala',
                'generate' => 'Generar escala',
                'print' => 'Imprimir',
            ],
        ],
        'events' => [
            'title' => 'Eventos',
            'subtitle' => 'Próximos eventos previstos:',
            'messages' => [
                'no_upcoming' => 'No hay eventos previstos para los próximos días.',
            ],
            'buttons' => [
                'new' => 'Nuevo evento',
                'history' => 'Historial de eventos',
                'agenda' => 'Próximos eventos',
            ],
        ],
        'meetings' => [
            'title' => 'Reuniones',
            'subtitle' => 'Próximas reuniones previstas:',
            'messages' => [
                'no_upcoming' => 'No hay reuniones previstas para los próximos días.',
            ],
            'buttons' => [
                'new' => 'Nueva reunión',
                'history' => 'Historial',
                'agenda' => 'Próximas reuniones',
            ],
        ],
        'research' => [
            'title' => 'Encuestas',
            'subtitle' => 'Encuestas abiertas:',
            'messages' => [
                'empty' => 'No hay encuestas abiertas en este momento.',
                'no_responsible' => 'Responsable no informado',
            ],
            'buttons' => [
                'new' => 'Nueva encuesta',
                'panel' => 'Panel de encuestas',
            ],
        ],
        'visitors' => [
            'title' => 'Visitantes',
            'labels' => [
                'month' => 'Visitas este mes:',
                'none' => 'Aún no hay historial de visitantes.',
            ],
            'buttons' => [
                'new' => 'Registrar visitante',
                'history' => 'Historial de visitantes',
            ],
        ],
        'groups' => [
            'title' => 'Grupos',
            'messages' => [
                'empty' => 'Ningún grupo ha sido creado hasta el momento.',
            ],
            'buttons' => [
                'new' => 'Nuevo grupo',
                'print' => 'Imprimir lista',
                'members' => 'Miembros',
            ],
        ],
        'departments' => [
            'title' => 'Departamentos',
            'messages' => [
                'empty' => 'Ningún departamento ha sido agregado aún.',
            ],
            'buttons' => [
                'new' => 'Nuevo departamento',
                'print' => 'Imprimir lista',
                'team' => 'Equipo',
            ],
        ],
        'sectors' => [
            'title' => 'Sectores',
            'messages' => [
                'empty' => 'Ningún sector ha sido registrado aún.',
                'no_description' => 'Sin descripción registrada.',
            ],
            'labels' => [
                'related' => 'Relacionados',
            ],
            'buttons' => [
                'view' => 'Ver',
                'edit' => 'Editar',
                'new' => 'Nuevo sector',
            ],
        ],
        'finance' => [
            'title' => 'Control financiero',
            'labels' => [
                'current_balance' => 'Saldo actual',
                'entries' => 'Ingresos',
                'exits' => 'Egresos',
                'recent' => 'Movimientos recientes',
            ],
            'messages' => [
                'no_entries' => 'No hay movimientos registrados.',
                'no_cashier' => 'Ninguna caja creada. Crea una para comenzar a registrar movimientos.',
            ],
            'buttons' => [
                'new_cashier' => 'Nueva caja',
                'new_type' => 'Tipo de movimiento',
            ],
        ],
        'courses' => [
            'title' => 'Cursos',
            'messages' => [
                'empty' => 'Ningún curso ha sido registrado hasta el momento.',
            ],
            'buttons' => [
                'new' => 'Nuevo curso',
                'print' => 'Imprimir lista',
            ],
        ],
        'cells' => [
            'title' => 'Células',
            'labels' => [
                'meeting' => 'Encuentro semanal',
            ],
            'messages' => [
                'empty' => 'Ningún registro ha sido creado hasta el momento.',
            ],
            'buttons' => [
                'view' => 'Ver',
                'edit' => 'Editar',
                'new' => 'Nueva célula',
                'print' => 'Imprimir informe',
            ],
        ],
    ],
];
