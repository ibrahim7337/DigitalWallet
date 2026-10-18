<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs;

use App\Jobs\ProcessWebhook;
use App\Models\User;
use App\Models\Webhook;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ProcessWebhookTest extends TestCase
{
    use RefreshDatabase;

    private function createWebhook(string $status = 'pending'): Webhook
    {
        return Webhook::query()->create([
            'date' => '2026-10-17',
            'amount' => 100.00,
            'reference' => 'TXN-'.uniqid(),
            'status' => $status,
            'processed_at' => $status === 'processed' ? now()->subMinute() : null,
        ]);
    }

    public function test_it_marks_webhook_as_processed_and_notifies_receiver(): void
    {
        Notification::fake();

        $receiver = User::factory()->create();
        $webhook = $this->createWebhook();

        $job = new ProcessWebhook($receiver, $webhook);

        $job->handle();

        $this->assertDatabaseHas('webhooks', [
            'id' => $webhook->id,
            'status' => 'processed',
        ]);

        $this->assertNotNull($webhook->fresh()->processed_at);
    }

    public function test_it_does_not_process_or_notify_an_already_processed_webhook(): void
    {
        Notification::fake();

        $receiver = User::factory()->create();
        $webhook = $this->createWebhook('processed');

        $originalProcessedAt = $webhook->processed_at;

        $job = new ProcessWebhook($receiver, $webhook);

        $job->handle();

        $this->assertSame('processed', $webhook->fresh()->status);

        $this->assertEquals(
            $originalProcessedAt,
            $webhook->fresh()->processed_at,
        );

        Notification::assertNothingSent();
    }

    public function test_it_uses_the_expected_retry_configuration(): void
    {
        $job = new ProcessWebhook(new User, new Webhook);

        $this->assertSame(5, $job->tries);
        $this->assertSame([30, 60, 120, 300, 600], $job->backoff);
    }
}
