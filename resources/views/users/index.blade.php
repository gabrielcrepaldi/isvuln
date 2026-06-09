<x-app-sidebar-layout title="{{ __('users.management') }}">

    @push('head')
    <style>
      .page-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px}
      .btn-amber{background:var(--amber);color:#1a1200;font-family:'Sora',sans-serif;font-weight:600;font-size:14px;padding:10px 18px;border:none;border-radius:9px;cursor:pointer;transition:all .16s;display:inline-block}
      .btn-amber:hover{background:var(--amber-2)}
      .flash{padding:12px 16px;border-radius:10px;font-size:14px;margin-bottom:20px}
      .flash.ok{background:rgba(63,176,106,0.12);border:1px solid rgba(63,176,106,0.3);color:var(--ok)}
      .flash.err{background:rgba(240,86,79,0.12);border:1px solid rgba(240,86,79,0.3);color:var(--crit)}
      .tbl-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);overflow:hidden}
      table{width:100%;border-collapse:collapse}
      thead th{text-align:left;font-family:'JetBrains Mono',monospace;font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:var(--muted-2);padding:14px 22px;border-bottom:1px solid var(--line);background:var(--surface-2)}
      tbody td{padding:14px 22px;border-bottom:1px solid var(--line-soft);font-size:14px}
      tbody tr:last-child td{border-bottom:none}
      tbody tr:hover{background:var(--panel)}
      .role{font-family:'JetBrains Mono',monospace;font-size:10.5px;font-weight:700;padding:3px 9px;border-radius:5px}
      .role.admin{color:var(--crit);background:rgba(240,86,79,0.13)}
      .role.analyst{color:var(--med);background:rgba(240,179,44,0.13)}
      .role.viewer{color:var(--low);background:rgba(63,147,230,0.13)}
      .role.none{color:var(--muted);background:rgba(138,153,179,0.13)}
      .email{color:var(--muted)}
      .act{display:flex;gap:14px;align-items:center}
      .act a,.act button{font-size:13px;background:none;border:none;cursor:pointer;font-family:'Sora',sans-serif}
      .act .edit{color:var(--med)}
      .act .del{color:var(--crit)}
      .act a:hover,.act button:hover{text-decoration:underline}
      .you{color:var(--muted-2);font-size:12px}
      .pager{margin-top:18px}
    </style>
    @endpush

    <div class="page-head">
        <div></div>
        <a href="{{ route('users.create') }}" class="btn-amber">+ {{ __('users.new') }}</a>
    </div>

    @if(session('success'))<div class="flash ok">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="flash err">{{ session('error') }}</div>@endif

    <div class="tbl-card">
        <table>
            <thead>
                <tr>
                    <th>{{ __('users.f_name') }}</th>
                    <th>{{ __('users.f_email') }}</th>
                    <th>{{ __('users.f_role') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    @php $roleName = $user->roles->first()?->name ?? 'none'; @endphp
                    <tr>
                        <td style="color:var(--text)">{{ $user->name }}</td>
                        <td><span class="email">{{ $user->email }}</span></td>
                        <td><span class="role {{ $roleName }}">{{ strtoupper(__('app.role.' . $roleName)) }}</span></td>
                        <td>
                            <div class="act">
                                <a class="edit" href="{{ route('users.edit', $user) }}">{{ __('app.edit') }}</a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user) }}" method="POST"
                                          onsubmit="return confirm('{{ __('app.confirm_delete') }}')" style="display:inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="del">{{ __('app.delete') }}</button>
                                    </form>
                                @else
                                    <span class="you">{{ __('users.you') }}</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pager">{{ $users->links() }}</div>

</x-app-sidebar-layout>
