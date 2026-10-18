<?php

declare(strict_types=1);

namespace Tests\Unit\Services\ReceiveMoney;

use App\Services\ReceiveMoney\AcmeBankWebhookService;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AcmeBankWebhookServiceTest extends TestCase
{
    #[DataProvider('validWebhookProvider')]
    public function test_it_parses_acme_bank_webhook(
        string $payload,
        float $expectedAmount,
        string $expectedReference,
        string $expectedDate,
    ): void {
        $service = new AcmeBankWebhookService;

        $result = $service->parseTransaction($payload);

        $this->assertSame([
            'amount' => $expectedAmount,
            'reference' => $expectedReference,
            'date' => $expectedDate,
        ], $result);
    }

    public static function validWebhookProvider(): array
    {
        return [
            'amount with decimal comma' => [
                '156,50//202506159000001//20250615',
                156.50,
                '202506159000001',
                '2025-06-15',
            ],

            'amount with integer value' => [
                '200//REF-002//20250101',
                200.0,
                'REF-002',
                '2025-01-01',
            ],

            'amount with decimal point' => [
                '75.25//REF-003//20241231',
                75.25,
                'REF-003',
                '2024-12-31',
            ],
        ];
    }
}
