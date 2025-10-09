<?php

return [
    'title' => 'Cadastros',
    'common' => [
        'view' => 'Ver',
        'edit' => 'Editar',
        'delete' => 'Excluir',
        'manage' => 'Gerenciar',
        'history' => 'Histórico',
        'new' => 'Novo',
        'print' => 'Imprimir',
        'print_list' => 'Imprimir lista',
        'print_report' => 'Imprimir relatório',
        'status_label' => 'Status:',
        'status' => [
            'active' => 'Ativo',
            'inactive' => 'Inativo',
        ],
        'leader' => 'Liderança',
        'team' => 'Equipe',
        'no_description' => 'Sem descrição',
        'no_linked' => 'Nenhum agrupamento vinculado.',
        'related' => 'Relacionados',
        'see_more' => 'Ver',
        'until' => 'Até',
        'editor' => 'Editor',
        'general' => 'Geral',
        'open' => 'Abrir',
    ],
    'confirmations' => [
        'delete_scale_type' => 'Deseja realmente excluir este tipo de escala?',
        'delete_group' => 'Deseja realmente excluir este grupo?',
        'delete_department' => 'Deseja realmente excluir este departamento?',
        'delete_sector' => 'Deseja realmente excluir este setor?',
        'delete_course' => 'Deseja realmente excluir este curso?',
        'delete_cell' => 'Deseja realmente excluir esta célula?',
        'delete_cashier' => 'Excluir o caixa :name? Todos os lançamentos serão removidos.',
    ],
    'sections' => [
        'cults' => [
            'title' => 'Cultos',
            'subtitle' => 'Próximos cultos previstos:',
            'labels' => [
                'preacher' => 'Preletor',
                'event' => 'Evento',
                'none' => 'Nenhum',
            ],
            'messages' => [
                'no_upcoming' => 'Não há cultos previstos para os próximos dias.',
            ],
            'buttons' => [
                'schedule' => 'Agendar culto',
                'agenda' => 'Próximos cultos',
                'register' => 'Registrar',
                'history' => 'Histórico',
            ],
        ],
        'scales' => [
            'title' => 'Escalas',
            'subtitle' => 'Tipos de escala cadastrados:',
            'messages' => [
                'empty' => 'Nenhum tipo de escala foi cadastrado ainda.',
            ],
            'buttons' => [
                'view' => 'Ver escalas',
                'new_type' => 'Novo tipo de escala',
                'generate' => 'Gerar escala',
                'print' => 'Imprimir',
            ],
        ],
        'events' => [
            'title' => 'Eventos',
            'subtitle' => 'Próximos eventos previstos:',
            'messages' => [
                'no_upcoming' => 'Não há eventos previstos para os próximos dias.',
            ],
            'buttons' => [
                'new' => 'Novo evento',
                'history' => 'Histórico de eventos',
                'agenda' => 'Próximos eventos',
            ],
        ],
        'meetings' => [
            'title' => 'Reuniões',
            'subtitle' => 'Próximas reuniões previstas:',
            'messages' => [
                'no_upcoming' => 'Não há reuniões previstas para os próximos dias.',
            ],
            'buttons' => [
                'new' => 'Nova reunião',
                'history' => 'Histórico',
                'agenda' => 'Próximas reuniões',
            ],
        ],
        'research' => [
            'title' => 'Pesquisas',
            'subtitle' => 'Pesquisas abertas:',
            'messages' => [
                'empty' => 'Nenhuma pesquisa aberta no momento.',
                'no_responsible' => 'Responsável não informado',
            ],
            'buttons' => [
                'new' => 'Nova pesquisa',
                'panel' => 'Painel de pesquisas',
            ],
        ],
        'visitors' => [
            'title' => 'Visitantes',
            'labels' => [
                'month' => 'Visitas neste mês:',
                'none' => 'Ainda não há histórico de visitantes.',
            ],
            'buttons' => [
                'new' => 'Cadastrar visitante',
                'history' => 'Histórico de visitantes',
            ],
        ],
        'groups' => [
            'title' => 'Grupos',
            'messages' => [
                'empty' => 'Nenhum grupo foi criado até o momento.',
            ],
            'buttons' => [
                'new' => 'Novo grupo',
                'print' => 'Imprimir lista',
                'members' => 'Membros',
            ],
        ],
        'departments' => [
            'title' => 'Departamentos',
            'messages' => [
                'empty' => 'Nenhum departamento foi adicionado até o momento.',
            ],
            'buttons' => [
                'new' => 'Novo departamento',
                'print' => 'Imprimir lista',
                'team' => 'Equipe',
            ],
        ],
        'sectors' => [
            'title' => 'Setores',
            'messages' => [
                'empty' => 'Nenhum setor foi cadastrado até o momento.',
                'no_description' => 'Sem descrição cadastrada.',
            ],
            'labels' => [
                'related' => 'Relacionados',
            ],
            'buttons' => [
                'view' => 'Ver',
                'edit' => 'Editar',
                'new' => 'Novo setor',
            ],
        ],
        'finance' => [
            'title' => 'Controle financeiro',
            'labels' => [
                'current_balance' => 'Saldo atual',
                'entries' => 'Entradas',
                'exits' => 'Saídas',
                'recent' => 'Lançamentos recentes',
            ],
            'messages' => [
                'no_entries' => 'Nenhum lançamento registrado.',
                'no_cashier' => 'Nenhum caixa cadastrado. Crie um novo caixa para começar a registrar lançamentos.',
            ],
            'buttons' => [
                'new_cashier' => 'Novo caixa',
                'new_type' => 'Tipo de lançamento',
            ],
        ],
        'courses' => [
            'title' => 'Cursos',
            'messages' => [
                'empty' => 'Nenhum curso foi registrado até o momento.',
            ],
            'buttons' => [
                'new' => 'Novo curso',
                'print' => 'Imprimir lista',
            ],
        ],
        'cells' => [
            'title' => 'Células',
            'labels' => [
                'meeting' => 'Encontro semanal',
            ],
            'messages' => [
                'empty' => 'Nenhum cadastro foi efetuado até o momento.',
            ],
            'buttons' => [
                'view' => 'Ver',
                'edit' => 'Editar',
                'new' => 'Nova célula',
                'print' => 'Imprimir relatório',
            ],
        ],
    ],
];
