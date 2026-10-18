<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Controllers\Api\V1\ReceiveMoney;

use App\Http\Controllers\Api\V1\ReceiveMoney\AcmeBankWebhook;
use App\Services\ReceiveMoney\AcmeBankWebhookService;
use Illuminate\Http\Request;
use Mockery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tests\TestCase;

class AcmeBankWebhookTest extends TestCase
{
    public function test_it_returns_the_response_from_the_bank_service(): void
    {
        $request = Request::create('/api/v1/webhooks/AcmeBank', 'POST', [
            'webhooks' => ['TXN-001'],
        ]);

        $expectedResponse = response()->json([
            'message' => 'Webhook processed successfully.',
        ], 200);

        $bankService = Mockery::mock(AcmeBankWebhookService::class);

        $bankService
            ->shouldReceive('handle')
            ->once()
            ->with($request)
            ->andReturn($expectedResponse);

        $controller = new AcmeBankWebhook($bankService);

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
