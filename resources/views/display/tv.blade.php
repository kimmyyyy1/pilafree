<!DOCTYPE html>
<html lang="tl" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>PilaFree — Opisyal na Monitor sa Paghihintay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&family=JetBrains+Mono:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono-num { font-family: 'JetBrains Mono', monospace; }
        
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(16, 185, 129, 0.2), inset 0 0 12px rgba(16, 185, 129, 0.08); }
            50% { box-shadow: 0 0 38px rgba(16, 185, 129, 0.45), inset 0 0 20px rgba(16, 185, 129, 0.15); }
        }
        .glow-active { animation: pulse-glow 2.5s infinite ease-in-out; }

        @keyframes flash-spotlight {
            0%, 100% { border-color: rgba(245, 158, 11, 0.5); box-shadow: 0 0 35px rgba(245, 158, 11, 0.25); }
            50% { border-color: rgba(16, 185, 129, 0.9); box-shadow: 0 0 60px rgba(16, 185, 129, 0.5); }
        }
        .spotlight-flash { animation: flash-spotlight 1.2s ease-in-out 4; }

        @keyframes marquee {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-50%); }
        }
        .animate-marquee {
            display: inline-flex;
            white-space: nowrap;
            animation: marquee 35s linear infinite;
        }
    </style>
