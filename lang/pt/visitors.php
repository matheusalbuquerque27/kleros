<?php

return [
    'common' => [
        'fields' => [
            'name' => 'Nome',
            'phone' => 'Telefone',
            'visit_date' => 'Data da visita',
            'status' => 'Situação',
            'notes' => 'Observações',
            'keyword' => 'Palavra-chave',
        ],
        'placeholders' => [
            'name' => 'Nome completo',
            'phone' => '(00) 00000-0000',
            'notes' => 'Observações importantes',
            'search_name' => 'Nome do visitante',
        ],
        'buttons' => [
            'save' => 'Salvar dados',
            'update' => 'Atualizar dados',
            'cancel' => 'Cancelar',
            'history' => 'Histórico',
            'back' => 'Voltar',
            'search' => 'Procurar',
            'export' => 'Exportar',
            'options' => 'Opções',
            'print' => 'Imprimir',
            'add' => 'Cadastrar visitante',
            'edit' => 'Editar',
            'remove' => 'Remover',
            'copy' => 'Copiar telefone',
            'convert_member' => 'Tornar membro',
        ],
        'statuses' => [
            'not_informed' => 'Não informado',
        ],
        'tooltip' => [
            'copied' => 'Copiado!',
        ],
    ],
    'cadastro' => [
        'title' => 'Cadastrar Visitante',
        'section' => 'Registro',
    ],
    'edit' => [
        'title' => 'Editar visitante',
        'section' => 'Informações',
    ],
    'view' => [
        'title' => 'Informações de Visitante',
        'section' => 'Informações',
    ],
    'historico' => [
        'title' => 'Histórico de Visitantes',
        'filter' => [
            'heading' => 'Filtrar por período',
            'name_label' => 'Nome',
            'date_start_label' => 'Data inicial',
            'date_end_label' => 'Data final',
        ],
        'table' => [
            'name' => 'Nome',
            'date' => 'Data da visita',
            'phone' => 'Telefone',
            'status' => 'Situação',
        ],
        'empty' => 'Nenhum visitante retornado para esta pesquisa.',
    ],
    'validation' => [
        'required' => 'Nome, telefone e data de visita são obrigatórios.',
        'name_required' => 'Informe o nome do visitante.',
        'phone_required' => 'Informe o telefone do visitante.',
        'date_required' => 'Informe a data da visita.',
    ],
    'flash' => [
        'created' => ':name foi cadastrado(a) como visitante!',
        'updated' => ':name foi atualizado(a) com sucesso.',
        'deleted' => 'Visitante excluído com sucesso.',
        'converted' => ':name agora é um membro! Complete os dados cadastrais.',
    ],
    'export' => [
        'filename_prefix' => 'visitantes_',
        'headers' => [
            'Nome',
            'Data da visita',
            'Telefone',
            'Situação',
            'Observações',
        ],
    ],
    'search' => [
        'empty' => 'Nenhum visitante retornado para esta pesquisa.',
    ],
];
