<?php

namespace App\Services\Payments;

use App\Contracts\PaymentProcessor;
use App\Contracts\Stripe;

/**
 * Adapter in Adapter Pattern
 */
class PaymentGatewayAdapter implements PaymentProcessor
{
    public function __construct(
        private Stripe $stripe
    ) {}

    public function processPayment(int $amount): void
    {
        // Convert amount to cents if needed
        $valueInCents = $amount * 100;
        $this->stripe->makeCharge($valueInCents);
    }
}
