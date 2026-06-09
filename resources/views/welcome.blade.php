<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ISVuln — Security Audit Management</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
<style>
  :root{
    --bg:#0a0e16;--bg-2:#070a11;--surface:#111826;--surface-2:#0f1622;
    --panel:#16243a;--line:#1e2c44;--line-soft:#18243a;
    --amber:#ffb028;--amber-2:#ffc24d;--text:#eaeff7;--muted:#8a99b3;--muted-2:#5d6c86;
    --crit:#f0564f;--high:#f07a3c;--med:#f0b32c;--low:#3f93e6;--ok:#3fb06a;--radius:16px;
  }
  *{margin:0;padding:0;box-sizing:border-box}
  html{scroll-behavior:smooth}
  body{background:var(--bg);color:var(--text);font-family:'Sora',sans-serif;line-height:1.6;-webkit-font-smoothing:antialiased;overflow-x:hidden}
  .mono{font-family:'JetBrains Mono',monospace}
  .wrap{max-width:1200px;margin:0 auto;padding:0 32px}
  a{text-decoration:none;color:inherit}
  body::before{content:"";position:fixed;top:-200px;left:50%;transform:translateX(-50%);width:900px;height:600px;pointer-events:none;z-index:0;background:radial-gradient(ellipse,rgba(255,176,40,0.07),transparent 65%)}
  .nav{position:sticky;top:0;z-index:50;backdrop-filter:blur(12px);background:rgba(10,14,22,0.8);border-bottom:1px solid var(--line-soft)}
  .nav-inner{display:flex;justify-content:space-between;align-items:center;height:70px}
  .logo{display:flex;align-items:center;gap:11px;font-weight:700;font-size:19px;letter-spacing:-.01em}
  .logo .mark{width:34px;height:34px;background:var(--amber);border-radius:9px;display:flex;align-items:center;justify-content:center;color:#1a1200;font-weight:800;font-size:16px}
  .nav-links{display:flex;gap:30px;align-items:center;font-size:14.5px;color:var(--muted)}
  .nav-links a:hover{color:var(--text)}
  .nav-actions{display:flex;gap:12px;align-items:center}
  .btn{font-family:'Sora',sans-serif;font-size:14.5px;font-weight:600;padding:10px 20px;border-radius:9px;transition:all .16s;cursor:pointer;border:1px solid transparent;display:inline-block}
  .btn-amber{background:var(--amber);color:#1a1200}
  .btn-amber:hover{background:var(--amber-2);transform:translateY(-1px)}
  .btn-ghost{border-color:var(--line);color:var(--text)}
  .btn-ghost:hover{border-color:var(--muted-2);background:var(--surface)}
  .btn-lg{padding:13px 26px;font-size:15.5px}
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
  .hero{position:relative;z-index:1;padding:80px 0 50px;text-align:center}
  .pill{display:inline-flex;align-items:center;gap:8px;font-family:'JetBrains Mono',monospace;font-size:12.5px;color:var(--amber);border:1px solid rgba(255,176,40,0.25);background:rgba(255,176,40,0.05);border-radius:100px;padding:7px 15px;margin-bottom:30px}
  .pill .dt{width:7px;height:7px;border-radius:50%;background:var(--amber);box-shadow:0 0 8px var(--amber)}
  .hero h1{font-size:clamp(42px,6.5vw,72px);font-weight:800;line-height:1.04;letter-spacing:-.03em;margin-bottom:24px}
  .hero h1 .am{color:var(--amber)}
  .hero p{font-size:19px;color:var(--muted);max-width:600px;margin:0 auto 38px}
  .hero-cta{display:flex;gap:14px;justify-content:center}
  .preview{position:relative;z-index:1;margin-top:60px;padding-bottom:90px}
  .browser{border:1px solid var(--line);border-radius:var(--radius);overflow:hidden;background:var(--surface-2);box-shadow:0 40px 100px rgba(0,0,0,0.5)}
  .browser-bar{display:flex;align-items:center;gap:8px;padding:14px 18px;background:var(--bg-2);border-bottom:1px solid var(--line)}
  .browser-bar .c{width:11px;height:11px;border-radius:50%}
  .browser-url{margin-left:14px;font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--muted-2);background:var(--surface);padding:5px 14px;border-radius:6px;border:1px solid var(--line)}
  .dash{padding:26px}
  .dash-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:22px}
  .dash-head h2{font-size:21px;font-weight:700;letter-spacing:-.01em}
  .dash-head .sub{font-size:13px;color:var(--muted);margin-top:2px}
  .chip{font-family:'JetBrains Mono',monospace;font-size:12px;color:var(--muted);border:1px solid var(--line);border-radius:7px;padding:7px 13px}
  .metrics{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:16px}
  .metric{background:var(--surface);border:1px solid var(--line);border-radius:13px;padding:20px}
  .metric .ic{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;font-size:18px}
  .metric .n{font-size:30px;font-weight:700;letter-spacing:-.02em;line-height:1}
  .metric .l{font-size:13px;color:var(--muted);margin-top:6px}
  .ic.crit{background:rgba(240,86,79,0.12);color:var(--crit)}
  .ic.high{background:rgba(240,122,60,0.12);color:var(--high)}
  .ic.med{background:rgba(240,179,44,0.12);color:var(--med)}
  .ic.low{background:rgba(63,147,230,0.12);color:var(--low)}
  .lower{display:grid;grid-template-columns:1.7fr 1fr;gap:16px}
  .card{background:var(--surface);border:1px solid var(--line);border-radius:13px;overflow:hidden}
  .card-head{padding:18px 22px;border-bottom:1px solid var(--line);display:flex;justify-content:space-between;align-items:center}
  .card-head h3{font-size:16px;font-weight:600}
  .card-head a{font-size:13px;color:var(--amber)}
  .row{display:flex;align-items:center;gap:14px;padding:14px 22px;border-bottom:1px solid var(--line-soft);font-size:13.5px}
  .row:last-child{border:none}
  .sev{font-family:'JetBrains Mono',monospace;font-size:10.5px;font-weight:700;padding:3px 9px;border-radius:5px;white-space:nowrap}
  .sev.c{color:var(--crit);background:rgba(240,86,79,0.13)}
  .sev.h{color:var(--high);background:rgba(240,122,60,0.13)}
  .sev.m{color:var(--med);background:rgba(240,179,44,0.13)}
  .sev.l{color:var(--low);background:rgba(63,147,230,0.13)}
  .row .t{flex:1;color:var(--text)}
  .row .tg{color:var(--muted-2);font-family:'JetBrains Mono',monospace;font-size:11px}
  .row .st{color:var(--muted);font-family:'JetBrains Mono',monospace;font-size:10.5px}
  .donut-wrap{padding:22px;display:flex;flex-direction:column;align-items:center}
  .donut{position:relative;width:180px;height:180px;margin:8px 0 20px}
  .donut .center{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center}
  .donut .center .big{font-size:34px;font-weight:700;letter-spacing:-.02em}
  .donut .center .lbl{font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.1em}
  .legend{width:100%;display:flex;flex-direction:column;gap:9px}
  .leg{display:flex;align-items:center;gap:10px;font-size:13px}
  .leg .sw{width:10px;height:10px;border-radius:3px}
  .leg .nm{flex:1;color:var(--muted)}
  .leg .vl{font-family:'JetBrains Mono',monospace;font-weight:500}
  .features{position:relative;z-index:1;padding:30px 0 90px}
  .sec-label{font-family:'JetBrains Mono',monospace;font-size:12.5px;color:var(--amber);letter-spacing:.16em;text-transform:uppercase;text-align:center;margin-bottom:14px}
  .sec-title{font-size:clamp(28px,4vw,40px);font-weight:700;text-align:center;letter-spacing:-.02em;margin-bottom:54px}
  .feat-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
  .feat{background:var(--surface);border:1px solid var(--line);border-radius:14px;padding:30px 26px;transition:all .2s}
  .feat:hover{transform:translateY(-4px);border-color:rgba(255,176,40,0.3)}
  .feat .fi{width:46px;height:46px;border-radius:11px;background:var(--panel);display:flex;align-items:center;justify-content:center;color:var(--amber);font-family:'JetBrains Mono',monospace;font-weight:700;font-size:18px;margin-bottom:20px}
  .feat h3{font-size:18px;font-weight:600;margin-bottom:10px}
  .feat p{font-size:14.5px;color:var(--muted);line-height:1.65}
  .cta-band{position:relative;z-index:1;padding:70px 0}
  .cta-box{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);padding:60px 40px;text-align:center;position:relative;overflow:hidden}
  .cta-box::before{content:"";position:absolute;top:-100px;left:50%;transform:translateX(-50%);width:500px;height:300px;background:radial-gradient(ellipse,rgba(255,176,40,0.1),transparent 65%)}
  .cta-box h2{font-size:36px;font-weight:700;letter-spacing:-.02em;margin-bottom:16px;position:relative}
  .cta-box p{font-size:17px;color:var(--muted);margin-bottom:32px;position:relative}
  footer{border-top:1px solid var(--line-soft);padding:40px 0;position:relative;z-index:1}
  .foot-inner{display:flex;justify-content:space-between;align-items:center;font-family:'JetBrains Mono',monospace;font-size:12.5px;color:var(--muted-2);flex-wrap:wrap;gap:14px}
  .reveal{opacity:0;transform:translateY(26px);transition:all .7s cubic-bezier(.2,.7,.2,1)}
  .reveal.in{opacity:1;transform:none}
  @media(max-width:880px){.metrics{grid-template-columns:repeat(2,1fr)}.lower{grid-template-columns:1fr}.feat-grid{grid-template-columns:1fr}.nav-links{display:none}.row .tg{display:none}}
