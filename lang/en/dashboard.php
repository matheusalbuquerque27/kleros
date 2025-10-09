<?php

return [
    'title' => 'General Overview',
    'general' => [
        'heading' => 'General Data',
        'cards' => [
            'today' => [
                'label' => 'Today',
            ],
            'service' => [
                'label' => 'Service of the day',
                'unknown_preacher' => 'Speaker not informed',
                'no_event' => 'Service without related event',
                'no_service' => 'No service recorded',
                'cta' => 'Schedule now',
            ],
            'members_active' => [
                'label' => 'Active members',
                'small' => 'Total on record: :total',
            ],
            'new_month' => [
                'label' => 'New this month',
                'small' => 'Joined in :period',
                'period_format' => 'MMM/Y',
            ],
            'members_groups' => [
                'label' => 'Members in groups',
                'small' => ':count without link',
            ],
            'visitors' => [
                'label' => 'Visitors',
                'small' => ':count since the beginning',
            ],
            'structure' => [
                'label' => 'Structure',
                'small' => ':groups groups · :cells cells',
            ],
            'organization' => [
                'label' => 'Internal organization',
                'small' => ':departments departments · :sectors sectors',
            ],
        ],
    ],
    'chart' => [
        'title' => 'Members per group',
        'subtitle' => 'Upcoming services: :services · Scheduled events: :events',
        'empty' => 'There are no members linked to groups yet.',
    ],
    'recados' => [
        'heading' => 'Announcements',
        'empty' => 'No new announcements.',
        'sent_by' => 'Sent by :name',
    ],
    'events' => [
        'heading' => 'Events',
        'empty' => 'No events scheduled for the next days.',
        'general_owner' => 'General',
    ],
    'birthdays' => [
        'heading' => 'Birthdays',
        'empty' => 'No birthdays this month.',
        'no_ministry' => 'Not assigned',
    ],
    'visitors' => [
        'heading' => 'Visitors',
        'empty' => 'No visitors have checked in yet.',
    ],
    'days' => [
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
    ],
    'numbers' => [
        'decimal' => '.',
        'thousand' => ',',
    ],
    'intl_locale' => 'en-US',
];
