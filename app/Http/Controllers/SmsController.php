<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Patient;
use App\Models\Ticket;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    /**
     * Webhook endpoint para sa Semaphore / Inbound SMS.
     */
    public function inbound(Request $request)
    {
        // Support Semaphore format, Twilio format, or custom simulation
        $sender = $request->input('sender') ?? $request->input('From') ?? $request->input('phone_number');
        $message = trim($request->input('message') ?? $request->input('Body') ?? $request->input('text') ?? '');

        if (!$sender || !$message) {
            return response()->json(['status' => 'error', 'message' => 'Missing sender or message content.'], 400);
        }

        $reply = $this->processMessage($sender, $message);

        // Send automated SMS reply back to the patient's phone
        \App\Services\SmsService::send($sender, $reply);

        return response()->json([
            'status' => 'success',
            'reply' => $reply,
            'sender' => $sender,
        ]);
    }

    /**
     * Parse inbound SMS commands:
     * 1. PILA [CODE] [NAME] [SENIOR/PWD/PREGNANT]
     *    Example: PILA OPD JUAN DELA CRUZ SENIOR
     * 2. STATUS [TICKET_NUMBER]
     *    Example: STATUS OPD-P01
     * 3. GAMOT [MEDICINE_NAME]
     *    Example: GAMOT AMLODIPINE
     */
    public function processMessage(string $sender, string $message): string
    {
        $parts = preg_split('/\s+/', $message);
        $command = strtoupper($parts[0] ?? '');

        if ($command === 'PILA') {
            return $this->handlePilaCommand($sender, array_slice($parts, 1));
        }

        if ($command === 'STATUS') {
            $ticketNumber = strtoupper($parts[1] ?? '');
            return $this->handleStatusCommand($ticketNumber);
        }

        if ($command === 'GAMOT') {
            $medName = implode(' ', array_slice($parts, 1));
            return $this->handleGamotCommand($medName);
        }

        return "PilaFree Bot: Hindi maintindihan ang utos. Format: 'PILA [OPD/PEDS/DENT] [Pangalan]' o 'STATUS [Ticket#]' o 'GAMOT [Pangalan ng Gamot]'. Salamat!";
    }

    private function handlePilaCommand(string $sender, array $args): string
    {
        if (count($args) < 2) {
            return "PilaFree: Kulang ang detalye. Format: 'PILA [DEPT_CODE] [PANGALAN]'. Halimbawa: 'PILA OPD Juan Dela Cruz'";
        }

        $code = strtoupper(array_shift($args));
        $department = Department::where('code', $code)->where('is_active', true)->first();

        if (!$department) {
            $validCodes = Department::where('is_active', true)->pluck('code')->implode(', ');
            return "PilaFree: Hindi kilalang klinika. Piliin ang isa sa mga sumusunod: {$validCodes}.";
        }

        // Check if last argument is priority keyword
        $isPriority = false;
        $priorityType = null;
        $lastWord = strtoupper(end($args));

        if (in_array($lastWord, ['SENIOR', 'PWD', 'BUNTIS', 'PREGNANT'])) {
            $isPriority = true;
            $priorityType = match ($lastWord) {
                'SENIOR' => 'senior',
                'PWD' => 'pwd',
                'BUNTIS', 'PREGNANT' => 'pregnant',
                default => 'senior'
            };
            array_pop($args); // remove the priority tag from name
        }

        $fullName = implode(' ', $args);

        // Check quota
        $todayCount = Ticket::where('department_id', $department->id)
            ->whereDate('created_at', today())
            ->count();

        if ($todayCount >= $department->daily_quota) {
            return "PilaFree: Pasensya na po, naabot na ng {$department->code} ang limit ng pila para sa araw na ito.";
        }

        // Create Patient
        $patient = Patient::create([
            'full_name' => $fullName,
            'phone_number' => $sender,
            'is_priority' => $isPriority,
            'priority_type' => $priorityType,
        ]);

        // Generate Ticket Number
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

        Ticket::create([
            'ticket_number' => $ticketNumber,
            'patient_id' => $patient->id,
            'department_id' => $department->id,
            'status' => 'waiting',
            'queue_sequence' => $todayCount + 1,
            'is_priority' => $isPriority,
            'assigned_room' => $department->room_number,
            'channel' => 'sms',
        ]);

        $ahead = Ticket::where('department_id', $department->id)
            ->whereDate('created_at', today())
            ->where('status', 'waiting')
            ->count() - 1;

        return "PilaFree: Salamat {$fullName}! Ang inyong Ticket ay {$ticketNumber} sa {$department->name} ({$department->room_number}). May {$ahead} na naghihintay sa unahan ninyo. Mag-te-text po kami kapag tinatawag na kayo.";
    }

    private function handleStatusCommand(string $ticketNumber): string
    {
        if (!$ticketNumber) {
            return "PilaFree: Mangyaring ilagay ang ticket number. Halimbawa: 'STATUS OPD-001'";
        }

        $ticket = Ticket::with('department')
            ->where('ticket_number', $ticketNumber)
            ->whereDate('created_at', today())
            ->first();

        if (!$ticket) {
            return "PilaFree: Hindi nahanap ang Ticket #{$ticketNumber} ngayong araw.";
        }

        $statusTag = match ($ticket->status) {
            'waiting' => 'NAGHIHINTAY SA PILA',
            'called' => 'KASALUKUYANG TINATAWAG SA TV BOARD',
            'serving' => 'KASALUKUYANG KINUKONSULTA',
            'completed' => 'TAPOS NA',
            'no_show' => 'NO-SHOW / HINDI SUMIPOT',
            default => strtoupper($ticket->status),
        };

        return "PilaFree Status: Ticket {$ticket->ticket_number} sa {$ticket->department->name} ay [{$statusTag}]. Kwarto: {$ticket->department->room_number}.";
    }

    private function handleGamotCommand(string $medName): string
    {
        if (!$medName) {
            return "PilaFree: I-type ang gamot na nais alamin. Halimbawa: 'GAMOT AMLODIPINE'";
        }

        $medicine = \App\Models\Medicine::where('generic_name', 'like', "%{$medName}%")
            ->orWhere('brand_name', 'like', "%{$medName}%")
            ->first();

        if (!$medicine) {
            return "PilaFree: Walang nahanap na record para sa '{$medName}'.";
        }

        $status = $medicine->stock_quantity > 0 
            ? "MAY STOCK PA ({$medicine->stock_quantity} {$medicine->unit} available - LIBRE)"
            : "OUT OF STOCK SA KASALUKUYAN";

        return "PilaFree Gamot: {$medicine->generic_name} ({$medicine->dosage}): [{$status}].";
    }
}
