@extends('layouts.app')

@section('title', 'PilaFree — Opisyal na Pagkuha ng Numero sa Pila at Imbentaryo ng Gamot')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <!-- Live "Kasalukuyang Tinatawag" Ribbon (Swipeable on Mobile, Wrap on Desktop) -->
    <div class="mb-5 bg-white rounded-2xl p-3 border border-slate-200/90 shadow-2xs flex items-center justify-between gap-3">
        <div class="flex items-center gap-2 shrink-0">
            <span class="px-2.5 py-1 rounded-lg bg-emerald-700 text-white text-[11px] font-extrabold uppercase tracking-wider flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span>
                Tinatawag Ngayon
            </span>
        </div>

        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-0.5">
            @foreach($departments as $dept)
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-xl text-xs border shrink-0 {{ $dept->currentServingTicket ? 'bg-emerald-50/90 border-emerald-200 text-emerald-950' : 'bg-slate-50 border-slate-200/70 text-slate-500' }}">
                    <span class="font-bold text-slate-600">{{ $dept->code }}:</span>
                    @if($dept->currentServingTicket)
                        <a href="{{ route('queue.track', ['ticket' => $dept->currentServingTicket->ticket_number]) }}" class="font-mono font-extrabold text-emerald-800 hover:underline">
                            {{ $dept->currentServingTicket->ticket_number }}
                        </a>
                    @else
                        <span class="font-mono text-slate-400">---</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Institutional Hero Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-emerald-950 to-slate-900 text-white p-5 sm:p-8 lg:p-10 shadow-lg mb-6 sm:mb-8 border border-emerald-800/40">
        <div class="absolute -top-24 -right-24 w-80 h-80 rounded-full bg-emerald-500/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-center">
            <div class="lg:col-span-8">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/15 text-emerald-300 text-[11px] sm:text-xs font-bold tracking-wide border border-emerald-400/30 mb-3 sm:mb-4">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    Opisyal na Pampublikong Serbisyong Pangkalusugan
                </div>
                <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-snug sm:leading-[1.15]">
                    Maayos, Maginhawa, at Makataong Sistema ng Pagpila sa Sentrong Pangkalusugan.
                </h1>
                <p class="mt-3 sm:mt-4 text-xs sm:text-base text-slate-300 font-normal leading-relaxed max-w-2xl">
                    Magparehistro online o sa pamamagitan ng SMS upang makakuha ng opisyal na numero sa pila. Makatatanggap po kayo ng abiso sa text bago ang inyong nakatakdang pagtawag upang hindi na kailangang maghintay nang matagal sa klinika.
                </p>

                <!-- Hero Action Buttons -->
                <div class="mt-5 sm:mt-6 flex flex-col sm:flex-row sm:items-center gap-2.5 sm:gap-3">
                    <a href="#registrationSection" class="w-full sm:w-auto px-5 py-3.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold text-xs sm:text-sm transition-all shadow-md flex items-center justify-center gap-2">
                        <span>Kumuha ng Numero sa Pila</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                    </a>
                    <a href="{{ route('medicines.index') }}" class="w-full sm:w-auto px-5 py-3.5 rounded-xl bg-white/10 hover:bg-white/15 text-white font-bold text-xs sm:text-sm border border-white/15 transition-all flex items-center justify-center gap-2">
                        <span>Tingnan ang Imbentaryo ng Gamot ({{ $medicines->where('stock_quantity', '>', 0)->count() }} Uri)</span>
                    </a>
                </div>
            </div>

            <!-- Live Key Indicators (2x2 Responsive Grid) -->
            <div class="lg:col-span-4 grid grid-cols-2 gap-2.5 sm:gap-3">
                <div class="p-3.5 sm:p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/10">
                    <div class="text-[10px] sm:text-xs text-emerald-300 font-bold uppercase tracking-wider">Nasa Pila Ngayon</div>
                    <div class="text-2xl sm:text-3xl font-black font-mono text-white mt-1">{{ $stats['total_waiting'] }}</div>
                    <div class="text-[10px] sm:text-[11px] text-slate-300 mt-0.5">Kasalukuyang naghihintay</div>
                </div>
                <div class="p-3.5 sm:p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/10">
                    <div class="text-[10px] sm:text-xs text-teal-300 font-bold uppercase tracking-wider">Napaglingkuran</div>
                    <div class="text-2xl sm:text-3xl font-black font-mono text-emerald-400 mt-1">{{ $stats['total_served'] }}</div>
                    <div class="text-[10px] sm:text-[11px] text-slate-300 mt-0.5">Natapos ngayong araw</div>
                </div>
                <div class="p-3.5 sm:p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/10">
                    <div class="text-[10px] sm:text-xs text-amber-300 font-bold uppercase tracking-wider">Express Lane</div>
                    <div class="text-lg sm:text-xl font-extrabold text-amber-300 mt-1.5">Bukas</div>
                    <div class="text-[10px] sm:text-[11px] text-slate-300 mt-0.5">Senior, PWD, Buntis</div>
                </div>
                <div class="p-3.5 sm:p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/10">
                    <div class="text-[10px] sm:text-xs text-sky-300 font-bold uppercase tracking-wider">Mga Klinika</div>
                    <div class="text-2xl sm:text-3xl font-black font-mono text-white mt-1">{{ $departments->count() }}</div>
                    <div class="text-[10px] sm:text-[11px] text-slate-300 mt-0.5">Aktibong departamento</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div id="registrationSection" class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">
        
        <!-- Left Column: Registration Form (Col 1 to 7) -->
        <div class="lg:col-span-7 bg-white rounded-3xl p-5 sm:p-8 border border-slate-200/90 shadow-xs">
            <div class="flex items-start sm:items-center justify-between pb-5 mb-6 border-b border-slate-100 gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-emerald-700">Pormularyo sa Pagpaparehistro</span>
                        <h2 class="text-lg sm:text-2xl font-extrabold text-slate-900">Pagkuha ng Numero sa Pila</h2>
                    </div>
                </div>
                <span class="hidden sm:inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold shrink-0">
                    Libre at Opisyal
                </span>
            </div>

            <form action="{{ route('queue.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- HAKBANG 1: Piliin ang Klinika -->
                <div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3 gap-1">
                        <label class="text-xs font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-emerald-700 text-white text-[11px] flex items-center justify-center shrink-0">1</span>
                            Piliin ang Klinika o Serbisyong Medikal <span class="text-rose-600">*</span>
                        </label>
                        <span class="text-[11px] text-slate-500">Pumili ng isang departamento sa ibaba</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($departments as $dept)
                            @php
                                $estWait = $dept->waiting_count * ($dept->avg_consultation_minutes ?? 10);
                                $quotaPct = min(100, round(($dept->today_total_count / max(1, $dept->daily_quota)) * 100));
                            @endphp
                            <label class="group relative flex flex-col justify-between p-4 rounded-2xl border-2 border-slate-200/90 cursor-pointer transition-all hover:border-emerald-600/60 has-[:checked]:border-emerald-700 has-[:checked]:bg-emerald-50/40 shadow-2xs">
                                <input type="radio" name="department_id" value="{{ $dept->id }}" class="sr-only" required {{ $loop->first ? 'checked' : '' }}>
                                
                                <div>
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-xs font-black font-mono px-2.5 py-1 rounded-lg bg-slate-800 text-white group-has-[:checked]:bg-emerald-700 transition-colors">
                                            {{ $dept->code }}
                                        </span>
                                        <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full {{ $dept->waiting_count > 0 ? 'bg-amber-100 text-amber-900' : 'bg-emerald-100 text-emerald-900' }}">
                                            {{ $dept->waiting_count }} nakapila (~{{ $estWait }} min)
                                        </span>
                                    </div>

                                    <div class="mt-2.5 font-extrabold text-sm text-slate-900 leading-snug">
                                        {{ $dept->name }}
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $dept->description }}</p>
                                </div>

                                <div class="mt-3.5 pt-2.5 border-t border-slate-200/70 space-y-1.5">
                                    <div class="flex items-center justify-between text-[11px] font-semibold text-slate-600">
                                        <span>📍 {{ $dept->room_number }}</span>
                                        <span>Bilang: {{ $dept->today_total_count }}/{{ $dept->daily_quota }}</span>
                                    </div>
                                    <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-emerald-600 rounded-full" style="width: {{ $quotaPct }}%"></div>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- HAKBANG 2: Impormasyon ng Pasyente -->
                <div class="pt-2">
                    <div class="mb-3">
                        <span class="text-xs font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-emerald-700 text-white text-[11px] flex items-center justify-center shrink-0">2</span>
                            Impormasyon ng Pasyente
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="full_name" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Buong Pangalan ng Pasyente <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" name="full_name" id="full_name" required placeholder="Hal. Juan S. Dela Cruz" class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-white focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 text-base sm:text-sm font-semibold transition-all placeholder:text-slate-400 placeholder:font-normal">
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="phone_number" class="block text-xs font-bold text-slate-700">
                                    Numero ng Cellphone (Opsyonal)
                                </label>
                                <span class="text-[10px] font-bold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                    Abiso sa SMS
                                </span>
                            </div>
                            <input type="tel" name="phone_number" id="phone_number" placeholder="0917 123 4567" class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-white focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 text-base sm:text-sm font-mono font-semibold transition-all placeholder:text-slate-400 placeholder:font-sans placeholder:font-normal">
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1.5">
                        Paalala: Ilagay ang inyong aktibong cellphone number upang makatanggap ng text kapag malapit nang tawagin ang inyong numero.
                    </p>
                </div>

                <!-- Prayoridad sa Pila (Express Lane) -->
                <div class="p-4 sm:p-5 rounded-2xl bg-amber-50/70 border border-amber-200">
                    <label class="flex items-start gap-3 cursor-pointer select-none">
                        <input type="checkbox" name="is_priority" value="1" id="is_priority_checkbox" class="mt-0.5 w-5 h-5 text-amber-600 rounded border-amber-400 focus:ring-amber-500 shrink-0" onchange="togglePriorityCards(this.checked)">
                        <div>
                            <span class="text-sm font-extrabold text-amber-950 block">
                                Prayoridad sa Pila (Alinsunod sa Batas RA 9994 at RA 7277)
                            </span>
                            <p class="text-xs text-amber-900/80 mt-0.5 leading-relaxed">
                                Lagyan po ng tsek kung ang pasyente ay <strong>Senior Citizen (60 taong gulang pataas), May Kapansanan (PWD), Nagdadalang-tao, o may kasamang sanggol</strong>.
                            </p>
                        </div>
                    </label>

                    <!-- Priority Category Options -->
                    <div id="priority_type_container" class="mt-4 pt-4 border-t border-amber-200/80 hidden">
                        <label class="block text-xs font-bold text-amber-950 mb-2.5">
                            Piliin ang Kategorya ng Prayoridad:
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                            <label class="flex flex-col items-center justify-center p-3 rounded-xl border-2 border-amber-200 bg-white cursor-pointer hover:border-amber-500 has-[:checked]:border-amber-600 has-[:checked]:bg-amber-100/60 transition-all text-center min-h-[76px]">
                                <input type="radio" name="priority_type" value="senior" checked class="sr-only">
                                <span class="text-xl mb-1">👵</span>
                                <span class="text-xs font-bold text-amber-950 leading-tight">Senior Citizen</span>
                            </label>
                            <label class="flex flex-col items-center justify-center p-3 rounded-xl border-2 border-amber-200 bg-white cursor-pointer hover:border-amber-500 has-[:checked]:border-amber-600 has-[:checked]:bg-amber-100/60 transition-all text-center min-h-[76px]">
                                <input type="radio" name="priority_type" value="pwd" class="sr-only">
                                <span class="text-xl mb-1">♿</span>
                                <span class="text-xs font-bold text-amber-950 leading-tight">PWD</span>
                            </label>
                            <label class="flex flex-col items-center justify-center p-3 rounded-xl border-2 border-amber-200 bg-white cursor-pointer hover:border-amber-500 has-[:checked]:border-amber-600 has-[:checked]:bg-amber-100/60 transition-all text-center min-h-[76px]">
                                <input type="radio" name="priority_type" value="pregnant" class="sr-only">
                                <span class="text-xl mb-1">🤰</span>
                                <span class="text-xs font-bold text-amber-950 leading-tight">Nagdadalang-tao</span>
                            </label>
                            <label class="flex flex-col items-center justify-center p-3 rounded-xl border-2 border-amber-200 bg-white cursor-pointer hover:border-amber-500 has-[:checked]:border-amber-600 has-[:checked]:bg-amber-100/60 transition-all text-center min-h-[76px]">
                                <input type="radio" name="priority_type" value="infant" class="sr-only">
                                <span class="text-xl mb-1">👶</span>
                                <span class="text-xs font-bold text-amber-950 leading-tight">May Sanggol</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- HAKBANG 3: Dahilan ng Pagpapakonsulta -->
                <div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2 gap-1">
                        <label for="notes" class="text-xs font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-emerald-700 text-white text-[11px] flex items-center justify-center shrink-0">3</span>
                            Dahilan ng Pagpapakonsulta o Sintomas (Opsyonal)
                        </label>
                        <span class="text-[11px] text-slate-500">Maaaring pumili sa mga karaniwang dahilan:</span>
                    </div>

                    <!-- Common Medical Complaint Chips -->
                    <div class="flex flex-wrap gap-1.5 mb-2.5">
                        @php
                            $symptomTags = [
                                'Lagnat at Trangkaso',
                                'Ubo at Sipon',
                                'Pagsusuri ng Presyon (BP)',
                                'Refill ng Maintenance na Gamot',
                                'Pananakit ng Ngipin',
                                'Prenatal Check-up',
                                'Pagbabakuna ng Bata',
                            ];
                        @endphp
                        @foreach($symptomTags as $tag)
                            <button type="button" onclick="appendSymptom('{{ $tag }}')" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-emerald-50 hover:text-emerald-800 hover:border-emerald-300 border border-slate-200 text-xs font-semibold text-slate-700 transition-colors cursor-pointer">
                                + {{ $tag }}
                            </button>
                        @endforeach
                    </div>

                    <textarea name="notes" id="notes" rows="2" placeholder="Ilagay ang maikling detalye ng inyong nararamdaman o sadya sa klinika..." class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-white focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 text-base sm:text-sm font-medium transition-all placeholder:text-slate-400"></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-4 px-6 rounded-2xl bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold text-sm sm:text-base shadow-md transition-all flex items-center justify-center gap-2.5 cursor-pointer">
                    <span>Kumuha ng Opisyal na Numero sa Pila</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>
        </div>

        <!-- Right Column: Ticket Verification, Medicine Inventory & SMS Guide (Col 8 to 12) -->
        <div class="lg:col-span-5 space-y-6">

            <!-- 1. Subaybayan ang Ticket Card -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/90 shadow-xs">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Subaybayan ang Inyong Numero</h3>
                        <p class="text-xs text-slate-500">Alamin ang katayuan ng inyong ticket at tinatayang oras ng tawag.</p>
                    </div>
                </div>
                
                <form action="{{ route('queue.track') }}" method="GET" class="mt-4 flex flex-col sm:flex-row gap-2">
                    <input type="text" name="ticket_number" required placeholder="Halimbawa: OPD-P01 o OPD-001" class="flex-1 uppercase font-mono font-bold px-4 py-3 rounded-xl border border-slate-300 text-base sm:text-sm focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600">
                    <button type="submit" class="px-5 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs sm:text-sm transition-colors shrink-0 cursor-pointer">
                        Suriin ang Ticket
                    </button>
                </form>

                <!-- Sample Tickets for Instant Testing -->
                <div class="mt-3.5 pt-3 border-t border-slate-100 flex items-center flex-wrap gap-1.5">
                    <span class="text-[11px] font-semibold text-slate-400 mr-1">Aktibong Ticket Ngayon:</span>
                    @foreach(['OPD-P01', 'OPD-P02', 'OPD-001', 'OBGYN-P01', 'DENT-001'] as $sampleTicket)
                        <a href="{{ route('queue.track', ['ticket' => $sampleTicket]) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-emerald-50 hover:text-emerald-800 text-xs font-mono font-bold text-slate-700 transition-colors">
                            {{ $sampleTicket }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- 2. Talaan ng Libreng Gamot -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/90 shadow-xs">
                <div class="flex items-center justify-between mb-4 gap-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900">Imbentaryo ng Libreng Gamot</h3>
                            <p class="text-xs text-slate-500">Kasalukuyang suplay sa parmasya ng sentro</p>
                        </div>
                    </div>
                    <a href="{{ route('medicines.index') }}" class="px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-bold transition-colors shrink-0">
                        Tingnan Lahat &rarr;
                    </a>
                </div>

                <div class="space-y-2.5">
                    @foreach($medicines->take(5) as $med)
                        @php
                            $isLow = $med->stock_quantity <= $med->low_stock_threshold && $med->stock_quantity > 0;
                            $isOut = $med->stock_quantity <= 0;
                        @endphp
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/60 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-xs font-bold text-slate-900 truncate">{{ $med->generic_name }}</span>
                                    <span class="text-[11px] font-semibold text-emerald-700 shrink-0">{{ $med->dosage }}</span>
                                </div>
                                <div class="text-[11px] text-slate-500 truncate">{{ $med->category }}</div>
                            </div>

                            <div class="shrink-0 text-right">
                                @if($isOut)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800">
                                        Walang Stock
                                    </span>
                                @elseif($isLow)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-900">
                                        Limitado: {{ $med->stock_quantity }} {{ $med->unit }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-900">
                                        {{ $med->stock_quantity }} {{ $med->unit }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 3. Gabay sa Pagpaparehistro sa SMS -->
            <div class="rounded-3xl bg-slate-900 text-white p-5 sm:p-6 shadow-md border border-slate-800">
                <div class="flex items-center justify-between mb-3">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-emerald-500/20 text-emerald-300 text-[10px] font-bold uppercase tracking-wider border border-emerald-500/30">
                        Serbisyo sa Text (SMS)
                    </span>
                    <span class="text-[11px] text-slate-400">Para sa Walang Internet</span>
                </div>

                <h3 class="text-base sm:text-lg font-extrabold text-white">Pagkuha ng Numero Gamit ang Text</h3>
                <p class="text-xs text-slate-300 mt-1 leading-relaxed">
                    Kung walang koneksyon sa internet, maaaring magpadala ng text message gamit ang anumang uri ng cellphone:
                </p>

                <div class="mt-4 p-3.5 rounded-2xl bg-slate-950 border border-slate-800 font-mono text-xs space-y-2.5 overflow-x-auto">
                    <div>
                        <span class="text-[10px] font-sans font-bold uppercase text-slate-400 block">Pagkuha ng Numero sa Pila:</span>
                        <code class="text-emerald-400 font-bold break-all">PILA OPD JUAN DELA CRUZ SENIOR</code>
                    </div>
                    <div class="pt-2 border-t border-slate-800">
                        <span class="text-[10px] font-sans font-bold uppercase text-slate-400 block">Pagsusuri ng Katayuan o Gamot:</span>
                        <code class="text-teal-300 font-bold">STATUS OPD-P01</code> <span class="text-slate-500">o</span> <code class="text-amber-300 font-bold">GAMOT AMLODIPINE</code>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
    function togglePriorityCards(checked) {
        const container = document.getElementById('priority_type_container');
        if (checked) {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }

    function appendSymptom(tag) {
        const notes = document.getElementById('notes');
        if (!notes.value.includes(tag)) {
            notes.value = notes.value ? notes.value.trim() + ', ' + tag : tag;
        }
        notes.focus();
    }
</script>
@endsection
