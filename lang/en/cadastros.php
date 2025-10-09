<?php

return [
    'title' => 'Records',
    'common' => [
        'view' => 'View',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'manage' => 'Manage',
        'history' => 'History',
        'new' => 'New',
        'print' => 'Print',
        'print_list' => 'Print list',
        'print_report' => 'Print report',
        'status_label' => 'Status:',
        'status' => [
            'active' => 'Active',
            'inactive' => 'Inactive',
        ],
        'leader' => 'Leadership',
        'team' => 'Team',
        'no_description' => 'No description',
        'no_linked' => 'No related groups.',
        'related' => 'Related',
        'see_more' => 'View',
        'until' => 'Until',
        'editor' => 'Editor',
        'general' => 'General',
        'open' => 'Open',
    ],
    'confirmations' => [
        'delete_scale_type' => 'Are you sure you want to delete this schedule type?',
        'delete_group' => 'Are you sure you want to delete this group?',
        'delete_department' => 'Are you sure you want to delete this department?',
        'delete_sector' => 'Are you sure you want to delete this sector?',
        'delete_course' => 'Are you sure you want to delete this course?',
        'delete_cell' => 'Are you sure you want to delete this cell group?',
        'delete_cashier' => 'Delete the cash box :name? All entries will be removed.',
    ],
    'sections' => [
        'cults' => [
            'title' => 'Services',
            'subtitle' => 'Upcoming scheduled services:',
            'labels' => [
                'preacher' => 'Preacher',
                'event' => 'Event',
                'none' => 'None',
            ],
            'messages' => [
                'no_upcoming' => 'No services scheduled for the next days.',
            ],
            'buttons' => [
                'schedule' => 'Schedule service',
                'agenda' => 'Upcoming services',
                'register' => 'Register',
                'history' => 'History',
            ],
        ],
        'scales' => [
            'title' => 'Schedules',
            'subtitle' => 'Registered schedule types:',
            'messages' => [
                'empty' => 'No schedule type has been registered yet.',
            ],
            'buttons' => [
                'view' => 'View schedules',
                'new_type' => 'New schedule type',
                'generate' => 'Generate schedule',
                'print' => 'Print',
            ],
        ],
        'events' => [
            'title' => 'Events',
            'subtitle' => 'Upcoming scheduled events:',
            'messages' => [
                'no_upcoming' => 'No events scheduled for the next days.',
            ],
            'buttons' => [
                'new' => 'New event',
                'history' => 'Event history',
                'agenda' => 'Upcoming events',
            ],
        ],
        'meetings' => [
            'title' => 'Meetings',
            'subtitle' => 'Upcoming scheduled meetings:',
            'messages' => [
                'no_upcoming' => 'No meetings scheduled for the next days.',
            ],
            'buttons' => [
                'new' => 'New meeting',
                'history' => 'History',
                'agenda' => 'Upcoming meetings',
            ],
        ],
        'research' => [
            'title' => 'Surveys',
            'subtitle' => 'Open surveys:',
            'messages' => [
                'empty' => 'No surveys open at the moment.',
                'no_responsible' => 'Owner not informed',
            ],
            'buttons' => [
                'new' => 'New survey',
                'panel' => 'Survey dashboard',
            ],
        ],
        'visitors' => [
            'title' => 'Visitors',
            'labels' => [
                'month' => 'Visits this month:',
                'none' => 'There is no visitor history yet.',
            ],
            'buttons' => [
                'new' => 'Register visitor',
                'history' => 'Visitor history',
            ],
        ],
        'groups' => [
            'title' => 'Groups',
            'messages' => [
                'empty' => 'No group has been created so far.',
            ],
            'buttons' => [
                'new' => 'New group',
                'print' => 'Print list',
                'members' => 'Members',
            ],
        ],
        'departments' => [
            'title' => 'Departments',
            'messages' => [
                'empty' => 'No department has been added yet.',
            ],
            'buttons' => [
                'new' => 'New department',
                'print' => 'Print list',
                'team' => 'Team',
            ],
        ],
        'sectors' => [
            'title' => 'Sectors',
            'messages' => [
                'empty' => 'No sector has been registered yet.',
                'no_description' => 'No description provided.',
            ],
            'labels' => [
                'related' => 'Related',
            ],
            'buttons' => [
                'view' => 'View',
                'edit' => 'Edit',
                'new' => 'New sector',
            ],
        ],
        'finance' => [
            'title' => 'Financial control',
            'labels' => [
                'current_balance' => 'Current balance',
                'entries' => 'Income',
                'exits' => 'Expenses',
                'recent' => 'Recent entries',
            ],
            'messages' => [
                'no_entries' => 'No entries recorded.',
                'no_cashier' => 'No cash boxes registered. Create one to start tracking transactions.',
            ],
            'buttons' => [
                'new_cashier' => 'New cash box',
                'new_type' => 'Entry type',
            ],
        ],
        'courses' => [
            'title' => 'Courses',
            'messages' => [
                'empty' => 'No course has been registered yet.',
            ],
            'buttons' => [
                'new' => 'New course',
                'print' => 'Print list',
            ],
        ],
        'cells' => [
            'title' => 'Cell groups',
            'labels' => [
                'meeting' => 'Weekly meeting',
            ],
            'messages' => [
                'empty' => 'No record has been created yet.',
            ],
            'buttons' => [
                'view' => 'View',
                'edit' => 'Edit',
                'new' => 'New cell group',
                'print' => 'Print report',
            ],
        ],
    ],
];
