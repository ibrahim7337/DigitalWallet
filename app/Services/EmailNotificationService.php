<?php

namespace App\Services;

use App\Contracts\NotificationChannel;
use App\Services\Notifications\Channels\EmailNotification;

class EmailNotificationService extends NotificationService
{
    public function createNotification(): NotificationChannel
    {
        return new EmailNotification;
    }
}
