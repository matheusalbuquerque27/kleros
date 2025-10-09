<?php

return [
    'meta' => [
        'register_title' => 'Registro de congregaciones — Kleros',
        'config_title' => 'Configurar congregación — Kleros',
    ],
    'header' => [
        'tagline' => 'Ecosistema para Iglesias',
        'link_denominations' => '¿Necesitas registrar la denominación?',
        'progress' => 'Paso 2 de 2 • Ajustes finales',
    ],
    'cadastro' => [
        'badge' => 'Check-in institucional',
        'title' => 'Registra tu congregación',
        'description' => 'Completa la información para conectar tu congregación al ecosistema Kleros y habilitar los siguientes pasos de implementación.',
        'alerts' => [
            'success' => 'Congregación registrada con éxito.',
        ],
        'denomination' => [
            'title' => 'Selecciona tu denominación',
            'subtitle' => 'Si tu iglesia aún no aparece en la lista puedes registrarla :link.',
            'link' => 'haciendo clic aquí',
            'search_label' => 'Buscar denominación',
            'search_placeholder' => 'Escribe para filtrar',
            'selected_label' => 'Denominación seleccionada',
            'toggle' => 'Cambiar',
            'empty' => 'No se encontraron denominaciones para la búsqueda ingresada.',
        ],
        'basic' => [
            'title' => 'Información básica',
            'subtitle' => 'Cuéntanos cómo se identifica la congregación y cómo podemos contactarte.',
            'fields' => [
                'nome' => ['label' => 'Identificación', 'placeholder' => '¿Cómo se llama la congregación?'],
                'nome_curto' => ['label' => 'Nombre abreviado', 'placeholder' => 'Usado en el subdominio y en el sistema'],
                'cnpj' => ['label' => 'Identificación fiscal', 'placeholder' => '00.000.000/0000-00'],
                'telefone' => ['label' => 'Teléfono', 'placeholder' => '(00) 00000-0000'],
                'email' => ['label' => 'Correo electrónico', 'placeholder' => 'contacto@tuiglesia.com'],
                'site' => ['label' => 'Sitio institucional (opcional)', 'placeholder' => 'https://www.tuiglesia.com'],
            ],
        ],
        'location' => [
            'title' => 'Ubicación',
            'subtitle' => 'Estos datos ayudan a configurar mapa, agenda y comunicación geográfica.',
            'fields' => [
                'endereco' => ['label' => 'Dirección', 'placeholder' => 'Calle, avenida o domicilio'],
                'numero' => ['label' => 'Número', 'placeholder' => 'S/N'],
                'complemento' => ['label' => 'Complemento', 'placeholder' => 'Bloque, sala o referencia'],
                'bairro' => ['label' => 'Barrio', 'placeholder' => 'Barrio o región'],
                'cep' => ['label' => 'Código postal', 'placeholder' => '00000-000'],
            ],
            'selects' => [
                'pais' => ['label' => 'País', 'placeholder' => 'Selecciona el país'],
                'estado' => ['label' => 'Estado', 'placeholder' => 'Selecciona el estado'],
                'cidade' => ['label' => 'Ciudad', 'placeholder' => 'Selecciona la ciudad'],
            ],
        ],
        'consent' => 'Al continuar aceptas que el equipo de Kleros se comunique contigo para los siguientes pasos.',
        'buttons' => [
            'back' => 'Volver al inicio',
            'submit' => 'Guardar y continuar',
        ],
        'js' => [
            'cta_select' => 'Haz clic para seleccionar',
        ],
    ],
    'config' => [
        'badge' => 'Personalización',
        'title' => 'Configura la experiencia de tu congregación',
        'description' => 'Define identidad visual, fuentes, módulos y preferencias antes de abrir el panel de la comunidad.',
        'intro' => '¡Primera etapa completada! Personaliza la congregación y finaliza el registro.',
        'success' => '¡Configuraciones guardadas con éxito! Tu congregación está lista para comenzar.',
        'next_steps' => [
            'title' => 'Próximos pasos',
            'description' => 'Puedes revisar el registro o acceder al panel cuando estés listo.',
            'back' => 'Volver al registro',
            'login' => 'Ir al inicio de sesión',
        ],
        'sections' => [
            'identity' => [
                'badge' => 'Identidad visual',
                'title' => 'Archivos e imágenes',
                'description' => 'Sube el logo y el banner para reforzar la identidad de la congregación en todo el sistema.',
                'logo_label' => 'Logo de la congregación',
                'logo_placeholder' => 'Selecciona un archivo PNG o SVG',
                'banner_label' => 'Banner para la pantalla de inicio de sesión',
                'banner_placeholder' => 'Imagen horizontal (JPG o PNG)',
                'upload' => 'Subir',
            ],
            'colors' => [
                'badge' => 'Colores y fuentes',
                'title' => 'Elige paleta y tipografía',
                'description' => 'Define tonos que reflejen la identidad de la congregación y elige la fuente principal de la interfaz.',
                'fields' => [
                    'primary' => 'Color primario',
                    'secondary' => 'Color secundario',
                    'accent' => 'Color de destaque',
                    'font' => 'Fuente de texto',
                    'preview_badge' => 'Vista previa',
                    'preview_quote' => '“Todo lo puedo en Cristo que me fortalece.”',
                ],
            ],
            'modules' => [
                'badge' => 'Temas y módulos',
                'title' => 'Organiza la estructura operativa',
                'description' => 'Activa módulos y elige el tema visual que verán los miembros.',
                'grouping' => 'Organización de agrupamientos',
                'grouping_options' => [
                    'grupo' => 'Solo grupos',
                    'departamento' => 'Grupos y Departamentos',
                    'setor' => 'Grupos, Departamentos y Sectores',
                ],
                'cells' => [
                    'title' => 'Células y grupos pequeños',
                    'question' => '¿Deseas habilitar el módulo de células?',
                    'active' => 'Activo',
                    'inactive' => 'Inactivo',
                ],
            ],
        ],
        'buttons' => [
            'back' => 'Volver al registro',
            'submit' => 'Finalizar configuración',
        ],
        'file_placeholder' => 'Selecciona un archivo',
    ],
    'validation' => [
        'nome_required' => 'Informa el nombre de la congregación.',
        'endereco_required' => 'Informa la dirección de la congregación.',
        'telefone_required' => 'Informa un teléfono de contacto.',
    ],
];
