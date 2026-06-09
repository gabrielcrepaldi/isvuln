<x-app-sidebar-layout title="{{ __('app.dashboard') }}">

    @push('head')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <style>
          .metrics{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:16px}
          .metric{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);padding:20px}
          .metric .mic{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;font-size:17px}
          .metric .n{font-size:30px;font-weight:700;letter-spacing:-.02em;line-height:1}
          .metric .l{font-size:13px;color:var(--muted);margin-top:6px}
          .mic.crit{background:rgba(240,86,79,0.12);color:var(--crit)}
          .mic.high{background:rgba(240,122,60,0.12);color:var(--high)}
          .mic.med{background:rgba(240,179,44,0.12);color:var(--med)}
          .mic.low{background:rgba(63,147,230,0.12);color:var(--low)}
          .squares{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px}
          .square{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);padding:18px 20px;display:flex;align-items:center;gap:16px}
          .square .sq-ic{width:44px;height:44px;border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0}
          .square .sq-n{font-size:26px;font-weight:700;line-height:1;letter-spacing:-.02em}
          .square .sq-l{font-size:13px;color:var(--muted);margin-top:4px}
          .sq-ic.open{background:rgba(240,179,44,0.12);color:var(--med)}
          .sq-ic.res{background:rgba(63,176,106,0.12);color:var(--ok)}
          .sq-ic.tot{background:rgba(255,176,40,0.12);color:var(--amber)}
          .lower{display:grid;grid-template-columns:1.7fr 1fr;gap:16px}
          .card{background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);overflow:hidden}
          .card-head{padding:18px 22px;border-bottom:1px solid var(--line);display:flex;justify-content:space-between;align-items:center}
          .card-head h3{font-size:16px;font-weight:600}
          .card-head a{font-size:13px;color:var(--amber)}
          .drow{display:flex;align-items:center;gap:14px;padding:14px 22px;border-bottom:1px solid var(--line-soft);font-size:13.5px}
          .drow:last-child{border:none}
          .sev{font-family:'JetBrains Mono',monospace;font-size:10.5px;font-weight:700;padding:3px 9px;border-radius:5px;white-space:nowrap}
          .sev.critical{color:var(--crit);background:rgba(240,86,79,0.13)}
          .sev.high{color:var(--high);background:rgba(240,122,60,0.13)}
          .sev.medium{color:var(--med);background:rgba(240,179,44,0.13)}
          .sev.low{color:var(--low);background:rgba(63,147,230,0.13)}
          .sev.info{color:var(--muted);background:rgba(138,153,179,0.13)}
          .drow .t{flex:1;color:var(--text)}
          .drow .st{color:var(--muted);font-family:'JetBrains Mono',monospace;font-size:10.5px}
          .empty{padding:34px;text-align:center;color:var(--muted-2)}
          .legend{padding:0 22px 22px;display:flex;flex-direction:column;gap:9px}
          .leg{display:flex;align-items:center;gap:10px;font-size:13px}
          .leg .sw{width:10px;height:10px;border-radius:3px}
          .leg .nm{flex:1;color:var(--muted)}
          .leg .vl{font-family:'JetBrains Mono',monospace;font-weight:500}
          @media(max-width:820px){.metrics{grid-template-columns:repeat(2,1fr)}.squares{grid-template-columns:1fr}.lower{grid-template-columns:1fr}}
        </style>
    @endpush

    {{-- Severity cards --}}
    <div class="metrics">
        <div class="metric"><div class="mic crit">▲</div><div class="n" style="color:var(--crit)">{{ $stats['critical'] }}</div><div class="l">{{ __('vuln.severity.critical') }}</div></div>
        <div class="metric"><div class="mic high">▲</div><div class="n" style="color:var(--high)">{{ $stats['high'] }}</div><div class="l">{{ __('vuln.severity.high') }}</div></div>
        <div class="metric"><div class="mic med">●</div><div class="n" style="color:var(--med)">{{ $stats['medium'] }}</div><div class="l">{{ __('vuln.severity.medium') }}</div></div>
        <div class="metric"><div class="mic low">●</div><div class="n" style="color:var(--low)">{{ $stats['low'] }}</div><div class="l">{{ __('vuln.severity.low') }}</div></div>
    </div>

    {{-- Stat squares --}}
    <div class="squares">
        <div class="square"><div class="sq-ic open">○</div><div><div class="sq-n">{{ $stats['open'] }}</div><div class="sq-l">{{ __('vuln.status.open') }}</div></div></div>
        <div class="square"><div class="sq-ic res">✓</div><div><div class="sq-n">{{ $stats['resolved'] }}</div><div class="sq-l">{{ __('vuln.status.resolved') }}</div></div></div>
        <div class="square"><div class="sq-ic tot">▦</div><div><div class="sq-n">{{ $stats['total'] }}</div><div class="sq-l">{{ __('home.total') }} {{ strtolower(__('vuln.title_plural')) }}</div></div></div>
    </div>

    {{-- Lower grid --}}
    <div class="lower">
        <div class="card">
            <div class="card-head"><h3>{{ __('home.recent_findings') }}</h3><a href="{{ route('vulnerabilities.index') }}">{{ __('home.view_all') }} →</a></div>
            @forelse($recent as $vuln)
                <a href="{{ route('vulnerabilities.show', $vuln) }}" class="drow">
                    <span class="sev {{ $vuln->severity }}">{{ strtoupper(__('vuln.severity.' . $vuln->severity)) }}</span>
                    <span class="t">{{ $vuln->title }}</span>
                    <span class="st">{{ strtoupper(__('vuln.status.' . $vuln->status)) }}</span>
                </a>
            @empty
                <div class="empty">{{ __('vuln.none_yet') }}</div>
            @endforelse
        </div>

        <div class="card">
            <div class="card-head"><h3>{{ __('home.by_severity') }}</h3></div>
            <div id="donut" style="margin-top:8px"></div>
            <div class="legend">
                <div class="leg"><span class="sw" style="background:var(--crit)"></span><span class="nm">{{ __('vuln.severity.critical') }}</span><span class="vl">{{ $stats['critical'] }}</span></div>
                <div class="leg"><span class="sw" style="background:var(--high)"></span><span class="nm">{{ __('vuln.severity.high') }}</span><span class="vl">{{ $stats['high'] }}</span></div>
                <div class="leg"><span class="sw" style="background:var(--med)"></span><span class="nm">{{ __('vuln.severity.medium') }}</span><span class="vl">{{ $stats['medium'] }}</span></div>
                <div class="leg"><span class="sw" style="background:var(--low)"></span><span class="nm">{{ __('vuln.severity.low') }}</span><span class="vl">{{ $stats['low'] }}</span></div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
      const options={
        series:[{{ $stats['critical'] }},{{ $stats['high'] }},{{ $stats['medium'] }},{{ $stats['low'] }}],
        chart:{type:'donut',height:240,fontFamily:'Sora, sans-serif'},
        labels:[@json(__('vuln.severity.critical')),@json(__('vuln.severity.high')),@json(__('vuln.severity.medium')),@json(__('vuln.severity.low'))],
        colors:['#f0564f','#f07a3c','#f0b32c','#3f93e6'],
        stroke:{width:0},
        legend:{show:false},
        dataLabels:{enabled:false},
        plotOptions:{pie:{donut:{size:'72%',labels:{show:true,
          total:{show:true,label:@json(__('home.total')),color:'#8a99b3',fontSize:'12px',
            formatter:function(w){return w.globals.seriesTotals.reduce((a,b)=>a+b,0)}},
          value:{color:'#eaeff7',fontSize:'30px',fontWeight:700}}}}},
        tooltip:{theme:'dark'}
      };
      new ApexCharts(document.querySelector("#donut"),options).render();
    </script>
    @endpush

</x-app-sidebar-layout>
