<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Controllers\Api\V1\ReceiveMoney;

use App\Http\Controllers\Api\V1\ReceiveMoney\PayTechBankWebhook;
use App\Services\ReceiveMoney\PayTechBankWebhookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class PayTechBankWebhookTest extends TestCase
{
    public function test_it_returns_the_response_from_the_bank_service(): void
    {
        $request = Request::create('/api/v1/webhooks/PayTechBank', 'POST', [
            'webhooks' => ['TXN-001'],
        ]);

        $expectedResponse = response()->json([
            'message' => 'Webhook processed successfully.',
        ]);

        $bankService = Mockery::mock(PayTechBankWebhookService::class);

        $bankService
            ->shouldReceive('handle')
            ->once()
            ->with($request)
            ->andReturn($expectedResponse);

        $controller = new PayTechBankWebhook($bankService);

        $response = $controller->handle($request);

        $this->assertSame($expectedResponse, $response);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(
            ['message' => 'Webhook processed successfully.'],
            $response->getData(true),
        );
    }
}
