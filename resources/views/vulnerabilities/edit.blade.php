<x-app-sidebar-layout title="{{ __('vuln.edit') }}">

    @push('head')
    <style>
      .form-card{max-width:760px;background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);padding:32px}
      .form-actions{display:flex;gap:12px;margin-top:8px}
      .btn-amber{background:var(--amber);color:#1a1200;font-family:'Sora',sans-serif;font-weight:600;font-size:14.5px;padding:11px 22px;border:none;border-radius:9px;cursor:pointer;transition:all .16s}
      .btn-amber:hover{background:var(--amber-2)}
      .btn-cancel{background:transparent;color:var(--muted);border:1px solid var(--line);font-family:'Sora',sans-serif;font-weight:500;font-size:14.5px;padding:11px 22px;border-radius:9px;text-decoration:none;transition:all .14s}
      .btn-cancel:hover{border-color:var(--muted-2);color:var(--text)}
    </style>
    @endpush

    <div class="form-card vform">
        <form action="{{ route('vulnerabilities.update', $vulnerability) }}" method="POST">
            @csrf
            @method('PUT')
            @include('vulnerabilities._form', ['vuln' => $vulnerability])
            <div class="form-actions">
                <button type="submit" class="btn-amber">{{ __('vuln.update_btn') }}</button>
                <a href="{{ route('vulnerabilities.index') }}" class="btn-cancel">{{ __('app.cancel') }}</a>
            </div>
        </form>
    </div>

</x-app-sidebar-layout>
