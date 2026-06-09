@php
    $sevClass = [
        'critical' => 'critical', 'high' => 'high', 'medium' => 'medium', 'low' => 'low', 'info' => 'info',
    ];
@endphp

<x-app-sidebar-layout title="{{ __('vuln.title_plural') }}">

    @push('head')
    <style>
      .page-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px}
      .btn-amber{background:var(--amber);color:#1a1200;font-family:'Sora',sans-serif;font-weight:600;font-size:14px;padding:10px 18px;border:none;border-radius:9px;cursor:pointer;transition:all .16s;display:inline-block}
      .btn-amber:hover{background:var(--amber-2)}
      .flash{background:rgba(63,176,106,0.12);border:1px solid rgba(63,176,106,0.3);color:var(--ok);padding:12px 16px;border-radius:10px;font-size:14px;margin-bottom:20px}
      .tbl-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);overflow:hidden}
      table{width:100%;border-collapse:collapse}
      thead th{text-align:left;font-family:'JetBrains Mono',monospace;font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:var(--muted-2);padding:14px 22px;border-bottom:1px solid var(--line);background:var(--surface-2)}
      tbody td{padding:14px 22px;border-bottom:1px solid var(--line-soft);font-size:14px}
      tbody tr:last-child td{border-bottom:none}
      tbody tr:hover{background:var(--panel)}
      .sev{font-family:'JetBrains Mono',monospace;font-size:10.5px;font-weight:700;padding:3px 9px;border-radius:5px;white-space:nowrap}
      .sev.critical{color:var(--crit);background:rgba(240,86,79,0.13)}
      .sev.high{color:var(--high);background:rgba(240,122,60,0.13)}
      .sev.medium{color:var(--med);background:rgba(240,179,44,0.13)}
      .sev.low{color:var(--low);background:rgba(63,147,230,0.13)}
      .sev.info{color:var(--muted);background:rgba(138,153,179,0.13)}
      .tgt{font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--muted)}
      .st{font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--muted)}
      .act{display:flex;gap:14px}
      .act a,.act button{font-size:13px;background:none;border:none;cursor:pointer;font-family:'Sora',sans-serif}
      .act .view{color:var(--low)}
      .act .edit{color:var(--med)}
      .act .del{color:var(--crit)}
      .act a:hover,.act button:hover{text-decoration:underline}
      .empty{padding:40px;text-align:center;color:var(--muted-2)}
      .pager{margin-top:18px}
      .pager a,.pager span{color:var(--muted)}
    </style>
    @endpush

    <div class="page-head">
        <div></div>
        @can('create', App\Models\Vulnerability::class)
            <a href="{{ route('vulnerabilities.create') }}" class="btn-amber">+ {{ __('vuln.new') }}</a>
        @endcan
    </div>

    @if(session('success'))
        <div class="flash">{{ session('success') }}</div>
    @endif

    <div class="tbl-card">
        <table>
            <thead>
                <tr>
		<th>{{ __('vuln.f_severity') }}</th><th>{{ __('vuln.f_title') }}</th><th>{{ __('vuln.f_target') }}</th><th>{{ __('vuln.f_status') }}</th><th>{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vulnerabilities as $vuln)
                    <tr>
                        <td><span class="sev {{ $vuln->severity }}">{{ strtoupper(__('vuln.severity.' . $vuln->severity)) }}</span></td>
			<td style="color:var(--text)">{{ $vuln->title }}</td>
                        <td><span class="tgt">{{ $vuln->target }}</span></td>
                        <td><span class="st">{{ strtoupper(__('vuln.status.' . $vuln->status)) }}</span></td>
			<td>
                            <div class="act">
                                
				<a class="view" href="{{ route('vulnerabilities.show', $vuln) }}">{{ __('app.view') }}</a>
				@can('update', $vuln)
                                    <a class="edit" href="{{ route('vulnerabilities.edit', $vuln) }}">{{ __('app.edit') }}</a>
                                @endcan
                                @can('delete', $vuln)
                                    <form action="{{ route('vulnerabilities.destroy', $vuln) }}" method="POST"
                                          onsubmit="return confirm('{{ __('app.confirm_delete') }}')" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="del">{{ __('app.delete') }}</button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty">{{ __('vuln.none_yet') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pager">{{ $vulnerabilities->links() }}</div>

</x-app-sidebar-layout>
