<x-app-sidebar-layout title="{{ __('users.new') }}">

    @push('head')
    <style>
      .form-card{max-width:560px;background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);padding:32px}
      .uform label{display:block;color:var(--muted);font-size:14px;font-weight:500;margin-bottom:6px}
      .uform input,.uform select{width:100%;background:var(--bg);border:1px solid var(--line);border-radius:9px;color:var(--text);padding:11px 14px;font-size:14.5px;font-family:'Sora',sans-serif;transition:border-color .15s}
      .uform input:focus,.uform select:focus{outline:none;border-color:var(--amber);box-shadow:0 0 0 3px rgba(255,176,40,0.12)}
      .uform .field{margin-bottom:18px}
      .uform .err{color:var(--crit);font-size:13px;margin-top:6px}
      .form-actions{display:flex;gap:12px;margin-top:8px}
      .btn-amber{background:var(--amber);color:#1a1200;font-family:'Sora',sans-serif;font-weight:600;font-size:14.5px;padding:11px 22px;border:none;border-radius:9px;cursor:pointer;transition:all .16s}
      .btn-amber:hover{background:var(--amber-2)}
      .btn-cancel{background:transparent;color:var(--muted);border:1px solid var(--line);font-family:'Sora',sans-serif;font-weight:500;font-size:14.5px;padding:11px 22px;border-radius:9px;text-decoration:none;transition:all .14s}
      .btn-cancel:hover{border-color:var(--muted-2);color:var(--text)}
    </style>
    @endpush

    <div class="form-card uform">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="field">
                <label>{{ __('users.f_name') }}</label>
                <input type="text" name="name" value="{{ old('name') }}">
                @error('name')<p class="err">{{ $message }}</p>@enderror
            </div>

            <div class="field">
                <label>{{ __('users.f_email') }}</label>
                <input type="email" name="email" value="{{ old('email') }}">
                @error('email')<p class="err">{{ $message }}</p>@enderror
            </div>

            <div class="field">
                <label>{{ __('users.f_role') }}</label>
                <select name="role">
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                            {{ __('app.role.' . $role->name) }}
                        </option>
                    @endforeach
                </select>
                @error('role')<p class="err">{{ $message }}</p>@enderror
            </div>

            <div class="field">
                <label>{{ __('users.f_password') }}</label>
                <input type="password" name="password">
                @error('password')<p class="err">{{ $message }}</p>@enderror
            </div>

            <div class="field">
                <label>{{ __('users.f_password_confirm') }}</label>
                <input type="password" name="password_confirmation">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-amber">{{ __('users.create_btn') }}</button>
                <a href="{{ route('users.index') }}" class="btn-cancel">{{ __('app.cancel') }}</a>
            </div>
        </form>
    </div>

</x-app-sidebar-layout>