</head>
<body class="flex flex-col min-h-screen bg-slate-950 text-slate-100 overflow-x-hidden selection:bg-emerald-600">

    <!-- Top Broadcast Header -->
    <header class="bg-slate-900 border-b border-slate-800 px-4 sm:px-6 py-3 sm:py-3.5 flex flex-wrap items-center justify-between gap-3 shadow-md">
        <!-- Brand & Back to Kiosk -->
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('home') }}" class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-emerald-600 flex items-center justify-center text-white font-black shadow-sm hover:bg-emerald-500 transition-colors shrink-0" title="Bumalik sa Kiosk">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </a>
            <div class="min-w-0">
                <div class="flex items-center gap-2">
                    <span class="text-lg sm:text-2xl font-black tracking-tight text-white">Pila<span class="text-emerald-400">Free</span></span>
                    <span class="px-2 py-0.5 rounded-md bg-emerald-500/20 text-emerald-300 font-bold text-[10px] tracking-wider uppercase border border-emerald-500/30 flex items-center gap-1.5 shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                        LIVE MONITOR
                    </span>
                </div>
                <p class="text-[11px] text-slate-400 font-medium truncate">Opisyal na Talaan ng Pila sa Sentrong Pangkalusugan</p>
            </div>
        </div>

        <!-- Right Controls & Clock -->
        <div class="flex items-center gap-2 sm:gap-3 ml-auto">
            <!-- Test Chime Button -->
            <button onclick="testVoiceCallout()" class="px-2.5 sm:px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-bold text-amber-300 border border-slate-700 flex items-center gap-1.5 transition-all cursor-pointer" title="Subukan ang Tunog at Boses">
                <span>🔔</span>
                <span class="hidden md:inline">Subukan ang Tawag</span>
            </button>

            <!-- Enable Audio Toggle -->
            <button id="enableAudioBtn" onclick="enableAudio()" class="px-3 sm:px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-extrabold flex items-center gap-1.5 transition-all shadow-sm cursor-pointer">
                <span id="audioIcon">🔇</span>
                <span id="audioStatus" class="hidden sm:inline">Buksan ang Tunog (Audio)</span>
                <span class="sm:hidden">Audio</span>
            </button>

            <!-- Fullscreen Button -->
            <button onclick="toggleFullscreen()" class="hidden sm:flex p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 transition-all cursor-pointer" title="Fullscreen Mode">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
            </button>

            <!-- Digital Clock -->
            <div class="text-right pl-3 border-l border-slate-800">
                <div id="liveClock" class="text-base sm:text-2xl font-black font-mono-num text-emerald-400 tracking-wider leading-none">
                    {{ now()->format('h:i:s A') }}
                </div>
                <div id="liveDate" class="text-[10px] sm:text-[11px] text-slate-400 font-medium mt-0.5">
                    {{ now()->format('M d, Y') }}
                </div>
            </div>
        </div>
    </header>

    <!-- Main Monitor Content -->
    <main class="flex-1 p-4 sm:p-6 max-w-[1600px] mx-auto w-full flex flex-col justify-between gap-5 sm:gap-6">

        <!-- Hero Spotlight Row: Kasalukuyang Tinatawag + QR Self-Service -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 items-stretch">
            
            <!-- Spotlight Card (Col 1 to 9) -->
            <div id="announcementBanner" class="lg:col-span-9 rounded-3xl bg-gradient-to-r from-slate-900 via-emerald-950/40 to-slate-900 border-2 border-emerald-500/40 p-5 sm:p-8 shadow-xl relative overflow-hidden flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
                <div class="flex items-center gap-4 sm:gap-6 relative z-10">
                    <div class="w-14 h-14 sm:w-20 sm:h-20 rounded-2xl bg-amber-500/20 border border-amber-400/40 text-amber-400 flex items-center justify-center shrink-0">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-md bg-amber-400/20 text-amber-300 text-[10px] sm:text-xs font-extrabold uppercase tracking-wider border border-amber-400/30">
                            Kasalukuyang Tinatawag
                        </div>
                        <div id="bannerTicket" class="text-4xl sm:text-6xl lg:text-7xl font-black font-mono-num text-white tracking-tight mt-1.5">
                            {{ $latestCalled ? $latestCalled->ticket_number : 'HANDA NA' }}
                        </div>
                    </div>
                </div>

                <div class="w-full sm:w-auto text-left sm:text-right relative z-10 bg-slate-950/80 px-5 py-3.5 rounded-2xl border border-slate-800">
                    <div class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider">Mangyaring Magtungo Sa:</div>
                    <div id="bannerRoom" class="text-xl sm:text-3xl font-black text-emerald-400 mt-0.5">
                        {{ $latestCalled && $latestCalled->department ? $latestCalled->department->room_number : 'Lugar ng Paghihintay' }}
                    </div>
                    <div id="bannerDept" class="text-xs text-slate-300 font-medium mt-0.5">
                        {{ $latestCalled && $latestCalled->department ? $latestCalled->department->name : 'Pumili ng departamento sa pormularyo' }}
                    </div>
                </div>
            </div>

            <!-- Scan QR to Join Queue Card (Col 10 to 12) -->
            <div class="lg:col-span-3 rounded-3xl bg-slate-900 border border-slate-800 p-5 flex items-center gap-4">
                <div class="bg-white p-2 rounded-xl shadow-md shrink-0">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode(route('home')) }}" alt="Kiosk QR Code" class="w-16 h-16 sm:w-20 sm:h-20 rounded">
                </div>
                <div>
                    <span class="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 font-bold text-[10px] uppercase tracking-wider">Online Pagpila</span>
                    <h3 class="text-sm font-extrabold text-white mt-1 leading-snug">Kumuha ng Numero sa Cellphone</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">I-scan ang QR code upang makapagparehistro online.</p>
                </div>
            </div>

        </div>

        <!-- 4-Department Live Channels Grid -->
        <div id="departmentsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 flex-1">
            @foreach($departments as $dept)
                @php
                    $serving = $dept->currentServingTicket;
                    $waitingList = $dept->waitingTickets()->take(4)->get();
                    $totalWaiting = $dept->waitingTickets()->count();
                @endphp
                <div class="rounded-3xl bg-slate-900/95 border border-slate-800 p-5 shadow-lg flex flex-col justify-between">
                    
                    <div>
                        <!-- Clinic Header -->
                        <div class="flex items-start justify-between gap-2 pb-3 border-b border-slate-800">
                            <div>
                                <span class="px-2.5 py-0.5 rounded-md bg-slate-800 text-emerald-400 font-black text-xs font-mono-num border border-slate-700">
                                    {{ $dept->code }}
                                </span>
                                <h2 class="text-sm sm:text-base font-extrabold text-white mt-2 leading-snug">{{ $dept->name }}</h2>
                            </div>
                            <span class="px-2.5 py-1 rounded-lg bg-slate-950 text-[11px] font-semibold text-slate-300 border border-slate-800 shrink-0">
                                {{ $dept->room_number }}
                            </span>
                        </div>

                        <!-- Currently Serving Display Box -->
                        <div class="mt-4 p-4 sm:p-5 rounded-2xl {{ $serving ? 'bg-gradient-to-b from-emerald-950/80 to-slate-950 border-2 border-emerald-500/50 glow-active' : 'bg-slate-950/60 border border-slate-800/80' }} text-center">
                            <div class="text-[10px] font-bold tracking-wider uppercase {{ $serving ? 'text-emerald-400' : 'text-slate-500' }}">
                                {{ $serving ? 'KASALUKUYANG TINATAWAG' : 'WALANG TINATAWAG' }}
                            </div>

                            <div class="text-3xl sm:text-4xl font-black font-mono-num mt-1.5 {{ $serving ? 'text-white' : 'text-slate-700' }}">
                                {{ $serving ? $serving->ticket_number : '---' }}
                            </div>

                            @if($serving)
                                <div class="mt-2 flex items-center justify-center">
                                    @if($serving->is_priority)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-400 text-slate-950 uppercase">
                                            Prayoridad
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-800 text-slate-300">
                                            Regular na Pila
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Up Next List -->
                    <div class="mt-4 pt-3.5 border-t border-slate-800/90">
                        <div class="flex items-center justify-between text-xs font-bold text-slate-400 mb-2">
                            <span class="uppercase tracking-wider text-[11px]">Susunod sa Pila:</span>
                            <span class="px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-400 font-mono-num text-[11px]">
                                {{ $totalWaiting }} nakapila
                            </span>
                        </div>

                        <div class="space-y-1.5">
                            @forelse($waitingList as $w)
                                <div class="flex items-center justify-between px-3 py-2 rounded-xl bg-slate-950/80 border border-slate-800/80 text-xs font-mono-num">
                                    <div class="flex items-center gap-2">
                                        <span class="text-slate-500 text-[10px]">#{{ $loop->iteration }}</span>
                                        <span class="font-bold text-slate-100 text-sm">{{ $w->ticket_number }}</span>
                                    </div>
                                    @if($w->is_priority)
                                        <span class="text-[10px] font-sans font-bold px-2 py-0.5 rounded bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                            Prayoridad
                                        </span>
                                    @else
                                        <span class="text-[10px] font-sans font-medium text-slate-500">Regular</span>
                                    @endif
                                </div>
                            @empty
                                <div class="py-3.5 text-center text-xs text-slate-600 italic bg-slate-950/40 rounded-xl border border-slate-800/50">
                                    Walang nakapilang pasyente
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

    </main>

    <!-- Bottom Live Pharmacy Stock Ticker -->
    <footer class="bg-slate-900 border-t border-slate-800 py-2.5 px-4 overflow-hidden flex items-center gap-3">
        <div class="px-2.5 py-1 rounded-md bg-emerald-500 text-slate-950 font-extrabold text-[10px] sm:text-[11px] uppercase tracking-wider shrink-0 z-10">
            SUPLAY NG LIBRENG GAMOT
        </div>

        <div class="overflow-hidden flex-1 relative">
            <div class="animate-marquee flex items-center gap-8 text-xs font-semibold text-slate-300">
                @foreach($medicines as $med)
                    <span class="inline-flex items-center gap-2">
                        <strong class="text-white">{{ $med->generic_name }} ({{ $med->dosage }}):</strong>
                        <span class="font-mono-num {{ $med->stock_quantity <= $med->low_stock_threshold ? 'text-amber-400' : 'text-emerald-400' }}">
                            {{ number_format($med->stock_quantity) }} {{ $med->unit }}
                        </span>
                        <span class="text-slate-600">•</span>
                    </span>
                @endforeach
                @foreach($medicines as $med)
                    <span class="inline-flex items-center gap-2">
                        <strong class="text-white">{{ $med->generic_name }} ({{ $med->dosage }}):</strong>
                        <span class="font-mono-num {{ $med->stock_quantity <= $med->low_stock_threshold ? 'text-amber-400' : 'text-emerald-400' }}">
                            {{ number_format($med->stock_quantity) }} {{ $med->unit }}
                        </span>
                        <span class="text-slate-600">•</span>
                    </span>
                @endforeach
            </div>
        </div>
    </footer>

    <!-- Audio Chime, Voice Callout & Real-Time Polling Engine -->
    <script>
        let audioEnabled = false;
        let lastAnnouncedId = {{ $latestCalled ? $latestCalled->id : 0 }};
        let lastAnnouncedTimestamp = {{ $latestCalled && $latestCalled->called_at ? $latestCalled->called_at->timestamp : 0 }};

        function enableAudio() {
            audioEnabled = true;
            const btn = document.getElementById('enableAudioBtn');
            document.getElementById('audioIcon').innerText = '🔊';
            const statusText = document.getElementById('audioStatus');
            if (statusText) statusText.innerText = 'Bukas ang Tunog';
            btn.className = 'px-3 sm:px-4 py-2 rounded-xl bg-emerald-500 text-slate-950 text-xs font-extrabold flex items-center gap-1.5 transition-all shadow-sm cursor-pointer';
            
            playChime();
            speakText("Handa na po ang sistema ng pagtawag sa PilaFree.");
        }

        function testVoiceCallout() {
            if (!audioEnabled) enableAudio();
            const ticket = document.getElementById('bannerTicket').innerText.trim() || 'O P D P 0 1';
            const room = document.getElementById('bannerRoom').innerText.trim() || 'Silid-Konsultasyon 1';
            const banner = document.getElementById('announcementBanner');
            banner.classList.remove('spotlight-flash');
            void banner.offsetWidth;
            banner.classList.add('spotlight-flash');

            playChime();
            setTimeout(() => {
                const phonetic = ticket.split('').join(' ');
                speakText(`Calling ticket number ${phonetic}. Please proceed to ${room}.`);
            }, 650);
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(() => {});
            } else {
                document.exitFullscreen();
            }
        }

        function playChime() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                
                const osc1 = ctx.createOscillator();
                const gain1 = ctx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(659.25, ctx.currentTime);
                gain1.gain.setValueAtTime(0.35, ctx.currentTime);
                gain1.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.75);
                osc1.connect(gain1);
                gain1.connect(ctx.destination);
                osc1.start(ctx.currentTime);
                osc1.stop(ctx.currentTime + 0.75);

                const osc2 = ctx.createOscillator();
                const gain2 = ctx.createGain();
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(523.25, ctx.currentTime + 0.35);
                gain2.gain.setValueAtTime(0.35, ctx.currentTime + 0.35);
                gain2.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 1.4);
                osc2.connect(gain2);
                gain2.connect(ctx.destination);
                osc2.start(ctx.currentTime + 0.35);
                osc2.stop(ctx.currentTime + 1.4);
            } catch (e) {
                console.log("Audio error:", e);
            }
        }

        function speakText(text) {
            if (!('speechSynthesis' in window)) return;
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.rate = 0.9;
            utterance.pitch = 1.0;
            utterance.lang = 'en-US';
            window.speechSynthesis.speak(utterance);
        }

        async function fetchDisplayData() {
            try {
                const res = await fetch("{{ route('display.feed') }}");
                if (!res.ok) return;
                const data = await res.json();

                document.getElementById('liveClock').innerText = data.server_time;
                document.getElementById('liveDate').innerText = data.server_date;

                if (data.announcement) {
                    const ann = data.announcement;
                    if (ann.id !== lastAnnouncedId || ann.called_at !== lastAnnouncedTimestamp) {
                        lastAnnouncedId = ann.id;
                        lastAnnouncedTimestamp = ann.called_at;

                        document.getElementById('bannerTicket').innerText = ann.ticket_number;
                        document.getElementById('bannerRoom').innerText = ann.room_number || 'Silid-Konsultasyon';
                        document.getElementById('bannerDept').innerText = ann.department_name;

                        const banner = document.getElementById('announcementBanner');
                        banner.classList.remove('spotlight-flash');
                        void banner.offsetWidth;
                        banner.classList.add('spotlight-flash');

                        if (audioEnabled) {
                            playChime();
                            setTimeout(() => {
                                const ticketPhonetic = ann.ticket_number.split('').join(' ');
                                speakText(`Calling ticket number ${ticketPhonetic}. Please proceed to ${ann.room_number || 'the consultation room'}.`);
                            }, 650);
                        }
                    }
                }

                renderDepartments(data.departments);
            } catch (err) {
                console.error("Poll error:", err);
            }
        }

        function renderDepartments(depts) {
            const grid = document.getElementById('departmentsGrid');
            if (!depts || depts.length === 0) return;

            let html = '';
            depts.forEach(dept => {
                const isServing = dept.current !== null;
                const ticketNum = isServing ? dept.current.ticket_number : '---';
                const laneBadge = isServing
                    ? (dept.current.is_priority
                        ? `<div class="mt-2 flex justify-center"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-400 text-slate-950 uppercase">Prayoridad</span></div>`
                        : `<div class="mt-2 flex justify-center"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-800 text-slate-300">Regular na Pila</span></div>`)
                    : '';

                let waitingRows = '';
                if (dept.waiting && dept.waiting.length > 0) {
                    dept.waiting.forEach((w, idx) => {
                        waitingRows += `
                            <div class="flex items-center justify-between px-3 py-2 rounded-xl bg-slate-950/80 border border-slate-800/80 text-xs font-mono-num">
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-500 text-[10px]">#${idx + 1}</span>
                                    <span class="font-bold text-slate-100 text-sm">${w.ticket_number}</span>
                                </div>
                                ${w.is_priority
                                    ? '<span class="text-[10px] font-sans font-bold px-2 py-0.5 rounded bg-amber-500/20 text-amber-300 border border-amber-500/30">Prayoridad</span>'
                                    : '<span class="text-[10px] font-sans font-medium text-slate-500">Regular</span>'}
                            </div>
                        `;
                    });
                } else {
                    waitingRows = `<div class="py-3.5 text-center text-xs text-slate-600 italic bg-slate-950/40 rounded-xl border border-slate-800/50">Walang nakapilang pasyente</div>`;
                }

                html += `
                    <div class="rounded-3xl bg-slate-900/95 border border-slate-800 p-5 shadow-lg flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between gap-2 pb-3 border-b border-slate-800">
                                <div>
                                    <span class="px-2.5 py-0.5 rounded-md bg-slate-800 text-emerald-400 font-black text-xs font-mono-num border border-slate-700">${dept.code}</span>
                                    <h2 class="text-sm sm:text-base font-extrabold text-white mt-2 leading-snug">${dept.name}</h2>
                                </div>
                                <span class="px-2.5 py-1 rounded-lg bg-slate-950 text-[11px] font-semibold text-slate-300 border border-slate-800 shrink-0">${dept.room || ''}</span>
                            </div>

                            <div class="mt-4 p-4 sm:p-5 rounded-2xl ${isServing ? 'bg-gradient-to-b from-emerald-950/80 to-slate-950 border-2 border-emerald-500/50 glow-active' : 'bg-slate-950/60 border border-slate-800/80'} text-center">
                                <div class="text-[10px] font-bold tracking-wider uppercase ${isServing ? 'text-emerald-400' : 'text-slate-500'}">
                                    ${isServing ? 'KASALUKUYANG TINATAWAG' : 'WALANG TINATAWAG'}
                                </div>
                                <div class="text-3xl sm:text-4xl font-black font-mono-num mt-1.5 ${isServing ? 'text-white' : 'text-slate-700'}">
                                    ${ticketNum}
                                </div>
                                ${laneBadge}
                            </div>
                        </div>

                        <div class="mt-4 pt-3.5 border-t border-slate-800/90">
                            <div class="flex items-center justify-between text-xs font-bold text-slate-400 mb-2">
                                <span class="uppercase tracking-wider text-[11px]">Susunod sa Pila:</span>
                                <span class="px-2 py-0.5 rounded bg-emerald-500/10 text-emerald-400 font-mono-num text-[11px]">${dept.waiting_count} nakapila</span>
                            </div>
                            <div class="space-y-1.5">
                                ${waitingRows}
                            </div>
                        </div>
                    </div>
                `;
            });

            grid.innerHTML = html;
        }

        setInterval(fetchDisplayData, 3000);
    </script>
</body>
</html>
