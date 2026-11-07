<?php

namespace App\Services\Payments;

use App\Contracts\PaymentProcessor;

/**
 * Client in Adapter Pattern
 */
class PaymentClient
{
    public function __construct(
        private PaymentProcessor $paymentProcessor,
    ) {}

    public function makePayment(): void
    {
        $this->paymentProcessor->processPayment(1000);
    }
}
