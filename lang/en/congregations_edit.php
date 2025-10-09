<?php

return [
    'title' => 'Settings',
    'tabs' => [
        'general' => 'General data',
        'visual' => 'Branding',
        'administrative' => 'Administration',
    ],
    'sections' => [
        'institutional' => [
            'title' => 'Institutional',
            'fields' => [
                'identification' => 'Identification',
                'short_name' => 'Short name',
                'cnpj' => 'Tax ID',
                'email' => 'Email',
                'phone' => 'Phone',
            ],
        ],
        'location' => [
            'title' => 'Location',
            'fields' => [
                'address' => 'Address',
                'number' => 'Number',
                'complement' => 'Complement',
                'district' => 'District',
                'country' => 'Country',
                'state' => 'State/Region',
                'city' => 'City',
            ],
            'placeholders' => [
                'country' => 'Select a country',
                'state' => 'Select a state/region',
                'city' => 'Select a city',
            ],
        ],
        'visual' => [
            'title' => 'Visual features',
            'files' => [
                'title' => 'Files and images',
                'logo' => 'Congregation logo',
                'logo_hint' => 'Select a PNG or SVG file',
                'banner' => 'Login screen banner',
                'banner_hint' => 'Horizontal image (JPG or PNG)',
                'upload' => 'Upload file',
                'current_logo' => 'Current logo',
                'current_banner' => 'Current banner',
            ],
            'colors' => [
                'title' => 'Colors and fonts',
                'description' => 'Pick palette and typography',
                'primary' => 'Primary color',
                'secondary' => 'Secondary color',
                'accent' => 'Accent color',
                'font' => 'Text font',
                'preview_label' => 'Selected font sample:',
                'preview_text' => 'I can do all things through Him who strengthens me.',
            ],
            'themes' => [
                'title' => 'Visual theme',
                'classic' => 'Classic',
                'modern' => 'Modern',
                'vintage' => 'Vintage',
            ],
        ],
        'administrative' => [
            'title' => 'Preferences',
            'grouping' => 'Group organization',
            'grouping_options' => [
                'grupo' => 'Groups only',
                'departamento' => 'Groups and Departments',
                'setor' => 'Groups, Departments, Sectors',
            ],
            'cells' => [
                'label' => 'Cells / Small groups',
                'active' => 'Active',
                'inactive' => 'Inactive',
            ],
            'language' => [
                'label' => 'System language',
                'language_options' => [
                    'English' => 'en',
                    'Portuguese' => 'pt',
                    'Spanish' => 'es',
                ],
            ],
        ],
    ],
    'buttons' => [
        'update' => 'Update',
        'restore' => 'Restore',
        'back' => 'Back',
    ],
    'placeholders' => [
        'email' => 'myemail@domain.com',
        'phone' => '(00) 00000-0000',
        'cnpj' => '00.000.000/0000-00',
    ],
    'scripts' => [
        'no_file' => 'No file selected',
        'file_deleted' => 'File deleted.',
        'error_delete' => 'Error while deleting the file.',
        'loading' => 'Loading...',
        'select_state' => 'Select a state',
        'select_city' => 'Select a city',
    ],
];
