<?php

namespace App\Services;

use App\Contracts\NotificationChannel;
use App\Services\Notifications\Channels\SMSNotification;

class SMSNotificationService extends NotificationService
{
    public function createNotification(): NotificationChannel
    {
        return new SMSNotification;
    }
}
