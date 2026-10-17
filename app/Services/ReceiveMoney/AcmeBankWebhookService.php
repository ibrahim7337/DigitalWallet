<?php

declare(strict_types=1);

namespace App\Services\ReceiveMoney;

class AcmeBankWebhookService extends BanksWebhookService
{
    /**
     * Parse Transaction
     * Sample 156,50//202506159000001//20250615
     *
     * @return array<mixed>
     */
    public function parseTransaction(string $webhook): array
    {
        $output = [];

        $webhook = explode('//', $webhook);

        $output = [
            'amount' => (float) str_replace(',', '.', $webhook[0]),
            'reference' => $webhook[1],
            'date' => $this->formatDate($webhook[2]),
        ];

        return $output;
    }
}
