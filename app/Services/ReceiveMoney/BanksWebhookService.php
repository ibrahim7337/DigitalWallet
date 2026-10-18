<?php

declare(strict_types=1);

namespace App\Services\ReceiveMoney;

use App\Jobs\ProcessWebhook;
use App\Models\User;
use App\Models\Webhook;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class BanksWebhookService
{
    /**
     * Parse Transaction
     *
     * @return array<mixed>
     */
    abstract protected function parseTransaction(string $webhook): array;

    /**
     * Handle Webhook
     */
    public function handle(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'webhooks' => ['required', 'array'],
            'webhooks.*' => ['required', 'string'],
        ]);

        $webhooks = $validated['webhooks'];

        try {
            // Temporary dummy receiver for development.
            $receiver = User::query()->findOrFail(1);

            foreach ($webhooks as $webhook) {
                $parsedWebhook = $this->parseTransaction($webhook);

                $event = Webhook::firstOrCreate($parsedWebhook);

                ProcessWebhook::dispatch($receiver, $event);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'Webhook processed failed.'], 200);
        }

        return response()->json(['message' => 'Webhook processed successfully.'], 200);
    }

    /**
     * Format Date
     */
    protected function formatDate(string $date): string
    {
        $timestamp = strtotime($date);

        return date('Y-m-d', (int) $timestamp);
    }
}
