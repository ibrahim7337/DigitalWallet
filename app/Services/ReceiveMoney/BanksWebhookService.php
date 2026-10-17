<?php

declare(strict_types=1);

namespace App\Services\ReceiveMoney;

use App\Models\Webhook;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            DB::beginTransaction();

            foreach ($webhooks as $webhook) {
                $parsedWebhook = $this->parseTransaction($webhook);

                $this->store($parsedWebhook);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

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

    /**
     * Store Transaction
     *
     * @param  array<mixed>  $webhook
     */
    private function store(array $webhook): void
    {
        Webhook::create($webhook);
    }
}
