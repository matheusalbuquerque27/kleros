<?php

return [
    'title' => 'Controle Geral',
    'general' => [
        'heading' => 'Dados Gerais',
        'cards' => [
            'today' => [
                'label' => 'Hoje',
            ],
            'service' => [
                'label' => 'Culto do dia',
                'unknown_preacher' => 'Preletor não informado',
                'no_event' => 'Culto sem evento associado',
                'no_service' => 'Sem culto registrado',
                'cta' => 'Agendar agora',
            ],
            'members_active' => [
                'label' => 'Membros ativos',
                'small' => 'Total registrado: :total',
            ],
            'new_month' => [
                'label' => 'Novos no mês',
                'small' => 'Entrada em :period',
                'period_format' => 'MMM/Y',
            ],
            'members_groups' => [
                'label' => 'Membros em grupos',
                'small' => ':count sem vínculo',
            ],
            'visitors' => [
                'label' => 'Visitantes',
                'small' => ':count desde o início',
            ],
            'structure' => [
                'label' => 'Estrutura',
                'small' => ':groups grupos · :cells células',
            ],
            'organization' => [
                'label' => 'Organização interna',
                'small' => ':departments departamentos · :sectors setores',
            ],
        ],
    ],
    'chart' => [
        'title' => 'Membros por grupos',
        'subtitle' => 'Próximos cultos: :services · Eventos agendados: :events',
        'empty' => 'Ainda não há membros vinculados aos grupos.',
    ],
    'recados' => [
        'heading' => 'Recados',
        'empty' => 'Não há novos recados.',
        'sent_by' => 'Enviado por :name',
    ],
    'events' => [
        'heading' => 'Eventos',
        'empty' => 'Não há eventos previstos para os próximos dias.',
        'general_owner' => 'Geral',
    ],
    'birthdays' => [
        'heading' => 'Aniversariantes',
        'empty' => 'Não há aniversariantes esse mês.',
        'no_ministry' => 'Não separado',
    ],
    'visitors' => [
        'heading' => 'Visitantes',
        'empty' => 'Ainda não recebemos visitantes.',
    ],
    'days' => [
        0 => 'Domingo',
        1 => 'Segunda-feira',
        2 => 'Terça-feira',
        3 => 'Quarta-feira',
        4 => 'Quinta-feira',
        5 => 'Sexta-feira',
        6 => 'Sábado',
    ],
    'numbers' => [
        'decimal' => ',',
        'thousand' => '.',
    ],
    'intl_locale' => 'pt-BR',
];
