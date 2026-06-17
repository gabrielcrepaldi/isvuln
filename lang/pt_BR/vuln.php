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

    // Consulta de CVE na NVD
    'nvd_lookup'        => 'Consultar',
    'nvd_looking_up'    => 'Consultando…',
    'nvd_invalid_cve'   => 'Informe um ID CVE válido (ex.: CVE-2024-1234).',
    'nvd_filled'        => 'Campos preenchidos pela NVD.',
    'nvd_failed'        => 'Consulta falhou.',
    'nvd_network_error' => 'Erro de rede — não foi possível acessar a NVD.',
    'nvd_cvss'          => 'CVSS',
    'nvd_bad_format'    => 'Formato de ID CVE inválido.',
    'nvd_api_failed'    => 'A requisição à API da NVD falhou.',
    'nvd_not_found'     => 'CVE não encontrado na base da NVD.',

    // Verificação de URL no VirusTotal
    'vt_scan'              => 'Verificar Alvo',
    'vt_scanning'          => 'Verificando…',
    'vt_verdict_malicious' => 'Malicioso',
    'vt_verdict_suspicious'=> 'Suspeito',
    'vt_verdict_clean'     => 'Limpo',
    'vt_flagged'           => ':count / :total mecanismos sinalizaram esta URL',
    'vt_reputation'        => 'Reputação',
    'vt_last_analysis'     => 'Última análise',
    'vt_no_target'         => 'Esta descoberta não possui URL de alvo para verificar.',
    'vt_not_analyzed'      => 'Esta URL ainda não foi analisada pelo VirusTotal.',
    'vt_api_failed'        => 'A requisição ao VirusTotal falhou.',
    'vt_no_key'            => 'A chave de API do VirusTotal não está configurada.',
    'vt_network_error'     => 'Erro de rede — não foi possível acessar o VirusTotal.',

    // Arquivos de evidência
    'ev_heading'    => 'Arquivos de Evidência',
    'ev_upload'     => 'Enviar',
    'ev_delete'     => 'Excluir',
    'ev_uploaded'   => 'Evidência enviada.',
    'ev_deleted'    => 'Evidência excluída.',
    'ev_error'      => 'Não foi possível enviar o arquivo. Escolha um arquivo válido e permitido.',
    'ev_error_size' => 'O arquivo é muito grande. O tamanho máximo permitido é 20 MB.',

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
