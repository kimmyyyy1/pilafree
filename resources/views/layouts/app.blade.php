<!DOCTYPE html>
<html lang="tl" class="h-full scroll-smooth bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="theme-color" content="#065f46">
    <title>@yield('title', 'PilaFree — Opisyal na Sistema ng Pagpila at Imbentaryo ng Gamot')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono-num { font-family: 'JetBrains Mono', monospace; }
        
        /* Hide scrollbar for horizontal pill strips on mobile */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Custom subtle scrollbar for desktop */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f8fafc; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding-bottom: 0 !important; }
        }
    </style>
    @yield('styles')
</head>
<body class="flex flex-col min-h-screen text-slate-800 antialiased selection:bg-emerald-700 selection:text-white overflow-x-hidden pb-24 md:pb-0">

    <!-- Institutional Top Status Strip -->
    <div class="bg-slate-900 text-slate-300 text-[11px] font-medium py-1.5 px-4 border-b border-slate-800 no-print">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-2">
            <div class="flex items-center gap-2 min-w-0">
                <span class="inline-flex items-center gap-1.5 text-emerald-400 font-bold shrink-0">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    BUKAS ANG TANGGAPAN
                </span>
                <span class="text-slate-600 hidden sm:inline">•</span>
                <span class="truncate text-slate-400 hidden sm:inline">Pampublikong Sentrong Pangkalusugan at Ospital</span>
            </div>
            <div class="flex items-center gap-3 font-mono text-[11px] shrink-0">
                <span id="navClock" class="text-slate-200 font-semibold">{{ now()->format('h:i A') }}</span>
                <span class="text-slate-600 hidden md:inline">|</span>
                <span class="text-emerald-400 hidden md:inline font-sans font-semibold">Serbisyong Makatao at Tapat</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/90 shadow-2xs no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand Identity -->
                <div class="flex items-center gap-3 min-w-0">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 sm:gap-3 group focus:outline-none">
                        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-emerald-700 flex items-center justify-center text-white shadow-sm group-hover:bg-emerald-800 transition-colors shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-1.5">
                                <span class="text-lg sm:text-xl font-extrabold tracking-tight text-slate-900">
                                    Pila<span class="text-emerald-700">Free</span>
                                </span>
                                <span class="text-[10px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    e-Kalusugan
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-500 font-medium truncate">
                                Opisyal na Sistema sa Pagpila at Libreng Gamot
                            </p>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-1 bg-slate-100 p-1 rounded-xl border border-slate-200/70">
                    <a href="{{ route('home') }}" class="px-3.5 py-2 text-xs font-bold rounded-lg transition-all flex items-center gap-1.5 {{ request()->routeIs('home') || request()->routeIs('queue.track') ? 'bg-white text-emerald-800 shadow-2xs border border-slate-200/60' : 'text-slate-600 hover:text-slate-900' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                        Pagkuha ng Numero
                    </a>
                    <a href="{{ route('medicines.index') }}" class="px-3.5 py-2 text-xs font-bold rounded-lg transition-all flex items-center gap-1.5 {{ request()->routeIs('medicines.*') ? 'bg-white text-emerald-800 shadow-2xs border border-slate-200/60' : 'text-slate-600 hover:text-slate-900' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        Imbentaryo ng Gamot
                    </a>
                    <a href="{{ route('display.tv') }}" target="_blank" class="px-3.5 py-2 text-xs font-bold rounded-lg text-slate-700 hover:text-emerald-800 hover:bg-white/60 transition-all flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
                        Monitor sa Paghihintay
                    </a>
                </nav>

                <!-- Right Action Buttons -->
                <div class="flex items-center gap-2 shrink-0">
                    <button type="button" onclick="document.getElementById('mobileTrackModal').classList.remove('hidden')" class="md:hidden px-3 py-2 text-xs font-bold rounded-xl bg-slate-100 text-slate-700 border border-slate-200 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>Ticket #</span>
                    </button>

                    <a href="{{ route('doctor.index') }}" class="px-3.5 py-2 sm:px-4 sm:py-2.5 text-xs font-bold rounded-xl transition-all flex items-center gap-1.5 {{ request()->routeIs('doctor.*') ? 'bg-emerald-700 text-white shadow-sm' : 'bg-slate-900 text-white hover:bg-slate-800' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <span class="hidden sm:inline">Himpilan ng Doktor</span>
                        <span class="sm:hidden">Doktor</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Bottom Navigation Bar (Ergonomic Thumb Zone for Smartphone Visitors) -->
    <nav class="md:hidden fixed bottom-0 inset-x-0 z-50 bg-white/95 backdrop-blur-lg border-t border-slate-200 px-2 py-1.5 grid grid-cols-4 gap-1 shadow-[0_-4px_20px_rgba(0,0,0,0.06)] no-print" style="padding-bottom: max(0.375rem, env(safe-area-inset-bottom));">
        <a href="{{ route('home') }}" class="flex flex-col items-center justify-center py-1.5 rounded-xl transition-colors {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'text-slate-500 font-medium hover:text-slate-900' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
            <span class="text-[10px] mt-0.5 leading-tight">Pagpila</span>
        </a>
        <button type="button" onclick="document.getElementById('mobileTrackModal').classList.remove('hidden')" class="flex flex-col items-center justify-center py-1.5 rounded-xl transition-colors {{ request()->routeIs('queue.track') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'text-slate-500 font-medium hover:text-slate-900' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <span class="text-[10px] mt-0.5 leading-tight">Subaybayan</span>
        </button>
        <a href="{{ route('medicines.index') }}" class="flex flex-col items-center justify-center py-1.5 rounded-xl transition-colors {{ request()->routeIs('medicines.*') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'text-slate-500 font-medium hover:text-slate-900' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
            <span class="text-[10px] mt-0.5 leading-tight">Gamot</span>
        </a>
        <a href="{{ route('display.tv') }}" class="flex flex-col items-center justify-center py-1.5 rounded-xl transition-colors text-slate-500 font-medium hover:text-slate-900">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span class="text-[10px] mt-0.5 leading-tight">Monitor</span>
        </a>
    </nav>

    <!-- Quick Ticket Tracker Modal (For Mobile & Any Page Access) -->
    <div id="mobileTrackModal" class="hidden fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4 no-print">
        <div class="bg-white w-full sm:max-w-md rounded-t-3xl sm:rounded-3xl p-6 shadow-2xl border border-slate-200 animate-fade-in">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Subaybayan ang Inyong Ticket</h3>
                    <p class="text-xs text-slate-500">Ilagay ang ticket number upang makita ang katayuan sa pila.</p>
                </div>
                <button type="button" onclick="document.getElementById('mobileTrackModal').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold">✕</button>
            </div>
            <form action="{{ route('queue.track') }}" method="GET" class="mt-4 space-y-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Numero ng Ticket:</label>
                    <input type="text" name="ticket_number" required placeholder="Halimbawa: OPD-P01 o OPD-001" class="w-full uppercase font-mono font-bold px-4 py-3.5 rounded-xl border border-slate-300 text-base sm:text-sm focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600">
                </div>
                <button type="submit" class="w-full py-3.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl text-sm shadow-sm transition-colors">
                    Tingnan ang Katayuan sa Pila
                </button>
            </form>
            <div class="mt-4 pt-3 border-t border-slate-100">
                <span class="text-[11px] font-semibold text-slate-400 block mb-2">Mabilisang Halimbawa:</span>
                <div class="flex flex-wrap gap-1.5">
                    @foreach(['OPD-P01', 'OPD-P02', 'OPD-001', 'OBGYN-P01', 'DENT-001'] as $sample)
                        <a href="{{ route('queue.track', ['ticket' => $sample]) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-emerald-50 hover:text-emerald-800 text-xs font-mono font-bold text-slate-700">
                            {{ $sample }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Notifications Container -->
    <main class="flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 no-print">
            @if(session('success'))
                <div class="mb-4 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-950 flex items-start sm:items-center justify-between gap-3 shadow-2xs">
                    <div class="flex items-start sm:items-center gap-3">
                        <div class="w-7 h-7 rounded-lg bg-emerald-700 text-white flex items-center justify-center shrink-0 mt-0.5 sm:mt-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="text-xs sm:text-sm font-bold leading-snug">{{ session('success') }}</div>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950 p-1 shrink-0">✕</button>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-4 p-4 rounded-2xl bg-sky-50 border border-sky-200 text-sky-950 flex items-start sm:items-center justify-between gap-3 shadow-2xs">
                    <div class="flex items-start sm:items-center gap-3">
                        <div class="w-7 h-7 rounded-lg bg-sky-700 text-white flex items-center justify-center shrink-0 font-bold text-xs">i</div>
                        <div class="text-xs sm:text-sm font-bold leading-snug">{{ session('info') }}</div>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-sky-700 hover:text-sky-950 p-1 shrink-0">✕</button>
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-4 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-950 flex items-start sm:items-center justify-between gap-3 shadow-2xs">
                    <div class="flex items-start sm:items-center gap-3">
                        <div class="w-7 h-7 rounded-lg bg-amber-600 text-white flex items-center justify-center shrink-0 font-bold text-xs">!</div>
                        <div class="text-xs sm:text-sm font-bold leading-snug">{{ session('warning') }}</div>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-amber-700 hover:text-amber-950 p-1 shrink-0">✕</button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-950 flex items-start sm:items-center justify-between gap-3 shadow-2xs">
                    <div class="flex items-start sm:items-center gap-3">
                        <div class="w-7 h-7 rounded-lg bg-rose-600 text-white flex items-center justify-center shrink-0 font-bold text-xs">✕</div>
                        <div class="text-xs sm:text-sm font-bold leading-snug">{{ session('error') }}</div>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-rose-700 hover:text-rose-950 p-1 shrink-0">✕</button>
                </div>
            @endif

            @if(session('sms_log'))
                <div class="mb-4 p-3.5 rounded-2xl bg-slate-900 text-white flex items-start sm:items-center justify-between gap-3 text-xs shadow-md border border-slate-800">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 min-w-0">
                        <span class="px-2 py-0.5 bg-emerald-500 text-slate-950 font-extrabold rounded text-[10px] uppercase tracking-wider w-fit shrink-0">
                            Abiso sa SMS
                        </span>
                        <span class="text-slate-200 font-mono text-xs break-words">{{ session('sms_log') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-white p-1 shrink-0">✕</button>
                </div>
            @endif
        </div>

        @yield('content')
    </main>

    <!-- Institutional Footer -->
    <footer class="mt-16 bg-white border-t border-slate-200/90 py-8 sm:py-10 text-xs text-slate-500 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            <div class="flex items-start sm:items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-700 text-white flex items-center justify-center font-extrabold shrink-0">P</div>
                <div>
                    <p class="font-bold text-slate-800 text-sm">PilaFree — Pampublikong Sistema sa Pagpila at Imbentaryo ng Gamot</p>
                    <p class="text-slate-500 mt-0.5">Nagtataguyod ng maayos, maginhawa, at tapat na serbisyong medikal para sa bawat mamamayang Pilipino.</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-slate-600 font-semibold">
                <a href="{{ route('home') }}" class="hover:text-emerald-700">Pagkuha ng Numero</a>
                <span class="text-slate-300">•</span>
                <a href="{{ route('medicines.index') }}" class="hover:text-emerald-700">Imbentaryo ng Gamot</a>
                <span class="text-slate-300">•</span>
                <a href="{{ route('display.tv') }}" target="_blank" class="hover:text-emerald-700">TV Monitor</a>
                <span class="text-slate-300">•</span>
                <a href="{{ route('doctor.index') }}" class="hover:text-emerald-700">Himpilan ng Doktor</a>
            </div>
        </div>
    </footer>

    <script>
        setInterval(() => {
            const el = document.getElementById('navClock');
            if (el) {
                el.innerText = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            }
        }, 15000);
    </script>
    @yield('scripts')
</body>
</html>
