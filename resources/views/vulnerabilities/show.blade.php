<x-app-sidebar-layout title="{{ __('vuln.detail') }}">

    @push('head')
    <style>
      .detail-head{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:20px}
      .detail-head h2{font-size:22px;font-weight:700;letter-spacing:-.01em;max-width:70%}
      .btn-amber{background:var(--amber);color:#1a1200;font-family:'Sora',sans-serif;font-weight:600;font-size:14px;padding:10px 18px;border-radius:9px;text-decoration:none}
      .btn-amber:hover{background:var(--amber-2)}
      .detail-card{max-width:820px;background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);padding:30px}
      .badges{display:flex;gap:10px;margin-bottom:24px}
      .sev{font-family:'JetBrains Mono',monospace;font-size:11px;font-weight:700;padding:4px 11px;border-radius:6px}
      .sev.critical{color:var(--crit);background:rgba(240,86,79,0.13)}
      .sev.high{color:var(--high);background:rgba(240,122,60,0.13)}
      .sev.medium{color:var(--med);background:rgba(240,179,44,0.13)}
      .sev.low{color:var(--low);background:rgba(63,147,230,0.13)}
      .sev.info{color:var(--muted);background:rgba(138,153,179,0.13)}
      .stbadge{font-family:'JetBrains Mono',monospace;font-size:11px;font-weight:700;padding:4px 11px;border-radius:6px;color:var(--muted);background:var(--panel)}
      .lbl{font-size:12px;color:var(--muted-2);text-transform:uppercase;letter-spacing:.07em;font-family:'JetBrains Mono',monospace}
      .val{color:var(--text);margin-top:5px;white-space:pre-line}
      .block{margin-bottom:22px}
      .two{display:grid;grid-template-columns:1fr 1fr;gap:20px}
      .mono{font-family:'JetBrains Mono',monospace;font-size:13px}
      .poc{background:#05080d;border:1px solid var(--line);color:var(--ok);padding:16px;border-radius:9px;font-family:'JetBrains Mono',monospace;font-size:13px;white-space:pre-wrap;margin-top:6px}
      .divider{border-top:1px solid var(--line-soft);padding-top:20px}
      .back{display:inline-block;margin-top:22px;color:var(--amber);font-size:14px}
    </style>
    @endpush

    <div class="detail-head">
        <h2>{{ $vulnerability->title }}</h2>
        @can('update', $vulnerability)
            <a href="{{ route('vulnerabilities.edit', $vulnerability) }}" class="btn-amber">{{ __('app.edit') }}</a>
        @endcan
    </div>

    <div class="detail-card">
        <div class="badges">
            <span class="sev {{ $vulnerability->severity }}">{{ strtoupper(__('vuln.severity.' . $vulnerability->severity)) }}</span>
            <span class="stbadge">{{ strtoupper(__('vuln.status.' . $vulnerability->status)) }}</span>
        </div>

        <div class="block">
            <div class="lbl">{{ __('vuln.f_description') }}</div>
            <div class="val">{{ $vulnerability->description }}</div>
        </div>

        <div class="block two">
            <div>
                <div class="lbl">{{ __('vuln.f_target') }}</div>
                <div class="val mono">{{ $vulnerability->target }}</div>
            </div>
            <div>
                <div class="lbl">{{ __('vuln.f_cve') }}</div>
                <div class="val mono">{{ $vulnerability->cve_id ?? '—' }}</div>
            </div>
        </div>

        @if($vulnerability->proof_of_concept)
            <div class="block">
                <div class="lbl">{{ __('vuln.f_poc') }}</div>
                <div class="poc">{{ $vulnerability->proof_of_concept }}</div>
            </div>
        @endif

        <div class="block two divider">
            <div>
                <div class="lbl">{{ __('vuln.reported_by') }}</div>
                <div class="val">{{ $vulnerability->creator->name ?? '—' }}</div>
            </div>
            <div>
                <div class="lbl">{{ __('vuln.assigned_to') }}</div>
                <div class="val">{{ $vulnerability->assignee->name ?? __('vuln.unassigned') }}</div>
            </div>
        </div>
    </div>

    <a href="{{ route('vulnerabilities.index') }}" class="back">← {{ __('vuln.back_to_list') }}</a>

</x-app-sidebar-layout>
