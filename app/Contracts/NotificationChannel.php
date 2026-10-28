<?php

namespace App\Contracts;

interface NotificationChannel
{
    public function send(
        string $recipient,
        string $message
    ): string;
}
