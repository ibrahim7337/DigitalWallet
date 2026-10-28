<?php

namespace App\Services\Notifications\Channels;

use App\Contracts\NotificationChannel;
use Illuminate\Support\Facades\Log;

class PushNotification implements NotificationChannel
{
    public function send(
        string $recipient,
        string $message
    ): string {
        Log::info('Push notification simulated.', [
            'recipient' => $recipient,
            'message' => $message,
        ]);

        return "Push notification sent to {$recipient}";
    }
}
