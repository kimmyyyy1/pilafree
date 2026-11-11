<?php

use App\Http\Controllers\DisplayController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\SmsController;
use Illuminate\Support\Facades\Route;

// Public Citizen & Patient Routes
Route::get('/', [QueueController::class, 'index'])->name('home');
Route::post('/ticket/store', [QueueController::class, 'storeTicket'])->name('queue.store');
Route::get('/ticket/track/{ticket?}', [QueueController::class, 'track'])->name('queue.track');
Route::get('/medicines', [QueueController::class, 'medicines'])->name('medicines.index');

// Live TV Waiting Room Display
Route::get('/tv', [DisplayController::class, 'index'])->name('display.tv');
Route::get('/api/display-feed', [DisplayController::class, 'feed'])->name('display.feed');

// Doctor & Clinic Station Console
Route::get('/doctor', [DoctorController::class, 'index'])->name('doctor.index');
Route::post('/doctor/call-next/{department}', [DoctorController::class, 'callNext'])->name('doctor.callNext');
Route::post('/doctor/call-ticket/{ticket}', [DoctorController::class, 'callSpecific'])->name('doctor.callSpecific');
Route::post('/doctor/start/{ticket}', [DoctorController::class, 'startServing'])->name('doctor.startServing');
Route::post('/doctor/repeat/{ticket}', [DoctorController::class, 'repeatCall'])->name('doctor.repeatCall');
Route::post('/doctor/no-show/{ticket}', [DoctorController::class, 'markNoShow'])->name('doctor.noShow');
Route::post('/doctor/complete/{ticket}', [DoctorController::class, 'completeAndDispense'])->name('doctor.complete');

// SMS Webhook & Simulation Endpoints
Route::post('/sms/webhook', [SmsController::class, 'inbound'])->name('sms.webhook');
Route::post('/sms/simulate', function (\Illuminate\Http\Request $request, SmsController $smsController) {
    $reply = $smsController->processMessage($request->input('sender', '09171234567'), $request->input('message', ''));
    return back()->with('sms_simulation_result', [
        'sender' => $request->input('sender'),
        'message' => $request->input('message'),
        'reply' => $reply,
    ]);
})->name('sms.simulate');
