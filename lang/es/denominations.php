<?php

return [
    'meta' => [
        'title' => 'Registra tu denominación — Kleros',
    ],
    'header' => [
        'tagline' => 'Ecosistema para Iglesias',
        'link_label' => '¿Ya tienes la denominación? Registra la congregación',
        'to_congregations' => 'Ir al registro de congregaciones',
    ],
    'hero' => [
        'badge' => 'Check-in denominacional',
        'title' => 'Registra tu denominación',
        'description' => 'Proporciona la información principal para organizar tus iglesias y habilitar los recursos de Kleros en toda la red.',
    ],
    'alerts' => [
        'success' => '¡Denominación registrada con éxito!',
        'error' => 'No se pudo registrar la denominación.',
    ],
    'identity' => [
        'title' => 'Identidad denominacional',
        'subtitle' => 'Estos datos aparecerán para todas las congregaciones vinculadas.',
        'fields' => [
            'name' => [
                'label' => 'Nombre completo',
                'placeholder' => 'Nombre oficial de la denominación',
            ],
            'doctrine' => [
                'label' => 'Base doctrinal',
                'placeholder' => 'Selecciona la tradición/confesión',
            ],
        ],
    ],
    'ministries' => [
        'title' => 'Estructura ministerial',
        'subtitle' => 'Enumera los ministerios o cargos utilizados (ej.: Pastor, Anciano, Diácono). Se sugerirán al registrar congregaciones.',
        'fields' => [
            'add' => 'Agrega los ministerios y presiona ENTER',
            'placeholder' => 'Escribe el ministerio y presiona ENTER',
            'helper' => 'Haz clic en un ítem para eliminarlo.',
        ],
    ],
    'consent' => 'Al continuar autorizas al equipo de Kleros a contactarte para validar la información.',
    'buttons' => [
        'to_congregations' => 'Ir al registro de congregaciones',
        'submit' => 'Guardar y continuar',
    ],
    'validation' => [
        'required' => 'Se requiere completar toda la información.',
        'string' => 'El título debe ser una cadena válida.',
        'max' => 'El título no puede exceder 255 caracteres.',
    ],
    'js' => [
        'selected_label' => 'Denominación seleccionada',
        'empty' => 'No se encontró ninguna denominación.',
        'cta_select' => 'Haz clic para seleccionar',
        'remove' => 'Eliminar',
        'toggle' => 'Cambiar',
    ],
];
