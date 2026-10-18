<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Webhook;
use App\Notifications\WebhookNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessWebhook implements ShouldQueue
{
    use Queueable;

    public int $tries = 5;

    /**
     * Backoff
     *
     * @var array<int>
     */
    public array $backoff = [30, 60, 120, 300, 600];

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $receiver,
        public Webhook $webhook,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->webhook->status === 'processed') {
            return;
        }

        $this->webhook->update([
            'status' => 'processed',
            'processed_at' => now(),
        ]);

        $this->receiver->notify(new WebhookNotification($this->webhook));
    }
}
