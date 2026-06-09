<x-guest-layout>
    <h1 style="font-size:22px;font-weight:700;margin-bottom:6px;">Welcome back</h1>
    <p style="color:var(--muted);font-size:14px;margin-bottom:26px;">Log in to your ISVuln account.</p>

    <!-- Session Status -->
    @if (session('status'))
        <div class="auth-status">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email">{{ __('Email') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" style="margin-top:6px;">
            @error('email')<p class="auth-error" style="margin-top:6px;">{{ $message }}</p>@enderror
        </div>

        <!-- Password -->
        <div style="margin-top:18px;">
            <label for="password">{{ __('Password') }}</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" style="margin-top:6px;">
            @error('password')<p class="auth-error" style="margin-top:6px;">{{ $message }}</p>@enderror
        </div>

        <!-- Remember Me -->
        <div style="margin-top:18px;display:flex;align-items:center;gap:8px;">
            <input id="remember_me" type="checkbox" name="remember">
            <label for="remember_me" style="font-weight:400;">{{ __('Remember me') }}</label>
        </div>

        <div style="margin-top:26px;display:flex;align-items:center;justify-content:space-between;gap:16px;">
            @if (Route::has('password.request'))
                <a class="auth-link" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <button type="submit" class="btn-amber">{{ __('Log in') }}</button>
        </div>

        <p style="margin-top:26px;text-align:center;color:var(--muted);font-size:14px;">
            Don't have an account?
            <a class="auth-link" href="{{ route('register') }}" style="color:var(--amber);">Register</a>
        </p>
    </form>
</x-guest-layout>