</style>
</head>
<body>

<nav class="nav">
  <div class="wrap nav-inner">
    <div class="logo"><span class="mark">IS</span> ISVuln</div>
    <div class="nav-links">
      <a href="#features">{{ __('home.nav_features') }}</a>
      <a href="#">{{ __('home.nav_docs') }}</a>
      <a href="#">{{ __('home.nav_pricing') }}</a>
    </div>
    <div class="nav-actions">
      <div class="lang" id="lang">
        <button class="lang-btn" type="button" onclick="document.getElementById('lang').classList.toggle('open')">
          <span>{{ app()->getLocale() === 'pt_BR' ? 'PT-BR' : 'EN' }}</span>
          <span class="arw">&#9660;</span>
        </button>
        <div class="lang-menu">
          <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'active' : '' }}">
            English <span class="ck">&#10003;</span>
          </a>
          <a href="{{ route('lang.switch', 'pt_BR') }}" class="{{ app()->getLocale() === 'pt_BR' ? 'active' : '' }}">
            Portugu&ecirc;s (BR) <span class="ck">&#10003;</span>
          </a>
        </div>
      </div>
      @auth
        <a href="{{ url('/dashboard') }}" class="btn btn-amber">{{ __('home.nav_dashboard') }} &rarr;</a>
      @else
        <a href="{{ route('login') }}" class="btn btn-ghost">{{ __('home.nav_login') }}</a>
        @if (Route::has('register'))
          <a href="{{ route('register') }}" class="btn btn-amber">{{ __('home.nav_register') }}</a>
        @endif
      @endauth
    </div>
  </div>
