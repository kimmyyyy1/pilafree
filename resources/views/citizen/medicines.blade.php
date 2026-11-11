@extends('layouts.app')

@section('title', 'Imbentaryo ng Libreng Gamot — PilaFree')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-8">

    <!-- Institutional Header Banner -->
    <div class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-emerald-950 to-slate-900 rounded-3xl p-5 sm:p-8 lg:p-10 text-white shadow-lg mb-6 sm:mb-8 border border-emerald-800/40">
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="max-w-2xl">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-[11px] sm:text-xs font-bold mb-3 border border-emerald-400/30">
                    Bukas at Tapat na Impormasyon sa Parmasya
                </span>
                <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight leading-snug">
                    Opisyal na Imbentaryo ng Libreng Gamot
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 mt-2 leading-relaxed">
                    Suriin ang kasalukuyang bilang ng mga maintenance at pangunahing gamot na maaaring makuha nang libre sa ating Sentrong Pangkalusugan bago magtungo sa tanggapan.
                </p>
            </div>
            
            <!-- Stock Summary KPI Boxes -->
            <div class="grid grid-cols-3 gap-2.5 sm:gap-3 shrink-0">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3.5 sm:p-4 border border-white/15 text-center">
                    <div class="text-xl sm:text-3xl font-black font-mono text-emerald-300">{{ $medicines->where('stock_quantity', '>', 0)->count() }}</div>
                    <div class="text-[10px] text-slate-300 font-bold uppercase mt-0.5">May Suplay</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3.5 sm:p-4 border border-white/15 text-center">
                    <div class="text-xl sm:text-3xl font-black font-mono text-amber-300">
                        {{ $medicines->filter(fn($m) => $m->stock_quantity > 0 && $m->stock_quantity <= $m->low_stock_threshold)->count() }}
                    </div>
                    <div class="text-[10px] text-amber-200 font-bold uppercase mt-0.5">Limitado</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3.5 sm:p-4 border border-white/15 text-center">
                    <div class="text-xl sm:text-3xl font-black font-mono text-white">{{ number_format($medicines->sum('stock_quantity')) }}</div>
                    <div class="text-[10px] text-slate-300 font-bold uppercase mt-0.5">Kabuuang Yunit</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Category Filter Controls -->
    <div class="bg-white rounded-3xl p-4 sm:p-6 border border-slate-200/90 shadow-xs mb-6 sm:mb-8 space-y-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="w-5 h-5 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" id="liveSearchInput" value="{{ $search }}" oninput="filterMedicines()" placeholder="Maghanap ng gamot (hal. Paracetamol, Amlodipine, Metformin, Losartan)..." class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-300 bg-white text-base sm:text-sm font-semibold focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 transition-all">
            </div>

            <a href="{{ route('home') }}#registrationSection" class="px-5 py-3 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-2xl text-xs sm:text-sm transition-colors flex items-center justify-center gap-2 shrink-0 shadow-2xs">
                <span>Pumila para sa Reseta &rarr;</span>
            </a>
        </div>

        <!-- Category Filter Pills (Swipeable on Mobile) -->
        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-0.5">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mr-1 shrink-0">Uri:</span>
            <button type="button" onclick="setCategoryFilter('ALL', this)" class="cat-pill px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all bg-slate-900 text-white shrink-0 cursor-pointer">
                Lahat ({{ $medicines->count() }})
            </button>
            @foreach($categories as $cat)
                <button type="button" onclick="setCategoryFilter('{{ $cat }}', this)" class="cat-pill px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all bg-slate-100 text-slate-600 hover:bg-slate-200 shrink-0 cursor-pointer">
                    {{ $cat }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Medicines Grid -->
    <div id="medicinesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
        @foreach($medicines as $med)
            @php
                $isOut = $med->stock_quantity <= 0;
                $isLow = !$isOut && $med->stock_quantity <= $med->low_stock_threshold;
                $stockPct = min(100, round(($med->stock_quantity / max(1, $med->low_stock_threshold * 4)) * 100));
            @endphp
            <div class="med-card bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/90 shadow-2xs hover:shadow-md hover:border-emerald-300 transition-all flex flex-col justify-between"
                 data-name="{{ strtolower($med->generic_name . ' ' . $med->brand_name . ' ' . $med->category) }}"
                 data-category="{{ $med->category }}">
                
                <div>
                    <!-- Category & Availability Badge -->
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600">
                            {{ $med->category }}
                        </span>

                        @if($isOut)
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                Walang Stock
                            </span>
                        @elseif($isLow)
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-900 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                                Limitado ang Suplay
                            </span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-900 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                May Stock (Libre)
                            </span>
                        @endif
                    </div>

                    <!-- Generic Name & Dosage -->
                    <div class="flex items-baseline justify-between gap-2">
                        <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 leading-tight">
                            {{ $med->generic_name }}
                        </h2>
                        <span class="font-mono font-bold text-xs px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200 shrink-0">
                            {{ $med->dosage }}
                        </span>
                    </div>

                    @if($med->brand_name)
                        <div class="text-xs font-medium text-slate-500 mt-0.5">
                            Brand / Katumbas: <span class="font-semibold text-slate-700">{{ $med->brand_name }}</span>
                        </div>
                    @endif

                    @if($med->instructions)
                        <div class="mt-4 p-3 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-600 leading-relaxed">
                            <span class="font-bold text-slate-800 block mb-0.5">Gabay sa Pag-inom:</span>
                            {{ $med->instructions }}
                        </div>
                    @endif
                </div>

                <!-- Stock Meter Footer -->
                <div class="mt-5 pt-3.5 border-t border-slate-100 space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-semibold text-slate-500">Natitirang Suplay:</span>
                        <span class="font-mono font-extrabold text-sm sm:text-base {{ $isOut ? 'text-rose-600' : ($isLow ? 'text-amber-700' : 'text-slate-900') }}">
                            {{ number_format($med->stock_quantity) }} <span class="text-xs font-sans font-semibold text-slate-500">{{ $med->unit }}</span>
                        </span>
                    </div>

                    <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500 {{ $isOut ? 'bg-rose-500' : ($isLow ? 'bg-amber-500' : 'bg-emerald-600') }}" style="width: {{ $stockPct }}%"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Empty Filter State -->
    <div id="emptyMedState" class="hidden bg-white rounded-3xl p-10 text-center border border-slate-200 my-4">
        <h3 class="text-base font-extrabold text-slate-900">Walang natagpuang gamot sa inyong hinahanap</h3>
        <p class="text-xs text-slate-500 mt-1">Subukang baguhin ang pangalan ng gamot o pumili ng ibang kategorya sa itaas.</p>
    </div>

    <!-- Institutional Guide Banner -->
    <div class="mt-10 bg-white rounded-3xl p-5 sm:p-8 border border-slate-200/90 shadow-2xs">
        <h3 class="text-base font-extrabold text-slate-900 mb-4">
            Proseso sa Pagkuha ng Libreng Gamot sa Sentrong Pangkalusugan
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <span class="w-6 h-6 rounded-lg bg-emerald-700 text-white font-bold flex items-center justify-center mb-2">1</span>
                <div class="font-extrabold text-slate-900 text-sm">Kumuha ng Numero sa Pila</div>
                <p class="text-slate-600 mt-1 leading-relaxed">Magparehistro online o sa pamamagitan ng SMS para sa Pangkalahatang Konsultasyon (OPD).</p>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <span class="w-6 h-6 rounded-lg bg-emerald-700 text-white font-bold flex items-center justify-center mb-2">2</span>
                <div class="font-extrabold text-slate-900 text-sm">Magpasuri sa Doktor</div>
                <p class="text-slate-600 mt-1 leading-relaxed">Ipakita ang inyong ID o lumang reseta upang maitala ng doktor ang kaukulang gamot para sa inyo.</p>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                <span class="w-6 h-6 rounded-lg bg-emerald-700 text-white font-bold flex items-center justify-center mb-2">3</span>
                <div class="font-extrabold text-slate-900 text-sm">Tanggapin sa Parmasya</div>
                <p class="text-slate-600 mt-1 leading-relaxed">Kusang nababawasan ang talaan sa pahinang ito sa bawat gamot na naipagkakaloob sa pasyente.</p>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    let activeCategory = 'ALL';

    function setCategoryFilter(category, btn) {
        activeCategory = category;
        document.querySelectorAll('.cat-pill').forEach(el => {
            el.className = 'cat-pill px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all bg-slate-100 text-slate-600 hover:bg-slate-200 shrink-0 cursor-pointer';
        });
        btn.className = 'cat-pill px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all bg-slate-900 text-white shrink-0 cursor-pointer';
        filterMedicines();
    }

    function filterMedicines() {
        const query = document.getElementById('liveSearchInput').value.toLowerCase().trim();
        const cards = document.querySelectorAll('.med-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const nameMatch = card.dataset.name.includes(query);
            const catMatch = (activeCategory === 'ALL') || (card.dataset.category === activeCategory);

            if (nameMatch && catMatch) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        document.getElementById('emptyMedState').classList.toggle('hidden', visibleCount > 0);
    }
</script>
@endsection
