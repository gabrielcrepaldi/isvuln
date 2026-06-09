{{-- Shared field styles + fields for create/edit. Expects $vuln (model or null) and $analysts. --}}
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
</style>

<div class="field">
    <label>Title</label>
    <input type="text" name="title" value="{{ old('title', $vuln->title ?? '') }}">
    @error('title')<p class="err">{{ $message }}</p>@enderror
</div>

<div class="field">
    <label>Description</label>
    <textarea name="description" rows="4">{{ old('description', $vuln->description ?? '') }}</textarea>
    @error('description')<p class="err">{{ $message }}</p>@enderror
</div>

<div class="field two">
    <div>
        <label>Severity</label>
        <select name="severity">
            @foreach(['critical','high','medium','low','info'] as $sev)
                <option value="{{ $sev }}" {{ old('severity', $vuln->severity ?? '') === $sev ? 'selected' : '' }}>{{ ucfirst($sev) }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label>Status</label>
        <select name="status">
            @foreach(['open','in_progress','resolved','accepted_risk'] as $st)
                <option value="{{ $st }}" {{ old('status', $vuln->status ?? 'open') === $st ? 'selected' : '' }}>{{ str_replace('_',' ', ucfirst($st)) }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="field two">
    <div>
        <label>Target</label>
        <input type="text" name="target" class="mono" placeholder="https://example.com/login" value="{{ old('target', $vuln->target ?? '') }}">
        @error('target')<p class="err">{{ $message }}</p>@enderror
    </div>
    <div>
        <label>CVE ID <span class="opt">(optional)</span></label>
        <input type="text" name="cve_id" class="mono" placeholder="CVE-2024-1234" value="{{ old('cve_id', $vuln->cve_id ?? '') }}">
    </div>
</div>

<div class="field">
    <label>Assign to <span class="opt">(optional)</span></label>
    <select name="assigned_to">
        <option value="">— Unassigned —</option>
        @foreach($analysts as $analyst)
            <option value="{{ $analyst->id }}" {{ old('assigned_to', $vuln->assigned_to ?? '') == $analyst->id ? 'selected' : '' }}>
                {{ $analyst->name }} ({{ $analyst->email }})
            </option>
        @endforeach
    </select>
</div>

<div class="field">
    <label>Proof of Concept <span class="opt">(optional)</span></label>
    <textarea name="proof_of_concept" rows="3" class="mono" placeholder="Steps to reproduce, payloads, etc.">{{ old('proof_of_concept', $vuln->proof_of_concept ?? '') }}</textarea>
</div>
