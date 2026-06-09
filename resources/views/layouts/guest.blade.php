<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ config('app.name', 'ISVuln') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root{
                --bg:#0a0e16;--surface:#111826;--panel:#16243a;--line:#1e2c44;--line-soft:#18243a;
                --amber:#ffb028;--amber-2:#ffc24d;--text:#eaeff7;--muted:#8a99b3;--muted-2:#5d6c86;
            }
            *{box-sizing:border-box}
            body{margin:0;background:var(--bg);font-family:'Sora',sans-serif;color:var(--text);-webkit-font-smoothing:antialiased}
            body::before{content:"";position:fixed;top:-200px;left:50%;transform:translateX(-50%);width:900px;height:600px;pointer-events:none;z-index:0;background:radial-gradient(ellipse,rgba(255,176,40,0.07),transparent 65%)}

            /* nav */
            .auth-nav{position:relative;z-index:2;border-bottom:1px solid var(--line-soft);backdrop-filter:blur(12px);background:rgba(10,14,22,0.8)}
            .auth-nav-inner{max-width:1200px;margin:0 auto;padding:0 32px;height:70px;display:flex;align-items:center}
            .auth-logo{display:flex;align-items:center;gap:11px;font-weight:700;font-size:19px;letter-spacing:-.01em;color:var(--text);text-decoration:none}
            .auth-logo .mark{width:34px;height:34px;background:var(--amber);border-radius:9px;display:flex;align-items:center;justify-content:center;color:#1a1200;font-weight:800;font-size:16px}

            /* main */
            .auth-shell{position:relative;z-index:1;min-height:calc(100vh - 70px - 81px);display:flex;flex-direction:column;align-items:center;justify-content:center;padding:48px 24px}
            .auth-card{width:100%;max-width:420px;background:var(--surface);border:1px solid var(--line);border-radius:16px;padding:36px 32px;box-shadow:0 30px 80px rgba(0,0,0,0.45)}
            .auth-card label{color:var(--muted);font-size:14px;font-weight:500}
            .auth-card input[type=text],.auth-card input[type=email],.auth-card input[type=password]{
                width:100%;background:var(--bg);border:1px solid var(--line);border-radius:9px;
                color:var(--text);padding:11px 14px;font-size:14.5px;font-family:'Sora',sans-serif;transition:border-color .15s}
            .auth-card input:focus{outline:none;border-color:var(--amber);box-shadow:0 0 0 3px rgba(255,176,40,0.12)}
            .auth-card input[type=checkbox]{accent-color:var(--amber)}
            .btn-amber{background:var(--amber);color:#1a1200;font-family:'Sora',sans-serif;font-weight:600;
                font-size:14.5px;padding:11px 22px;border:none;border-radius:9px;cursor:pointer;transition:all .16s;text-transform:none;letter-spacing:0}
            .btn-amber:hover{background:var(--amber-2)}
            .auth-link{color:var(--muted);font-size:14px;text-decoration:none;transition:color .15s}
            .auth-link:hover{color:var(--amber)}
            .auth-error{color:#f0564f;font-size:13px}
            .auth-status{background:rgba(63,176,106,0.12);border:1px solid rgba(63,176,106,0.3);color:#3fb06a;
                padding:11px 14px;border-radius:9px;font-size:14px;margin-bottom:18px}

            /* footer */
            .auth-footer{position:relative;z-index:1;border-top:1px solid var(--line-soft);padding:30px 0}
            .auth-footer-inner{max-width:1200px;margin:0 auto;padding:0 32px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:14px;font-family:'JetBrains Mono',monospace;font-size:12.5px;color:var(--muted-2)}
        </style>
    </head>
    <body>
        <nav class="auth-nav">
            <div class="auth-nav-inner">
                <a href="{{ url('/') }}" class="auth-logo">
                    <span class="mark">IS</span> ISVuln
                </a>
            </div>
        </nav>

        <div class="auth-shell">
            <div class="auth-card">
                {{ $slot }}
            </div>
        </div>

        <footer class="auth-footer">
            <div class="auth-footer-inner">
                <span>ISVULN // built with Laravel + Tailwind</span>
                <span>a learning project · not for production targets</span>
            </div>
        </footer>
    </body>
</html>
