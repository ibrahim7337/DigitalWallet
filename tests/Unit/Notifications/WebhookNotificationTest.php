<?php

declare(strict_types=1);

namespace Tests\Unit\Notifications;

use App\Models\User;
use App\Notifications\WebhookNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Notification;
use Tests\TestCase;

class WebhookNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_stores_the_webhook_reference(): void
    {
        $notification = new WebhookNotification('TXN-001');

        $this->assertSame('TXN-001', $notification->webhook);
    }

    public function test_it_uses_the_database_notification_channel(): void
    {
        $notification = new WebhookNotification('TXN-001');

        $user = new User;

        $this->assertSame(
            ['database'],
            $notification->via($user),
        );
    }

    public function test_it_returns_the_expected_database_payload(): void
    {
        $notification = new WebhookNotification('TXN-001');

        $user = new User;

        $this->assertSame(
            [
                'webhook' => 'TXN-001',
                'message' => 'Your Webhook had been received.',
            ],
            $notification->toDatabase($user),
        );
    }

    public function test_it_extends_the_laravel_notification_class(): void
    {
        $notification = new WebhookNotification('TXN-001');

        $this->assertInstanceOf(Notification::class, $notification);
    }
}
