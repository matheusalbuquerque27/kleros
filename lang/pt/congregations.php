<?php

return [
    'meta' => [
        'register_title' => 'Check-in de Congregações — Kleros',
        'config_title' => 'Configurar Congregação — Kleros',
    ],
    'header' => [
        'tagline' => 'Ecossistema para Igrejas',
        'link_denominations' => 'Precisa cadastrar a denominação?',
        'progress' => 'Passo 2 de 2 • Configurações finais',
    ],
    'cadastro' => [
        'badge' => 'Check-in institucional',
        'title' => 'Cadastre sua congregação',
        'description' => 'Preencha as informações abaixo para conectar sua congregação ao ecossistema Kleros e liberar os próximos passos da implantação.',
        'alerts' => [
            'success' => 'Congregação cadastrada com sucesso.',
        ],
        'denomination' => [
            'title' => 'Selecione sua denominação',
            'subtitle' => 'Caso sua igreja ainda não esteja listada, você pode cadastrá-la :link.',
            'link' => 'clicando aqui',
            'search_label' => 'Pesquisar denominação',
            'search_placeholder' => 'Digite para filtrar',
            'selected_label' => 'Denominação selecionada',
            'toggle' => 'Trocar',
            'empty' => 'Nenhuma denominação encontrada para a busca digitada.',
        ],
        'basic' => [
            'title' => 'Informações básicas',
            'subtitle' => 'Conte-nos como a congregação é identificada e como podemos entrar em contato.',
            'fields' => [
                'nome' => ['label' => 'Identificação', 'placeholder' => 'Como a congregação é chamada?'],
                'nome_curto' => ['label' => 'Nome reduzido', 'placeholder' => 'Usado no subdomínio e no sistema'],
                'cnpj' => ['label' => 'CNPJ', 'placeholder' => '00.000.000/0000-00'],
                'telefone' => ['label' => 'Telefone', 'placeholder' => '(00) 00000-0000'],
                'email' => ['label' => 'E-mail', 'placeholder' => 'contato@suaigreja.com'],
                'site' => ['label' => 'Site institucional (opcional)', 'placeholder' => 'https://www.suaigreja.com'],
            ],
        ],
        'location' => [
            'title' => 'Localização',
            'subtitle' => 'Essas informações ajudam a configurar mapa, agenda e comunicação geográfica.',
            'fields' => [
                'endereco' => ['label' => 'Endereço', 'placeholder' => 'Rua, avenida ou logradouro'],
                'numero' => ['label' => 'Número', 'placeholder' => 'S/N'],
                'complemento' => ['label' => 'Complemento', 'placeholder' => 'Bloco, sala ou referência'],
                'bairro' => ['label' => 'Bairro', 'placeholder' => 'Bairro ou região'],
                'cep' => ['label' => 'CEP', 'placeholder' => '00000-000'],
            ],
            'selects' => [
                'pais' => ['label' => 'País', 'placeholder' => 'Selecione o país'],
                'estado' => ['label' => 'Estado', 'placeholder' => 'Selecione o estado'],
                'cidade' => ['label' => 'Cidade', 'placeholder' => 'Selecione a cidade'],
            ],
        ],
        'consent' => 'Ao prosseguir você concorda em ser contatado pela equipe Kleros para os próximos passos.',
        'buttons' => [
            'back' => 'Voltar para o início',
            'submit' => 'Salvar e continuar',
        ],
        'js' => [
            'cta_select' => 'Clique para selecionar',
        ],
    ],
    'config' => [
        'badge' => 'Personalização',
        'title' => 'Configure a experiência da sua congregação',
        'description' => 'Defina identidade visual, fontes, módulos e preferências antes de liberar o acesso ao painel da comunidade.',
        'intro' => 'Primeira etapa concluída! Personalize a congregação e finalize o cadastro.',
        'success' => 'Configurações personalizadas com sucesso! Sua congregação está pronta para começar.',
        'next_steps' => [
            'title' => 'Próximos passos',
            'description' => 'Você pode revisar o cadastro ou entrar no painel quando estiver pronto.',
            'back' => 'Voltar ao cadastro',
            'login' => 'Ir para o login',
        ],
        'sections' => [
            'identity' => [
                'badge' => 'Identidade visual',
                'title' => 'Arquivos e imagens',
                'description' => 'Envie logo e banner para reforçar a presença visual da congregação em todos os ambientes do sistema.',
                'logo_label' => 'Logo da congregação',
                'logo_placeholder' => 'Selecione um arquivo PNG ou SVG',
                'banner_label' => 'Banner para tela de login',
                'banner_placeholder' => 'Imagem horizontal (JPG ou PNG)',
                'upload' => 'Upload',
            ],
            'colors' => [
                'badge' => 'Cores e fontes',
                'title' => 'Escolha a paleta e tipografia',
                'description' => 'Defina tons que reflitam a identidade da congregação e escolha a fonte principal da interface.',
                'fields' => [
                    'primary' => 'Cor primária',
                    'secondary' => 'Cor secundária',
                    'accent' => 'Cor de destaque',
                    'font' => 'Fonte de texto',
                    'preview_badge' => 'Pré-visualização',
                    'preview_quote' => '“Tudo posso naquele que me fortalece.”',
                ],
            ],
            'modules' => [
                'badge' => 'Temas e módulos',
                'title' => 'Organize a estrutura operacional',
                'description' => 'Ative módulos e escolha o tema visual padrão que será apresentado aos membros.',
                'grouping' => 'Organização de agrupamentos',
                'grouping_options' => [
                    'grupo' => 'Apenas grupos',
                    'departamento' => 'Grupos e Departamentos',
                    'setor' => 'Grupos, Departamentos e Setores',
                ],
                'cells' => [
                    'title' => 'Células e pequenos grupos',
                    'question' => 'Deseja habilitar o módulo de células?',
                    'active' => 'Ativo',
                    'inactive' => 'Inativo',
                ],
            ],
        ],
        'buttons' => [
            'back' => 'Voltar ao cadastro',
            'submit' => 'Concluir configuração',
        ],
        'file_placeholder' => 'Selecione um arquivo',
    ],
    'validation' => [
        'nome_required' => 'Informe o nome da congregação.',
        'endereco_required' => 'Informe o endereço da congregação.',
        'telefone_required' => 'Informe um telefone de contato.',
    ],
];
