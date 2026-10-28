<?php

namespace App\Services;

use App\Contracts\NotificationChannel;

abstract class NotificationService
{
    abstract protected function createNotification(): NotificationChannel;

    public function notify(
        string $recipient,
        string $message
    ): string {
        $notification = $this->createNotification();

        return $notification->send($recipient, $message);
    }
}