</nav>

<header class="hero">
  <div class="wrap">
    <span class="pill reveal"><span class="dt"></span>{{ __('home.pill') }}</span>
    <h1 class="reveal">{{ __('home.hero_title_1') }}<br><span class="am">{{ __('home.hero_title_2') }}</span></h1>
    <p class="reveal">{{ __('home.hero_subtitle') }}</p>
    <div class="hero-cta reveal">
      @auth
        <a href="{{ url('/dashboard') }}" class="btn btn-amber btn-lg">{{ __('home.hero_cta_open') }} &rarr;</a>
      @else
        <a href="{{ route('register') }}" class="btn btn-amber btn-lg">{{ __('home.hero_cta_start') }} &rarr;</a>
        <a href="{{ route('login') }}" class="btn btn-ghost btn-lg">{{ __('home.hero_cta_login') }}</a>
      @endauth
    </div>
  </div>
</header>

<section class="preview">
  <div class="wrap">
    <div class="browser reveal">
      <div class="browser-bar">
        <span class="c" style="background:#f0564f"></span>
        <span class="c" style="background:#f0b32c"></span>
        <span class="c" style="background:#3fb06a"></span>
        <span class="browser-url">{{ config('app.url') }}/dashboard</span>
      </div>
      <div class="dash">
        <div class="dash-head">
          <div>
            <h2>{{ __('home.dash_title') }}</h2>
            <div class="sub">{{ __('home.dash_sub') }}</div>
          </div>
          <span class="chip">May 28 &ndash; Jun 3</span>
        </div>

        <div class="metrics">
          <div class="metric"><div class="ic crit">&#9650;</div><div class="n" style="color:var(--crit)">12</div><div class="l">{{ __('home.critical') }}</div></div>
          <div class="metric"><div class="ic high">&#9650;</div><div class="n" style="color:var(--high)">28</div><div class="l">{{ __('home.high') }}</div></div>
          <div class="metric"><div class="ic med">&#9679;</div><div class="n" style="color:var(--med)">46</div><div class="l">{{ __('home.medium') }}</div></div>
          <div class="metric"><div class="ic low">&#9679;</div><div class="n" style="color:var(--low)">33</div><div class="l">{{ __('home.low') }}</div></div>
        </div>

        <div class="lower">
          <div class="card">
            <div class="card-head"><h3>{{ __('home.recent_findings') }}</h3><a>{{ __('home.view_all') }} &rarr;</a></div>
            <div class="row"><span class="sev c">CRITICAL</span><span class="t">SQL Injection on /login</span><span class="tg">/login</span><span class="st">OPEN</span></div>
            <div class="row"><span class="sev h">HIGH</span><span class="t">Stored XSS in product reviews</span><span class="tg">/products</span><span class="st">IN_PROGRESS</span></div>
            <div class="row"><span class="sev m">MEDIUM</span><span class="t">Missing rate limit on checkout</span><span class="tg">/checkout</span><span class="st">OPEN</span></div>
            <div class="row"><span class="sev l">LOW</span><span class="t">Verbose server header disclosure</span><span class="tg">/</span><span class="st">RESOLVED</span></div>
          </div>
          <div class="card">
            <div class="card-head"><h3>{{ __('home.by_severity') }}</h3></div>
            <div class="donut-wrap">
              <div class="donut">
                <svg width="180" height="180" viewBox="0 0 180 180">
                  <circle cx="90" cy="90" r="70" fill="none" stroke="#1e2c44" stroke-width="20"/>
                  <circle cx="90" cy="90" r="70" fill="none" stroke="#f0564f" stroke-width="20" stroke-dasharray="55 385" stroke-dashoffset="0" transform="rotate(-90 90 90)" stroke-linecap="round"/>
                  <circle cx="90" cy="90" r="70" fill="none" stroke="#f07a3c" stroke-width="20" stroke-dasharray="128 312" stroke-dashoffset="-55" transform="rotate(-90 90 90)" stroke-linecap="round"/>
                  <circle cx="90" cy="90" r="70" fill="none" stroke="#f0b32c" stroke-width="20" stroke-dasharray="120 320" stroke-dashoffset="-183" transform="rotate(-90 90 90)" stroke-linecap="round"/>
                  <circle cx="90" cy="90" r="70" fill="none" stroke="#3f93e6" stroke-width="20" stroke-dasharray="80 360" stroke-dashoffset="-303" transform="rotate(-90 90 90)" stroke-linecap="round"/>
                </svg>
                <div class="center"><span class="big">119</span><span class="lbl">{{ __('home.total') }}</span></div>
              </div>
              <div class="legend">
                <div class="leg"><span class="sw" style="background:var(--crit)"></span><span class="nm">{{ __('home.critical') }}</span><span class="vl">12</span></div>
                <div class="leg"><span class="sw" style="background:var(--high)"></span><span class="nm">{{ __('home.high') }}</span><span class="vl">28</span></div>
                <div class="leg"><span class="sw" style="background:var(--med)"></span><span class="nm">{{ __('home.medium') }}</span><span class="vl">46</span></div>
                <div class="leg"><span class="sw" style="background:var(--low)"></span><span class="nm">{{ __('home.low') }}</span><span class="vl">33</span></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="features" id="features">
  <div class="wrap">
    <div class="sec-label reveal">{{ __('home.cap_label') }}</div>
    <h2 class="sec-title reveal">{{ __('home.cap_title') }}</h2>
    <div class="feat-grid">
      <div class="feat reveal"><div class="fi">#</div><h3>{{ __('home.feat_1_t') }}</h3><p>{{ __('home.feat_1_p') }}</p></div>
      <div class="feat reveal"><div class="fi">&rarr;</div><h3>{{ __('home.feat_2_t') }}</h3><p>{{ __('home.feat_2_p') }}</p></div>
      <div class="feat reveal"><div class="fi">@</div><h3>{{ __('home.feat_3_t') }}</h3><p>{{ __('home.feat_3_p') }}</p></div>
      <div class="feat reveal"><div class="fi">%</div><h3>{{ __('home.feat_4_t') }}</h3><p>{{ __('home.feat_4_p') }}</p></div>
      <div class="feat reveal"><div class="fi">+</div><h3>{{ __('home.feat_5_t') }}</h3><p>{{ __('home.feat_5_p') }}</p></div>
      <div class="feat reveal"><div class="fi">=</div><h3>{{ __('home.feat_6_t') }}</h3><p>{{ __('home.feat_6_p') }}</p></div>
    </div>
  </div>
