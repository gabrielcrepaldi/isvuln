<?php

return [
    'title'             => 'Audit Log',
    'filters'           => 'Filters',
    'filter'            => 'Filter',

    // Action names
    'action' => [
        'created'      => 'Created',
        'updated'      => 'Updated',
        'deleted'      => 'Deleted',
        'login'        => 'Login',
        'logout'       => 'Logout',
        'login_failed' => 'Login failed',
    ],

    'anonymous'         => 'anonymous',
    'resource'          => 'Resource',
    'changes'           => 'Changes',
    'no_logs'           => 'No audit log entries match your filters.',
    'search_placeholder'=> 'Search type or IP…',
    'all_actions'       => 'All actions',
    'all_users'         => 'All users',

    // Table column headers
    'col_time'          => 'Timestamp',
    'col_user'          => 'User',
    'col_ip'            => 'IP address',
    'col_action'        => 'Action',
];
