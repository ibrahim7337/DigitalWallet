<?php

namespace App\Services;

use App\Contracts\NotificationChannel;
use App\Services\Notifications\Channels\PushNotification;

class PushNotificationService extends NotificationService
{
    public function createNotification(): NotificationChannel
    {
        return new PushNotification;
    }
}
