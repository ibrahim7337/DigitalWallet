<?php

namespace App\Services\Notifications\Channels;

use App\Contracts\NotificationChannel;
use Illuminate\Support\Facades\Log;

class EmailNotification implements NotificationChannel
{
    public function send(
        string $recipient,
        string $message
    ): string {
        Log::info('Email notification simulated.', [
            'recipient' => $recipient,
            'message' => $message,
        ]);

        return "Email notification sent to {$recipient}";
    }
}
