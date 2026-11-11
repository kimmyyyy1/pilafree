<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Ticket;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    /**
     * Public landing page / Kiosk.
     */
    public function index()
    {
        $departments = Department::where('is_active', true)
            ->with(['currentServingTicket'])
            ->withCount([
                'tickets as waiting_count' => function ($query) {
                    $query->whereDate('created_at', today())
                        ->where('status', 'waiting');
                },
                'tickets as today_total_count' => function ($query) {
                    $query->whereDate('created_at', today());
                }
            ])
            ->get();

        $medicines = Medicine::orderBy('generic_name')->get();

        $stats = [
            'total_waiting' => Ticket::whereDate('created_at', today())->where('status', 'waiting')->count(),
            'total_served' => Ticket::whereDate('created_at', today())->where('status', 'completed')->count(),
            'total_today' => Ticket::whereDate('created_at', today())->count(),
        ];

        return view('citizen.index', compact('departments', 'medicines', 'stats'));
    }

    /**
     * Kumuha ng bagong queue ticket.
     */
    public function storeTicket(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'full_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:30',
            'is_priority' => 'nullable|boolean',
            'priority_type' => 'nullable|string|in:senior,pwd,pregnant,infant',
            'notes' => 'nullable|string|max:500',
        ]);

        $department = Department::findOrFail($validated['department_id']);

        // Check daily quota
        $todayCount = Ticket::where('department_id', $department->id)
            ->whereDate('created_at', today())
            ->count();

        if ($todayCount >= $department->daily_quota) {
            return back()->with('error', "Paumanhin po, naabot na ng {$department->name} ang itinakdang bilang ng pasyente para sa araw na ito.");
        }

        $isPriority = !empty($validated['is_priority']) || !empty($validated['priority_type']);

        // Find or create Patient
        $patient = Patient::create([
            'full_name' => trim($validated['full_name']),
            'phone_number' => $validated['phone_number'] ? trim($validated['phone_number']) : null,
            'is_priority' => $isPriority,
            'priority_type' => $isPriority ? ($validated['priority_type'] ?? 'senior') : null,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Generate Ticket Number
        // E.g., OPD-P01 (Priority) or OPD-002 (Regular)
        $priorityCount = Ticket::where('department_id', $department->id)
            ->whereDate('created_at', today())
            ->where('is_priority', true)
            ->count();

        $regularCount = Ticket::where('department_id', $department->id)
            ->whereDate('created_at', today())
            ->where('is_priority', false)
            ->count();

        if ($isPriority) {
            $num = $priorityCount + 1;
            $ticketNumber = "{$department->code}-P" . str_pad($num, 2, '0', STR_PAD_LEFT);
        } else {
            $num = $regularCount + 1;
            $ticketNumber = "{$department->code}-" . str_pad($num, 3, '0', STR_PAD_LEFT);
        }

        $ticket = Ticket::create([
            'ticket_number' => $ticketNumber,
            'patient_id' => $patient->id,
            'department_id' => $department->id,
            'status' => 'waiting',
            'queue_sequence' => $todayCount + 1,
            'is_priority' => $isPriority,
            'assigned_room' => $department->room_number,
            'channel' => 'web',
            'remarks' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('queue.track', ['ticket' => $ticket->ticket_number])
            ->with('success', "Matagumpay na nairehistro ang inyong numero sa pila: {$ticket->ticket_number}.");
    }

    /**
     * Subaybayan ang ticket status at estimated wait time.
     */
    public function track(Request $request, $ticket = null)
    {
        $ticketNumber = $ticket ?? $request->query('ticket_number');

        if (!$ticketNumber) {
            return redirect()->route('home');
        }

        $ticket = Ticket::with(['patient', 'department', 'dispenses.medicine'])
            ->where('ticket_number', trim($ticketNumber))
            ->whereDate('created_at', today())
            ->first();

        if (!$ticket) {
            return redirect()->route('home')->with('error', "Hindi po natagpuan ang ticket number na '{$ticketNumber}' sa talaan ngayong araw.");
        }

        // Count how many people are ahead
        $aheadCount = 0;
        if ($ticket->status === 'waiting') {
            $aheadQuery = Ticket::where('department_id', $ticket->department_id)
                ->whereDate('created_at', today())
                ->where('status', 'waiting');

            if ($ticket->is_priority) {
                // Only earlier priority tickets are ahead of a priority ticket
                $aheadCount = $aheadQuery->where('is_priority', true)
                    ->where('id', '<', $ticket->id)
                    ->count();
            } else {
                // All waiting priority tickets + earlier regular tickets are ahead
                $aheadPriority = (clone $aheadQuery)->where('is_priority', true)->count();
                $aheadRegular = (clone $aheadQuery)->where('is_priority', false)->where('id', '<', $ticket->id)->count();
                $aheadCount = $aheadPriority + $aheadRegular;
            }
        }

        $estimatedMinutes = $aheadCount * ($ticket->department->avg_consultation_minutes ?? 10);

        return view('citizen.track', compact('ticket', 'aheadCount', 'estimatedMinutes'));
    }

    /**
     * Public page para sa Libreng Gamot status.
     */
    public function medicines(Request $request)
    {
        $search = $request->query('q');
        $category = $request->query('category');

        $query = Medicine::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('generic_name', 'like', "%{$search}%")
                  ->orWhere('brand_name', 'like', "%{$search}%");
            });
        }

        if ($category && $category !== 'All') {
            $query->where('category', $category);
        }

        $medicines = $query->orderBy('generic_name')->get();
        $categories = Medicine::select('category')->distinct()->pluck('category');

        return view('citizen.medicines', compact('medicines', 'categories', 'search', 'category'));
    }
}