</section>

<section class="cta-band">
  <div class="wrap">
    <div class="cta-box reveal">
      <h2>{{ __('home.cta_title') }}</h2>
      <p>{{ __('home.cta_sub') }}</p>
      @auth
        <a href="{{ url('/dashboard') }}" class="btn btn-amber btn-lg" style="position:relative">{{ __('home.hero_cta_open') }} &rarr;</a>
      @else
        <a href="{{ route('register') }}" class="btn btn-amber btn-lg" style="position:relative">{{ __('home.cta_btn') }} &rarr;</a>
      @endauth
    </div>
  </div>
</section>

<footer>
  <div class="wrap foot-inner">
    <span>ISVULN // {{ __('home.foot_left') }}</span>
    <span>{{ __('home.foot_right') }}</span>
  </div>
</footer>

<script>
  const r=[...document.querySelectorAll('.reveal')];
  r.forEach((el,i)=>el.style.transitionDelay=(Math.min(i,8)*70)+'ms');
  const io=new IntersectionObserver(e=>e.forEach(x=>{if(x.isIntersecting){x.target.classList.add('in');io.unobserve(x.target)}}),{threshold:.1});
  r.forEach(el=>io.observe(el));
  document.addEventListener('click',function(e){
    const l=document.getElementById('lang');
    if(l && !l.contains(e.target)) l.classList.remove('open');
  });
</script>
</body>
</html>
