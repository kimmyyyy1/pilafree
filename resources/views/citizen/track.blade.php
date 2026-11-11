@extends('layouts.app')

@section('title', 'Ticket ' . $ticket->ticket_number . ' — Katayuan sa Pila')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-5 sm:py-8">

    <!-- Top Navigation & Actions -->
    <div class="mb-4 sm:mb-5 flex flex-wrap items-center justify-between gap-2 no-print">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 shadow-2xs transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Bumalik sa Pormularyo</span>
        </a>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 shadow-2xs transition-all cursor-pointer">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>I-print ang Ticket</span>
            </button>
            <a href="{{ route('display.tv') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-bold text-emerald-800 hover:bg-emerald-100 transition-all">
                <span>Monitor sa Paghihintay</span>
            </a>
        </div>
    </div>

    <!-- Official Digital Queue Pass Card -->
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-lg overflow-hidden relative">

        <!-- Dynamic Header -->
        <div class="p-5 sm:p-8 {{ $ticket->status === 'called' ? 'bg-gradient-to-r from-amber-600 to-orange-600 text-white' : ($ticket->status === 'serving' ? 'bg-gradient-to-r from-emerald-700 to-teal-700 text-white' : ($ticket->status === 'completed' ? 'bg-slate-800 text-white' : 'bg-slate-900 text-white')) }} transition-all">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5">
                <div>
                    <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-white/15 text-[10px] font-bold uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        Opisyal na Katibayan sa Pagpila
                    </div>
                    <div class="text-4xl sm:text-6xl font-black font-mono tracking-tight mt-2">
                        {{ $ticket->ticket_number }}
                    </div>
                    <div class="text-xs sm:text-sm text-slate-200 mt-1 font-medium">
                        {{ $ticket->department->name }} • {{ $ticket->department->room_number }}
                    </div>
                </div>

                <!-- Priority Tag & QR Verification -->
                <div class="flex flex-row sm:flex-col items-center sm:items-end justify-between gap-3 pt-3 sm:pt-0 border-t sm:border-t-0 border-white/15">
                    @php
                        $priorityLabel = match($ticket->patient->priority_type) {
                            'senior' => 'Senior Citizen (Prayoridad)',
                            'pwd' => 'May Kapansanan / PWD',
                            'pregnant' => 'Nagdadalang-tao (Prayoridad)',
                            'infant' => 'May Kasamang Sanggol',
                            default => 'Linya ng Prayoridad',
                        };
                    @endphp
                    @if($ticket->is_priority)
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-amber-400 text-slate-950 font-extrabold text-[11px] uppercase shadow-xs">
                            {{ $priorityLabel }}
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white/15 text-white font-bold text-[11px] uppercase border border-white/20">
                            Regular na Pila
                        </span>
                    @endif

                    <!-- QR Code Box -->
                    <div class="bg-white p-2 rounded-xl shadow-sm flex items-center gap-2.5 text-slate-900 shrink-0">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{ urlencode(route('queue.track', ['ticket' => $ticket->ticket_number])) }}" alt="QR Code" class="w-11 h-11 sm:w-12 sm:h-12 rounded">
                        <div class="text-left pr-1">
                            <div class="text-[9px] font-bold uppercase text-slate-400">Talaan Blg.</div>
                            <div class="text-xs font-mono font-bold text-slate-800">#{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</div>
                            <div class="text-[10px] text-emerald-700 font-semibold">{{ $ticket->created_at->format('h:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call Notification Alert -->
            @if($ticket->status === 'called')
                <div class="mt-5 p-4 rounded-2xl bg-white text-slate-900 shadow-lg flex items-start sm:items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-amber-500 text-slate-950 flex items-center justify-center font-extrabold shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    </div>
                    <div>
                        <div class="text-xs sm:text-sm font-extrabold text-rose-700 uppercase">
                            Kasalukuyan na pong Tinatawag ang Inyong Numero
                        </div>
                        <div class="text-xs text-slate-700 mt-0.5">
                            Mangyaring magtungo na po sa <strong class="text-slate-950 underline">{{ $ticket->department->room_number ?? 'Silid-Konsultasyon' }}</strong>.
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Ticket Divider Line -->
        <div class="relative flex items-center justify-between px-4 bg-slate-100 py-1.5 border-y border-dashed border-slate-300">
            <div class="w-5 h-5 -ml-6 rounded-full bg-slate-50 border-r border-slate-300"></div>
            <div class="text-[10px] font-mono tracking-wider text-slate-500 uppercase font-semibold truncate px-2">
                Petsa: {{ now()->format('F d, Y') }} • Paraan: {{ strtoupper($ticket->channel) }}
            </div>
            <div class="w-5 h-5 -mr-6 rounded-full bg-slate-50 border-l border-slate-300"></div>
        </div>

        <!-- Ticket Details Body -->
        <div class="p-5 sm:p-8 space-y-6">

            <!-- 4-Step Progress Indicator -->
            @php
                $steps = [
                    'waiting' => ['label' => 'Nakapila', 'order' => 1],
                    'called' => ['label' => 'Tinatawag', 'order' => 2],
                    'serving' => ['label' => 'Konsultasyon', 'order' => 3],
                    'completed' => ['label' => 'Natapos Na', 'order' => 4],
                ];
                $currentOrder = $steps[$ticket->status]['order'] ?? 0;
            @endphp
            <div class="grid grid-cols-4 gap-1.5 sm:gap-3">
                @foreach($steps as $key => $step)
                    @php
                        $isActive = $currentOrder >= $step['order'];
                        $isCurrent = $ticket->status === $key;
                    @endphp
                    <div class="flex flex-col items-center text-center">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center text-xs sm:text-sm font-extrabold transition-all {{ $isCurrent ? 'bg-emerald-700 text-white ring-4 ring-emerald-100' : ($isActive ? 'bg-emerald-100 text-emerald-900' : 'bg-slate-100 text-slate-400') }}">
                            {{ $step['order'] }}
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold mt-1.5 leading-tight {{ $isCurrent ? 'text-emerald-800 font-extrabold' : ($isActive ? 'text-slate-800' : 'text-slate-400') }}">
                            {{ $step['label'] }}
                        </span>
                    </div>
                @endforeach
            </div>

            <!-- Queue Position & Estimated Wait Box -->
            @if($ticket->status === 'waiting')
                <div class="p-4 sm:p-5 rounded-2xl bg-emerald-50/70 border border-emerald-200">
                    <div class="grid grid-cols-2 gap-4 pb-4 border-b border-emerald-200/70">
                        <div>
                            <div class="text-[11px] font-bold text-slate-600 uppercase">Bilang sa Unahan Ninyo</div>
                            <div class="text-2xl sm:text-4xl font-black font-mono text-slate-900 mt-1">
                                {{ $aheadCount }}
                                <span class="text-xs font-sans font-semibold text-slate-600 block sm:inline">{{ $aheadCount === 0 ? '(Susunod na po kayo)' : 'pasyente' }}</span>
                            </div>
                        </div>
                        <div>
                            <div class="text-[11px] font-bold text-slate-600 uppercase">Tinatayang Paghihintay</div>
                            <div class="text-2xl sm:text-4xl font-black font-mono text-emerald-800 mt-1">
                                ~{{ max(2, $estimatedMinutes) }} <span class="text-xs sm:text-sm font-sans font-bold">minuto</span>
                            </div>
                        </div>
                    </div>

                    <!-- Queue Line Visualization -->
                    <div class="mt-3.5">
                        <div class="text-[11px] font-bold text-emerald-950 mb-2">
                            Kasalukuyang Daloy ng Pila patungo sa {{ $ticket->department->room_number }}:
                        </div>
                        <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-2 px-3 bg-white rounded-xl border border-emerald-200/80">
                            <div class="px-2.5 py-1.5 rounded-lg bg-slate-900 text-white text-xs font-bold shrink-0">
                                Doktor
                            </div>
                            <span class="text-slate-400 font-bold">&larr;</span>

                            @for($i = 1; $i <= min($aheadCount, 4); $i++)
                                <div class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 text-xs font-mono font-semibold shrink-0">
                                    Pasyente #{{ $i }}
                                </div>
                            @endfor

                            @if($aheadCount > 4)
                                <span class="text-xs font-bold text-slate-500 shrink-0">+{{ $aheadCount - 4 }} pa</span>
                            @endif

                            <div class="px-3 py-1.5 rounded-lg bg-emerald-700 text-white text-xs font-bold shrink-0">
                                Kayo ({{ $ticket->ticket_number }})
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Patient & Department Details -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-sm">
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70">
                    <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Pangalan ng Pasyente</div>
                    <div class="font-extrabold text-slate-900 text-sm sm:text-base mt-0.5">{{ $ticket->patient->full_name }}</div>
                    <div class="text-xs text-slate-500 mt-1">
                        @if($ticket->patient->phone_number)
                            Numero sa SMS: <span class="font-mono font-semibold text-slate-700">{{ $ticket->patient->phone_number }}</span>
                        @else
                            Walang nakatalang numero sa SMS
                        @endif
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70">
                    <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Klinika at Silid</div>
                    <div class="font-extrabold text-slate-900 text-sm sm:text-base mt-0.5">{{ $ticket->department->room_number }}</div>
                    <div class="text-xs text-emerald-800 font-bold mt-1">{{ $ticket->department->name }}</div>
                </div>
            </div>

            @if($ticket->remarks)
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70 text-xs">
                    <span class="font-bold text-slate-500 uppercase tracking-wider block mb-1">Naitalang Sintomas o Tala ng Doktor:</span>
                    <p class="text-slate-800 font-medium leading-relaxed">{{ $ticket->remarks }}</p>
                </div>
            @endif

            <!-- Dispensed Medicines Record -->
            @if($ticket->dispenses && $ticket->dispenses->count() > 0)
                <div class="p-4 sm:p-5 rounded-2xl bg-teal-50/80 border border-teal-200">
                    <h3 class="text-xs font-extrabold text-teal-950 uppercase tracking-wider mb-3">
                        Talaan ng Naipagkaloob na Libreng Gamot:
                    </h3>
                    <div class="space-y-2">
                        @foreach($ticket->dispenses as $dispense)
                            <div class="flex items-center justify-between text-xs bg-white p-3 rounded-xl border border-teal-200/70 gap-2">
                                <div>
                                    <span class="font-extrabold text-slate-900">{{ $dispense->medicine->generic_name }}</span>
                                    <span class="text-emerald-800 font-bold">({{ $dispense->medicine->dosage }})</span>
                                    <div class="text-[11px] text-slate-500 mt-0.5">{{ $dispense->dosage_instructions }}</div>
                                </div>
                                <span class="font-mono font-extrabold text-teal-950 bg-teal-100 px-2.5 py-1 rounded-lg shrink-0">
                                    {{ $dispense->quantity }} {{ $dispense->medicine->unit }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Auto-Update Indicator -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pt-3 border-t border-slate-100 text-[11px] text-slate-500 no-print">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-600 animate-ping shrink-0"></span>
                    <span>Kusang nag-a-update ang pahinang ito bawat 5 segundo.</span>
                </div>
                <button onclick="window.location.reload()" class="text-emerald-700 font-bold hover:underline text-left sm:text-right cursor-pointer">
                    I-refresh ang Katayuan ↻
                </button>
            </div>

        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
    const currentStatus = "{{ $ticket->status }}";

    if (currentStatus === 'called' && 'vibrate' in navigator) {
        navigator.vibrate([300, 150, 300]);
    }

    if (currentStatus === 'waiting' || currentStatus === 'called' || currentStatus === 'serving') {
        setTimeout(function() {
            window.location.reload();
        }, 5000);
    }
</script>
@endsection
