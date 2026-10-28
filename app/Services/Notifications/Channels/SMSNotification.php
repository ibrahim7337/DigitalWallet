<?php

namespace App\Services\Notifications\Channels;

use App\Contracts\NotificationChannel;
use Illuminate\Support\Facades\Log;

class SMSNotification implements NotificationChannel
{
    public function send(
        string $recipient,
        string $message
    ): string {
        Log::info('SMS notification simulated.', [
            'recipient' => $recipient,
            'message' => $message,
        ]);

        return "SMS notification sent to {$recipient}";
    }
}
