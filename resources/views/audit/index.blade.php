@php
    // Action badge colour mapping
    $actionClass = [
        'created'      => 'ok',
        'updated'      => 'med',
        'deleted'      => 'crit',
        'login'        => 'low',
        'logout'       => 'muted',
        'login_failed' => 'crit',
    ];
@endphp

<x-app-sidebar-layout title="{{ __('audit.title') }}">

    @push('head')
    <style>
      .filter-bar{display:flex;flex-wrap:wrap;gap:12px;align-items:center;margin-bottom:24px}
      .filter-bar select,.filter-bar input{font-family:'Sora',sans-serif;font-size:14px;color:var(--text);background:var(--surface);border:1px solid var(--line);border-radius:9px;padding:10px 13px;outline:none;transition:border-color .15s}
      .filter-bar select:focus,.filter-bar input:focus{border-color:var(--amber)}
      .filter-bar input::placeholder{color:var(--muted-2)}
      .btn-amber{background:var(--amber);color:#1a1200;font-family:'Sora',sans-serif;font-weight:600;font-size:14px;padding:10px 18px;border:none;border-radius:9px;cursor:pointer;transition:all .16s}
      .btn-amber:hover{background:var(--amber-2)}

      .tbl-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);overflow:hidden}
      table{width:100%;border-collapse:collapse}
      thead th{text-align:left;font-family:'JetBrains Mono',monospace;font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:var(--muted-2);padding:14px 22px;border-bottom:1px solid var(--line);background:var(--surface-2)}
      tbody td{padding:14px 22px;border-bottom:1px solid var(--line-soft);font-size:14px;vertical-align:top}
      tbody tr:last-child td{border-bottom:none}
      tbody tr:hover{background:var(--panel)}

      .ts{font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--muted);white-space:nowrap}
      .ip{font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--muted)}

      .usr{display:flex;flex-direction:column;gap:4px}
      .usr .nm{color:var(--text)}
      .usr .nm.anon{color:var(--muted-2);font-style:italic}
      .rolebadge{font-family:'JetBrains Mono',monospace;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--low);background:rgba(63,147,230,0.13);padding:2px 7px;border-radius:5px;width:fit-content}

      .badge{font-family:'JetBrains Mono',monospace;font-size:10.5px;font-weight:700;padding:3px 9px;border-radius:5px;white-space:nowrap;text-transform:uppercase}
      .badge.ok{color:var(--ok);background:rgba(63,176,106,0.13)}
      .badge.med{color:var(--med);background:rgba(240,179,44,0.13)}
      .badge.crit{color:var(--crit);background:rgba(240,86,79,0.13)}
      .badge.low{color:var(--low);background:rgba(63,147,230,0.13)}
      .badge.muted{color:var(--muted);background:rgba(138,153,179,0.13)}

      .res{font-family:'JetBrains Mono',monospace;font-size:12.5px;color:var(--text)}
      .res .id{color:var(--muted-2)}
      .res .none{color:var(--muted-2)}

      .diff{margin-top:8px;display:flex;flex-direction:column;gap:3px}
      .diff-row{font-family:'JetBrains Mono',monospace;font-size:11.5px;line-height:1.5}
      .diff-row .fld{color:var(--muted);margin-right:6px}
      .diff-row .old{color:var(--crit)}
      .diff-row .arr{color:var(--muted-2);margin:0 5px}
      .diff-row .new{color:var(--ok)}

      .empty{padding:40px;text-align:center;color:var(--muted-2)}
      .pager{margin-top:18px}
      .pager a,.pager span{color:var(--muted)}
    </style>
    @endpush

    <form method="GET" action="{{ route('audit.index') }}" class="filter-bar">
        <select name="action">
            <option value="">{{ __('audit.all_actions') }}</option>
            @foreach($actions as $a)
                <option value="{{ $a }}" @selected(request('action') === $a)>
                    {{ __('audit.action.' . $a) }}
                </option>
            @endforeach
        </select>

        <select name="user_id">
            <option value="">{{ __('audit.all_users') }}</option>
            @foreach($users as $u)
                <option value="{{ $u->id }}" @selected((string) request('user_id') === (string) $u->id)>
                    {{ $u->name }}
                </option>
            @endforeach
        </select>

        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="{{ __('audit.search_placeholder') }}">

        <button type="submit" class="btn-amber">{{ __('audit.filter') }}</button>
    </form>

    <div class="tbl-card">
        <table>
            <thead>
                <tr>
                    <th>{{ __('audit.col_time') }}</th>
                    <th>{{ __('audit.col_user') }}</th>
                    <th>{{ __('audit.col_ip') }}</th>
                    <th>{{ __('audit.col_action') }}</th>
                    <th>{{ __('audit.resource') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    @php
                        $isAnon = is_null($log->user_id);
                        $shortType = $log->auditable_type ? class_basename($log->auditable_type) : null;
                        $role = $log->user?->roles->first()?->name;
                    @endphp
                    <tr>
                        <td><span class="ts">{{ $log->created_at->format('Y-m-d H:i:s') }}</span></td>
                        <td>
                            <div class="usr">
                                @if($isAnon)
                                    <span class="nm anon">{{ __('audit.anonymous') }}</span>
                                @else
                                    <span class="nm">{{ $log->user?->name ?? __('audit.anonymous') }}</span>
                                    @if($role)
                                        <span class="rolebadge">{{ __('app.role.' . $role) }}</span>
                                    @endif
                                @endif
                            </div>
                        </td>
                        <td><span class="ip">{{ $log->ip_address ?? '—' }}</span></td>
                        <td>
                            <span class="badge {{ $actionClass[$log->action] ?? 'muted' }}">
                                {{ __('audit.action.' . $log->action) }}
                            </span>
                        </td>
                        <td>
                            @if($shortType)
                                <span class="res">{{ $shortType }} <span class="id">#{{ $log->auditable_id }}</span></span>
                            @else
                                <span class="res none">—</span>
                            @endif

                            @if($log->action === 'updated' && $log->new_values)
                                <div class="diff">
                                    @foreach($log->new_values as $field => $newVal)
                                        @php $oldVal = data_get($log->old_values, $field); @endphp
                                        <div class="diff-row">
                                            <span class="fld">{{ $field }}:</span>
                                            <span class="old">{{ \Illuminate\Support\Str::limit(is_scalar($oldVal) || is_null($oldVal) ? ($oldVal ?? '∅') : json_encode($oldVal), 60) }}</span>
                                            <span class="arr">→</span>
                                            <span class="new">{{ \Illuminate\Support\Str::limit(is_scalar($newVal) || is_null($newVal) ? ($newVal ?? '∅') : json_encode($newVal), 60) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty">{{ __('audit.no_logs') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pager">{{ $logs->links() }}</div>

</x-app-sidebar-layout>
