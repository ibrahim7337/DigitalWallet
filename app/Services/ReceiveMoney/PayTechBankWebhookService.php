<?php

declare(strict_types=1);

namespace App\Services\ReceiveMoney;

class PayTechBankWebhookService extends BanksWebhookService
{
    /**
     * Parse Transaction
     * Sample 20250615156,50#202506159000001#note/debt payment march/internal_reference/A462JE81
     *
     * @return array<mixed>
     */
    public function parseTransaction(string $webhook): array
    {
        $output = [];

        $firstPart = explode(',', $webhook);
        $secondPart = explode('#', $firstPart[1]);
        $thirdPart = explode('/', $secondPart[2]);

        $output = [
            'date' => $this->formatDate($firstPart[0]),
            'amount' => (float) str_replace(',', '.', $secondPart[0]),
            'reference' => $secondPart[1],
            $thirdPart[0] => $thirdPart[1],
            $thirdPart[2] => $thirdPart[3],
        ];

        return $output;
    }
}
