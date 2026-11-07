<?php

namespace App\Contracts;

/**
 * Target in Adapter Pattern
 */
interface PaymentProcessor
{
    public function processPayment(int $amount): void;
}
