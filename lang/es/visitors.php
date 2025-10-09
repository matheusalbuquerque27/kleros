<?php

return [
    'common' => [
        'fields' => [
            'name' => 'Nombre',
            'phone' => 'Teléfono',
            'visit_date' => 'Fecha de visita',
            'status' => 'Situación',
            'notes' => 'Observaciones',
            'keyword' => 'Palabra clave',
        ],
        'placeholders' => [
            'name' => 'Nombre completo',
            'phone' => '(00) 00000-0000',
            'notes' => 'Observaciones importantes',
            'search_name' => 'Nombre del visitante',
        ],
        'buttons' => [
            'save' => 'Guardar datos',
            'update' => 'Actualizar datos',
            'cancel' => 'Cancelar',
            'history' => 'Historial',
            'back' => 'Volver',
            'search' => 'Buscar',
            'export' => 'Exportar',
            'options' => 'Opciones',
            'print' => 'Imprimir',
            'add' => 'Registrar visitante',
            'edit' => 'Editar',
            'remove' => 'Eliminar',
            'copy' => 'Copiar teléfono',
            'convert_member' => 'Convertir en miembro',
        ],
        'statuses' => [
            'not_informed' => 'No informado',
        ],
        'tooltip' => [
            'copied' => '¡Copiado!',
        ],
    ],
    'cadastro' => [
        'title' => 'Registrar Visitante',
        'section' => 'Registro',
    ],
    'edit' => [
        'title' => 'Editar visitante',
        'section' => 'Información',
    ],
    'view' => [
        'title' => 'Información del visitante',
        'section' => 'Información',
    ],
    'historico' => [
        'title' => 'Historial de Visitantes',
        'filter' => [
            'heading' => 'Filtrar por período',
            'name_label' => 'Nombre',
            'date_label' => 'Fecha',
        ],
        'table' => [
            'name' => 'Nombre',
            'date' => 'Fecha de visita',
            'phone' => 'Teléfono',
            'status' => 'Situación',
        ],
        'empty' => 'Ningún visitante encontrado para esta búsqueda.',
    ],
    'validation' => [
        'required' => 'Nombre, teléfono y fecha de visita son obligatorios.',
        'name_required' => 'El nombre del visitante es obligatorio.',
        'phone_required' => 'El teléfono del visitante es obligatorio.',
        'date_required' => 'La fecha de visita es obligatoria.',
    ],
    'flash' => [
        'created' => '¡:name fue registrado(a) como visitante!',
        'updated' => ':name fue actualizado(a) con éxito.',
        'deleted' => 'Visitante eliminado con éxito.',
        'converted' => '¡:name ahora es miembro! Completa los datos de registro.',
    ],
    'export' => [
        'filename_prefix' => 'visitantes_',
        'headers' => [
            'Nombre',
            'Fecha de visita',
            'Teléfono',
            'Situación',
            'Observaciones',
        ],
    ],
    'search' => [
        'empty' => 'Ningún visitante encontrado para esta búsqueda.',
    ],
];
