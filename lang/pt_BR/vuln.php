<?php

return [
    'title_plural'   => 'Vulnerabilidades',
    'new'            => 'Nova Descoberta',
    'edit'           => 'Editar Vulnerabilidade',
    'create'         => 'Nova Vulnerabilidade',
    'detail'         => 'Detalhe da Descoberta',
    'create_btn'     => 'Criar Descoberta',
    'update_btn'     => 'Atualizar Descoberta',
    'none_yet'       => 'Nenhuma vulnerabilidade registrada ainda.',
    'back_to_list'   => 'Voltar à lista',

    // Field labels
    'f_title'        => 'Título',
    'f_description'  => 'Descrição',
    'f_severity'     => 'Severidade',
    'f_status'       => 'Status',
    'f_target'       => 'Alvo',
    'f_cve'          => 'ID CVE',
    'f_assign'       => 'Atribuir a',
    'f_poc'          => 'Prova de Conceito',
    'optional'       => '(opcional)',
    'unassigned'     => 'Não atribuído',
    'reported_by'    => 'Reportado por',
    'assigned_to'    => 'Atribuído a',
    'poc_placeholder'=> 'Passos para reproduzir, payloads, etc.',

    // Severity (display only — DB keeps English)
    'severity' => [
        'critical' => 'Crítica',
        'high'     => 'Alta',
        'medium'   => 'Média',
        'low'      => 'Baixa',
        'info'     => 'Informativa',
    ],

    // Status (display only — DB keeps English)
    'status' => [
        'open'          => 'Aberta',
        'in_progress'   => 'Em Progresso',
        'resolved'      => 'Resolvida',
        'accepted_risk' => 'Risco Aceito',
    ],
];
