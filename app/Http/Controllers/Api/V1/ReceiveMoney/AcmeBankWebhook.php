<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\ReceiveMoney;

use App\Services\ReceiveMoney\AcmeBankWebhookService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AcmeBankWebhook
{
    /**
     * Construct
     */
    public function __construct(
        public AcmeBankWebhookService $bankService
    ) {}

    /**
     * Generate Webhook
     */
    public function handle(Request $request): JsonResponse
    {
        return $this->bankService->handle($request);
    }
}
