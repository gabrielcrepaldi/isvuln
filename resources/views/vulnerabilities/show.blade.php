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
      .btn-scan{background:var(--panel);color:var(--text);border:1px solid var(--line);border-radius:8px;padding:7px 14px;font-family:'Sora',sans-serif;font-weight:600;font-size:13px;cursor:pointer;margin-top:8px;transition:border-color .15s}
      .btn-scan:hover{border-color:var(--amber)}
      .btn-scan:disabled{opacity:.5;cursor:not-allowed}
      .vt-panel{margin-top:14px;background:var(--panel);border:1px solid var(--line);border-radius:9px;padding:16px}
      .vt-verdict{display:inline-block;font-family:'JetBrains Mono',monospace;font-size:11px;font-weight:700;padding:4px 11px;border-radius:6px;margin-bottom:10px}
      .vt-verdict.malicious{color:var(--crit);background:rgba(240,86,79,0.13)}
      .vt-verdict.suspicious{color:var(--med);background:rgba(240,179,44,0.13)}
      .vt-verdict.clean{color:var(--ok);background:rgba(63,230,138,0.13)}
      .vt-stat{font-size:14px;color:var(--text);margin-top:4px}
      .vt-meta{font-size:12px;color:var(--muted);margin-top:8px;font-family:'JetBrains Mono',monospace}
      .vt-msg{font-size:13px;margin-top:8px}
      .vt-msg.err{color:var(--crit)}
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
                <button type="button" class="btn-scan" id="vt-scan-btn">{{ __('vuln.vt_scan') }}</button>
                <div id="vt-panel" class="vt-panel" style="display:none">
                    <span id="vt-verdict" class="vt-verdict"></span>
                    <div id="vt-stat" class="vt-stat"></div>
                    <div id="vt-meta" class="vt-meta"></div>
                </div>
                <p id="vt-msg" class="vt-msg" style="display:none"></p>
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

    <script>
    document.getElementById('vt-scan-btn').addEventListener('click', async function () {
        const btn = this;
        const panel = document.getElementById('vt-panel');
        const msg = document.getElementById('vt-msg');

        panel.style.display = 'none';
        msg.style.display = 'none';
        btn.disabled = true;
        btn.textContent = @json(__('vuln.vt_scanning'));

        try {
            const res = await fetch('{{ route('vulnerabilities.scan', $vulnerability) }}', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();

            if (!res.ok) {
                msg.textContent = data.error ?? @json(__('vuln.vt_api_failed'));
                msg.className = 'vt-msg err';
                msg.style.display = 'block';
                return;
            }

            const verdictLabels = {
                malicious:  @json(__('vuln.vt_verdict_malicious')),
                suspicious: @json(__('vuln.vt_verdict_suspicious')),
                clean:      @json(__('vuln.vt_verdict_clean')),
            };

            const verdict = document.getElementById('vt-verdict');
            verdict.textContent = verdictLabels[data.verdict] ?? data.verdict;
            verdict.className = 'vt-verdict ' + data.verdict;

            const flagged = data.malicious + data.suspicious;
            document.getElementById('vt-stat').textContent =
                @json(__('vuln.vt_flagged'))
                    .replace(':count', flagged)
                    .replace(':total', data.total);

            const metaParts = [
                @json(__('vuln.vt_reputation')) + ': ' + data.reputation,
            ];
            if (data.last_analysis_date) {
                metaParts.push(@json(__('vuln.vt_last_analysis')) + ': ' + data.last_analysis_date);
            }
            document.getElementById('vt-meta').textContent = metaParts.join('  •  ');

            panel.style.display = 'block';
        } catch (e) {
            msg.textContent = @json(__('vuln.vt_network_error'));
            msg.className = 'vt-msg err';
            msg.style.display = 'block';
        } finally {
            btn.disabled = false;
            btn.textContent = @json(__('vuln.vt_scan'));
        }
    });
    </script>


@can('update', $vulnerability)
@push('head')
<style>
  .ev-file::file-selector-button{
    background:var(--amber);color:#1a1200;font-family:'Sora',sans-serif;font-weight:600;
    font-size:13px;border:none;border-radius:7px;padding:7px 14px;margin-right:12px;cursor:pointer;
    transition:background .15s;
  }
  .ev-file::file-selector-button:hover{background:var(--amber-2)}
</style>
@endpush
<div style="max-width:820px;margin-top:24px;background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);padding:24px">
    <div class="lbl" style="margin-bottom:14px">{{ __('vuln.ev_heading') }}</div>

    <form action="{{ route('evidence.store', $vulnerability) }}" method="POST" enctype="multipart/form-data" style="display:flex;gap:10px;margin-bottom:18px">
        @csrf
        <input type="file" name="evidence" required class="ev-file"
               style="flex:1;background:var(--bg);border:1px solid var(--line);border-radius:9px;color:var(--text);padding:9px 12px;font-size:13px">
        <button type="submit" class="btn-amber">{{ __('vuln.ev_upload') }}</button>
    </form>

    @error('evidence')
        <p style="color:var(--crit);font-size:13px;margin-top:-8px;margin-bottom:18px">{{ $message }}</p>
    @enderror

    @foreach($vulnerability->evidenceFiles as $ev)
        <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--line-soft);font-size:13px">
            <a href="{{ route('evidence.download', $ev) }}" style="flex:1;color:var(--low)" class="mono">{{ $ev->original_name }}</a>
            <span class="mono" style="color:var(--muted-2)">{{ number_format($ev->size / 1024, 1) }} KB</span>
            <form action="{{ route('evidence.destroy', $ev) }}" method="POST" style="display:inline">
                @csrf @method('DELETE')
                <button type="submit" style="background:none;border:none;color:var(--crit);cursor:pointer;font-size:13px">{{ __('vuln.ev_delete') }}</button>
            </form>
        </div>
    @endforeach
</div>
@endcan
</x-app-sidebar-layout>
