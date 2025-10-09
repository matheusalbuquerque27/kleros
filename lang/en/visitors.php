<?php

return [
    'common' => [
        'fields' => [
            'name' => 'Name',
            'phone' => 'Phone',
            'visit_date' => 'Visit date',
            'status' => 'Status',
            'notes' => 'Notes',
            'keyword' => 'Keyword',
        ],
        'placeholders' => [
            'name' => 'Full name',
            'phone' => '(00) 00000-0000',
            'notes' => 'Important notes',
            'search_name' => 'Visitor name',
        ],
        'buttons' => [
            'save' => 'Save data',
            'update' => 'Update data',
            'cancel' => 'Cancel',
            'history' => 'History',
            'back' => 'Back',
            'search' => 'Search',
            'export' => 'Export',
            'options' => 'Options',
            'print' => 'Print',
            'add' => 'Register visitor',
            'edit' => 'Edit',
            'remove' => 'Remove',
            'copy' => 'Copy phone',
            'convert_member' => 'Convert to member',
        ],
        'statuses' => [
            'not_informed' => 'Not informed',
        ],
        'tooltip' => [
            'copied' => 'Copied!',
        ],
    ],
    'cadastro' => [
        'title' => 'Register Visitor',
        'section' => 'Record',
    ],
    'edit' => [
        'title' => 'Edit visitor',
        'section' => 'Information',
    ],
    'view' => [
        'title' => 'Visitor details',
        'section' => 'Information',
    ],
    'historico' => [
        'title' => 'Visitor History',
        'filter' => [
            'heading' => 'Filter by period',
            'name_label' => 'Name',
            'date_label' => 'Date',
        ],
        'table' => [
            'name' => 'Name',
            'date' => 'Visit date',
            'phone' => 'Phone',
            'status' => 'Status',
        ],
        'empty' => 'No visitors were found for this search.',
    ],
    'validation' => [
        'required' => 'Name, phone and visit date are required.',
        'name_required' => 'Visitor name is required.',
        'phone_required' => 'Visitor phone is required.',
        'date_required' => 'Visit date is required.',
    ],
    'flash' => [
        'created' => ':name has been registered as a visitor!',
        'updated' => ':name was updated successfully.',
        'deleted' => 'Visitor deleted successfully.',
        'converted' => ':name is now a member! Complete the record details.',
    ],
    'export' => [
        'filename_prefix' => 'visitors_',
        'headers' => [
            'Name',
            'Visit date',
            'Phone',
            'Status',
            'Notes',
        ],
    ],
    'search' => [
        'empty' => 'No visitors were found for this search.',
    ],
];
