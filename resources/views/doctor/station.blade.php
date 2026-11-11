@extends('layouts.app')

@section('title', 'Himpilan ng Doktor at Nars — ' . $currentDept->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">

    <!-- Top Clinic Switcher Tabs (Swipeable on Mobile) -->
    <div class="mb-5 sm:mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-3 rounded-2xl border border-slate-200/90 shadow-2xs">
        <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pb-0.5 sm:pb-0">
            @foreach($departments as $dept)
                @php $isActiveDept = $currentDept->id === $dept->id; @endphp
                <a href="{{ route('doctor.index', ['department_id' => $dept->id]) }}"
                   class="px-3.5 py-2.5 rounded-xl text-xs font-extrabold transition-all flex items-center gap-2 shrink-0 {{ $isActiveDept ? 'bg-slate-900 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-100' }}">
                    <span class="font-mono">{{ $dept->code }}</span>
                    <span class="hidden md:inline font-semibold opacity-80">• {{ $dept->room_number }}</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-mono {{ $isActiveDept ? 'bg-emerald-500 text-slate-950 font-black' : 'bg-slate-200 text-slate-700' }}">
                        {{ $dept->waiting_count }}
                    </span>
                </a>
            @endforeach
        </div>

        <div class="flex items-center gap-2 px-1 sm:pr-2 border-t sm:border-t-0 pt-2 sm:pt-0 border-slate-100">
            <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
            <span class="text-xs font-bold text-slate-700">Dr. Josefa Rizal, MD (Naka-duty)</span>
        </div>
    </div>

    <!-- Station Key Indicators Row -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-2xs flex items-center justify-between">
            <div>
                <div class="text-[10px] sm:text-[11px] font-bold uppercase text-slate-500 tracking-wider">Kasalukuyang Pila</div>
                <div class="text-2xl sm:text-3xl font-black font-mono text-slate-900 mt-0.5">{{ $waitingTickets->count() }}</div>
                <div class="text-[10px] sm:text-[11px] text-slate-500">Naghihintay sa {{ $currentDept->code }}</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 hidden sm:flex items-center justify-center font-bold text-sm">PILA</div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-amber-200/90 shadow-2xs flex items-center justify-between">
            <div>
                <div class="text-[10px] sm:text-[11px] font-bold uppercase text-amber-700 tracking-wider">Prayoridad</div>
                <div class="text-2xl sm:text-3xl font-black font-mono text-amber-600 mt-0.5">{{ $waitingTickets->where('is_priority', true)->count() }}</div>
                <div class="text-[10px] sm:text-[11px] text-amber-800/80">Senior, PWD, Buntis</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 hidden sm:flex items-center justify-center font-bold text-sm">PRIO</div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-emerald-200/90 shadow-2xs flex items-center justify-between">
            <div>
                <div class="text-[10px] sm:text-[11px] font-bold uppercase text-emerald-700 tracking-wider">Napaglingkuran</div>
                <div class="text-2xl sm:text-3xl font-black font-mono text-emerald-700 mt-0.5">{{ $completedTickets->count() }}</div>
                <div class="text-[10px] sm:text-[11px] text-emerald-800/80">Natapos ngayong araw</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 hidden sm:flex items-center justify-center font-bold text-sm">OK</div>
        </div>

        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-2xs flex items-center justify-between">
            <div>
                <div class="text-[10px] sm:text-[11px] font-bold uppercase text-slate-500 tracking-wider">Tinatayang Oras</div>
                <div class="text-2xl sm:text-3xl font-black font-mono text-slate-900 mt-0.5">~{{ $waitingTickets->count() * $currentDept->avg_consultation_minutes }}m</div>
                <div class="text-[10px] sm:text-[11px] text-slate-500">{{ $currentDept->avg_consultation_minutes }} min / konsultasyon</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 hidden sm:flex items-center justify-center font-bold text-sm">ORAS</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Left Column: Active Patient Terminal & Prescription Builder (Col 1 to 7) -->
        <div class="lg:col-span-7 space-y-6">

            <!-- Active Patient Consultation Card -->
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xs overflow-hidden">
                <!-- Card Top Bar -->
                <div class="px-5 sm:px-6 py-4 bg-slate-900 text-white flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <h2 class="text-xs sm:text-sm font-extrabold uppercase tracking-wider truncate">{{ $currentDept->name }}</h2>
                        <p class="text-[11px] text-slate-400">{{ $currentDept->room_number }}</p>
                    </div>

                    <div class="shrink-0">
                        @if($currentTicket)
                            <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase {{ $currentTicket->status === 'called' ? 'bg-amber-400 text-slate-950 animate-pulse' : 'bg-emerald-400 text-slate-950' }}">
                                {{ $currentTicket->status === 'called' ? 'Tinatawag sa Monitor' : 'Kasalukuyang Kinukonsulta' }}
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-slate-800 text-slate-400">
                                Naghihintay ng Pasyente
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-5 sm:p-6">
                    @if($currentTicket)
                        <!-- Patient Info & Action Controls -->
                        <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200/80 mb-6">
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2.5 flex-wrap">
                                        <span class="text-3xl sm:text-4xl font-black font-mono text-slate-900">{{ $currentTicket->ticket_number }}</span>
                                        @if($currentTicket->is_priority)
                                            <span class="px-2.5 py-1 rounded-lg text-[11px] font-extrabold bg-amber-400 text-slate-950 uppercase">
                                                Prayoridad ({{ strtoupper($currentTicket->patient->priority_type ?? 'Senior/PWD') }})
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold bg-slate-200 text-slate-700">
                                                Regular na Pila
                                            </span>
                                        @endif
                                    </div>

                                    <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 mt-2">{{ $currentTicket->patient->full_name }}</h3>
                                    
                                    <div class="flex flex-wrap items-center gap-2 text-xs text-slate-600 mt-1 font-medium">
                                        @if($currentTicket->patient->age)
                                            <span>Edad: {{ $currentTicket->patient->age }}</span>
                                        @endif
                                        @if($currentTicket->patient->gender)
                                            <span>• {{ $currentTicket->patient->gender }}</span>
                                        @endif
                                        @if($currentTicket->patient->phone_number)
                                            <span class="font-mono text-emerald-800 font-bold">• SMS: {{ $currentTicket->patient->phone_number }}</span>
                                        @endif
                                        <span>• Paraan: {{ strtoupper($currentTicket->channel) }}</span>
                                    </div>

                                    @if($currentTicket->remarks || $currentTicket->patient->notes)
                                        <div class="mt-3 p-3 rounded-xl bg-amber-50/80 border border-amber-200 text-xs text-amber-950">
                                            <span class="font-bold uppercase text-[10px] text-amber-800 block mb-0.5">Dahilan ng Pagpapakonsulta / Sintomas:</span>
                                            {{ $currentTicket->remarks ?? $currentTicket->patient->notes }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Quick Action Buttons (Full width on mobile, stacked on desktop) -->
                                <div class="grid grid-cols-1 sm:flex sm:flex-col gap-2 shrink-0 w-full sm:w-auto">
                                    <form action="{{ route('doctor.repeatCall', $currentTicket) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full px-3.5 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 rounded-xl text-xs font-extrabold transition-all flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                            <span>Tawagin Muli sa Monitor</span>
                                        </button>
                                    </form>

                                    @if($currentTicket->status === 'called')
                                        <form action="{{ route('doctor.startServing', $currentTicket) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full px-3.5 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-extrabold transition-all flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                                <span>Simulan ang Konsultasyon</span>
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('doctor.noShow', $currentTicket) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full px-3.5 py-2.5 bg-white hover:bg-rose-50 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition-all cursor-pointer">
                                            Hindi Nakarating (No-Show)
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Multi-Medicine Prescription & Completion Form -->
                        <form action="{{ route('doctor.complete', $currentTicket) }}" method="POST" class="space-y-5">
                            @csrf

                            <div>
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                                    <label class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">
                                        Pagrereseta at Pagkakaloob ng Libreng Gamot
                                    </label>
                                    <button type="button" onclick="addMedicineRow()" class="w-fit px-3 py-1.5 rounded-lg bg-teal-50 hover:bg-teal-100 text-teal-900 border border-teal-200 text-xs font-bold transition-colors cursor-pointer">
                                        + Magdagdag pa ng Gamot
                                    </button>
                                </div>

                                <div id="prescriptionRows" class="space-y-3">
                                    <div class="p-3.5 sm:p-4 rounded-2xl bg-teal-50/50 border border-teal-200/80 grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                                        <div class="sm:col-span-6">
                                            <label class="block text-[11px] font-bold text-teal-950 mb-1">Piliin ang Gamot mula sa Parmasya:</label>
                                            <select name="medicines[0][id]" class="w-full px-3 py-2.5 rounded-xl border border-teal-300 bg-white text-base sm:text-xs font-bold text-slate-800 focus:ring-2 focus:ring-teal-600">
                                                <option value="">-- Walang gamot / Konsultasyon lamang --</option>
                                                @foreach($medicines as $med)
                                                    <option value="{{ $med->id }}">
                                                        {{ $med->generic_name }} ({{ $med->dosage }}) — {{ $med->stock_quantity }} {{ $med->unit }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label class="block text-[11px] font-bold text-teal-950 mb-1">Bilang (Qty):</label>
                                            <input type="number" name="medicines[0][qty]" value="10" min="1" max="200" class="w-full px-3 py-2.5 rounded-xl border border-teal-300 bg-white text-base sm:text-xs font-mono font-bold text-center">
                                        </div>
                                        <div class="sm:col-span-4">
                                            <label class="block text-[11px] font-bold text-teal-950 mb-1">Paraan ng Pag-inom:</label>
                                            <input type="text" name="medicines[0][instructions]" placeholder="Hal. 1 tableta pagkatapos kumain" class="w-full px-3 py-2.5 rounded-xl border border-teal-300 bg-white text-base sm:text-xs">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Doctor Clinical Notes -->
                            <div>
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                                    <label for="doctorRemarks" class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                                        Tala ng Doktor at Vital Signs:
                                    </label>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach(['BP: 120/80 mmHg', 'Temp: 36.8°C', 'Maayos ang Kondisyon', 'Bumalik matapos ang 7 araw'] as $vital)
                                            <button type="button" onclick="appendVital('{{ $vital }}')" class="px-2.5 py-1 rounded-md bg-slate-100 hover:bg-slate-200 text-[11px] font-semibold text-slate-700 cursor-pointer">
                                                + {{ $vital }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                                <textarea name="remarks" id="doctorRemarks" rows="2" placeholder="Ilagay ang pagsusuri, presyon ng dugo, o mahahalagang bilin sa pasyente..." class="w-full px-4 py-3 rounded-xl border border-slate-300 text-base sm:text-xs font-medium focus:ring-2 focus:ring-emerald-600">{{ $currentTicket->remarks }}</textarea>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="w-full py-3.5 px-5 rounded-2xl bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold text-sm transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer">
                                <span>Tapusin ang Konsultasyon at Ibigay ang Gamot</span>
                            </button>
                        </form>

                    @else
                        <!-- Standby Empty State -->
                        <div class="py-10 sm:py-12 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                            </div>
                            <h3 class="text-base sm:text-lg font-extrabold text-slate-900">Handa na ang Silid-Konsultasyon</h3>
                            <p class="text-xs text-slate-500 max-w-md mx-auto mt-1 mb-6 leading-relaxed">
                                Mayroong <strong>{{ $waitingTickets->count() }} pasyente</strong> na naghihintay sa {{ $currentDept->code }}. Pindutin ang button sa ibaba upang tawagin ang susunod sa pila.
                            </p>

                            <form action="{{ route('doctor.callNext', $currentDept) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full sm:w-auto py-4 px-8 rounded-2xl bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold text-sm sm:text-base shadow-md transition-all inline-flex items-center justify-center gap-2.5 cursor-pointer">
                                    <span>Tawagin ang Susunod na Pasyente</span>
                                    <span>&rarr;</span>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <!-- Quick "Call Next Immediately" Footer Bar -->
                @if($currentTicket)
                    <div class="px-5 sm:px-6 py-3.5 bg-slate-100 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <span class="text-xs font-semibold text-slate-600">Nais tawagin agad ang kasunod na pasyente sa pila?</span>
                        <form action="{{ route('doctor.callNext', $currentDept) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full sm:w-auto px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition-all cursor-pointer">
                                Tawagin ang Susunod &rarr;
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Interactive SMS Gateway Simulator (#smsSimulator) -->
            <div id="smsSimulator" class="bg-slate-900 rounded-3xl p-5 sm:p-6 text-white border border-slate-800 shadow-lg">
                <div class="flex items-start sm:items-center justify-between mb-4 gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm sm:text-base font-extrabold text-white">Subukan ang Sistema ng Pagpila sa SMS</h3>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-500 text-slate-950 uppercase">Simulator</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-0.5">Subukan kung paano tumutugon ang sistema kapag nag-text ang isang pasyente.</p>
                    </div>
                </div>

                <!-- Preset SMS Commands -->
                <div class="mb-4">
                    <span class="text-[11px] font-semibold text-slate-400 block mb-2">Mabilisang Halimbawa ng Text Command:</span>
                    <div class="flex flex-wrap gap-1.5">
                        <button type="button" onclick="fillSmsCommand('PILA {{ $currentDept->code }} Jose Santos SENIOR')" class="px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-emerald-500/20 hover:text-emerald-300 border border-slate-700 text-xs font-mono text-slate-300 transition-colors cursor-pointer">
                            PILA {{ $currentDept->code }} Jose Santos SENIOR
                        </button>
                        <button type="button" onclick="fillSmsCommand('STATUS OPD-P01')" class="px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-emerald-500/20 hover:text-emerald-300 border border-slate-700 text-xs font-mono text-slate-300 transition-colors cursor-pointer">
                            STATUS OPD-P01
                        </button>
                        <button type="button" onclick="fillSmsCommand('GAMOT AMLODIPINE')" class="px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-emerald-500/20 hover:text-emerald-300 border border-slate-700 text-xs font-mono text-slate-300 transition-colors cursor-pointer">
                            GAMOT AMLODIPINE
                        </button>
                    </div>
                </div>

                @if(session('sms_simulation_result'))
                    @php $sim = session('sms_simulation_result'); @endphp
                    <div class="mb-4 p-4 rounded-2xl bg-slate-950 border border-slate-800 space-y-3">
                        <div class="flex justify-end">
                            <div class="max-w-xs bg-emerald-700 text-white px-3.5 py-2.5 rounded-2xl rounded-br-xs text-xs">
                                <div class="text-[10px] font-semibold text-emerald-200 mb-0.5">Mula sa: {{ $sim['sender'] }}</div>
                                <div class="font-mono font-bold">{{ $sim['message'] }}</div>
                            </div>
                        </div>
                        <div class="flex justify-start">
                            <div class="max-w-md bg-slate-800 text-slate-100 px-4 py-2.5 rounded-2xl rounded-bl-xs text-xs border border-slate-700">
                                <div class="text-[10px] font-bold text-emerald-400 mb-0.5">Awtomatikong Tugon ng PilaFree SMS:</div>
                                <div class="font-mono leading-relaxed">{{ $sim['reply'] }}</div>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('sms.simulate') }}" method="POST" class="flex flex-col sm:flex-row gap-2.5">
                    @csrf
                    <input type="text" name="sender" value="09171234567" required placeholder="Numero ng Nagpadala" class="sm:w-40 px-3.5 py-3 rounded-xl bg-slate-950 border border-slate-700 text-base sm:text-xs font-mono text-slate-200 focus:ring-2 focus:ring-emerald-500">
                    <input type="text" name="message" id="smsMessageInput" value="PILA {{ $currentDept->code }} Pedro Penduko SENIOR" required placeholder="Ilagay ang text command..." class="flex-1 px-3.5 py-3 rounded-xl bg-slate-950 border border-slate-700 text-base sm:text-xs font-mono text-emerald-300 focus:ring-2 focus:ring-emerald-500">
                    <button type="submit" class="px-5 py-3 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold text-xs sm:text-sm rounded-xl transition-all shrink-0 cursor-pointer">
                        Ipadala ang Text
                    </button>
                </form>
            </div>

        </div>

        <!-- Right Column: Waiting Queue & Completed History (Col 8 to 12) -->
        <div class="lg:col-span-5 space-y-6">

            <!-- Waiting Queue Card -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/90 shadow-xs">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 gap-2">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900">
                            Talaan ng Naghihintay sa {{ $currentDept->code }} ({{ $waitingTickets->count() }})
                        </h2>
                        <p class="text-xs text-slate-500">Nauuna sa pila ang mga Senior Citizen, PWD, at Nagdadalang-tao.</p>
                    </div>
                </div>

                <div class="space-y-2.5 max-h-[460px] overflow-y-auto pr-1">
                    @forelse($waitingTickets as $ticket)
                        <div class="p-3.5 rounded-2xl {{ $ticket->is_priority ? 'bg-amber-50/60 border border-amber-200' : 'bg-slate-50 border border-slate-200/80' }} flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-xs font-bold text-slate-400 font-mono">#{{ $loop->iteration }}</span>
                                    <a href="{{ route('queue.track', ['ticket' => $ticket->ticket_number]) }}" target="_blank" class="font-black font-mono text-sm text-slate-900 hover:text-emerald-700 hover:underline">
                                        {{ $ticket->ticket_number }}
                                    </a>
                                    @if($ticket->is_priority)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-400 text-slate-950 uppercase">
                                            Prayoridad
                                        </span>
                                    @endif
                                </div>
                                <div class="font-bold text-xs text-slate-800 mt-1 truncate">{{ $ticket->patient->full_name }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">
                                    Paraan: {{ strtoupper($ticket->channel) }} • {{ $ticket->created_at->format('h:i A') }}
                                </div>
                            </div>

                            <!-- Direct Call Action -->
                            <form action="{{ route('doctor.callSpecific', $ticket) }}" method="POST" class="shrink-0">
                                @csrf
                                <button type="submit" class="px-3.5 py-2 rounded-xl bg-white hover:bg-emerald-700 hover:text-white text-slate-800 border border-slate-300 text-xs font-bold transition-all shadow-2xs cursor-pointer">
                                    Tawagin
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="py-10 text-center text-xs text-slate-400 italic bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                            Walang naghihintay na pasyente sa {{ $currentDept->code }} sa kasalukuyan.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Completed History Card -->
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/90 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-extrabold text-slate-900">
                        Mga Natapos na Konsultasyon ({{ $completedTickets->count() }})
                    </h2>
                </div>

                <div class="space-y-2.5">
                    @forelse($completedTickets as $c)
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/60 flex items-center justify-between gap-2 text-xs">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-slate-900">{{ $c->ticket_number }}</span>
                                    <span class="font-semibold text-slate-700 truncate">{{ $c->patient->full_name }}</span>
                                </div>
                                @if($c->dispenses && $c->dispenses->count() > 0)
                                    <div class="text-[11px] text-emerald-800 font-semibold mt-0.5 truncate">
                                        Gamot: {{ $c->dispenses->map(fn($d) => $d->medicine->generic_name . ' (' . $d->quantity . ')')->implode(', ') }}
                                    </div>
                                @else
                                    <div class="text-[11px] text-slate-500 mt-0.5">Natapos ang konsultasyon</div>
                                @endif
                            </div>
                            <span class="text-[11px] font-mono text-slate-500 shrink-0">
                                {{ $c->completed_at ? $c->completed_at->format('h:i A') : 'Tapos Na' }}
                            </span>
                        </div>
                    @empty
                        <div class="text-xs text-slate-400 italic p-6 text-center bg-slate-50 rounded-2xl">
                            Wala pang natatapos na konsultasyon ngayong araw.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
    let medRowIndex = 1;

    function addMedicineRow() {
        const container = document.getElementById('prescriptionRows');
        const row = document.createElement('div');
        row.className = 'p-3.5 sm:p-4 rounded-2xl bg-teal-50/50 border border-teal-200/80 grid grid-cols-1 sm:grid-cols-12 gap-3 items-end relative';
        row.innerHTML = `
            <div class="sm:col-span-6">
                <label class="block text-[11px] font-bold text-teal-950 mb-1">Karagdagang Gamot:</label>
                <select name="medicines[${medRowIndex}][id]" class="w-full px-3 py-2.5 rounded-xl border border-teal-300 bg-white text-base sm:text-xs font-bold text-slate-800">
                    <option value="">-- Pumili ng Gamot --</option>
                    @foreach($medicines as $med)
                        <option value="{{ $med->id }}">{{ $med->generic_name }} ({{ $med->dosage }}) — {{ $med->stock_quantity }} {{ $med->unit }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-[11px] font-bold text-teal-950 mb-1">Bilang (Qty):</label>
                <input type="number" name="medicines[${medRowIndex}][qty]" value="10" min="1" max="200" class="w-full px-3 py-2.5 rounded-xl border border-teal-300 bg-white text-base sm:text-xs font-mono font-bold text-center">
            </div>
            <div class="sm:col-span-3">
                <label class="block text-[11px] font-bold text-teal-950 mb-1">Paraan ng Pag-inom:</label>
                <input type="text" name="medicines[${medRowIndex}][instructions]" placeholder="Hal. 1 tableta araw-araw" class="w-full px-3 py-2.5 rounded-xl border border-teal-300 bg-white text-base sm:text-xs">
            </div>
            <div class="sm:col-span-1 flex justify-end">
                <button type="button" onclick="this.parentElement.parentElement.remove()" class="w-full sm:w-auto py-2 px-3 rounded-xl bg-rose-100 hover:bg-rose-200 text-rose-800 text-xs font-bold cursor-pointer" title="Alisin">Alisin</button>
            </div>
        `;
        container.appendChild(row);
        medRowIndex++;
    }

    function appendVital(text) {
        const el = document.getElementById('doctorRemarks');
        if (!el.value.includes(text)) {
            el.value = el.value ? el.value.trim() + ' | ' + text : text;
        }
        el.focus();
    }

    function fillSmsCommand(cmd) {
        const input = document.getElementById('smsMessageInput');
        input.value = cmd;
        input.focus();
    }
</script>
@endsection
