<?php

return [
    'meta' => [
        'title' => 'Cadastre sua denominação — Kleros',
    ],
    'header' => [
        'tagline' => 'Ecossistema para Igrejas',
        'link_label' => 'Já tem a denominação? Cadastre a congregação',
        'to_congregations' => 'Ir para cadastro de congregações',
    ],
    'hero' => [
        'badge' => 'Check-in denominacional',
        'title' => 'Cadastre sua denominação',
        'description' => 'Informe os dados principais para que possamos organizar suas igrejas e habilitar os recursos do Kleros para toda a rede.',
    ],
    'alerts' => [
        'success' => 'Denominação cadastrada com sucesso!',
        'error' => 'Erro ao cadastrar a denominação.',
    ],
    'identity' => [
        'title' => 'Identidade denominacional',
        'subtitle' => 'Esses dados aparecerão para todas as congregações vinculadas.',
        'fields' => [
            'name' => [
                'label' => 'Nome completo',
                'placeholder' => 'Nome oficial da denominação',
            ],
            'doctrine' => [
                'label' => 'Base doutrinária',
                'placeholder' => 'Selecione a tradição/confissão',
            ],
        ],
    ],
    'ministries' => [
        'title' => 'Estrutura ministerial',
        'subtitle' => 'Liste os ministérios ou cargos utilizados (ex.: Pastor, Presbítero, Diácono). Eles serão sugeridos ao cadastrar congregações.',
        'fields' => [
            'add' => 'Adicione os ministérios e pressione ENTER',
            'placeholder' => 'Digite o ministério e pressione ENTER',
            'helper' => 'Clique em um item para removê-lo.',
        ],
    ],
    'consent' => 'Ao continuar você autoriza a equipe Kleros a entrar em contato para validar as informações.',
    'buttons' => [
        'to_congregations' => 'Ir para cadastro de congregações',
        'submit' => 'Salvar e continuar',
    ],
    'validation' => [
        'required' => 'É obrigatório preencher todas as informações.',
        'string' => 'O título deve ser uma string válida.',
        'max' => 'O título não pode exceder 255 caracteres.',
    ],
    'js' => [
        'selected_label' => 'Denominação selecionada',
        'empty' => 'Nenhuma denominação encontrada.',
        'cta_select' => 'Clique para selecionar',
        'remove' => 'Remover',
        'toggle' => 'Trocar',
    ],
];
