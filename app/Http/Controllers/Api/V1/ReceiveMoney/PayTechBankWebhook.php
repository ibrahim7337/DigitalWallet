<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\ReceiveMoney;

use App\Services\ReceiveMoney\PayTechBankWebhookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PayTechBankWebhook
{
    /**
     * Construct
     */
    public function __construct(
        public PayTechBankWebhookService $bankService
    ) {}

    /**
     * Generate Webhook
     */
    public function handle(Request $request): JsonResponse
    {
        return $this->bankService->handle($request);
    }
}
