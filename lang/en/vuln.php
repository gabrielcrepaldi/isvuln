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

    // NVD CVE lookup
    'nvd_lookup'        => 'Lookup',
    'nvd_looking_up'    => 'Looking up…',
    'nvd_invalid_cve'   => 'Enter a valid CVE ID (e.g. CVE-2024-1234).',
    'nvd_filled'        => 'Fields filled from NVD.',
    'nvd_failed'        => 'Lookup failed.',
    'nvd_network_error' => 'Network error — could not reach NVD.',
    'nvd_cvss'          => 'CVSS',
    'nvd_bad_format'    => 'Invalid CVE ID format.',
    'nvd_api_failed'    => 'NVD API request failed.',
    'nvd_not_found'     => 'CVE not found in NVD database.',

    // VirusTotal URL scan
    'vt_scan'              => 'Scan Target',
    'vt_scanning'          => 'Scanning…',
    'vt_verdict_malicious' => 'Malicious',
    'vt_verdict_suspicious'=> 'Suspicious',
    'vt_verdict_clean'     => 'Clean',
    'vt_flagged'           => ':count / :total engines flagged this URL',
    'vt_reputation'        => 'Reputation',
    'vt_last_analysis'     => 'Last analysis',
    'vt_no_target'         => 'This finding has no target URL to scan.',
    'vt_not_analyzed'      => 'This URL has not been analyzed by VirusTotal yet.',
    'vt_api_failed'        => 'VirusTotal request failed.',
    'vt_no_key'            => 'VirusTotal API key is not configured.',
    'vt_network_error'     => 'Network error — could not reach VirusTotal.',

    // Evidence files
    'ev_heading'    => 'Evidence Files',
    'ev_upload'     => 'Upload',
    'ev_delete'     => 'Delete',
    'ev_uploaded'   => 'Evidence uploaded.',
    'ev_deleted'    => 'Evidence deleted.',
    'ev_error'      => 'The file could not be uploaded. Please choose a valid, permitted file.',
    'ev_error_size' => 'The file is too large. The maximum allowed size is 20 MB.',

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
