<style>
  .vform label{display:block;color:var(--muted);font-size:14px;font-weight:500;margin-bottom:6px}
  .vform input[type=text],.vform textarea,.vform select{
    width:100%;background:var(--bg);border:1px solid var(--line);border-radius:9px;
    color:var(--text);padding:11px 14px;font-size:14.5px;font-family:'Sora',sans-serif;transition:border-color .15s}
  .vform textarea{resize:vertical}
  .vform input:focus,.vform textarea:focus,.vform select:focus{outline:none;border-color:var(--amber);box-shadow:0 0 0 3px rgba(255,176,40,0.12)}
  .vform .opt{color:var(--muted-2);font-weight:400}
  .vform .err{color:var(--crit);font-size:13px;margin-top:6px}
  .vform .field{margin-bottom:18px}
  .vform .two{display:grid;grid-template-columns:1fr 1fr;gap:16px}
  .vform .mono{font-family:'JetBrains Mono',monospace;font-size:13px}
  .vform .cve-row{display:flex;gap:8px;align-items:flex-start}
  .vform .cve-row input{flex:1}
  .btn-lookup{background:var(--amber);color:#000;border:none;border-radius:9px;padding:11px 16px;font-size:13px;font-weight:600;cursor:pointer;white-space:nowrap;font-family:'Sora',sans-serif;transition:opacity .15s}
  .btn-lookup:hover{opacity:.85}
  .btn-lookup:disabled{opacity:.5;cursor:not-allowed}
  .cvss-badge{display:inline-block;margin-top:6px;padding:4px 10px;border-radius:6px;font-size:12px;font-weight:600;font-family:'JetBrains Mono',monospace;background:var(--line);color:var(--muted)}
  .nvd-msg{font-size:13px;margin-top:6px}
  .nvd-msg.err{color:var(--crit)}
  .nvd-msg.ok{color:#4ade80}
</style>

<div class="field">
    <label>{{ __('vuln.f_title') }}</label>
    <input type="text" name="title" value="{{ old('title', $vuln->title ?? '') }}">
    @error('title')<p class="err">{{ $message }}</p>@enderror
</div>

<div class="field">
    <label>{{ __('vuln.f_description') }}</label>
    <textarea name="description" rows="4">{{ old('description', $vuln->description ?? '') }}</textarea>
    @error('description')<p class="err">{{ $message }}</p>@enderror
</div>

<div class="field two">
    <div>
        <label>{{ __('vuln.f_severity') }}</label>
        <select name="severity">
            @foreach(['critical','high','medium','low','info'] as $sev)
                <option value="{{ $sev }}" {{ old('severity', $vuln->severity ?? '') === $sev ? 'selected' : '' }}>{{ __('vuln.severity.' . $sev) }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label>{{ __('vuln.f_status') }}</label>
        <select name="status">
            @foreach(['open','in_progress','resolved','accepted_risk'] as $st)
                <option value="{{ $st }}" {{ old('status', $vuln->status ?? 'open') === $st ? 'selected' : '' }}>{{ __('vuln.status.' . $st) }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="field two">
    <div>
        <label>{{ __('vuln.f_target') }}</label>
        <input type="text" name="target" class="mono" placeholder="https://example.com/login" value="{{ old('target', $vuln->target ?? '') }}">
        @error('target')<p class="err">{{ $message }}</p>@enderror
    </div>
    <div>
        <label>{{ __('vuln.f_cve') }} <span class="opt">{{ __('vuln.optional') }}</span></label>
        <div class="cve-row">
            <input type="text" id="cve_id_input" name="cve_id" class="mono" placeholder="CVE-2024-1234" value="{{ old('cve_id', $vuln->cve_id ?? '') }}">
            <button type="button" class="btn-lookup" id="nvd-lookup-btn">{{ __('vuln.nvd_lookup') }}</button>
        </div>
        <span id="cvss-badge" class="cvss-badge" style="display:none"></span>
        <p id="nvd-msg" class="nvd-msg" style="display:none"></p>
    </div>
</div>

<div class="field">
    <label>{{ __('vuln.f_assign') }} <span class="opt">{{ __('vuln.optional') }}</span></label>
    <select name="assigned_to">
        <option value="">— {{ __('vuln.unassigned') }} —</option>
        @foreach($analysts as $analyst)
            <option value="{{ $analyst->id }}" {{ old('assigned_to', $vuln->assigned_to ?? '') == $analyst->id ? 'selected' : '' }}>
                {{ $analyst->name }} ({{ $analyst->email }})
            </option>
        @endforeach
    </select>
</div>

<div class="field">
    <label>{{ __('vuln.f_poc') }} <span class="opt">{{ __('vuln.optional') }}</span></label>
    <textarea name="proof_of_concept" rows="3" class="mono" placeholder="{{ __('vuln.poc_placeholder') }}">{{ old('proof_of_concept', $vuln->proof_of_concept ?? '') }}</textarea>
</div>

<script>
document.getElementById('nvd-lookup-btn').addEventListener('click', async function () {
    const btn = this;
    const cveId = document.getElementById('cve_id_input').value.trim().toUpperCase();
    const msg = document.getElementById('nvd-msg');
    const badge = document.getElementById('cvss-badge');

    msg.style.display = 'none';
    badge.style.display = 'none';

    if (!/^CVE-\d{4}-\d{4,}$/.test(cveId)) {
        msg.textContent = @json(__('vuln.nvd_invalid_cve'));
        msg.className = 'nvd-msg err';
        msg.style.display = 'block';
        return;
    }

    btn.disabled = true;
    btn.textContent = @json(__('vuln.nvd_looking_up'));

    try {
        const res = await fetch(`{{ route('nvd.lookup') }}?cve_id=${encodeURIComponent(cveId)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();

        if (!res.ok) {
            msg.textContent = data.error ?? @json(__('vuln.nvd_failed'));
            msg.className = 'nvd-msg err';
            msg.style.display = 'block';
            return;
        }

        if (data.description) {
            document.querySelector('textarea[name="description"]').value = data.description;
        }

        if (data.severity) {
            const sel = document.querySelector('select[name="severity"]');
            for (const opt of sel.options) {
                if (opt.value === data.severity) { opt.selected = true; break; }
            }
        }

        if (data.cvss_score !== null && data.cvss_score !== undefined) {
            badge.textContent = `{{ __('vuln.nvd_cvss') }} ${data.cvss_version}: ${data.cvss_score}`;
            badge.style.display = 'inline-block';
        }

        msg.textContent = @json(__('vuln.nvd_filled'));
        msg.className = 'nvd-msg ok';
        msg.style.display = 'block';
    } catch (e) {
        msg.textContent = @json(__('vuln.nvd_network_error'));
        msg.className = 'nvd-msg err';
        msg.style.display = 'block';
    } finally {
        btn.disabled = false;
        btn.textContent = @json(__('vuln.nvd_lookup'));
    }
});
</script>
