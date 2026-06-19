<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ config('app.name', 'ISVuln') }}@isset($title) — {{ $title }}@endisset</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

@vite(['resources/css/app.css', 'resources/js/app.js'])

<style>
  :root{
    --bg:#0a0e16;--bg-2:#070a11;--surface:#111826;--surface-2:#0f1622;
    --panel:#16243a;--line:#1e2c44;--line-soft:#18243a;
    --amber:#ffb028;--amber-2:#ffc24d;--text:#eaeff7;--muted:#8a99b3;--muted-2:#5d6c86;
    --crit:#f0564f;--high:#f07a3c;--med:#f0b32c;--low:#3f93e6;--ok:#3fb06a;--radius:14px;
    --sidebar-w:260px;
  }
  *{margin:0;padding:0;box-sizing:border-box}
  body{background:var(--bg);color:var(--text);font-family:'Sora',sans-serif;-webkit-font-smoothing:antialiased}
  .mono{font-family:'JetBrains Mono',monospace}
  a{text-decoration:none;color:inherit}

  .sidebar{position:fixed;top:0;left:0;bottom:0;width:var(--sidebar-w);background:var(--surface-2);
    border-right:1px solid var(--line);display:flex;flex-direction:column;z-index:40}
  .sb-logo{display:flex;align-items:center;gap:11px;font-weight:700;font-size:19px;padding:22px;border-bottom:1px solid var(--line-soft)}
  .sb-logo .mark{width:34px;height:34px;background:var(--amber);border-radius:9px;display:flex;align-items:center;justify-content:center;color:#1a1200;font-weight:800;font-size:16px}
  .sb-section{padding:18px 16px 6px;font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--muted-2);text-transform:uppercase;letter-spacing:.12em}
  .sb-nav{flex:1;padding:4px 12px}
  .sb-item{display:flex;align-items:center;gap:12px;padding:11px 14px;border-radius:9px;color:var(--muted);font-size:14.5px;font-weight:500;margin-bottom:3px;transition:all .14s}
  .sb-item:hover{background:var(--panel);color:var(--text)}
  .sb-item.active{background:rgba(255,176,40,0.1);color:var(--amber)}
  .sb-item .ic{width:20px;text-align:center;font-family:'JetBrains Mono',monospace;font-size:15px}
  .sb-user{border-top:1px solid var(--line-soft);padding:16px;display:flex;align-items:center;gap:12px}
  .sb-avatar{width:38px;height:38px;border-radius:9px;background:var(--panel);display:flex;align-items:center;justify-content:center;font-weight:600;font-size:14px;color:var(--amber)}
  .sb-user .info{flex:1;min-width:0}
  .sb-user .nm{font-size:14px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
  .sb-user .rl{font-family:'JetBrains Mono',monospace;font-size:11px;color:var(--muted-2);text-transform:uppercase}

  .main{margin-left:var(--sidebar-w);min-height:100vh}
  .topbar{height:70px;border-bottom:1px solid var(--line-soft);display:flex;align-items:center;justify-content:space-between;padding:0 32px;background:rgba(10,14,22,0.7);backdrop-filter:blur(10px);position:sticky;top:0;z-index:30}
  .topbar h1{font-size:20px;font-weight:700;letter-spacing:-.01em}
  .topbar .actions{display:flex;align-items:center;gap:14px}
  .btn-logout{font-family:'Sora',sans-serif;font-size:14px;font-weight:500;color:var(--muted);background:transparent;border:1px solid var(--line);border-radius:9px;padding:9px 16px;cursor:pointer;transition:all .14s}
  .btn-logout:hover{border-color:var(--crit);color:var(--crit)}
  .content{padding:28px 32px}

  /* language switcher */
  .lang{position:relative}
  .lang-btn{display:flex;align-items:center;gap:7px;font-family:'JetBrains Mono',monospace;font-size:13px;color:var(--muted);background:transparent;border:1px solid var(--line);border-radius:9px;padding:9px 13px;cursor:pointer;transition:all .15s}
  .lang-btn:hover{border-color:var(--muted-2);color:var(--text)}
  .lang-btn .arw{font-size:9px;transition:transform .2s}
  .lang.open .lang-btn .arw{transform:rotate(180deg)}
  .lang-menu{position:absolute;top:calc(100% + 8px);right:0;min-width:160px;background:var(--surface);border:1px solid var(--line);border-radius:11px;overflow:hidden;opacity:0;visibility:hidden;transform:translateY(-6px);transition:all .16s;z-index:60}
  .lang.open .lang-menu{opacity:1;visibility:visible;transform:none}
  .lang-menu a{display:flex;align-items:center;gap:9px;padding:11px 15px;font-size:13.5px;color:var(--muted);transition:all .12s}
  .lang-menu a:hover{background:var(--panel);color:var(--text)}
  .lang-menu a.active{color:var(--amber)}
  .lang-menu a .ck{margin-left:auto;font-size:12px;opacity:0}
  .lang-menu a.active .ck{opacity:1}

  @media(max-width:820px){
    .sidebar{transform:translateX(-100%)}
    .main{margin-left:0}
  }
</style>
@stack('head')
</head>
<body>

<aside class="sidebar">
  <div class="sb-logo"><span class="mark">IS</span> ISVuln</div>
  <div class="sb-section">{{ __('app.menu') }}</div>
  <nav class="sb-nav">
    <a href="{{ route('dashboard') }}" class="sb-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <span class="ic">▣</span> {{ __('app.dashboard') }}
    </a>
    <a href="{{ route('vulnerabilities.index') }}" class="sb-item {{ request()->routeIs('vulnerabilities.*') ? 'active' : '' }}">
      <span class="ic">⚠</span> {{ __('app.vulnerabilities') }}
    </a>
    @role('admin')
      <a href="{{ route('users.index') }}" class="sb-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
        <span class="ic">◉</span> {{ __('app.users') }}
      </a>
      <a href="{{ route('audit.index') }}" class="sb-item {{ request()->routeIs('audit.*') ? 'active' : '' }}">
        <span class="ic">≡</span> {{ __('app.audit_log') }}
      </a>
    @endrole
  </nav>
  <div class="sb-user">
    <div class="sb-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
    <div class="info">
      <div class="nm">{{ Auth::user()->name }}</div>
      <div class="rl">{{ __('app.role.' . (Auth::user()->roles->first()?->name ?? 'none')) }}</div>
    </div>
  </div>
</aside>

<div class="main">
  <div class="topbar">
    <h1>{{ $title ?? __('app.dashboard') }}</h1>
    <div class="actions">
      <div class="lang" id="lang">
        <button type="button" class="lang-btn" onclick="document.getElementById('lang').classList.toggle('open')">
          <span>{{ app()->getLocale() === 'pt_BR' ? 'PT-BR' : 'EN' }}</span>
          <span class="arw">▼</span>
        </button>
        <div class="lang-menu">
          <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'active' : '' }}">English <span class="ck">✓</span></a>
          <a href="{{ route('lang.switch', 'pt_BR') }}" class="{{ app()->getLocale() === 'pt_BR' ? 'active' : '' }}">Português (BR) <span class="ck">✓</span></a>
        </div>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-logout">{{ __('app.logout') }}</button>
      </form>
    </div>
  </div>

  <div class="content">
    {{ $slot }}
  </div>
</div>

<script>
  document.addEventListener('click',function(e){
    const l=document.getElementById('lang');
    if(l && !l.contains(e.target)) l.classList.remove('open');
  });
</script>
@stack('scripts')
</body>
</html>
