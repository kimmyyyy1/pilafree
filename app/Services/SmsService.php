<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Magpadala ng SMS alert sa pasyente.
     */
    public static function send(string $phoneNumber, string $message): array
    {
        $apiKey = config('services.semaphore.api_key');
        $senderName = config('services.semaphore.sender_name');

        // Normalize Philippine phone number to 09XXXXXXXXX or 639XXXXXXXXX
        $cleanNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        if (str_starts_with($cleanNumber, '63') && strlen($cleanNumber) === 12) {
            $cleanNumber = '0' . substr($cleanNumber, 2);
        }

        // Always log for debugging and local demo
        Log::info("[PilaFree SMS Outbound] To: {$cleanNumber} | Message: {$message}");

        // Kung walang Semaphore API Key sa .env, fallback to simulated success
        if (empty($apiKey)) {
            return [
                'status' => 'simulated',
                'message' => 'Simulated: Walang SEMAPHORE_API_KEY sa .env. Na-log sa Laravel storage/logs.',
                'recipient' => $cleanNumber,
            ];
        }

        try {
            $response = Http::timeout(10)->post('https://api.semaphore.co/api/v4/messages', [
                'apikey' => $apiKey,
                'number' => $cleanNumber,
                'message' => $message,
                'sendername' => $senderName,
            ]);

            if ($response->successful()) {
                Log::info("[PilaFree SMS Sent Success]: " . $response->body());
                return [
                    'status' => 'sent',
                    'response' => $response->json(),
                ];
            } else {
                Log::error("[PilaFree SMS Send Failed]: " . $response->body());
                return [
                    'status' => 'failed',
                    'error' => $response->body(),
                ];
            }
        } catch (\Exception $e) {
            Log::error("[PilaFree SMS Exception]: " . $e->getMessage());
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];
        }
    }
}
