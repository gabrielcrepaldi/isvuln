<x-guest-layout>
    <h1 style="font-size:22px;font-weight:700;margin-bottom:6px;">Create your account</h1>
    <p style="color:var(--muted);font-size:14px;margin-bottom:26px;">Get started with ISVuln.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <label for="name">{{ __('Name') }}</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" style="margin-top:6px;">
            @error('name')<p class="auth-error" style="margin-top:6px;">{{ $message }}</p>@enderror
        </div>

        <!-- Email Address -->
        <div style="margin-top:18px;">
            <label for="email">{{ __('Email') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" style="margin-top:6px;">
            @error('email')<p class="auth-error" style="margin-top:6px;">{{ $message }}</p>@enderror
        </div>

        <!-- Password -->
        <div style="margin-top:18px;">
            <label for="password">{{ __('Password') }}</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" style="margin-top:6px;">
            @error('password')<p class="auth-error" style="margin-top:6px;">{{ $message }}</p>@enderror
        </div>

        <!-- Confirm Password -->
        <div style="margin-top:18px;">
            <label for="password_confirmation">{{ __('Confirm Password') }}</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" style="margin-top:6px;">
            @error('password_confirmation')<p class="auth-error" style="margin-top:6px;">{{ $message }}</p>@enderror
        </div>

        <div style="margin-top:26px;">
            <button type="submit" class="btn-amber" style="width:100%;">{{ __('Register') }}</button>
        </div>

        <p style="margin-top:24px;text-align:center;color:var(--muted);font-size:14px;">
            Already registered?
            <a class="auth-link" href="{{ route('login') }}" style="color:var(--amber);">Log in</a>
        </p>
    </form>
</x-guest-layout>
