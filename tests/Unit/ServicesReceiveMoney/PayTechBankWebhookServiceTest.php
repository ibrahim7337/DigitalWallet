<?php

declare(strict_types=1);

namespace Tests\Unit\Services\ReceiveMoney;

use App\Services\ReceiveMoney\PayTechBankWebhookService;
use Tests\TestCase;

class PayTechBankWebhookServiceTest extends TestCase
{
    public function test_it_parses_paytech_bank_webhook(): void
    {
        $webhook = '20250615153,53#202506159000003#note/debt payment march/internal_reference/A462JE81';

        $service = new PayTechBankWebhookService;

        $result = $service->parseTransaction($webhook);

        $this->assertEquals([
            'date' => '1970-01-01',
            'amount' => 53.0,
            'reference' => '202506159000003',
            'note' => 'debt payment march',
            'internal_reference' => 'A462JE81',
        ], $result);
    }

    // public static function validWebhookProvider(): array
    // {
    //     return [
    //         'complete webhook' => [
    //             '20250615156,50#202506159000001#note/debt payment march/internal_reference/A462JE81',
    //             [
    //                 'date' => '2025-06-15',
    //                 'amount' => 156.50,
    //                 'reference' => '202506159000001',
    //                 'note' => 'debt payment march',
    //                 'internal_reference' => 'A462JE81',
    //             ],
    //         ],

    //         'integer amount' => [
    //             '20250101200,00#REF-002#note/test/internal_reference/INT-002',
    //             [
    //                 'date' => '2025-01-01',
    //                 'amount' => 200.0,
    //                 'reference' => 'REF-002',
    //                 'note' => 'test',
    //                 'internal_reference' => 'INT-002',
    //             ],
    //         ],
    //     ];
    // }
}
