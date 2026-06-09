<?php

return [
    'title_plural'   => 'Vulnerabilities',
    'new'            => 'New Finding',
    'edit'           => 'Edit Vulnerability',
    'create'         => 'New Vulnerability',
    'detail'         => 'Finding Detail',
    'create_btn'     => 'Create Finding',
    'update_btn'     => 'Update Finding',
    'none_yet'       => 'No vulnerabilities logged yet.',
    'back_to_list'   => 'Back to list',

    // Field labels
    'f_title'        => 'Title',
    'f_description'  => 'Description',
    'f_severity'     => 'Severity',
    'f_status'       => 'Status',
    'f_target'       => 'Target',
    'f_cve'          => 'CVE ID',
    'f_assign'       => 'Assign to',
    'f_poc'          => 'Proof of Concept',
    'optional'       => '(optional)',
    'unassigned'     => 'Unassigned',
    'reported_by'    => 'Reported by',
    'assigned_to'    => 'Assigned to',
    'poc_placeholder'=> 'Steps to reproduce, payloads, etc.',

    // Severity (display only — DB keeps English)
    'severity' => [
        'critical' => 'Critical',
        'high'     => 'High',
        'medium'   => 'Medium',
        'low'      => 'Low',
        'info'     => 'Info',
    ],

    // Status (display only — DB keeps English)
    'status' => [
        'open'          => 'Open',
        'in_progress'   => 'In Progress',
        'resolved'      => 'Resolved',
        'accepted_risk' => 'Accepted Risk',
    ],
];
