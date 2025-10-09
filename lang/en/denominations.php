<?php

return [
    'meta' => [
        'title' => 'Register your denomination — Kleros',
    ],
    'header' => [
        'tagline' => 'Ecosystem for Churches',
        'link_label' => 'Already have the denomination? Register the congregation',
        'to_congregations' => 'Go to congregation registration',
    ],
    'hero' => [
        'badge' => 'Denominational check-in',
        'title' => 'Register your denomination',
        'description' => 'Provide the main information so we can organize your churches and enable Kleros resources across your entire network.',
    ],
    'alerts' => [
        'success' => 'Denomination registered successfully!',
        'error' => 'Unable to register the denomination.',
    ],
    'identity' => [
        'title' => 'Denominational identity',
        'subtitle' => 'This data will be displayed to every linked congregation.',
        'fields' => [
            'name' => [
                'label' => 'Full name',
                'placeholder' => 'Official denomination name',
            ],
            'doctrine' => [
                'label' => 'Doctrinal basis',
                'placeholder' => 'Select the tradition/confession',
            ],
        ],
    ],
    'ministries' => [
        'title' => 'Ministerial structure',
        'subtitle' => 'List the ministries or roles in use (e.g. Pastor, Elder, Deacon). They will be suggested when registering congregations.',
        'fields' => [
            'add' => 'Add ministries and press ENTER',
            'placeholder' => 'Type a ministry and press ENTER',
            'helper' => 'Click an item to remove it.',
        ],
    ],
    'consent' => 'By continuing you allow the Kleros team to contact you and validate the information.',
    'buttons' => [
        'to_congregations' => 'Go to congregation registration',
        'submit' => 'Save and continue',
    ],
    'validation' => [
        'required' => 'All fields are required.',
        'string' => 'The title must be a valid string.',
        'max' => 'The title may not exceed 255 characters.',
    ],
    'js' => [
        'selected_label' => 'Selected denomination',
        'empty' => 'No denomination found.',
        'cta_select' => 'Click to select',
        'remove' => 'Remove',
        'toggle' => 'Change',
    ],
];
