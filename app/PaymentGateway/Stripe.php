<?php

namespace App\Contracts;

/**
 * Adaptee in Adapter Pattern
 */
class Stripe
{
    public function makeCharge(int $valueInCents): void
    {
        // Logic to make a charge using Stripe's API
    }
}
