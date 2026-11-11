<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Dispense;
use App\Models\Medicine;
use App\Models\Ticket;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Doctor & Nurse Station Console.
     */
    public function index(Request $request)
    {
        $departments = Department::where('is_active', true)
            ->withCount(['tickets as waiting_count' => function ($q) {
                $q->whereDate('created_at', today())->where('status', 'waiting');
            }])
            ->get();
        $selectedDeptId = $request->query('department_id', $departments->first()->id ?? null);
        $currentDept = $departments->firstWhere('id', (int) $selectedDeptId) ?? $departments->first();

        if (!$currentDept) {
            return redirect()->route('home')->with('error', 'Walang aktibong departamento.');
        }

        // Current ticket in this department
        $currentTicket = Ticket::with(['patient', 'dispenses.medicine'])
            ->where('department_id', $currentDept->id)
            ->whereDate('created_at', today())
            ->whereIn('status', ['called', 'serving'])
            ->latest('called_at')
            ->first();

        // Waiting queue (Seniors/PWD first, then sequence)
        $waitingTickets = Ticket::with('patient')
            ->where('department_id', $currentDept->id)
            ->whereDate('created_at', today())
            ->where('status', 'waiting')
            ->orderByDesc('is_priority')
            ->orderBy('id')
            ->get();

        // Completed today
        $completedTickets = Ticket::with(['patient', 'dispenses.medicine'])
            ->where('department_id', $currentDept->id)
            ->whereDate('created_at', today())
            ->where('status', 'completed')
            ->latest('completed_at')
            ->take(10)
            ->get();

        // Available medicines for prescription
        $medicines = Medicine::where('is_available', true)
            ->where('stock_quantity', '>', 0)
            ->orderBy('generic_name')
            ->get();

        return view('doctor.station', compact(
            'departments',
            'currentDept',
            'currentTicket',
            'waitingTickets',
            'completedTickets',
            'medicines'
        ));
    }

    /**
     * Tawagin ang susunod na pasyente (Priority first).
     */
    public function callNext(Request $request, Department $department)
    {
        // Check if there is an existing unfinished ticket
        $activeTicket = Ticket::where('department_id', $department->id)
            ->whereDate('created_at', today())
            ->whereIn('status', ['called', 'serving'])
            ->latest('called_at')
            ->first();

        // Find next waiting ticket (Priority first, then FIFO)
        $nextTicket = Ticket::where('department_id', $department->id)
            ->whereDate('created_at', today())
            ->where('status', 'waiting')
            ->orderByDesc('is_priority')
            ->orderBy('id')
            ->first();

        if (!$nextTicket) {
            return back()->with('info', "Wala nang naghihintay na pasyente sa {$department->name}.");
        }

        // Auto-complete previous ticket if it was being served
        if ($activeTicket && $activeTicket->id !== $nextTicket->id) {
            $activeTicket->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        }

        // Call next
        $nextTicket->update([
            'status' => 'called',
            'called_at' => now(),
            'assigned_room' => $department->room_number,
        ]);

        // Send simulated/real SMS alert to called patient
        if ($nextTicket->patient && $nextTicket->patient->phone_number) {
            $this->dispatchSmsNotification(
                $nextTicket->patient->phone_number,
                "PilaFree Alert: Tinatawag na po ang inyong Ticket #{$nextTicket->ticket_number} sa {$department->name} ({$department->room_number}). Mangyaring lumapit na po. Salamat!"
            );
        }

        // Alert the 3rd person in line (Proximity reminder)
        $upcomingTickets = Ticket::where('department_id', $department->id)
            ->whereDate('created_at', today())
            ->where('status', 'waiting')
            ->orderByDesc('is_priority')
            ->orderBy('id')
            ->take(3)
            ->get();

        if ($upcomingTickets->count() >= 3) {
            $third = $upcomingTickets->get(2);
            if ($third->patient && $third->patient->phone_number) {
                $this->dispatchSmsNotification(
                    $third->patient->phone_number,
                    "PilaFree Paalala: Pang-3 na po kayo sa pila para sa {$department->name}. Mangyaring maghanda na at magtungo malapit sa waiting area."
                );
            }
        }

        return back()->with('success', "Tinatawag na ang Ticket: {$nextTicket->ticket_number} ({$nextTicket->patient->full_name})");
    }

    /**
     * Start serving consultation.
     */
    public function startServing(Ticket $ticket)
    {
        $ticket->update([
            'status' => 'serving',
            'serving_at' => now(),
        ]);

        return back()->with('success', "Nagsimula na ang konsultasyon para sa {$ticket->ticket_number}.");
    }

    /**
     * Re-announce current ticket.
     */
    public function repeatCall(Ticket $ticket)
    {
        $ticket->update([
            'called_at' => now(),
        ]);

        return back()->with('success', "Muling tinawag ang {$ticket->ticket_number} sa TV Waiting Board.");
    }

    /**
     * Mark patient as No-Show.
     */
    public function markNoShow(Ticket $ticket)
    {
        $ticket->update([
            'status' => 'no_show',
            'completed_at' => now(),
        ]);

        return back()->with('warning', "Minarkahan bilang NO-SHOW ang Ticket: {$ticket->ticket_number}.");
    }

    /**
     * Call a specific ticket from the waiting queue.
     */
    public function callSpecific(Ticket $ticket)
    {
        $department = $ticket->department;

        // Auto-complete previous active ticket in this department
        Ticket::where('department_id', $department->id)
            ->whereDate('created_at', today())
            ->whereIn('status', ['called', 'serving'])
            ->where('id', '!=', $ticket->id)
            ->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

        $ticket->update([
            'status' => 'called',
            'called_at' => now(),
            'assigned_room' => $department->room_number,
        ]);

        if ($ticket->patient && $ticket->patient->phone_number) {
            $this->dispatchSmsNotification(
                $ticket->patient->phone_number,
                "PilaFree Alert: Tinatawag na po ang inyong Ticket #{$ticket->ticket_number} sa {$department->name} ({$department->room_number}). Mangyaring lumapit na po."
            );
        }

        return back()->with('success', "Tinatawag na ang Ticket: {$ticket->ticket_number} ({$ticket->patient->full_name})");
    }

    /**
     * Complete consultation and dispense free medicines.
     */
    public function completeAndDispense(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'remarks' => 'nullable|string|max:1000',
            'medicines' => 'nullable|array',
            'medicines.*.id' => 'nullable|exists:medicines,id',
            'medicines.*.qty' => 'nullable|integer|min:1',
            'medicines.*.instructions' => 'nullable|string|max:255',
        ]);

        // Dispense each medicine
        if (!empty($validated['medicines'])) {
            foreach ($validated['medicines'] as $medData) {
                if (empty($medData['id'])) {
                    continue;
                }
                $medicine = Medicine::find($medData['id']);
                $qty = (int) ($medData['qty'] ?? 1);

                if ($medicine && $qty > 0) {
                    $actualQty = min($qty, $medicine->stock_quantity);

                    if ($actualQty > 0) {
                        $medicine->decrement('stock_quantity', $actualQty);

                        Dispense::create([
                            'ticket_id' => $ticket->id,
                            'medicine_id' => $medicine->id,
                            'quantity' => $actualQty,
                            'dosage_instructions' => !empty($medData['instructions']) ? $medData['instructions'] : $medicine->instructions,
                            'dispensed_by' => 'Dr. Josefa Rizal, MD',
                            'dispensed_at' => now(),
                        ]);
                    }
                }
            }
        }

        $ticket->update([
            'status' => 'completed',
            'completed_at' => now(),
            'remarks' => $validated['remarks'] ?? $ticket->remarks,
        ]);

        return back()->with('success', "Matagumpay na natapos ang konsultasyon at na-dispense ang gamot para sa Ticket: {$ticket->ticket_number}.");
    }

    /**
     * Helper to log or send SMS.
     */
    private function dispatchSmsNotification(string $phoneNumber, string $message)
    {
        \App\Services\SmsService::send($phoneNumber, $message);
        session()->flash('sms_log', "📱 SMS Alert naipadala kay {$phoneNumber}: \"{$message}\"");
    }
}
