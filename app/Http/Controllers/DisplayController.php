<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Ticket;
use Illuminate\Http\Request;

class DisplayController extends Controller
{
    /**
     * TV Waiting Room Board (Fullscreen display).
     */
    public function index()
    {
        $departments = Department::where('is_active', true)->get();

        $latestCalled = Ticket::with('department')
            ->whereDate('created_at', today())
            ->whereIn('status', ['called', 'serving'])
            ->latest('called_at')
            ->first();

        $medicines = \App\Models\Medicine::where('stock_quantity', '>', 0)
            ->orderBy('generic_name')
            ->get();

        return view('display.tv', compact('departments', 'latestCalled', 'medicines'));
    }

    /**
     * Live JSON feed for real-time polling / screen updates.
     */
    public function feed()
    {
        $departments = Department::where('is_active', true)->get()->map(function ($dept) {
            $current = Ticket::with('patient')
                ->where('department_id', $dept->id)
                ->whereDate('created_at', today())
                ->whereIn('status', ['called', 'serving'])
                ->latest('called_at')
                ->first();

            $waiting = Ticket::with('patient')
                ->where('department_id', $dept->id)
                ->whereDate('created_at', today())
                ->where('status', 'waiting')
                ->orderByDesc('is_priority')
                ->orderBy('id')
                ->take(4)
                ->get();

            return [
                'id' => $dept->id,
                'name' => $dept->name,
                'code' => $dept->code,
                'room' => $dept->room_number,
                'current' => $current ? [
                    'id' => $current->id,
                    'ticket_number' => $current->ticket_number,
                    'status' => $current->status,
                    'is_priority' => $current->is_priority,
                    'patient_name' => $current->patient->full_name,
                    'called_at' => $current->called_at ? $current->called_at->toIso8601String() : null,
                ] : null,
                'waiting' => $waiting->map(function ($t) {
                    return [
                        'ticket_number' => $t->ticket_number,
                        'is_priority' => $t->is_priority,
                        'priority_type' => $t->patient->priority_type,
                    ];
                }),
                'waiting_count' => Ticket::where('department_id', $dept->id)
                    ->whereDate('created_at', today())
                    ->where('status', 'waiting')
                    ->count(),
            ];
        });

        // Check if there was any call within the last 12 seconds to trigger voice announcement
        $latestCalled = Ticket::with('department')
            ->whereDate('created_at', today())
            ->whereIn('status', ['called', 'serving'])
            ->where('called_at', '>=', now()->subSeconds(12))
            ->latest('called_at')
            ->first();

        return response()->json([
            'departments' => $departments,
            'announcement' => $latestCalled ? [
                'id' => $latestCalled->id,
                'ticket_number' => $latestCalled->ticket_number,
                'department_name' => $latestCalled->department->name,
                'room_number' => $latestCalled->department->room_number,
                'called_at' => $latestCalled->called_at->timestamp,
            ] : null,
            'server_time' => now()->format('h:i:s A'),
            'server_date' => now()->format('l, F d, Y'),
        ]);
    }
}
